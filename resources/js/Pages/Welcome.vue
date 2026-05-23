<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
});

const features = [
    {
        icon: `<path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="currentColor"/>
               <path d="M6 11H8C8.6 11 9 10.6 9 10V8C9 7.4 8.6 7 8 7H6C5.4 7 5 7.4 5 8V10C5 10.6 5.4 11 6 11ZM12 11H14C14.6 11 15 10.6 15 10V8C15 7.4 14.6 7 14 7H12C11.4 7 11 7.4 11 8V10C11 10.6 11.4 11 12 11ZM18 11H20C20.6 11 21 10.6 21 10V8C21 7.4 20.6 7 20 7H18C17.4 7 17 7.4 17 8V10C17 10.6 17.4 11 18 11ZM6 17H8C8.6 17 9 16.6 9 16V14C9 13.4 8.6 13 8 13H6C5.4 13 5 13.4 5 14V16C5 16.6 5.4 17 6 17ZM12 17H14C14.6 17 15 16.6 15 16V14C15 13.4 14.6 13 14 13H12C11.4 13 11 13.4 11 14V16C11 16.6 11.4 17 12 17ZM18 17H20C20.6 17 21 16.6 21 16V14C21 13.4 20.6 13 20 13H18C17.4 13 17 13.4 17 14V16C17 16.6 17.4 17 18 17Z" fill="currentColor"/>`,
        color: 'primary',
        title: 'Kasir Cepat & Tepat',
        desc: 'Proses transaksi dalam hitungan detik. Interface yang intuitif membuat kasir bekerja tanpa hambatan.',
    },
    {
        icon: `<path opacity="0.3" d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z" fill="currentColor"/>
               <path d="M14 2L20 8H14V2Z" fill="currentColor"/>
               <path d="M9 13H15C15.5523 13 16 12.5523 16 12C16 11.4477 15.5523 11 15 11H9C8.44772 11 8 11.4477 8 12C8 12.5523 8.44772 13 9 13ZM9 17H15C15.5523 17 16 16.5523 16 16C16 15.4477 15.5523 15 15 15H9C8.44772 15 8 15.4477 8 16C8 16.5523 8.44772 17 9 17Z" fill="currentColor"/>`,
        color: 'success',
        title: 'Laporan Otomatis',
        desc: 'Laporan harian, mingguan, dan bulanan dibuat otomatis. Analisis profit secara real-time.',
    },
    {
        icon: `<path opacity="0.3" d="M12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7C16 9.20914 14.2091 11 12 11Z" fill="currentColor"/>
               <path d="M3.00065 20.1992C3.38826 15.4265 7.26191 13 11.9833 13C16.7712 13 20.7049 15.2932 20.9979 20.2C21.0096 20.3955 20.9979 21 20.2467 21C16.5411 21 11.0347 21 3.7275 21C3.0628 21 2.9862 20.4592 3.00065 20.1992Z" fill="currentColor"/>`,
        color: 'warning',
        title: 'Manajemen Member',
        desc: 'Program loyalitas pelanggan dengan sistem poin terintegrasi. Tingkatkan retensi pelanggan.',
    },
    {
        icon: `<path opacity="0.3" d="M16 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H16C17.1046 20 18 19.1046 18 18V6C18 4.89543 17.1046 4 16 4Z" fill="currentColor"/>
               <path d="M11 10H15C15.5523 10 16 10.4477 16 11C16 11.5523 15.5523 12 15 12H11C10.4477 12 10 11.5523 10 11C10 10.4477 10.4477 10 11 10ZM11 15H15C15.5523 15 16 15.4477 16 16C16 16.5523 15.5523 17 15 17H11C10.4477 17 10 16.5523 10 16C10 15.4477 10.4477 15 11 15ZM6 11C5.44772 11 5 11.4477 5 12C5 12.5523 5.44772 13 6 13C6.55228 13 7 12.5523 7 12C7 11.4477 6.55228 11 6 11ZM6 16C5.44772 16 5 16.4477 5 17C5 17.5523 5.44772 18 6 18C6.55228 18 7 17.5523 7 17C7 16.4477 6.55228 16 6 16Z" fill="currentColor"/>`,
        color: 'info',
        title: 'Stok Real-time',
        desc: 'Pantau stok produk secara real-time. Notifikasi otomatis saat stok hampir habis.',
    },
    {
        icon: `<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5773C9.56752 21.0244 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0244 16.5541 19.5773C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
               <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24181 14.7183 8.24181 14.4272 8.53714L11.2659 11.3042C11.0608 11.5099 10.7657 11.5099 10.5606 11.3042Z" fill="currentColor"/>`,
        color: 'danger',
        title: 'Multi-Toko',
        desc: 'Kelola beberapa toko dalam satu platform. Dashboard terpusat untuk semua cabang bisnis Anda.',
    },
    {
        icon: `<path opacity="0.3" d="M3 13C3 12.4 3.4 12 4 12H20C20.6 12 21 12.4 21 13V21C21 21.6 20.6 22 20 22H4C3.4 22 3 21.6 3 21V13Z" fill="currentColor"/>
               <path d="M3 11L12 2L21 11H3Z" fill="currentColor"/>`,
        color: 'dark',
        title: 'Multi-Payment',
        desc: 'Terima pembayaran tunai, kartu, dan dompet digital. Terintegrasi dengan Midtrans.',
    },
];

const stats = [
    { val: '99.9%', label: 'Uptime Server' },
    { val: '< 1 menit', label: 'Proses Transaksi' },
    { val: '100%', label: 'Data Aman' },
    { val: '24/7', label: 'Support Tersedia' },
];

const barData = [
    { label: 'Sab', pct: 38, active: false },
    { label: 'Min', pct: 22, active: false },
    { label: 'Sen', pct: 55, active: false },
    { label: 'Sel', pct: 47, active: false },
    { label: 'Rab', pct: 63, active: false },
    { label: 'Kam', pct: 70, active: false },
    { label: 'Jum', pct: 92, active: true },
];

const donutData = [
    { label: 'Kaos', pct: 44, color: '#3b82f6' },
    { label: 'Celana', pct: 26, color: '#10b981' },
    { label: 'Sepatu', pct: 18, color: '#f59e0b' },
    { label: 'Hoodie', pct: 12, color: '#ef4444' },
];
</script>

<template>
    <Head title="Welcome" />

    <div class="lp-root">

        <!-- ──────────────────── NAVBAR ──────────────────── -->
        <nav class="lp-nav">
            <div class="lp-nav-inner">
                <!-- Brand -->
                <div class="lp-brand">
                    <div class="lp-brand-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M9 22V12h6v10" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="lp-brand-text">POS<span>OWII</span></span>
                </div>

                <!-- Actions -->
                <div class="lp-nav-actions">
                    <Link v-if="canLogin" :href="route('login')" class="lp-btn-ghost">
                        Masuk
                    </Link>
                    <Link v-if="canLogin" :href="route('login')" class="lp-btn-solid">
                        Mulai Sekarang
                        <i class="ki-outline ki-arrow-right ms-2 fs-6"></i>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- ──────────────────── HERO ──────────────────── -->
        <section class="lp-hero">
            <!-- Decorative blobs -->
            <div class="lp-blob lp-blob-1"></div>
            <div class="lp-blob lp-blob-2"></div>
            <div class="lp-blob lp-blob-3"></div>

            <div class="lp-hero-inner">
                <!-- Badge -->
                <div class="mb-7">
                    <span class="badge badge-light-primary fs-8 fw-bold px-5 py-3 rounded-pill ls-wide text-uppercase">
                        ✦ Point of Sale System
                    </span>
                </div>

                <!-- Headline -->
                <h1 class="lp-headline">
                    Kelola toko Anda<br/>
                    <span class="lp-gradient-text">lebih mudah & cepat</span>
                </h1>

                <!-- Subtext -->
                <p class="lp-subtext">
                    Platform manajemen penjualan lengkap dengan kasir digital, laporan otomatis,
                    dan manajemen stok real-time — semuanya dalam satu genggaman.
                </p>

                <!-- CTA Buttons -->
                <div class="lp-cta-group">
                    <Link v-if="canLogin" :href="route('login')" class="lp-cta-primary">
                        <i class="ki-outline ki-rocket fs-4 me-2"></i>
                        Mulai Gratis
                    </Link>
                    <a href="#features" class="lp-cta-secondary">
                        <i class="ki-outline ki-information-5 fs-4 me-2"></i>
                        Pelajari Fitur
                    </a>
                </div>

                <!-- Social proof -->
                <div class="lp-social-proof mt-12">
                    <div class="d-flex align-items-center gap-3">
                        <div class="lp-avatars">
                            <span class="lp-avatar lp-av-1">A</span>
                            <span class="lp-avatar lp-av-2">B</span>
                            <span class="lp-avatar lp-av-3">C</span>
                            <span class="lp-avatar lp-av-4">D</span>
                        </div>
                        <div>
                            <div class="lp-stars">
                                <i class="ki-solid ki-star text-warning fs-7"></i>
                                <i class="ki-solid ki-star text-warning fs-7"></i>
                                <i class="ki-solid ki-star text-warning fs-7"></i>
                                <i class="ki-solid ki-star text-warning fs-7"></i>
                                <i class="ki-solid ki-star text-warning fs-7"></i>
                            </div>
                            <span class="text-muted fs-8 fw-semibold">Dipercaya 100+ pemilik toko</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Visual: Dashboard Preview -->
            <div class="lp-hero-visual">
                <div class="lp-dashboard-card">

                    <!-- Browser chrome bar -->
                    <div class="lp-dc-header">
                        <div class="d-flex align-items-center gap-2">
                            <div class="lp-dc-dot red"></div>
                            <div class="lp-dc-dot yellow"></div>
                            <div class="lp-dc-dot green"></div>
                        </div>
                        <div class="lp-dc-url">dashboard/posowi.com</div>
                        <div></div>
                    </div>

                    <!-- Dashboard body -->
                    <div class="lp-dc-body">

                        <!-- Top bar -->
                        <div class="lp-db-topbar">
                            <div>
                                <div class="lp-db-page-title">Dashboard</div>
                                <div class="lp-db-page-sub">Ringkasan toko hari ini</div>
                            </div>
                            <div class="lp-db-toko-badge">
                                <span class="lp-db-toko-dot"></span>
                                Toko Utama
                            </div>
                        </div>

                        <!-- Stat cards row -->
                        <div class="lp-stat-cards">
                            <div class="lp-sc primary">
                                <div class="lp-sc-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="#3b82f6"/>
                                        <path d="M6 11H8C8.6 11 9 10.6 9 10V8C9 7.4 8.6 7 8 7H6C5.4 7 5 7.4 5 8V10C5 10.6 5.4 11 6 11Z" fill="#3b82f6"/>
                                    </svg>
                                </div>
                                <div class="lp-sc-label">PENDAPATAN HARI INI</div>
                                <div class="lp-sc-val">Rp 4.250.000</div>
                                <div class="lp-sc-sub primary">87 transaksi</div>
                            </div>
                            <div class="lp-sc info">
                                <div class="lp-sc-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M16 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H16C17.1046 20 18 19.1046 18 18V6C18 4.89543 17.1046 4 16 4Z" fill="#06b6d4"/>
                                        <path d="M11 10H15C15.5523 10 16 10.4477 16 11C16 11.5523 15.5523 12 15 12H11C10.4477 12 10 11.5523 10 11C10 10.4477 10.4477 10 11 10Z" fill="#06b6d4"/>
                                    </svg>
                                </div>
                                <div class="lp-sc-label">TOTAL PRODUK</div>
                                <div class="lp-sc-val">248</div>
                                <div class="lp-sc-sub warning">⚠ 3 stok menipis</div>
                            </div>
                            <div class="lp-sc warning">
                                <div class="lp-sc-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7C16 9.20914 14.2091 11 12 11Z" fill="#f59e0b"/>
                                        <path d="M3 20.1992C3.38826 15.4265 7.26191 13 11.9833 13C16.7712 13 20.7049 15.2932 20.9979 20.2C21.0096 20.3955 20.9979 21 20.2467 21H3.7275C3.0628 21 2.9862 20.4592 3 20.1992Z" fill="#f59e0b"/>
                                    </svg>
                                </div>
                                <div class="lp-sc-label">TOTAL MEMBER</div>
                                <div class="lp-sc-val">1.204</div>
                                <div class="lp-sc-sub warning">Member Aktif</div>
                            </div>
                            <div class="lp-sc danger">
                                <div class="lp-sc-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5773C9.56752 21.0244 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0244 16.5541 19.5773C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="#ef4444"/>
                                    </svg>
                                </div>
                                <div class="lp-sc-label">STOK MENIPIS</div>
                                <div class="lp-sc-val">3</div>
                                <div class="lp-sc-sub danger">Produk perlu diisi</div>
                            </div>
                        </div>

                        <!-- Charts row -->
                        <div class="lp-charts-row">
                            <!-- Bar chart -->
                            <div class="lp-chart-box">
                                <div class="lp-chart-top">
                                    <div>
                                        <div class="lp-chart-title">Pendapatan 7 Hari Terakhir</div>
                                        <div class="lp-chart-sub">Total penjualan per hari</div>
                                    </div>
                                    <span class="lp-chart-badge">87 Transaksi Hari Ini</span>
                                </div>
                                <!-- Bar chart body -->
                                <div class="lp-bar-chart">
                                    <div class="lp-bar-y-labels">
                                        <span>Rp 5jt</span>
                                        <span>Rp 4jt</span>
                                        <span>Rp 3jt</span>
                                        <span>Rp 2jt</span>
                                        <span>Rp 1jt</span>
                                        <span>Rp 0</span>
                                    </div>
                                    <div class="lp-bar-area">
                                        <div class="lp-bar-grid">
                                            <div class="lp-bar-gridline" v-for="i in 5" :key="i"></div>
                                        </div>
                                        <div class="lp-bars-wrap">
                                            <div class="lp-bw" v-for="b in barData" :key="b.label">
                                                <div class="lp-bv" :style="{height: b.pct+'%'}" :class="b.active ? 'lp-bv-active' : ''"></div>
                                                <span class="lp-bl">{{ b.label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Donut chart -->
                            <div class="lp-donut-box">
                                <div class="lp-chart-title">Produk Terlaris</div>
                                <div class="lp-chart-sub">Bulan ini berdasarkan qty</div>
                                <div class="lp-donut-wrap">
                                    <svg viewBox="0 0 100 100" class="lp-donut-svg">
                                        <!-- segments: total circumference 251.2 (r=40) -->
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="18"/>
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#3b82f6" stroke-width="18"
                                            stroke-dasharray="110 141.2" stroke-dashoffset="0" transform="rotate(-90 50 50)"/>
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#10b981" stroke-width="18"
                                            stroke-dasharray="65 186.2" stroke-dashoffset="-110" transform="rotate(-90 50 50)"/>
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#f59e0b" stroke-width="18"
                                            stroke-dasharray="45 206.2" stroke-dashoffset="-175" transform="rotate(-90 50 50)"/>
                                        <circle cx="50" cy="50" r="40" fill="none" stroke="#ef4444" stroke-width="18"
                                            stroke-dasharray="31.2 220" stroke-dashoffset="-220" transform="rotate(-90 50 50)"/>
                                        <circle cx="50" cy="50" r="28" fill="white"/>
                                    </svg>
                                </div>
                                <div class="lp-donut-legend">
                                    <div class="lp-dl-item" v-for="d in donutData" :key="d.label">
                                        <span class="lp-dl-dot" :style="{background: d.color}"></span>
                                        <span class="lp-dl-label">{{ d.label }}</span>
                                        <span class="lp-dl-pct">{{ d.pct }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Floating badge -->
                <div class="lp-float-badge">
                    <i class="ki-outline ki-verify fs-5 text-success me-2"></i>
                    <span>Stok diperbarui otomatis</span>
                </div>
                <div class="lp-float-badge lp-float-badge-2">
                    <i class="ki-outline ki-chart-simple fs-5 text-primary me-2"></i>
                    <span>Laporan siap diekspor</span>
                </div>
            </div>
        </section>

        <!-- ──────────────────── STATS BAR ──────────────────── -->
        <section class="lp-stats-bar">
            <div class="lp-stats-inner">
                <div v-for="s in stats" :key="s.label" class="lp-stat-item">
                    <div class="lp-stat-val">{{ s.val }}</div>
                    <div class="lp-stat-label">{{ s.label }}</div>
                </div>
            </div>
        </section>

        <!-- ──────────────────── FEATURES ──────────────────── -->
        <section id="features" class="lp-section">
            <div class="lp-section-inner">
                <!-- Section header -->
                <div class="text-center mb-16">
                    <span class="badge badge-light-primary fs-8 fw-bold px-4 py-2 rounded-pill mb-5 text-uppercase ls-wide">
                        Fitur Unggulan
                    </span>
                    <h2 class="lp-section-title">Semua yang Anda butuhkan<br/>ada di sini</h2>
                    <p class="lp-section-sub">
                        Dirancang khusus untuk bisnis retail Indonesia — dari warung kecil hingga jaringan toko.
                    </p>
                </div>

                <!-- Feature grid -->
                <div class="lp-features-grid">
                    <div
                        v-for="f in features"
                        :key="f.title"
                        class="lp-feature-card"
                    >
                        <div :class="`lp-feature-icon bg-light-${f.color}`">
                            <span :class="`svg-icon svg-icon-2x svg-icon-${f.color}`">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" v-html="f.icon"></svg>
                            </span>
                        </div>
                        <h3 class="lp-feature-title">{{ f.title }}</h3>
                        <p class="lp-feature-desc">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ──────────────────── CTA BANNER ──────────────────── -->
        <section class="lp-cta-section">
            <div class="lp-cta-inner">
                <div class="lp-blob lp-blob-cta-1"></div>
                <div class="lp-blob lp-blob-cta-2"></div>

                <div class="text-center position-relative z-index-1">
                    <h2 class="lp-cta-title">Siap memulai?</h2>
                    <p class="lp-cta-sub">
                        Bergabunglah dengan ratusan pemilik toko yang sudah mengelola bisnis mereka lebih efisien.
                    </p>
                    <Link v-if="canLogin" :href="route('login')" class="lp-cta-main">
                        <i class="ki-outline ki-rocket fs-4 me-2"></i>
                        Masuk ke Dashboard
                    </Link>
                    <div class="mt-6 text-muted fs-8">
                        Tidak perlu kartu kredit · Setup dalam 5 menit
                    </div>
                </div>
            </div>
        </section>

        <!-- ──────────────────── FOOTER ──────────────────── -->
        <footer class="lp-footer">
            <div class="lp-footer-inner">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="lp-brand-icon" style="width:28px;height:28px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M9 22V12h6v10" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="lp-brand-text">POS<span>OWII</span></span>
                </div>
                <p class="text-muted fs-8 mb-0">
                    &copy; 2026 POSOWI · Platform manajemen toko modern
                </p>
                <a href="https://github.com/markumarkntl" target="_blank" class="lp-github-link mt-3">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                    </svg>
                    <span>@markumarkntl</span>
                </a>
            </div>
        </footer>

    </div>
</template>

<style scoped>
/* ─── Root ─── */
.lp-root {
    font-family: 'Inter', sans-serif;
    background: #fff;
    min-height: 100vh;
    overflow-x: hidden;
}

/* ─── Navbar ─── */
.lp-nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.lp-nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.lp-nav-actions { display: flex; align-items: center; gap: 12px; }

/* ─── Brand ─── */
.lp-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
.lp-brand-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #6366f1, #818cf8);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(99,102,241,0.35);
    flex-shrink: 0;
}
.lp-brand-text {
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: #111827;
}
.lp-brand-text span { color: #6366f1; }

/* ─── Nav Buttons ─── */
.lp-btn-ghost {
    padding: 8px 18px;
    font-size: 13.5px;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    border-radius: 8px;
    transition: background .2s, color .2s;
}
.lp-btn-ghost:hover { background: #f3f4f6; color: #111827; }
.lp-btn-solid {
    display: inline-flex;
    align-items: center;
    padding: 9px 20px;
    font-size: 13.5px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    transition: all .25s;
}
.lp-btn-solid:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99,102,241,0.45);
    color: #fff;
}

/* ─── HERO ─── */
.lp-hero {
    min-height: 100vh;
    padding-top: 68px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    gap: 4rem;
    max-width: 1200px;
    margin: 0 auto;
    padding-left: 2rem;
    padding-right: 2rem;
}
.lp-hero-inner {
    flex: 1;
    max-width: 560px;
    position: relative;
    z-index: 2;
}

/* Blobs */
.lp-blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
}
.lp-blob-1 {
    width: 500px; height: 500px;
    top: -150px; right: -150px;
    background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
}
.lp-blob-2 {
    width: 400px; height: 400px;
    bottom: -100px; left: -100px;
    background: radial-gradient(circle, rgba(168,85,247,0.08) 0%, transparent 70%);
}
.lp-blob-3 {
    width: 300px; height: 300px;
    top: 40%; left: 40%;
    background: radial-gradient(circle, rgba(59,130,246,0.06) 0%, transparent 70%);
}

/* Hero text */
.lp-headline {
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 800;
    letter-spacing: -1px;
    line-height: 1.15;
    color: #0f172a;
    margin-bottom: 1.5rem;
}
.lp-gradient-text {
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.lp-subtext {
    font-size: 16px;
    color: #64748b;
    line-height: 1.8;
    margin-bottom: 2.5rem;
    max-width: 480px;
}

/* CTA group */
.lp-cta-group { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.lp-cta-primary {
    display: inline-flex;
    align-items: center;
    padding: 14px 28px;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(99,102,241,0.4);
    transition: all .3s;
}
.lp-cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(99,102,241,0.5);
    color: #fff;
}
.lp-cta-secondary {
    display: inline-flex;
    align-items: center;
    padding: 14px 24px;
    font-size: 15px;
    font-weight: 600;
    color: #374151;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    transition: all .25s;
}
.lp-cta-secondary:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #111827;
    transform: translateY(-1px);
}

/* Social proof */
.lp-avatars { display: flex; }
.lp-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    margin-left: -8px;
}
.lp-avatar:first-child { margin-left: 0; }
.lp-av-1 { background: #6366f1; }
.lp-av-2 { background: #10b981; }
.lp-av-3 { background: #f59e0b; }
.lp-av-4 { background: #ef4444; }
.lp-stars { display: flex; gap: 1px; margin-bottom: 2px; }

/* ─── Hero Visual ─── */
.lp-hero-visual {
    flex: 1;
    max-width: 560px;
    position: relative;
    z-index: 2;
}

/* Dashboard card wrapper */
.lp-dashboard-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.09);
    box-shadow: 0 24px 72px rgba(0,0,0,0.13), 0 4px 16px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* Browser chrome */
.lp-dc-header {
    padding: 10px 14px;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.lp-dc-dot { width: 9px; height: 9px; border-radius: 50%; }
.lp-dc-dot.red   { background: #ef4444; }
.lp-dc-dot.yellow{ background: #f59e0b; }
.lp-dc-dot.green { background: #10b981; }
.lp-dc-url {
    font-size: 10px;
    color: #94a3b8;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 3px 10px;
    border-radius: 4px;
    font-family: monospace;
}
.lp-dc-body { padding: 12px; }

/* Dashboard top bar */
.lp-db-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.lp-db-page-title { font-size: 13px; font-weight: 700; color: #0f172a; }
.lp-db-page-sub   { font-size: 10px; color: #94a3b8; margin-top: 1px; }
.lp-db-toko-badge {
    display: flex; align-items: center; gap: 5px;
    font-size: 10px; font-weight: 600;
    color: #6366f1;
    background: #eff6ff;
    padding: 4px 10px; border-radius: 20px;
}
.lp-db-toko-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #6366f1;
}

/* Stat cards */
.lp-stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 7px; margin-bottom: 10px; }
.lp-sc {
    padding: 10px 10px 8px;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}
.lp-sc.primary { background: #eff6ff; }
.lp-sc.info    { background: #ecfeff; }
.lp-sc.warning { background: #fffbeb; }
.lp-sc.danger  { background: #fff1f2; }
.lp-sc-icon { position: absolute; top: 8px; right: 8px; opacity: 0.7; }
.lp-sc-label {
    font-size: 8px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 4px;
}
.lp-sc.primary .lp-sc-label { color: #3b82f6; }
.lp-sc.info    .lp-sc-label { color: #06b6d4; }
.lp-sc.warning .lp-sc-label { color: #f59e0b; }
.lp-sc.danger  .lp-sc-label { color: #ef4444; }
.lp-sc-val {
    font-size: 16px; font-weight: 800;
    color: #0f172a; line-height: 1; margin-bottom: 4px;
}
.lp-sc-sub { font-size: 9px; font-weight: 600; }
.lp-sc-sub.primary { color: #3b82f6; }
.lp-sc-sub.warning { color: #f59e0b; }
.lp-sc-sub.danger  { color: #ef4444; }

/* Charts row */
.lp-charts-row { display: grid; grid-template-columns: 1fr 0.55fr; gap: 8px; }

/* Bar chart box */
.lp-chart-box {
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px;
}
.lp-donut-box {
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px;
    display: flex; flex-direction: column;
}
.lp-chart-top {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 8px;
}
.lp-chart-title { font-size: 10px; font-weight: 700; color: #1e293b; }
.lp-chart-sub   { font-size: 9px; color: #94a3b8; margin-top: 1px; }
.lp-chart-badge {
    font-size: 8px; font-weight: 600;
    color: #6366f1;
    background: #eff6ff;
    padding: 3px 7px; border-radius: 20px;
    white-space: nowrap;
}

/* Bar chart internals */
.lp-bar-chart { display: flex; gap: 4px; height: 70px; }
.lp-bar-y-labels {
    display: flex; flex-direction: column;
    justify-content: space-between;
    font-size: 7px; color: #cbd5e1;
    padding-bottom: 14px;
    text-align: right;
    min-width: 24px;
}
.lp-bar-area { flex: 1; position: relative; }
.lp-bar-grid { position: absolute; inset: 0; bottom: 14px; display: flex; flex-direction: column; justify-content: space-between; }
.lp-bar-gridline { border-top: 1px dashed #e2e8f0; }
.lp-bars-wrap {
    position: absolute; inset: 0;
    display: flex; align-items: flex-end; gap: 3px;
    padding-bottom: 14px;
}
.lp-bw { flex: 1; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; gap: 2px; }
.lp-bv {
    width: 100%; border-radius: 3px 3px 0 0;
    background: #dbeafe;
    transition: height .3s;
}
.lp-bv-active { background: linear-gradient(to top, #3b82f6, #6366f1); }
.lp-bl { font-size: 7px; color: #94a3b8; font-weight: 500; position: absolute; bottom: 0; }

/* Donut chart */
.lp-donut-wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 4px 0; }
.lp-donut-svg { width: 72px; height: 72px; }
.lp-donut-legend { display: flex; flex-direction: column; gap: 3px; }
.lp-dl-item { display: flex; align-items: center; gap: 5px; }
.lp-dl-dot { width: 7px; height: 7px; border-radius: 2px; flex-shrink: 0; }
.lp-dl-label { font-size: 9px; color: #475569; flex: 1; }
.lp-dl-pct { font-size: 9px; font-weight: 700; color: #1e293b; }

/* Floating badges */
.lp-float-badge {
    position: absolute;
    left: -30px;
    bottom: 60px;
    background: #fff;
    border-radius: 50px;
    padding: 10px 16px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border: 1px solid #f1f5f9;
    animation: float 3s ease-in-out infinite;
}
.lp-float-badge-2 {
    left: auto;
    right: -20px;
    bottom: auto;
    top: 30px;
    animation-delay: 1.5s;
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

/* ─── Stats Bar ─── */
.lp-stats-bar {
    background: #0f172a;
    padding: 3rem 0;
}
.lp-stats-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 2rem;
}
.lp-stat-item { text-align: center; }
.lp-stat-val {
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #6366f1, #a855f7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
    margin-bottom: 6px;
}
.lp-stat-label { font-size: 13px; color: #94a3b8; font-weight: 500; }

/* ─── Section ─── */
.lp-section { padding: 6rem 0; }
.lp-section-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}
.lp-section-title {
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
    line-height: 1.2;
    margin-bottom: 1rem;
}
.lp-section-sub {
    font-size: 16px;
    color: #64748b;
    line-height: 1.7;
    max-width: 540px;
    margin: 0 auto;
}

/* Features Grid */
.lp-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
.lp-feature-card {
    padding: 2rem;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    background: #fff;
    transition: all .3s;
}
.lp-feature-card:hover {
    border-color: #e0e7ff;
    box-shadow: 0 12px 40px rgba(99,102,241,0.1);
    transform: translateY(-4px);
}
.lp-feature-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.25rem;
}
.lp-feature-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}
.lp-feature-desc {
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
    margin: 0;
}

/* ─── CTA Section ─── */
.lp-cta-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
}
.lp-cta-inner {
    max-width: 700px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    text-align: center;
}
.lp-blob-cta-1 {
    position: absolute;
    width: 400px; height: 400px;
    top: -200px; left: -150px;
    background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
    filter: blur(60px);
}
.lp-blob-cta-2 {
    position: absolute;
    width: 300px; height: 300px;
    bottom: -150px; right: -100px;
    background: radial-gradient(circle, rgba(168,85,247,0.2) 0%, transparent 70%);
    filter: blur(60px);
}
.lp-cta-title {
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.5px;
    margin-bottom: 1rem;
}
.lp-cta-sub {
    font-size: 16px;
    color: rgba(255,255,255,0.65);
    line-height: 1.7;
    margin-bottom: 2rem;
}
.lp-cta-main {
    display: inline-flex;
    align-items: center;
    padding: 16px 36px;
    font-size: 16px;
    font-weight: 700;
    color: #1e1b4b;
    background: linear-gradient(135deg, #fff, #e0e7ff);
    border-radius: 14px;
    text-decoration: none;
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    transition: all .3s;
}
.lp-cta-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 40px rgba(0,0,0,0.35);
    color: #4f46e5;
}

/* ─── Footer ─── */
.lp-footer {
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    padding: 2.5rem 0;
}
.lp-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.lp-github-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    transition: color .2s;
}
.lp-github-link:hover { color: #6366f1; }

/* ─── Utility ─── */
.ls-wide { letter-spacing: 0.1em; }
.mb-7 { margin-bottom: 1.75rem; }
.mb-12 { margin-bottom: 3rem; }
.mb-16 { margin-bottom: 4rem; }
.mt-12 { margin-top: 3rem; }
.mt-6 { margin-top: 1.5rem; }
.z-index-1 { position: relative; z-index: 1; }

/* ─── Responsive ─── */
@media (max-width: 1024px) {
    .lp-hero {
        flex-direction: column;
        padding-top: calc(68px + 3rem);
        padding-bottom: 3rem;
        text-align: center;
        gap: 3rem;
    }
    .lp-hero-inner { max-width: 100%; }
    .lp-hero-visual { max-width: 500px; width: 100%; }
    .lp-subtext { margin: 0 auto 2.5rem; }
    .lp-cta-group { justify-content: center; }
    .lp-social-proof { justify-content: center; }
    .lp-features-grid { grid-template-columns: repeat(2, 1fr); }
    .lp-float-badge { display: none; }
}
@media (max-width: 640px) {
    .lp-features-grid { grid-template-columns: 1fr; }
    .lp-stats-inner { gap: 1.5rem; }
    .lp-nav-actions .lp-btn-ghost { display: none; }
}
</style>