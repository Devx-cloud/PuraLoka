<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budaya Loka AI - Perbaikan Desain</title>
    <!-- Memuat Tailwind CSS CDN --><script src="https://cdn.tailwindcss.com"></script>
    <!-- Konfigurasi Font Inter --><style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    <!-- Memuat Alpine.js CDN (untuk x-data) --><script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

<!-- CONTAINER UTAMA: Latar Belakang Gradien yang Lebih Dalam --><div class="min-h-screen bg-gradient-to-br from-emerald-900 to-emerald-600 flex flex-col items-center justify-center p-4 sm:p-6 lg:l-10">
    <div class="w-full max-w-4xl bg-white p-6 sm:p-12 rounded-[2rem] shadow-2xl transition-all duration-500 ease-in-out border border-gray-100"
        x-data="{ 
            imagePreview: null,
            imageFile: null,
            promptText: '',
            isGenerating: false,
            jobId: null,
            pollInterval: null,
            message: '', // Untuk pesan notifikasi

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
                
                // Simulasikan Fetch API call (Gantilah dengan logika server-side Anda)
                console.log('Mengirim permintaan generasi...');
                
                // Karena ini adalah HTML tunggal, saya akan mensimulasikan respons async
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
                        this.message = '✅ Video Selesai! Mengarahkan ke unduhan (Simulasi).';
                        
                        // Dalam implementasi nyata: window.location.href = statusData.video_url;
                        console.log('Video Selesai! Mengarahkan ke halaman download.');

                    } else if (progress > 50) {
                        this.message = `Sedang memproses tahap akhir... ${progress}%`;
                    } else if (progress > 10) {
                        this.message = `Sedang diproses oleh AI. Status: ${progress}%`;
                    }
                    
                }, 1000); // Polling lebih cepat untuk simulasi
            } 
        }">
        
        <!-- HEADER --><div class="text-center mb-10">
            <h1 class="text-5xl font-extrabold text-gray-800 tracking-tight">Budaya Loka <span class="text-green-600">AI</span></h1>
            <p class="text-gray-500 mt-3 text-lg">Ubah gambar diam menjadi video yang hidup dengan sentuhan AI generatif.</p>
        </div>

        <form @submit.prevent="submitGeneration" class="space-y-10">

            <!-- 1. UNGGAH GAMBAR (KONTROL YANG LEBIH SEDERHANA UNTUK Z-INDEX) --><div class="border-b pb-8 border-gray-200">
                <label for="image_upload" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                    <span class="bg-green-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">1</span> Unggah Gambar Utama Anda
                </label>

                <!-- Div Wrapper Utama yang Menjadi Target Klik -->
                <div
                    class="relative mt-1 flex items-center justify-center border-4 border-dashed rounded-3xl p-8 transition duration-300 h-96 cursor-pointer group shadow-inner"
                    :class="{'border-green-600 bg-green-50/50': imagePreview, 'border-gray-300 hover:border-green-500 hover:bg-gray-50': !imagePreview}"
                    onclick="document.getElementById('image_upload_v6').click()">
                    
                    <!-- INPUT FILE SEKARANG DI SINI, TAPI DIBUAT TIDAK TERLIHAT -->
                    <input id="image_upload_v6" name="image" type="file" class="hidden" @change="previewFile" accept="image/*" required>

                    <!-- Preview Gambar - Selalu ABSOLUTE untuk overlay (dibatalkan, cukup flex center) -->
                    <div x-show="imagePreview" class="w-full h-full flex items-center justify-center">
                        <img :src="imagePreview" alt="Image Preview" class="max-h-full max-w-full object-contain rounded-2xl shadow-xl border-4 border-white">
                    </div>

                    <!-- Placeholder Drag and Drop - Menggunakan x-show -->
                    <div x-show="!imagePreview" class="w-full h-full flex flex-col items-center justify-center text-gray-600">
                        <!-- Icon Upload (dikecilkan sedikit) -->
                        <svg class="h-16 w-16 text-gray-400 group-hover:text-green-600 transition-colors mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        
                        <!-- Teks Utama: Drop, Upload or Paste Images (Sesuai Permintaan) -->
                        <p class="text-3xl font-bold text-gray-800 tracking-tight mb-2">
                            Drop, Upload or Paste Images
                        </p>
                        
                        <!-- Daftar Format: Supported formats... (Sesuai Permintaan) -->
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

            <!-- 2. PROMPT INPUT --><div class="border-b pb-8 border-gray-200">
                <label for="prompt_input" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                    <span class="bg-green-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">2</span> Deskripsikan Video Anda (Prompt)
                </label>
                <textarea
                    id="prompt_input"
                    name="prompt"
                    rows="4"
                    x-model="promptText"
                    required
                    placeholder="Contoh: 'pemandangan pura dari atas dengan nuansa senja'"
                    class="shadow-lg focus:ring-green-600 focus:border-green-600 block w-full text-base border-gray-300 rounded-xl p-4 transition duration-200 resize-y"></textarea>
                <p class="mt-3 text-sm text-gray-500">
                    Gunakan deskripsi yang detail dan spesifik untuk mendapatkan hasil video yang maksimal.
                </p>
            </div>

            <!-- 3. TOMBOL SUBMIT --><div class="flex flex-col items-center">
                <button
                    type="submit"
                    :disabled="isGenerating"
                    class="
                        w-full flex justify-center items-center py-4 px-6 border border-transparent 
                        rounded-xl shadow-xl text-xl font-extrabold text-white 
                        transition duration-300 transform 
                        hover:scale-[1.01]
                        /* Kelas Default - Warna Putih (text-white) dipertahankan */
                        bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-70 shadow-green-500/50
                    "
                    :class="{
                        /* Kelas Kondisional (Override) */
                        'bg-green-400 cursor-not-allowed animate-pulse shadow-green-300/50 hover:bg-green-400': isGenerating,
                    }">
                    
                    <!-- Teks Saat TIDAK GENERATING -->
                    <span x-show="!isGenerating">
                        Generate Video Sekarang!
                    </span>

                    <!-- Teks Saat GENERATING -->
                    <span x-show="isGenerating" class="flex items-center">
                        <!-- SVG Spinner dengan text-white --><svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <!-- Teks Status -->
                        <span x-text="jobId ? 'Video ID ' + jobId.substring(0, 8) + ' Sedang Diproses...' : 'Sedang Mengantri...'"></span>
                    </span>
                </button>
            </div>
            
            <!-- PESAN/STATUS NOTIFIKASI --><div x-show="message" x-text="message" x-transition:enter.duration.500ms x-cloak
                 class="text-center p-4 rounded-xl font-medium border border-blue-300 bg-blue-100 text-blue-800 shadow-md">
            </div>

        </form>
    </div>
</div>

</body>
</html>
