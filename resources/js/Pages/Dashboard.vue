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
                    <div class="card card-flush bgi-no-repeat bg-light-primary position-relative overflow-hidden h-md-50">
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
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ formatRupiah(stats.today_revenue) }}</span>
                                <span class="text-primary fs-8 fw-semibold mt-2">{{ stats.today_count }} transaksi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Month Revenue -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-success position-relative overflow-hidden h-md-50">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-success position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M16 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H16C17.1046 20 18 19.1046 18 18V6C18 4.89543 17.1046 4 16 4Z" fill="currentColor"/>
                                    <path d="M11 10C10.4477 10 10 10.4477 10 11C10 11.5523 10.4477 12 11 12H15C15.5523 12 16 11.5523 16 11C16 10.4477 15.5523 10 15 10H11Z" fill="currentColor"/>
                                    <path d="M11 15C10.4477 15 10 15.4477 10 16C10 16.5523 10.4477 17 11 17H15C15.5523 17 16 16.5523 16 16C16 15.4477 15.5523 15 15 15H11Z" fill="currentColor"/>
                                    <path d="M6 11C5.44772 11 5 11.4477 5 12C5 12.5523 5.44772 13 6 13C6.55228 13 7 12.5523 7 12C7 11.4477 6.55228 11 6 11Z" fill="currentColor"/>
                                    <path d="M6 16C5.44772 16 5 16.4477 5 17C5 17.5523 5.44772 18 6 18C6.55228 18 7 17.5523 7 17C7 16.4477 6.55228 16 6 16Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-success fw-bold fs-8 text-uppercase">Pendapatan Bulan Ini</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ formatRupiah(stats.month_revenue) }}</span>
                                <span class="text-success fs-8 fw-semibold mt-2">Target Penjualan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-info position-relative overflow-hidden h-md-50">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-info position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M10 3.5C10 2.11929 11.1193 1 12.5 1C13.8807 1 15 2.11929 15 3.5V5.5C15 6.88071 13.8807 8 12.5 8C11.1193 8 10 6.88071 10 5.5V3.5Z" fill="currentColor"/>
                                    <path d="M17.5 15.5C17.5 16.8807 16.3807 18 15 18H2C0.895431 18 0 17.1046 0 16V10.5C0 9.39543 0.895431 8.5 2 8.5H15C16.3807 8.5 17.5 9.61929 17.5 11V15.5Z" fill="currentColor"/>
                                    <path d="M20.5 15.5C20.5 16.8807 19.3807 18 18 18H16.5C17.8807 18 19 16.8807 19 15.5V11C19 9.61929 17.8807 8.5 16.5 8.5H18C19.3807 8.5 20.5 9.61929 20.5 11V15.5Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-info fw-bold fs-8 text-uppercase">Total Produk</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ stats.total_products }}</span>
                                <span v-if="stats.low_stock > 0" class="text-danger fs-8 fw-semibold mt-2">
                                    ⚠️ {{ stats.low_stock }} stok menipis
                                </span>
                                <span v-else class="text-info fs-8 fw-semibold mt-2">Stok Tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Members -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-flush bgi-no-repeat bg-light-warning position-relative overflow-hidden h-md-50">
                        <div class="card-body">
                            <span class="svg-icon svg-icon-2x svg-icon-warning position-absolute top-0 end-0 me-4 mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M16.0336 9.62611E-08L19.3616 3.32793C20.1513 4.11775 20.6342 5.18848 20.6342 6.11216C20.6342 8.21263 19.2673 9.95722 17.transilv2 11.3506C16.8505 11.8268 16.6559 12.0149 16.4073 12.0149C16.1587 12.0149 15.964 11.8268 15.5748 11.4515C14.4818 10.4282 12.64 9.42225 10.7347 9.42225C10.4779 9.42225 10.3123 9.42225 10.0274 9.43141L5.54withdrawNamespace 4.94401C4.77233 4.16993 4.77233 2.97274 5.54withdrawNamespace 2.19866L8.86793 -1.08849e-07C9.65766 -0.789749 10.7284 -1.08849e-07 11.6519 -1.08849e-07L16.0336 9.62611E-08Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M3.20296 14.6684L1.41421 16.4571C0.633591 17.2312 0.633591 18.4284 1.41421 19.2025L4.13223 21.9205C4.90285 22.6946 6.10898 22.6946 6.88959 21.9205C7.66625 21.1464 7.66625 19.9492 6.88959 19.1751L5.10084 17.3864C4.81003 17.0956 4.81003 16.6353 5.10084 16.3444C5.39165 16.0536 5.85192 16.0536 6.14273 16.3444L7.93148 18.1331C8.70209 18.9072 9.90822 18.9072 10.6888 18.1331C11.4655 17.359 11.4655 16.1618 10.6888 15.3877L8.88012 13.599C8.58932 13.3082 8.58932 12.8479 8.88012 12.557C9.17092 12.2662 9.6312 12.2662 9.922 12.557L11.7317 14.3457C12.5023 15.1198 13.7085 15.1198 14.4891 14.3457C15.2657 13.5716 15.2657 12.3744 14.4891 11.6003L12.6804 9.81151C12.3896 9.52071 12.3896 9.06043 12.6804 8.76962C12.9712 8.47882 13.4315 8.47882 13.7223 8.76962L15.531 10.5583C16.3016 11.3324 17.5078 11.3324 18.2884 10.5583C19.065 9.78424 19.065 8.5871 18.2884 7.813L16.4997 6.02424C16.2089 5.73344 16.2089 5.27316 16.4997 4.98236C16.7905 4.69155 17.2508 4.69155 17.5416 4.98236L19.3303 6.77112C20.1009 7.54552 21.3071 7.54552 22.0876 6.77112C22.8643 6.00671 22.8643 4.80957 22.0876 4.03517L19.3696 1.31715C18.599 0.542742 17.3928 0.542742 16.6122 1.31715L3.20296 14.6684Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="text-warning fw-bold fs-8 text-uppercase">Total Member</span>
                                <span class="text-dark fw-bolder fs-2x mt-2">{{ stats.total_members }}</span>
                                <span class="text-warning fs-8 fw-semibold mt-2">Aktif Berbelanja</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-6 mb-6">
                <!-- Revenue Chart (Line/Bar) -->
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Pendapatan 7 Hari Terakhir</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Total penjualan per hari</span>
                            </h3>
                            <div class="card-toolbar">
                                <span class="badge badge-light-primary">{{ stats.today_count }} Transaksi</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="weeklyData.length === 0" class="text-center text-muted py-10">
                                Belum ada data transaksi
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
                                <span class="text-muted mt-1 fw-semibold fs-7">Berdasarkan penjualan</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div v-if="topProducts.length === 0" class="text-center text-muted py-10">
                                Belum ada data
                            </div>
                            <canvas v-else id="topProductsChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Charts Row -->
            <div class="row g-6 mb-6">
                <!-- Payment Methods -->
                <div class="col-xl-4">
                    <div class="card card-flush h-100">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Metode Pembayaran</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Distribusi pembayaran</span>
                            </h3>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <div v-if="recentTransactions.length === 0" class="text-center text-muted py-10 w-100">
                                Belum ada data
                            </div>
                            <canvas v-else id="paymentMethodsChart" style="height: 250px; width: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions Summary -->
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Transaksi Terbaru</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">{{ recentTransactions.length }} transaksi terakhir</span>
                            </h3>
                            <div class="card-toolbar">
                                <Link :href="route('pos.history')" class="btn btn-sm btn-light">
                                    Lihat Semua
                                </Link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div v-if="recentTransactions.length === 0" class="text-center text-muted py-10">
                                Belum ada transaksi
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
                                        <tr v-for="trx in recentTransactions" :key="trx.id" class="border-bottom">
                                            <td class="ps-4">
                                                <span class="badge badge-light-primary fw-bold">
                                                    {{ trx.no_transaksi }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ trx.user?.name ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ trx.member?.nama ?? '-' }}</span>
                                            </td>
                                            <td class="fw-bold text-success fs-6">{{ formatRupiah(trx.total) }}</td>
                                            <td>
                                                <span class="badge fw-bold"
                                                    :class="{
                                                        'badge-light-success': trx.metode_bayar === 'tunai',
                                                        'badge-light-info': trx.metode_bayar === 'transfer',
                                                        'badge-light-warning': trx.metode_bayar === 'qris',
                                                    }">
                                                    {{ trx.metode_bayar.toUpperCase() }}
                                                </span>
                                            </td>
                                            <td class="text-muted fs-7">{{ formatDate(trx.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    stats:              Object,
    weeklyData:         Array,
    topProducts:        Array,
    recentTransactions: Array,
})

let revenueChart = null
let topProductsChart = null
let paymentMethodsChart = null

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})

const chartData = computed(() => {
    const days = []
    for (let i = 6; i >= 0; i--) {
        const d = new Date()
        d.setDate(d.getDate() - i)
        const dateStr = d.toISOString().split('T')[0]
        const found = props.weeklyData.find(w => w.date === dateStr)
        days.push({
            date: dateStr,
            label: d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }),
            total: found?.total ?? 0,
        })
    }
    const max = Math.max(...days.map(d => d.total), 1)
    return days.map(d => ({ ...d, height: Math.round((d.total / max) * 160) }))
})

// Initialize Charts
onMounted(() => {
    if (props.weeklyData.length > 0) {
        initRevenueChart()
    }
    if (props.topProducts.length > 0) {
        initTopProductsChart()
    }
    if (props.recentTransactions.length > 0) {
        initPaymentMethodsChart()
    }
})

const initRevenueChart = () => {
    const days = []
    const revenues = []
    
    for (let i = 6; i >= 0; i--) {
        const d = new Date()
        d.setDate(d.getDate() - i)
        const dateStr = d.toISOString().split('T')[0]
        const found = props.weeklyData.find(w => w.date === dateStr)
        days.push(d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }))
        revenues.push(found?.total ?? 0)
    }

    const ctx = document.getElementById('revenueChart')
    if (ctx) {
        revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenues,
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    borderWidth: 0,
                    borderRadius: 4,
                    barThickness: 'flex',
                    maxBarThickness: 30,
                }]
            },
            options: {
                indexAxis: 'x',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
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
}

const initTopProductsChart = () => {
    const labels = props.topProducts.slice(0, 5).map(p => p.nama)
    const data = props.topProducts.slice(0, 5).map(p => p.total_qty)
    
    const ctx = document.getElementById('topProductsChart')
    if (ctx) {
        topProductsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107',
                        '#20c997',
                        '#fd7e14'
                    ],
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
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        })
    }
}

const initPaymentMethodsChart = () => {
    const paymentData = {
        tunai: 0,
        transfer: 0,
        qris: 0
    }
    
    props.recentTransactions.forEach(trx => {
        if (paymentData.hasOwnProperty(trx.metode_bayar)) {
            paymentData[trx.metode_bayar]++
        }
    })
    
    const ctx = document.getElementById('paymentMethodsChart')
    if (ctx) {
        paymentMethodsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Tunai', 'Transfer', 'QRIS'],
                datasets: [{
                    data: [paymentData.tunai, paymentData.transfer, paymentData.qris],
                    backgroundColor: [
                        '#198754',
                        '#0dcaf0',
                        '#ffc107'
                    ],
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
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        })
    }
}
</script>

<style scoped>
.bgi-no-repeat {
    background-repeat: no-repeat !important;
}

.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.svg-icon {
    display: inline-block;
}

.card-title {
    margin-bottom: 0;
}

.page-title {
    padding-bottom: 1rem;
}

.page-heading {
    font-size: 2rem;
}

.page-desc {
    font-size: 0.875rem;
    color: #6c757d;
}

.h-md-50 {
    min-height: 200px;
}

.table-responsive {
    border-radius: 0.475rem;
}

.badge {
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
}
</style>