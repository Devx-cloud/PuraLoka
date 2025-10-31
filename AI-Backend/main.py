import requests
import json
import uuid
import time
import os
import io
from fastapi import FastAPI, UploadFile, File, Form, HTTPException
from fastapi.responses import StreamingResponse, JSONResponse

# --- Konfigurasi ---
COMFYUI_ADDRESS = "http://127.0.0.1:8188"
# Pastikan path ini benar relatif terhadap tempat Anda menjalankan 'python main.py'
WORKFLOW_API_FILE = "models/PuraLokaModel.json"

app = FastAPI()

# === FUNGSI HELPER UNTUK BERBICARA DENGAN COMFYUI ===

def upload_image_to_comfy(image_file: bytes, filename: str) -> str:
    """Mengunggah file gambar ke endpoint /upload/image ComfyUI"""
    files = {
        'image': (filename, image_file, 'image/jpeg'),
        'overwrite': (None, 'true'),
    }
    try:
        response = requests.post(f"{COMFYUI_ADDRESS}/upload/image", files=files)
        response.raise_for_status()
        data = response.json()
        
        if 'name' in data:
            return data['name']
        else:
            error_message = data.get('error', 'Respon ComfyUI tidak valid (key "name" tidak ditemukan).')
            raise HTTPException(status_code=500, detail=f"ComfyUI gagal upload gambar: {error_message}")
        
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal koneksi atau respon dari ComfyUI: {e}")

def queue_prompt(prompt_workflow: dict) -> dict:
    """Mengirim workflow yang sudah dimodifikasi ke /prompt ComfyUI"""
    client_id = str(uuid.uuid4())
    data = {"prompt": prompt_workflow, "client_id": client_id}
    
    try:
        response = requests.post(f"{COMFYUI_ADDRESS}/prompt", json=data)
        response.raise_for_status()
        return response.json()
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal mengirim prompt ke ComfyUI: {e}")

def get_history(prompt_id: str) -> dict:
    """Mendapatkan hasil/history dari prompt_id tertentu"""
    try:
        response = requests.get(f"{COMFYUI_ADDRESS}/history/{prompt_id}")
        response.raise_for_status()
        return response.json()
    except requests.RequestException:
        # Jika ID tidak ditemukan, ComfyUI mungkin mengembalikan 404, anggap PENDING
        return {}

def get_file_from_comfy(filename: str, subfolder: str, folder_type: str) -> bytes:
    """Mengambil file output (video/gambar) dari ComfyUI"""
    url = f"{COMFYUI_ADDRESS}/view?filename={filename}&subfolder={subfolder}&type={folder_type}"
    try:
        response = requests.get(url)
        response.raise_for_status()
        return response.content
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal mengambil file video: {e}")

# === ENDPOINT API UTAMA (NON-BLOCKING) ===

@app.post("/generate-video/")
async def generate_video(
    image: UploadFile = File(...),
    prompt: str = Form(...) # Menerima prompt dari form Laravel
):
    
    # 1. Baca file gambar
    image_bytes = await image.read()
    
    # 2. Upload gambar ke ComfyUI
    try:
        uploaded_filename = upload_image_to_comfy(image_bytes, image.filename)
    except HTTPException as e:
        return JSONResponse(status_code=e.status_code, content={"detail": e.detail})

    # 3. Muat template workflow
    try:
        with open(WORKFLOW_API_FILE, "r") as f:
            workflow_data = json.load(f)
    except FileNotFoundError:
        raise HTTPException(status_code=500, detail=f"File {WORKFLOW_API_FILE} tidak ditemukan.")

    # 4. Modifikasi workflow dengan input dari pengguna
    
    # Inject Image (Node ID 23)
    if "23" in workflow_data and "image" in workflow_data["23"]["inputs"]:
        workflow_data["23"]["inputs"]["image"] = uploaded_filename
    else:
        # Error jika Node Load Image tidak ditemukan
        raise HTTPException(status_code=500, detail="Kesalahan konfigurasi: Node ID 23 (Load Image) tidak ditemukan.")

    # Inject Prompt (Node ID 21 - ASUMSI Positive CLIP Text Encode)
    if "21" in workflow_data and "text" in workflow_data["21"]["inputs"]:
        workflow_data["21"]["inputs"]["text"] = prompt
    else:
        # Error jika Node Prompt tidak ditemukan
        raise HTTPException(status_code=500, detail="Kesalahan konfigurasi: Node ID 21 (Prompt) tidak ditemukan.")

    # Set dynamic output prefix (Node ID 25 - SaveVideo)
    base_filename, _ = os.path.splitext(image.filename)
    new_prefix = f"{base_filename}_generated"
    if "25" in workflow_data and "filename_prefix" in workflow_data["25"]["inputs"]:
        workflow_data["25"]["inputs"]["filename_prefix"] = new_prefix
    
    # 5. Kirim workflow ke ComfyUI
    try:
        result = queue_prompt(workflow_data) 
        prompt_id = result.get('prompt_id')
        if not prompt_id:
            raise Exception("ComfyUI tidak mengembalikan prompt_id.")
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Gagal antrian prompt: {e}")

    # 6. Kembalikan ID PROMPT dan status NON-BLOCKING
    return {"status": "processing", "prompt_id": prompt_id}

# === ENDPOINT UNTUK CEK STATUS (Polling dari Frontend) ===

@app.get("/status/{prompt_id}")
async def get_video_status(prompt_id: str):
    history = get_history(prompt_id)

    if not history or prompt_id not in history:
        # Berarti masih dalam antrian atau sedang diproses
        return {"status": "PENDING"} 

    history_data = history[prompt_id]
    outputs = history_data.get('outputs', {})

    # Cek error dari ComfyUI
    if "error" in history_data.get('prompt', {}):
        return {"status": "FAILED", "error": history_data['prompt']['error']}

    # Cek apakah output video sudah siap (Node ID 25 - SaveVideo)
    if "25" in outputs and "gifs" in outputs["25"]:
        video_data = outputs["25"]["gifs"][0]
        
        # Video siap, kembalikan URL download yang mengarah ke endpoint FastAPI lainnya
        download_url = f"/download/{video_data['filename']}/{video_data['subfolder']}/{video_data['type']}"
        return {"status": "DONE", "video_url": download_url}
    
    # Jika belum ada output, berarti sedang berjalan
    return {"status": "PROCESSING"}

# === ENDPOINT UNTUK MENGUNDUH VIDEO SETELAH SELESAI ===

@app.get("/download/{filename}/{subfolder}/{folder_type}")
async def download_video(filename: str, subfolder: str, folder_type: str):
    """Mengambil video biner dari ComfyUI dan mengembalikannya untuk diunduh."""
    try:
        video_bytes = get_file_from_comfy(filename, subfolder, folder_type)
    except HTTPException as e:
        return JSONResponse(status_code=e.status_code, content={"detail": e.detail})

    return StreamingResponse(
        io.BytesIO(video_bytes),
        media_type="video/mp4", # Sesuaikan dengan format video output (mp4, webm, gif)
        headers={"Content-Disposition": f"attachment; filename={filename}"}
    )

# --- Jalankan server jika file ini dieksekusi ---
if __name__ == "__main__":
    import uvicorn
    # FastAPI akan berjalan di port 8000, sehingga Laravel harus dijalankan di port lain (misal 8080)
    print("Menjalankan API Pembungkus di http://127.0.0.1:8001")
    uvicorn.run(app, host="127.0.0.1", port=8001)