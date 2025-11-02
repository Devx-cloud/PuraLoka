import requests
import json
import uuid
import time
from fastapi import FastAPI, UploadFile, File, Form, HTTPException
from fastapi.responses import StreamingResponse
import io
import os

# --- Konfigurasi ---
COMFYUI_ADDRESS = "http://127.0.0.1:8188"
# Pastikan path ini benar relatif terhadap tempat Anda menjalankan 'python main.py'
WORKFLOW_API_FILE = "models/PuraLokaModel.json"

app = FastAPI()

# === FUNGSI HELPER UNTUK BERBICARA DENGAN COMFYUI ===

def upload_image_to_comfy(image_file: bytes, filename: str) -> str:
    """Mengunggah file gambar ke endpoint /upload/image ComfyUI"""
    files = {
        'image': (filename, image_file, 'image/jpeg'), # Tipe MIME bisa disesuaikan
        'overwrite': (None, 'true'),
    }
    try:
        response = requests.post(f"{COMFYUI_ADDRESS}/upload/image", files=files)
        response.raise_for_status()
        
        data = response.json()
        
        # --- DEBUGGING PRINT (bisa dihapus nanti) ---
        print("==== RESPON DARI COMFYUI /upload/image ====")
        print(data)
        print("============================================")
        
        # === PERBAIKAN: Menggunakan 'name' berdasarkan output debug Anda ===
        if 'name' in data:
            # Berhasil! Kembalikan nama filenya
            return data['name']
        else:
            # Jika 'name' tidak ada, baru laporkan error
            error_message = data.get('error', 'Respon tidak diketahui dari ComfyUI (key "name" tidak ditemukan).')
            print(f"ComfyUI GAGAL meng-upload: {error_message}")
            raise HTTPException(status_code=500, detail=f"ComfyUI gagal upload gambar: {error_message}")
        
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal koneksi ke ComfyUI: {e}")
    except json.JSONDecodeError:
        raise HTTPException(status_code=500, detail=f"ComfyUI mengembalikan respon non-JSON: {response.text}")

def queue_prompt(prompt_workflow: dict) -> dict:
    """Mengirim workflow yang sudah dimodifikasi ke /prompt ComfyUI"""
    client_id = str(uuid.uuid4())
    data = {"prompt": prompt_workflow, "client_id": client_id}
    
    try:
        response = requests.post(f"{COMFYUI_ADDRESS}/prompt", json=data)
        response.raise_for_status() # Lempar error jika status code bukan 2xx
        return response.json() # Akan berisi "prompt_id"
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal mengirim prompt ke ComfyUI: {e}")

def get_history(prompt_id: str) -> dict:
    """Mendapatkan hasil/history dari prompt_id tertentu"""
    try:
        response = requests.get(f"{COMFYUI_ADDRESS}/history/{prompt_id}")
        response.raise_for_status()
        return response.json()
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal mengambil history: {e}")

def get_file_from_comfy(filename: str, subfolder: str, folder_type: str) -> bytes:
    """Mengambil file output (video/gambar) dari ComfyUI"""
    url = f"{COMFYUI_ADDRESS}/view?filename={filename}&subfolder={subfolder}&type={folder_type}"
    try:
        response = requests.get(url)
        response.raise_for_status()
        return response.content # Data biner dari file
    except requests.RequestException as e:
        raise HTTPException(status_code=500, detail=f"Gagal mengambil file: {e}")

# === ENDPOINT API UTAMA UNTUK FRONTEND ANDA ===

@app.post("/generate-video/")
async def generate_video(
    image: UploadFile = File(...) # Parameter motion_bucket_id DIHAPUS
):
    """
    Endpoint utama. Hanya menerima gambar. motion_bucket_id diatur di backend.
    """
    
    # 1. Baca file gambar yang diupload
    image_bytes = await image.read()
    
    # 2. Upload gambar ini ke ComfyUI
    print("Mengunggah gambar ke ComfyUI...")
    uploaded_filename = upload_image_to_comfy(image_bytes, image.filename)
    
    # 3. Muat template workflow SVD dari file
    try:
        with open(WORKFLOW_API_FILE, "r") as f:
            workflow_data = json.load(f)
    except FileNotFoundError:
        raise HTTPException(status_code=500, detail=f"File {WORKFLOW_API_FILE} tidak ditemukan.")

    # 4. Modifikasi workflow dengan input dari pengguna
    
    # ID "23" adalah ID LoadImage Anda yang sebenarnya
    workflow_data["23"]["inputs"]["image"] = uploaded_filename
    
    # ID "12" adalah ID SVD_img2vid_Conditioning Anda yang sebenarnya
    # Nilainya sekarang DI-HARDCODE sesuai permintaan Anda
    workflow_data["12"]["inputs"]["motion_bucket_id"] = 127 # <-- Ganti nilai ini jika perlu

    # -----------------------------------------------------------------
    # === [BAGIAN BARU] Mengatur nama file output dinamis ===
    # -----------------------------------------------------------------
    # Ambil nama file tanpa ekstensi (misal, "pura.png" -> "pura")
    base_filename, _ = os.path.splitext(image.filename)
    new_prefix = f"{base_filename}_generated"
    
    # ID "25" adalah ID SaveVideo (VHS_VideoCombine) Anda yang sebenarnya
    # PERBAIKAN: Menghapus ["prompt"]
    if "25" in workflow_data:
        workflow_data["25"]["inputs"]["filename_prefix"] = new_prefix
        print(f"Mengatur prefix nama file output ke: {new_prefix}")
    else:
        # Peringatan jika ID node 25 tidak ditemukan
        print("Peringatan: Node ID '25' (SaveVideo) tidak ditemukan di workflow. Menggunakan nama default.")
    # -----------------------------------------------------------------
    
    # 5. Kirim workflow ke ComfyUI untuk diproses
    print("Mengirim prompt ke ComfyUI...")
    result = queue_prompt(workflow_data) 
    
    prompt_id = result.get('prompt_id')
    if not prompt_id:
        raise HTTPException(status_code=500, detail="ComfyUI tidak mengembalikan prompt_id.")
    
    print(f"Workflow diterima, ID: {prompt_id}. Menunggu hasil...")

    # 6. Tunggu (Polling) hasilnya
    output_video = None
    # debug_printed = False # (Kode debug ini bisa dihapus)
    while not output_video:
        time.sleep(1) # Tunggu 1 detik sebelum cek lagi
        history = get_history(prompt_id)
        
        if not history or prompt_id not in history:
            continue # Masih diproses
        
        # Proses selesai, ambil data output
        history_data = history[prompt_id]
        outputs = history_data.get('outputs', {})
        
        # === PERBAIKAN FINAL: Gunakan 'gifs' berdasarkan output DEBUG ===
        if "25" in outputs and "gifs" in outputs["25"]:
            # Ambil data video pertama dari list 'gifs'
            video_data = outputs["25"]["gifs"][0]
            
            # 7. Ambil file video dari ComfyUI
            video_bytes = get_file_from_comfy(
                video_data['filename'],
                video_data['subfolder'],
                video_data['type']
            )
            output_video = video_bytes
            print(f"Video berhasil dibuat! Nama file: {video_data['filename']}")
        else:
            # Cek apakah ada error
            if "error" in history_data:
                 raise HTTPException(status_code=500, detail=f"ComfyUI Error: {history_data['error']}")
            
            # (Kode debug sudah dihapus, tapi Anda bisa tambahkan lagi jika perlu)
            
            print("Output belum siap...") # Tetap print ini

    # 8. Kembalikan file video ke frontend
    return StreamingResponse(io.BytesIO(output_video), media_type="video/mp4")

# --- Jalankan server jika file ini dieksekusi ---
if __name__ == "__main__":
    import uvicorn
    print("Menjalankan API Pembungkus di http://127.0.0.1:8001")
    uvicorn.run(app, host="127.0.0.1", port=8001)