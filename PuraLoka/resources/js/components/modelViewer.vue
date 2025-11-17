<template>
  <div ref="container" class="w-full h-full">
    <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-70 text-gray-700 font-bold text-xl">
        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memuat Model 3D...
    </div>
  </div>
</template>

<script>
// Mengimpor Three.js dan tambahan yang diperlukan
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';

export default {
  // Model URL di-inject dari Blade melalui prop
  props: {
    modelUrl: {
      type: String,
      required: true
    }
  },
  data() {
      return {
          scene: null,
          camera: null,
          renderer: null,
          controls: null,
          model: null,
          loading: true,
          animationFrameId: null, // Untuk mengelola requestAnimationFrame
      }
  },
  watch: {
    // Memuat ulang model setiap kali modelUrl berubah
    modelUrl(newUrl) {
      if (newUrl) {
        this.loadModel(newUrl);
      }
    }
  },
  mounted() {
    this.initThree();
    if (this.modelUrl) {
      this.loadModel(this.modelUrl);
    }
    window.addEventListener('resize', this.onWindowResize);
  },
  beforeUnmount() {
    // Membersihkan resources sebelum komponen dilepas
    if (this.renderer) {
      this.renderer.dispose();
    }
    if (this.animationFrameId) {
        cancelAnimationFrame(this.animationFrameId);
    }
    window.removeEventListener('resize', this.onWindowResize);
  },
  methods: {
    initThree() {
      const container = this.$refs.container;
      if (!container) return;
      
      // 1. Scene
      this.scene = new THREE.Scene();
      this.scene.background = new THREE.Color(0xf5f5f5); 

      // 2. Camera
      this.camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
      this.camera.position.set(2.5, 2.5, 4); 

      // 3. Renderer
      this.renderer = new THREE.WebGLRenderer({ antialias: true });
      this.renderer.setSize(container.clientWidth, container.clientHeight);
      this.renderer.setPixelRatio(window.devicePixelRatio);
      container.appendChild(this.renderer.domElement);

      // 4. Lights
      // Cahaya lembut dari atas
      this.scene.add(new THREE.AmbientLight(0xffffff, 0.8)); 
      // Cahaya direksional untuk highlight
      const directionalLight = new THREE.DirectionalLight(0xffffff, 0.6);
      directionalLight.position.set(5, 5, 5).normalize();
      this.scene.add(directionalLight);

      // 5. Controls (Interaksi Pengguna)
      this.controls = new OrbitControls(this.camera, this.renderer.domElement);
      this.controls.enableDamping = true; // Untuk putaran yang lebih mulus

      // Memulai loop animasi
      this.animate();
    },
    
    loadModel(url) {
        this.loading = true;
        // Membersihkan model sebelumnya
        if (this.model) {
            this.scene.remove(this.model);
            this.model.traverse(child => {
                if (child.isMesh) {
                    child.geometry.dispose();
                    if (Array.isArray(child.material)) {
                        child.material.forEach(m => m.dispose());
                    } else {
                        child.material.dispose();
                    }
                }
            });
            this.model = null;
        }

        const loader = new GLTFLoader();
        loader.load(
            url,
            (gltf) => {
                this.model = gltf.scene;
                this.scene.add(this.model);
                this.loading = false;
                
                // Secara otomatis menyesuaikan kamera dan kontrol agar model terlihat bagus
                const box = new THREE.Box3().setFromObject(this.model);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());
                const maxDim = Math.max(size.x, size.y, size.z);
                const cameraDistance = maxDim * 1.5;

                this.controls.target.copy(center);
                this.camera.position.set(center.x + cameraDistance, center.y + cameraDistance, center.z + cameraDistance);
                this.camera.far = maxDim * 5;
                this.camera.updateProjectionMatrix();
                this.controls.update();
            },
            (xhr) => {
                // Tampilkan persentase loading jika diinginkan (opsional)
                // console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
            },
            (error) => {
                this.loading = false;
                console.error('Terjadi kesalahan saat memuat model 3D:', error);
                // Di sini Anda bisa memancarkan event ke Alpine untuk menampilkan pesan error
            }
        );
    },
    
    animate() {
      this.animationFrameId = requestAnimationFrame(this.animate);
      if (this.controls) {
        this.controls.update(); 
      }
      if (this.renderer && this.scene && this.camera) {
        this.renderer.render(this.scene, this.camera);
      }
    },

    onWindowResize() {
        const container = this.$refs.container;
        if (!container || !this.camera || !this.renderer) return;

        this.camera.aspect = container.clientWidth / container.clientHeight;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(container.clientWidth, container.clientHeight);
    }
  }
}
</script>

<style scoped>
/* Pastikan kontainer Three.js menempati ruang penuh Vue Component */
div {
    position: relative;
}
</style>