import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import ModelViewer from './components/ModelViewer.vue';

window.Alpine = Alpine;

Alpine.start();

// Inisialisasi Vue
const app = createApp({});

// Daftarkan Komponen ModelViewer
// Komponen ini akan digunakan di Blade dengan tag <model-viewer>
app.component('model-viewer', ModelViewer);

// Mounting Vue ke kontainer di Blade
// ID '#threejs-viewer-container' hanya digunakan jika kita ingin mount Vue di elemen yang spesifik.
// Dalam konteks ini, kita akan menggunakan Vue secara global untuk kemudahan.
// Namun, karena <model-viewer> berada di dalam blok Alpine, kita biarkan Alpine menangani renderingnya.

// Kita mount Vue ke body atau elemen root agar komponen di dalamnya dapat diinisialisasi
// saat DOM siap. Karena kita menggunakan Blade, kita mount ke body untuk memastikan inisialisasi.
// app.mount('body');