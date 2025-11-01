@extends('layouts.app')

@section('content')
<div class="bg-gray-50">

<!-- CONTAINER UTAMA: Latar Belakang Gradien yang Lebih Dalam --><div class="min-h-screen bg-gradient-to-br from-emerald-900 to-emerald-600 flex flex-col items-center justify-center p-4 sm:p-6 lg:l-10">
    <div 
        class="w-full max-w-4xl bg-white p-6 sm:p-12 rounded-[2rem] shadow-2xl transition-all duration-500 ease-in-out border border-gray-100"
        x-data="{ 
            // State Kontrol Halaman: 'input' atau 'output'
            currentPage: 'input', 
            
            // Data Input
            imagePreview: null,
            imageFile: null,
            promptText: '',
            
            // State Generasi
            isGenerating: false,
            jobId: null,
            pollInterval: null,
            message: '', // Untuk pesan notifikasi
            
            // Data Output (Simulasi)
            videoUrl: 'https://placehold.co/600x400/089445/FFFFFF/video-final.mp4', 

            // Fungsi untuk menampilkan preview gambar
            previewFile(event) {
                const file = event.target.files[0];
                this.imageFile = file;
                this.message = '';

                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.imagePreview = null;
                }
            },
            
            // Fungsi submit untuk memulai proses AI
            async submitGeneration() {
                this.message = '';

                if (!this.imageFile || this.promptText.trim() === '') {
                    this.message = '⚠️ Mohon unggah gambar dan masukkan prompt.';
                    return;
                }

                this.isGenerating = true;
                this.jobId = null;
                
                // Simulasikan Fetch API call
                console.log('Mengirim permintaan generasi...');
                
                try {
                    // Simulasikan pengiriman data dan penundaan respons
                    await new Promise(resolve => setTimeout(resolve, 1500)); 

                    // SIMULASI RESPON: Berhasil dan mendapatkan ID
                    const data = { prompt_id: 'abcd1234efgh5678' }; 
                    this.jobId = data.prompt_id;
                    this.message = 'Permintaan diterima. Menunggu proses AI dimulai...';
                    this.startPolling();

                } catch (error) {
                    console.error('Error komunikasi:', error);
                    this.message = '❌ Gagal memulai proses. Cek konsol untuk detail.';
                    this.isGenerating = false;
                }
            },

            startPolling() {
                if (this.pollInterval) clearInterval(this.pollInterval);

                // SIMULASI Polling Status
                let progress = 0;
                this.pollInterval = setInterval(() => {
                    if (!this.jobId) return clearInterval(this.pollInterval);
                    
                    progress += 10;
                    
                    if (progress >= 100) {
                        clearInterval(this.pollInterval);
                        this.isGenerating = false;
                        this.message = '✅ Video Selesai! Mengarahkan ke halaman output...';
                        
                        // TRANSISI KE HALAMAN OUTPUT
                        this.currentPage = 'output'; 

                    } else if (progress > 50) {
                        this.message = `Sedang memproses tahap akhir... ${progress}%`;
                    } else if (progress > 10) {
                        this.message = `Sedang diproses oleh AI. Status: ${progress}%`;
                    }
                    
                }, 1000); // Polling lebih cepat untuk simulasi
            },
            
            // Fungsi untuk mereset ke halaman input
            resetApp() {
                this.currentPage = 'input';
                this.imagePreview = null;
                this.imageFile = null;
                this.promptText = '';
                this.message = '';
                this.jobId = null;
                if (this.pollInterval) clearInterval(this.pollInterval);
            }
        }"
        x-cloak>
        
        
        <!-- HEADER (Umum) --><div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-gray-800 tracking-tight">Budaya Loka <span class="text-emerald-600">AI</span></h1>
        </div>
        
        
        <!-- ================================== HALAMAN INPUT ================================== -->
        <form x-show="currentPage === 'input'" @submit.prevent="submitGeneration" class="space-y-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95">
            <p class="text-gray-500 mt-3 text-lg">Ubah gambar diam menjadi video yang hidup dengan sentuhan AI generatif.</p>

            <!-- 1. UNGGAH GAMBAR -->
            <div class="border-b pb-8 border-gray-200">
                <label for="image_upload_v6" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                    <span class="bg-emerald-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">1</span> Unggah Gambar Utama Anda
                </label>

                <!-- Div Wrapper Utama yang Menjadi Target Klik -->
                <div
                    class="relative mt-1 flex items-center justify-center border-4 border-dashed rounded-3xl p-8 transition duration-300 h-96 cursor-pointer group shadow-inner"
                    :class="{'border-emerald-600 bg-green-50/50': imagePreview, 'border-gray-300 hover:border-emerald-500 hover:bg-gray-50': !imagePreview}"
                    onclick="document.getElementById('image_upload_v6').click()">
                    
                    <input id="image_upload_v6" name="image" type="file" class="hidden" @change="previewFile" accept="image/*" required>

                    <!-- Preview Gambar -->
                    <div x-show="imagePreview" class="w-full h-full flex items-center justify-center">
                        <img :src="imagePreview" alt="Image Preview" class="max-h-full max-w-full object-contain rounded-2xl shadow-xl border-4 border-white">
                    </div>

                    <!-- Placeholder Drag and Drop -->
                    <div x-show="!imagePreview" class="w-full h-full flex flex-col items-center justify-center text-gray-600">
                        <svg class="h-16 w-16 text-gray-400 group-hover:text-green-600 transition-colors mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <p class="text-3xl font-bold text-gray-800 tracking-tight mb-2">
                            Drop, Upload or Paste Images
                        </p>
                        <p class="text-base text-gray-500 font-medium text-center">
                            Supported formats: JPG, PNG, GIF, JFIF (JPEG), HEIC, PDF
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            Maksimal 10MB
                        </p>
                    </div>
                </div>
                <p x-show="imageFile" class="mt-4 text-base text-gray-700 text-center font-semibold truncate" x-text="'File dipilih: ' + imageFile?.name"></p>
            </div>

            <!-- 2. PROMPT INPUT -->
            <div class="border-b pb-8 border-gray-200">
                <label for="prompt_input" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                    <span class="bg-emerald-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">2</span> Deskripsikan Video Anda (Prompt)
                </label>
                <textarea
                    id="prompt_input"
                    name="prompt"
                    rows="4"
                    x-model="promptText"
                    required
                    placeholder="Contoh: 'Pemandangan ini bergerak dengan efek air terjun yang deras, disinari cahaya matahari terbit, dan burung-burung beterbangan di langit.'"
                    class="shadow-lg focus:ring-green-600 focus:border-green-600 block w-full text-base border-gray-300 rounded-xl p-4 transition duration-200 resize-y"></textarea>
                <p class="mt-3 text-sm text-gray-500">
                    Gunakan deskripsi yang detail dan spesifik untuk mendapatkan hasil video yang maksimal.
                </p>
            </div>

            <!-- 3. TOMBOL SUBMIT -->
            <div class="flex flex-col items-center">
                <button
                    type="submit"
                    :disabled="isGenerating"
                    class="
                        w-full flex justify-center items-center py-4 px-6 border border-transparent 
                        rounded-xl shadow-xl text-xl font-extrabold text-white 
                        transition duration-300 transform 
                        hover:scale-[1.01]
                        bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500 focus:ring-opacity-70 shadow-xl
                    "
                    :class="{
                        'bg-emerald-400 cursor-not-allowed animate-pulse shadow-xl hover:bg-emerald-400': isGenerating,
                    }">
                    
                    <span x-show="!isGenerating">
                        Generate Video Sekarang!
                    </span>

                    <span x-show="isGenerating" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="jobId ? 'Video ID ' + jobId.substring(0, 8) + ' Sedang Diproses...' : 'Sedang Mengantri...'"></span>
                    </span>
                </button>
            </div>
            
            <!-- PESAN/STATUS NOTIFIKASI -->
            <div x-show="message" x-text="message" x-transition:enter.duration.500ms x-cloak
                 class="text-center p-4 rounded-xl font-medium border border-blue-300 bg-blue-100 text-blue-800 shadow-md">
            </div>

        </form>
        
        
        <!-- ================================== HALAMAN OUTPUT ================================== -->
        <div x-show="currentPage === 'output'" class="space-y-10 text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95">
            
            <p class="text-gray-600 text-lg">Berikut adalah hasil video yang telah dibuat berdasarkan prompt Anda.</p>
            
            <!-- Video Player (Menggunakan URL Placeholder) -->
            <div class="relative w-full aspect-video rounded-2xl shadow-2xl overflow-hidden mx-auto bg-gray-900 border-4 border-white">
                <!-- Gunakan tag <video> atau iframe untuk embed, di sini disimulasikan dengan placeholder -->
                <img :src="videoUrl" alt="Video Placeholder" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                    <div class="text-white text-2xl font-bold bg-emerald-600 px-4 py-2 rounded-lg">Simulasi Video Output</div>
                </div>
            </div>

            <!-- Detail & Tombol Aksi -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-lg">
                <p class="text-lg font-semibold text-gray-700 mb-3">Prompt yang Digunakan:</p>
                <p class="text-gray-500 italic mb-6 break-words" x-text="promptText || 'Tidak ada prompt yang tersimpan.'"></p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <!-- Tombol Download (Simulasi) -->
                    <a :href="videoUrl" download="budaya_loka_ai_video.mp4" 
                       class="flex items-center justify-center py-3 px-6 rounded-xl font-bold text-lg text-white bg-emerald-600 hover:bg-emerald-700 transition duration-200 shadow-md shadow-green-500/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Video
                    </a>
                    
                    <!-- Tombol Kembali -->
                    <button @click="resetApp"
                        class="flex items-center justify-center py-3 px-6 rounded-xl font-bold text-lg text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-200 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                        </svg>
                        Buat Video Baru
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

</div>
@endsection
