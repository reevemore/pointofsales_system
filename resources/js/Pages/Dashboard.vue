<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Dashboard
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Ringkasan Kinerja Toko Anda</span>
                    </h1>
                </div>
            </div>

            <!-- Stat Cards Grid -->
            <div class="row g-6 mb-6">
                <!-- Today Revenue -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-primary position-relative overflow-hidden">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-primary position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20 22H4C3.4 22 3 21.6 3 21V3C3 2.4 3.4 2 4 2H20C20.6 2 21 2.4 21 3V21C21 21.6 20.6 22 20 22Z" fill="currentColor"/>
                                    <path d="M6 11H8C8.6 11 9 10.6 9 10V8C9 7.4 8.6 7 8 7H6C5.4 7 5 7.4 5 8V10C5 10.6 5.4 11 6 11Z" fill="currentColor"/>
                                    <path d="M12 11H14C14.6 11 15 10.6 15 10V8C15 7.4 14.6 7 14 7H12C11.4 7 11 7.4 11 8V10C11 10.6 11.4 11 12 11Z" fill="currentColor"/>
                                    <path d="M18 11H20C20.6 11 21 10.6 21 10V8C21 7.4 20.6 7 20 7H18C17.4 7 17 7.4 17 8V10C17 10.6 17.4 11 18 11Z" fill="currentColor"/>
                                    <path d="M6 17H8C8.6 17 9 16.6 9 16V14C9 13.4 8.6 13 8 13H6C5.4 13 5 13.4 5 14V16C5 16.6 5.4 17 6 17Z" fill="currentColor"/>
                                    <path d="M12 17H14C14.6 17 15 16.6 15 16V14C15 13.4 14.6 13 14 13H12C11.4 13 11 13.4 11 14V16C11 16.6 11.4 17 12 17Z" fill="currentColor"/>
                                    <path d="M18 17H20C20.6 17 21 16.6 21 16V14C21 13.4 20.6 13 20 13H18C17.4 13 17 13.4 17 14V16C17 16.6 17.4 17 18 17Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-primary fw-bold fs-8 text-uppercase">Pendapatan Hari Ini</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ formatRupiah(stats.pendapatan_hari_ini) }}</span>
                                <span class="text-primary fs-8 fw-semibold mt-2">{{ stats.transaksi_hari_ini }} transaksi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Produk -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-info position-relative overflow-hidden">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-info position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M16 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H16C17.1046 20 18 19.1046 18 18V6C18 4.89543 17.1046 4 16 4Z" fill="currentColor"/>
                                    <path d="M11 10C10.4477 10 10 10.4477 10 11C10 11.5523 10.4477 12 11 12H15C15.5523 12 16 11.5523 16 11C16 10.4477 15.5523 10 15 10H11Z" fill="currentColor"/>
                                    <path d="M11 15C10.4477 15 10 15.4477 10 16C10 16.5523 10.4477 17 11 17H15C15.5523 17 16 16.5523 16 16C16 15.4477 15.5523 15 15 15H11Z" fill="currentColor"/>
                                    <path d="M6 11C5.44772 11 5 11.4477 5 12C5 12.5523 5.44772 13 6 13C6.55228 13 7 12.5523 7 12C7 11.4477 6.55228 11 6 11Z" fill="currentColor"/>
                                    <path d="M6 16C5.44772 16 5 16.4477 5 17C5 17.5523 5.44772 18 6 18C6.55228 18 7 17.5523 7 17C7 16.4477 6.55228 16 6 16Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-info fw-bold fs-8 text-uppercase">Total Produk</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ stats.total_produk }}</span>
                                <span v-if="stokMenipis.length > 0" class="text-danger fs-8 fw-semibold mt-2">
                                    ⚠️ {{ stokMenipis.length }} stok menipis
                                </span>
                                <span v-else class="text-info fs-8 fw-semibold mt-2">Stok Tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Members -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-warning position-relative overflow-hidden">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-warning position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7C16 9.20914 14.2091 11 12 11Z" fill="currentColor"/>
                                    <path d="M3.00065 20.1992C3.38826 15.4265 7.26191 13 11.9833 13C16.7712 13 20.7049 15.2932 20.9979 20.2C21.0096 20.3955 20.9979 21 20.2467 21C16.5411 21 11.0347 21 3.7275 21C3.0628 21 2.9862 20.4592 3.00065 20.1992Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-warning fw-bold fs-8 text-uppercase">Total Member</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ stats.total_member }}</span>
                                <span class="text-warning fs-8 fw-semibold mt-2">Member Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Menipis Alert -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-danger position-relative overflow-hidden">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-danger position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5773C9.56752 21.0244 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0244 16.5541 19.5773C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                                    <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24181 14.7183 8.24181 14.4272 8.53714L11.2659 11.3042C11.0608 11.5099 10.7657 11.5099 10.5606 11.3042Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-danger fw-bold fs-8 text-uppercase">Stok Menipis</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ stokMenipis.length }}</span>
                                <span class="text-danger fs-8 fw-semibold mt-2">Produk perlu diisi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-6 mb-6">
                <!-- Revenue Chart -->
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Pendapatan 7 Hari Terakhir</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Total penjualan per hari</span>
                            </h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light-primary">{{ stats.transaksi_hari_ini }} Transaksi Hari Ini</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="!grafikPenjualan || grafikPenjualan.length === 0" class="text-center text-muted py-10">
                                <i class="ki-duotone ki-chart-line fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <p>Belum ada data transaksi</p>
                            </div>
                            <canvas v-else id="revenueChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Products Chart -->
                <div class="col-xl-4">
                    <div class="card card-flush h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Produk Terlaris (Top 5)</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Bulan ini berdasarkan qty</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div v-if="!produkTerlaris || produkTerlaris.length === 0" class="text-center text-muted py-10">
                                <i class="ki-duotone ki-package fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <p>Belum ada data</p>
                            </div>
                            <canvas v-else id="topProductsChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions + Stok Menipis Row -->
            <div class="row g-6 mb-6">
                <!-- Recent Transactions -->
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Transaksi Terbaru</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">{{ transaksiTerbaru.length }} transaksi terakhir</span>
                            </h3>
                            <div class="card-toolbar">
                                <Link :href="route('pos.history')" class="btn btn-sm btn-light">
                                    Lihat Semua
                                </Link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div v-if="!transaksiTerbaru || transaksiTerbaru.length === 0" class="text-center text-muted py-10">
                                <i class="ki-duotone ki-document fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <p>Belum ada transaksi</p>
                            </div>
                            <div v-else class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted fs-7 text-uppercase">
                                            <th class="ps-4">No. Transaksi</th>
                                            <th>Kasir</th>
                                            <th>Member</th>
                                            <th>Total</th>
                                            <th>Metode</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="trx in transaksiTerbaru" :key="trx.id" class="border-bottom">
                                            <td class="ps-4">
                                                <span class="badge badge-light-primary fw-bold">
                                                    {{ trx.no_transaksi }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ trx.kasir ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ trx.member ?? '-' }}</span>
                                            </td>
                                            <td class="fw-bold text-success fs-6">{{ formatRupiah(trx.total) }}</td>
                                            <td>
                                                <span class="badge fw-bold"
                                                    :class="{
                                                        'badge-light-success': trx.metode_bayar === 'tunai',
                                                        'badge-light-info': trx.metode_bayar === 'transfer',
                                                        'badge-light-warning': trx.metode_bayar === 'qris',
                                                    }">
                                                    {{ trx.metode_bayar?.toUpperCase() }}
                                                </span>
                                            </td>
                                            <td class="text-muted fs-7">{{ trx.created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Menipis -->
                <div class="col-xl-4">
                    <div class="card card-flush h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">⚠️ Stok Menipis</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Produk dengan stok ≤ 10</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <div v-if="!stokMenipis || stokMenipis.length === 0" class="text-center text-muted py-10">
                                <i class="ki-duotone ki-shield-tick fs-3x text-success mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <p>Semua stok aman!</p>
                            </div>
                            <div v-else>
                                <div
                                    v-for="produk in stokMenipis"
                                    :key="produk.id"
                                    class="d-flex align-items-center justify-content-between py-3 border-bottom"
                                >
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-35px symbol-light-danger">
                                            <span class="symbol-label fw-bold text-danger fs-7">
                                                {{ produk.nama?.charAt(0)?.toUpperCase() }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-gray-800 fs-7">{{ produk.nama }}</div>
                                            <div class="text-muted fs-8">{{ produk.satuan }}</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-light-danger fw-bold">
                                        {{ produk.stok }} tersisa
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    stats:             Object,
    stokMenipis:       Array,
    grafikPenjualan:   Array,
    transaksiTerbaru:  Array,
    produkTerlaris:    Array,
})

let revenueChart      = null
let topProductsChart  = null

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

onMounted(() => {
    if (props.grafikPenjualan && props.grafikPenjualan.length > 0) {
        initRevenueChart()
    }
    if (props.produkTerlaris && props.produkTerlaris.length > 0) {
        initTopProductsChart()
    }
})

const initRevenueChart = () => {
    const days     = []
    const revenues = []

    for (let i = 6; i >= 0; i--) {
        const d       = new Date()
        d.setDate(d.getDate() - i)
        const dateStr = d.toISOString().split('T')[0]
        const found   = props.grafikPenjualan.find(w => w.tanggal === dateStr)
        days.push(d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }))
        revenues.push(found?.total ?? 0)
    }

    const ctx = document.getElementById('revenueChart')
    if (!ctx) return

    if (revenueChart) revenueChart.destroy()
    revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenues,
                backgroundColor: 'rgba(13, 110, 253, 0.8)',
                borderColor: '#0d6efd',
                borderWidth: 0,
                borderRadius: 6,
                maxBarThickness: 40,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => formatRupiah(ctx.raw)
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => formatRupiah(value)
                    }
                }
            }
        }
    })
}

const initTopProductsChart = () => {
    const labels = props.produkTerlaris.slice(0, 5).map(p => p.nama)
    const data   = props.produkTerlaris.slice(0, 5).map(p => p.total_qty)

    const ctx = document.getElementById('topProductsChart')
    if (!ctx) return

    if (topProductsChart) topProductsChart.destroy()
    topProductsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: ['#0d6efd','#198754','#ffc107','#20c997','#fd7e14'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, font: { size: 12 } }
                }
            }
        }
    })
}
</script>

<style scoped>
.bg-light-primary { background-color: rgba(13, 110, 253, 0.1) !important; }
.bg-light-success { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-light-info    { background-color: rgba(13, 202, 240, 0.1) !important; }
.bg-light-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
.bg-light-danger  { background-color: rgba(220, 53, 69, 0.1) !important; }
.bgi-no-repeat    { background-repeat: no-repeat !important; }
.card             { min-height: 150px; }
.card-title       { margin-bottom: 0; }
.badge            { padding: 0.35rem 0.65rem; font-size: 0.75rem; }
</style>