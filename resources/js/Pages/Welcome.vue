<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="welcome-wrapper d-flex flex-column flex-center min-vh-100 px-5">
        
        <div class="blob-1"></div>
        <div class="blob-2"></div>

        <div class="text-center z-index-1">
            <div class="mb-6">
                <span class="badge badge-outline badge-primary text-uppercase ls-2 fw-bold px-4 py-3 fs-8">
                    0,01% garam cina
                </span>
            </div>

            <h1 class="display-3 fw-bolder text-gray-900 mb-6 tracking-tight">
                Kelola toko Anda <br/>
                <span class="text-gradient">lebih mudah & cepat</span>
            </h1>

            <p class="fs-4 text-gray-500 fw-semibold mb-10 max-w-600px mx-auto">
                Platform manajemen penjualan lengkap yang dirancang untuk efisiensi bisnis Anda. 
                Pantau semuanya dalam satu genggaman.
            </p>

            <div class="d-flex flex-center flex-column gap-5">
                <Link 
                    v-if="canLogin" 
                    :href="route('login')" 
                    class="btn-modern"
                >
                    <span>Masuk ke Dashboard</span>
                    <i class="ki-outline ki-arrow-right fs-2 ms-3"></i>
                </Link>

                <a 
                    href="https://github.com/markumarkntl" 
                    target="_blank" 
                    class="github-link d-flex align-items-center gap-2 mt-5 text-gray-600 text-hover-primary transition-all"
                >
                    <i class="fab fa-github fs-3"></i>
                    <span class="fw-bold fs-6">@markumarkntl</span>
                </a>
            </div>
        </div>

        <div class="position-absolute bottom-0 mb-10 text-gray-400 fs-7 fw-semibold">
            &copy; 2026 POS System. Built for excellence.
        </div>
    </div>
</template>

<style scoped>
/* Background Styling */
.welcome-wrapper {
    background-color: #ffffff;
    overflow: hidden;
    position: relative;
}

/* Efek Gradient pada Teks */
.text-gradient {
    background: linear-gradient(90deg, #6366f1, #a855f7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Tombol Modern Interaktif */
.btn-modern {
    display: inline-flex;
    align-items: center;
    padding: 1.25rem 2.5rem;
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
    background: #1a1d27;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.btn-modern:hover {
    transform: translateY(-5px);
    background: #000;
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    color: #fff;
}

.btn-modern:active {
    transform: translateY(0);
}

/* GitHub Link Animation */
.github-link {
    text-decoration: none;
    opacity: 0.7;
}

.github-link:hover {
    opacity: 1;
    transform: scale(1.05);
}

/* Ornamen Dekoratif (Blob) */
.blob-1 {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
}

.blob-2 {
    position: absolute;
    bottom: -150px;
    left: -150px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(168, 85, 247, 0.08) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
}

/* Utility */
.max-w-600px {
    max-width: 600px;
}

.ls-2 {
    letter-spacing: 0.2rem;
}

.transition-all {
    transition: all 0.3s ease;
}
</style>