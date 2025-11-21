@extends('layouts.app')

@section('content')

{{-- Script model-viewer tetap di sini --}}
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/1.12.0/model-viewer.min.js"></script>

<div class="bg-gray-50">
    <div class="min-h-screen bg-gradient-to-br from-emerald-900 to-emerald-600 flex flex-col items-center justify-center p-4 sm:p-6 lg:l-10">
        
        {{-- 
        ========================================================================
        PERUBAHAN UTAMA:
        Semua logika JS sekarang ada di dalam x-data="{...}" di bawah ini.
        ========================================================================
        --}}
        <!-- <div
            class="w-full max-w-5xl bg-white p-6 sm:p-12 rounded-[2rem] shadow-2xl transition-all duration-500 ease-in-out border border-gray-100 relative"
            x-data="{
                // ================== STATE APP ==================
                appState: 'input', 
                imagePreview: null,
                imageFile: null,
                promptText: '',
                
                // Status dan Polling
                jobId: null,
                pollTimer: null, 
                message: '',
                isError: false,
                
                // Data Output
                modelPath: null,
                
                // Konfigurasi API
                API_BASE_URL: 'https://mx5tmd8z-8080.asse.devtunnels.ms/api/v1',
                POLL_INTERVAL_MS: 5000,
                FETCH_TIMEOUT_MS: 60000,

                // ================== FUNGSI HELPER ==================

                /**
                 * Wrapper fetch dengan AbortController untuk timeout.
                 */
                async fetchWithTimeout(url, options = {}) {
                    const controller = new AbortController();
                    const id = setTimeout(() => controller.abort(), this.FETCH_TIMEOUT_MS);

                    try {
                        const response = await fetch(url, {
                            ...options,
                            signal: controller.signal 
                        });
                        return response;
                    } catch (error) {
                        if (error.name === 'AbortError') {
                            throw new Error('Request timeout. Server butuh waktu terlalu lama untuk merespons.');
                        }
                        throw error;
                    } finally {
                        clearTimeout(id);
                    }
                },

                /**
                 * Fungsi untuk menangani preview gambar
                 */
                previewFile(event) {
                    const file = event.target.files[0];
                    if (!file) {
                        this.imagePreview = null;
                        this.imageFile = null;
                        return;
                    }
                    
                    this.imageFile = file;
                    this.message = '';
                    this.isError = false;
                    
                    if (this.imagePreview) {
                        URL.revokeObjectURL(this.imagePreview);
                    }
                    this.imagePreview = URL.createObjectURL(file);
                },

                /**
                 * Helper untuk menghentikan polling dan menampilkan error
                 */
                showError(errorMessage) {
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    this.pollTimer = null;
                    this.isError = true;
                    this.message = '❌ Error: ' + errorMessage;
                    this.appState = 'processing';
                },

                /**
                 * Helper untuk menghentikan polling dan menampilkan sukses
                 */
                showSuccess(modelUrl) {
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    this.pollTimer = null;

                    console.log('URL Model yang Diterima:', modelUrl);

                    this.modelPath = modelUrl;
                    this.message = '✅ Model 3D Selesai!';
                    this.isError = false;
                    
                    setTimeout(() => { 
                        this.appState = 'output'; 
                    }, 500);
                },

                // ================== LOGIKA UTAMA (SUBMIT & POLL) ==================

                /**
                 * 1. SUBMIT GENERATION
                 */
                async submitGeneration() {
                    if (!this.imageFile) {
                        this.message = '⚠️ Mohon unggah gambar 2D.';
                        this.isError = true;
                        return;
                    }

                    this.isError = false;
                    this.jobId = null;
                    this.appState = 'processing'; 
                    this.message = 'Mengupload gambar...';

                    const formData = new FormData();
                    formData.append('image', this.imageFile);
                    formData.append('prompt', this.promptText.trim() || 'model 3d berdasarkan gambar'); 
                    
                    try {
                        const response = await this.fetchWithTimeout(`${this.API_BASE_URL}/image2d-to-3d`, { 
                            method: 'POST',
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            throw new Error(errorData.message || `Gagal mengirim data. Status: ${response.status}`);
                        }
                        
                        const result = await response.json();
                        
                        if (!result.job_id) { 
                            throw new Error('Server tidak mengembalikan Job ID.');
                        }

                        this.jobId = result.job_id; 
                        this.message = `Berhasil di-upload. Memulai status check untuk job [${this.jobId.substring(0, 8)}]...`;
                        
                        this.pollJobStatus(); 

                    } catch (error) {
                        this.showError(error.message);
                    }
                },

                /**
                 * 2. POLLING STATUS
                 */
                /**
                 * 2. POLLING STATUS (Refactored untuk Efisiensi)
                 * Logika disatukan untuk memanggil scheduleNextPoll() hanya sekali.
                 */
                async pollJobStatus() {
                    if (!this.jobId) return;

                    // Flag untuk menentukan apakah polling harus dilanjutkan
                    let shouldContinuePolling = false;

                    try {
                        const response = await this.fetchWithTimeout(`${this.API_BASE_URL}/status/${this.jobId}`);

                        console.log(`[Poll Status] Job ${this.jobId.substring(0, 8)}: Menerima HTTP Status ${response.status}`);

                        if (response.status === 404) {
                            // Job belum siap di backend, coba lagi
                            this.message = `Status [${this.jobId.substring(0, 8)}]: Menunggu inisialisasi job...`;
                            shouldContinuePolling = true;

                        } else if (!response.ok) {
                            // Error server (500, 502, dll.)
                            // Asumsikan ini error sementara dan coba lagi
                            this.message = `Server status error (${response.status}). Mencoba lagi...`;
                            shouldContinuePolling = true;

                        } else {
                            // Response sukses (200 OK)
                            const result = await response.json();
                            const status = (result.status || '').toLowerCase();

                            switch (status) {
                                case 'completed':
                                    const { filename, subfolder } = result;
                                    if (filename && subfolder) {
                                        const modelProxyURL = `${this.API_BASE_URL}/get-model?filename=${encodeURIComponent(filename)}&subfolder=${encodeURIComponent(subfolder)}`;
                                        this.showSuccess(modelProxyURL);
                                    } else {
                                        this.showError('Proses selesai, tapi backend tidak mengirim data file.');
                                    }
                                    // JANGAN set shouldContinuePolling (Berhenti)
                                    break;
                                
                                case 'failed':
                                    this.showError(result.error || 'Proses gagal karena error tidak diketahui.');
                                    // JANGAN set shouldContinuePolling (Berhenti)
                                    break;
                                
                                case 'processing':
                                    this.message = `Status [${this.jobId.substring(0, 8)}]: Masih memproses...`;
                                    shouldContinuePolling = true;
                                    break;
                                
                                case 'pending':
                                case 'queued':
                                    this.message = `Status [${this.jobId.substring(0, 8)}]: Menunggu antrian...`;
                                    shouldContinuePolling = true;
                                    break;
                                
                                default:
                                    this.message = `Status [${this.jobId.substring(0, 8)}]: Status tidak dikenal (${status}).`;
                                    shouldContinuePolling = true;
                            }
                        }
                        
                    } catch (error) {
                        // (Error seperti timeout atau network putus)
                        console.warn('Error saat polling:', error.message);
                        this.message = `Koneksi ke server status terputus... Mencoba lagi.`;
                        shouldContinuePolling = true; // Coba lagi
                    }

                    // --- PENGENDALI POLLING UTAMA ---
                    // Panggil scheduleNextPoll HANYA SEKALI di akhir, jika diperlukan.
                    if (shouldContinuePolling) {
                        this.scheduleNextPoll();
                    }
                },

                /**
                 * Helper untuk menjadwalkan poll berikutnya
                 */
                scheduleNextPoll() {
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    if (this.appState === 'processing' && !this.isError) {
                        this.pollTimer = setTimeout(() => {
                            this.pollJobStatus();
                        }, this.POLL_INTERVAL_MS);
                    }
                },

                /**
                 * RESET APP
                 */
                resetApp() {
                    if (this.pollTimer) clearTimeout(this.pollTimer);
                    if (this.imagePreview) URL.revokeObjectURL(this.imagePreview);

                    this.appState = 'input';
                    this.imagePreview = null;
                    this.imageFile = null;
                    this.promptText = '';
                    this.message = '';
                    this.jobId = null;
                    this.isError = false;
                    this.modelPath = null;
                    
                    const fileInput = document.getElementById('image_upload');
                    if (fileInput) fileInput.value = '';
                }
            }"
            x-cloak>  -->
            {{-- AKHIR DARI BLOK x-data --}}

            <div class="text-center mb-10">
                <h1 class="text-5xl font-extrabold text-gray-800 tracking-tight">2D ke 3D <span class="text-emerald-600">AI Generator</span></h1>
            </div>

            {{-- ====================================================== --}}
            {{-- 1. FORM INPUT (appState === 'input') --}}
            {{-- ====================================================== --}}
            <form x-show="appState === 'input'" @submit.prevent="submitGeneration" class="space-y-10" 
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 transform translate-y-4">
                
                <p class="text-gray-500 mt-3 text-lg">Ubah gambar 2D Anda menjadi model 3D interaktif yang siap pakai.</p>

                {{-- Bagian Upload Gambar --}}
                <div class="border-b pb-8 border-gray-200">
                    <label for="image_upload" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                        <span class="bg-emerald-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">1</span> Unggah Gambar 2D Anda
                    </label>
                    <div
                        class="relative mt-1 flex items-center justify-center border-4 border-dashed rounded-3xl p-8 transition duration-300 h-96 cursor-pointer group shadow-inner"
                        :class="{'border-emerald-600 bg-emerald-50/50': imagePreview, 'border-gray-300 hover:border-emerald-500 hover:bg-gray-50': !imagePreview}"
                        onclick="document.getElementById('image_upload').click()">

                        <input id="image_upload" name="image" type="file" class="hidden" @change="previewFile" accept="image/*">

                        {{-- Tampilan Preview Gambar --}}
                        <div x-show="imagePreview" class="w-full h-full flex items-center justify-center">
                            <img :src="imagePreview" alt="Image Preview" class="max-h-full max-w-full object-contain rounded-2xl shadow-xl border-4 border-white">
                        </div>

                        {{-- Tampilan Dropzone Awal --}}
                        <div x-show="!imagePreview" class="w-full h-full flex flex-col items-center justify-center text-gray-600">
                            <svg class="h-16 w-16 text-gray-400 group-hover:text-emerald-600 transition-colors mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <p class="text-3xl font-bold text-gray-800 tracking-tight mb-2">Pilih/Seret Gambar</p>
                            <p class="text-base text-gray-500 font-medium text-center">Supported formats: JPG, PNG, dll.</p>
                        </div>
                    </div>
                    <p x-show="imageFile" class="mt-4 text-base text-gray-700 text-center font-semibold truncate" x-text="'File dipilih: ' + imageFile?.name"></p>
                </div>

                {{-- Bagian Input Prompt --}}
                <!-- <div class="border-b pb-8 border-gray-200">
                    <label for="prompt_input" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                        <span class="bg-emerald-600 text-white w-9 h-9 flex items-center justify-center rounded-full mr-3 text-xl font-black">2</span> Deskripsikan Model 3D (Opsional)
                    </label>
                    <textarea
                        id="prompt_input"
                        name="prompt"
                        rows="3"
                        x-model="promptText"
                        placeholder="Contoh: 'model 3d robot minimalis, warna perak mengkilap'"
                        class="shadow-lg focus:ring-emerald-600 focus:border-emerald-600 block w-full text-base border-gray-300 rounded-xl p-4 transition duration-200 resize-y"></textarea>
                    <p class="mt-3 text-sm text-gray-500">
                        Gunakan deskripsi spesifik jika diperlukan untuk memandu AI.
                    </p>
                </div> -->

                {{-- Tombol Submit --}}
                <div class="flex flex-col items-center">
                    <button
                        type="submit"
                        :disabled="!imageFile"
                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent 
                            rounded-xl shadow-xl text-xl font-extrabold text-white 
                            transition duration-300 transform 
                            hover:scale-[1.01]
                            bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500 focus:ring-opacity-70"
                        :class="{ 'bg-gray-400 cursor-not-allowed hover:bg-gray-400': !imageFile }">
                        <span x-text="imageFile ? 'Upload dan Proses' : 'Pilih Gambar Dulu'"></span>
                    </button>
                </div>
            </form>

            {{-- ====================================================== --}}
            {{-- 2. PROCESSING STATE (appState === 'processing') --}}
            {{-- ====================================================== --}}
            <div x-show="appState === 'processing'" id="statusArea" class="space-y-8 text-center"
                 x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 transform scale-95">

                {{-- Spinner, disembunyikan jika error --}}
                <div x-show="!isError" class="flex justify-center spinner">
                    <svg class="animate-spin h-20 w-20 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                {{-- Pesan Status (Bisa error atau info) --}}
                <div id="statusMessage"
                     x-text="message" 
                     class="p-4 rounded-xl font-semibold border shadow-md text-xl break-words"
                     :class="{
                         'border-red-300 bg-red-100 text-red-800': isError,
                         'border-blue-300 bg-blue-100 text-blue-800': !isError
                     }">
                </div>
                
                {{-- Tombol "Coba Lagi" hanya muncul saat error --}}
                <button 
                    x-show="isError" 
                    @click="resetApp"
                    class="link-button flex items-center justify-center py-3 px-6 rounded-xl font-bold text-lg text-white bg-red-600 hover:bg-red-700 transition duration-200 shadow-md">
                    Coba Lagi
                </button>
            </div>

            {{-- ====================================================== --}}
            {{-- 3. OUTPUT STATE (appState === 'output') --}}
            {{-- ====================================================== --}}
            <div x-show="appState === 'output'" id="resultArea" class="space-y-10 text-center" 
                x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 transform scale-95">

                <h2 class="text-4xl font-bold text-gray-800">Hasil 3D Model Anda</h2>
                <p class="text-gray-600 text-lg">Model 3D Anda telah selesai. Putar untuk melihatnya dari semua sisi.</p>

                <div class="relative w-full aspect-[4/3] rounded-2xl shadow-2xl overflow-hidden mx-auto bg-gray-200 border-4 border-white">
                    <template x-if="modelPath">
                        <model-viewer 
                            :src="modelPath" 
                            alt="3D Model Hasil AI" 
                            ar 
                            ar-modes="webxr scene-viewer quick-look" 
                            camera-controls 
                            touch-action="pan-y"
                            class="w-full h-full"
                            shadow-intensity="1"
                            auto-rotate>
                        </model-viewer>
                    </template>
                </div>

                {{-- Bagian Download dan Reset --}}
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-lg">
                    <p class="text-lg font-semibold text-gray-700 mb-6">Jalur Model: <span class="text-gray-500 italic break-all text-sm" x-text="modelPath"></span></p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        {{-- Tombol Download --}}
                        <a :href="modelPath" download="ai_generated_3d_model.glb" id="modelLink"
                            class="flex items-center justify-center py-3 px-6 rounded-xl font-bold text-lg text-white bg-emerald-600 hover:bg-emerald-700 transition duration-200 shadow-md shadow-emerald-500/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Model (.GLB)
                        </a>

                        {{-- Tombol Reset/Buat Baru --}}
                        <button @click="resetApp" id="resetButton"
                            class="flex items-center justify-center py-3 px-6 rounded-xl font-bold text-lg text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-200 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                            Buat Model Baru
                        </button>
                    </div>
                </div>
            </div> {{-- Akhir dari appState 'output' --}}
        </div> {{-- Akhir dari x-data --}}
    </div>
</div>



@endsection