<template>
    <Head title="Laporan Profit" />

    <AuthenticatedLayout>
        <div class="profit-container">

            <!-- Page Header -->
            <div class="page-header mb-8">
                <div class="header-content">
                    <div class="header-text">
                        <h1 class="page-title">💰 Laporan Profit</h1>
                        <p class="page-subtitle">Analisis keuntungan bersih per periode</p>
                    </div>
                    <div class="header-badge">
                        <span class="live-dot"></span>
                        <span class="live-text">Live Data</span>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="filter-card mb-6">
                <div class="filter-card-inner">
                    <div class="filter-header">
                        <div class="filter-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="filter-title">Filter Periode</h3>
                            <p class="filter-subtitle">Pilih rentang tanggal untuk laporan profit</p>
                        </div>
                    </div>
                    <div class="filter-controls">
                        <div class="date-group">
                            <label class="date-label">Dari Tanggal</label>
                            <div class="date-input-wrapper">
                                <svg class="date-icon" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <input v-model="filter.from" type="date" class="date-input" />
                            </div>
                        </div>
                        <div class="date-separator">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                        <div class="date-group">
                            <label class="date-label">Sampai Tanggal</label>
                            <div class="date-input-wrapper">
                                <svg class="date-icon" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <input v-model="filter.to" type="date" class="date-input" />
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button @click="applyFilter" class="btn-tampilkan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                Tampilkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid mb-6">

                <!-- Total Omzet -->
                <div class="summary-card summary-card--blue">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Total Omzet</span>
                            <span class="summary-value summary-value--sm">
                                {{ formatRupiah(totalOmzet) }}
                            </span>
                            <span class="summary-unit">total penjualan</span>
                        </div>
                    </div>
                </div>

                <!-- Total Modal -->
                <div class="summary-card summary-card--orange">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--orange">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Total Modal (HPP)</span>
                            <span class="summary-value summary-value--sm">
                                {{ formatRupiah(totalModal) }}
                            </span>
                            <span class="summary-unit">harga pokok penjualan</span>
                        </div>
                    </div>
                </div>

                <!-- Total Profit -->
                <div class="summary-card summary-card--green">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Total Profit Bersih</span>
                            <span class="summary-value summary-value--sm"
                                :class="totalProfit >= 0 ? 'text-green' : 'text-red'">
                                {{ formatRupiah(totalProfit) }}
                            </span>
                            <span class="summary-unit">keuntungan bersih</span>
                        </div>
                    </div>
                </div>

                <!-- Margin -->
                <div class="summary-card summary-card--purple">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--purple">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Margin Rata-rata</span>
                            <span class="summary-value"
                                :class="avgMargin >= 0 ? 'text-green' : 'text-red'">
                                {{ avgMargin }}%
                            </span>
                            <span class="summary-unit">profit margin</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Profit Bar Visual -->
            <div class="profit-bar-card mb-6" v-if="totalOmzet > 0">
                <div class="profit-bar-label">
                    <span>Komposisi Omzet</span>
                    <span class="profit-bar-legend">
                        <span class="legend-dot legend-dot--modal"></span> Modal
                        <span class="legend-dot legend-dot--profit ms-3"></span> Profit
                    </span>
                </div>
                <div class="profit-bar">
                    <div class="profit-bar-modal"
                        :style="{ width: modalPercent + '%' }">
                        {{ modalPercent }}%
                    </div>
                    <div class="profit-bar-profit"
                        :style="{ width: profitPercent + '%' }">
                        {{ profitPercent }}%
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-header-left">
                        <h3 class="table-card-title">Detail Profit per Transaksi</h3>
                        <span class="table-card-count">
                            <span class="count-dot"></span>
                            {{ filteredData.length }} transaksi
                        </span>
                    </div>
                    <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input v-model="search" type="text" class="search-input"
                            placeholder="Cari no. transaksi..." />
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="profit-table">
                        <thead>
                            <tr>
                                <th class="th-no">#</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th class="text-right">Omzet</th>
                                <th class="text-right">Modal (HPP)</th>
                                <th class="text-right">Profit</th>
                                <th class="text-center">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredData.length === 0">
                                <td colspan="7" class="empty-state">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/>
                                        </svg>
                                    </div>
                                    <p class="empty-text">Tidak ada data profit pada periode ini</p>
                                </td>
                            </tr>
                            <tr v-for="(row, i) in filteredData" :key="row.id" class="profit-row">
                                <td><span class="row-no">{{ i + 1 }}</span></td>
                                <td>
                                    <span class="trx-no-badge">{{ row.no_transaksi }}</span>
                                </td>
                                <td>
                                    <div class="waktu-cell">
                                        <span class="waktu-date">{{ formatDateOnly(row.created_at) }}</span>
                                        <span class="waktu-time">{{ formatTimeOnly(row.created_at) }}</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <span class="amount-omzet">{{ formatRupiah(row.total) }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="amount-modal">{{ formatRupiah(row.modal) }}</span>
                                </td>
                                <td class="text-right">
                                    <span :class="row.profit >= 0 ? 'amount-profit' : 'amount-loss'">
                                        {{ formatRupiah(row.profit) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="margin-badge"
                                        :class="row.margin >= 20 ? 'margin-good'
                                            : row.margin >= 10 ? 'margin-ok'
                                            : 'margin-low'">
                                        {{ row.margin }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div v-if="filteredData.length > 0" class="table-footer">
                    <span class="footer-info">
                        {{ filteredData.length }} transaksi
                    </span>
                    <span class="footer-total">
                        Profit: <strong :class="totalProfit >= 0 ? 'text-green' : 'text-red'">
                            {{ formatRupiah(filteredData.reduce((s, r) => s + r.profit, 0)) }}
                        </strong>
                    </span>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    profitData:  Array,
    totalOmzet:  Number,
    totalModal:  Number,
    totalProfit: Number,
    from:        String,
    to:          String,
})

const search = ref('')
const filter = ref({ from: props.from, to: props.to })

const filteredData = computed(() => {
    if (!search.value) return props.profitData
    return props.profitData.filter(r =>
        r.no_transaksi.toLowerCase().includes(search.value.toLowerCase())
    )
})

const avgMargin = computed(() => {
    if (!props.profitData.length) return 0
    const total = props.profitData.reduce((s, r) => s + r.margin, 0)
    return Math.round(total / props.profitData.length * 10) / 10
})

const modalPercent = computed(() => {
    if (!props.totalOmzet) return 0
    return Math.round((props.totalModal / props.totalOmzet) * 100)
})

const profitPercent = computed(() => {
    if (!props.totalOmzet) return 0
    return 100 - modalPercent.value
})

const applyFilter = () => {
    router.get(route('laporan.profit'), filter.value, { preserveState: true })
}

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDateOnly = (val) => new Date(val).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric'
})

const formatTimeOnly = (val) => new Date(val).toLocaleTimeString('id-ID', {
    hour: '2-digit', minute: '2-digit'
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --font: 'Plus Jakarta Sans', sans-serif;
    --radius: 14px;
    --radius-sm: 8px;
    --bg: #f4f6fb;
    --surface: #ffffff;
    --border: #e8ecf2;
    --border-light: #f0f3f8;
    --text-primary: #0f172a;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --blue: #3b82f6;
    --blue-light: #eff6ff;
    --green: #10b981;
    --green-light: #f0fdf4;
    --green-mid: #d1fae5;
    --purple: #8b5cf6;
    --purple-light: #faf5ff;
    --purple-mid: #ede9fe;
    --orange: #f97316;
    --orange-light: #fff7ed;
    --orange-mid: #fed7aa;
    --red: #ef4444;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.05);
    --shadow-md: 0 8px 24px rgba(0,0,0,.08);
}

.profit-container {
    font-family: var(--font);
    padding: 32px;
    background: var(--bg);
    min-height: 100vh;
}

/* Header */
.page-header { margin-bottom: 28px; }
.header-content { display: flex; align-items: flex-start; justify-content: space-between; }
.page-title { font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; letter-spacing: -0.4px; }
.page-subtitle { font-size: 13px; color: var(--text-muted); margin: 0; }
.header-badge { display: flex; align-items: center; gap: 6px; background: var(--green-light); border: 1px solid var(--green-mid); padding: 6px 12px; border-radius: 20px; }
.live-dot { width: 7px; height: 7px; background: var(--green); border-radius: 50%; animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.3)} }
.live-text { font-size: 12px; font-weight: 600; color: var(--green); }

/* Filter */
.filter-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
.filter-card-inner { padding: 22px 24px; }
.filter-header { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
.filter-icon { width: 36px; height: 36px; background: var(--blue-light); color: var(--blue); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; }
.filter-title { font-size: 14px; font-weight: 700; color: var(--text-primary); margin: 0 0 2px; }
.filter-subtitle { font-size: 12px; color: var(--text-muted); margin: 0; }
.filter-controls { display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap; }
.date-group { display: flex; flex-direction: column; gap: 6px; }
.date-label { font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.4px; }
.date-input-wrapper { position: relative; display: flex; align-items: center; }
.date-icon { position: absolute; left: 10px; color: var(--text-muted); pointer-events: none; }
.date-input { height: 40px; padding: 0 12px 0 34px; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-family: var(--font); font-size: 13px; font-weight: 500; color: var(--text-primary); background: #fafbfc; width: 180px; outline: none; transition: border-color .2s, box-shadow .2s; }
.date-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(59,130,246,.1); background: white; }
.date-separator { color: var(--text-muted); padding-bottom: 10px; }
.filter-actions { display: flex; gap: 10px; }
.btn-tampilkan { display: flex; align-items: center; gap: 7px; height: 40px; padding: 0 20px; background: var(--blue); color: white; border: none; border-radius: var(--radius-sm); font-family: var(--font); font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(59,130,246,.3); transition: background .2s; }
.btn-tampilkan:hover { background: #2563eb; }

/* Summary Grid */
.summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.summary-card { position: relative; border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; padding: 22px 20px; box-shadow: var(--shadow-sm); transition: transform .2s, box-shadow .2s; }
.summary-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.summary-card--blue   { background: linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%); border-color: #bfdbfe; }
.summary-card--orange { background: linear-gradient(135deg,#fff7ed 0%,#fed7aa 100%); border-color: #fdba74; }
.summary-card--green  { background: linear-gradient(135deg,#f0fdf4 0%,#d1fae5 100%); border-color: #a7f3d0; }
.summary-card--purple { background: linear-gradient(135deg,#faf5ff 0%,#ede9fe 100%); border-color: #ddd6fe; }
.summary-card-bg { position: absolute; inset: 0; background: radial-gradient(ellipse at top right,rgba(255,255,255,.7) 0%,transparent 60%); pointer-events: none; }
.summary-card-content { position: relative; display: flex; align-items: flex-start; gap: 14px; }
.summary-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.summary-icon--blue   { background: rgba(59,130,246,.15); color: var(--blue); }
.summary-icon--orange { background: rgba(249,115,22,.15); color: var(--orange); }
.summary-icon--green  { background: rgba(16,185,129,.15); color: var(--green); }
.summary-icon--purple { background: rgba(139,92,246,.15); color: var(--purple); }
.summary-info { display: flex; flex-direction: column; }
.summary-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-secondary); margin-bottom: 4px; }
.summary-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1.1; letter-spacing: -0.5px; }
.summary-value--sm { font-size: 18px; }
.summary-unit { font-size: 11px; color: var(--text-muted); margin-top: 4px; font-weight: 500; }
.text-green { color: #065f46 !important; }
.text-red   { color: var(--red) !important; }

/* Profit Bar */
.profit-bar-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 20px 24px; box-shadow: var(--shadow-sm); }
.profit-bar-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 13px; font-weight: 600; color: var(--text-secondary); }
.profit-bar-legend { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500; }
.legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }
.legend-dot--modal  { background: var(--orange); }
.legend-dot--profit { background: var(--green); }
.ms-3 { margin-left: 12px; }
.profit-bar { display: flex; height: 36px; border-radius: 10px; overflow: hidden; gap: 2px; }
.profit-bar-modal  { background: linear-gradient(90deg,#fb923c,#f97316); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 700; min-width: 40px; transition: width .6s ease; }
.profit-bar-profit { background: linear-gradient(90deg,#34d399,#10b981); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 700; min-width: 40px; transition: width .6s ease; flex: 1; }

/* Table */
.table-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden; }
.table-card-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid var(--border-light); gap: 16px; flex-wrap: wrap; }
.table-header-left { display: flex; flex-direction: column; gap: 4px; }
.table-card-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0; }
.table-card-count { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); font-weight: 500; }
.count-dot { width: 6px; height: 6px; background: var(--green); border-radius: 50%; }
.search-wrapper { position: relative; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.search-input { height: 38px; width: 260px; padding: 0 12px 0 36px; border: 1.5px solid var(--border); border-radius: 20px; font-family: var(--font); font-size: 13px; color: var(--text-primary); background: #fafbfc; outline: none; transition: border-color .2s, box-shadow .2s; }
.search-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(59,130,246,.1); background: white; }
.search-input::placeholder { color: var(--text-muted); }

.table-wrapper { overflow-x: auto; }
.profit-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.profit-table thead tr { background: #f8fafc; border-bottom: 1px solid var(--border); }
.profit-table th { padding: 11px 14px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); white-space: nowrap; text-align: left; }
.profit-table th.text-center { text-align: center; }
.profit-table th.text-right  { text-align: right; }
.th-no { width: 44px; }
.profit-row { border-bottom: 1px solid var(--border-light); transition: background .15s; }
.profit-row:last-child { border-bottom: none; }
.profit-row:hover { background: #fafbff; }
.profit-table td { padding: 13px 14px; vertical-align: middle; }
.profit-table td.text-center { text-align: center; }
.profit-table td.text-right  { text-align: right; }
.row-no { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: var(--blue-light); color: var(--blue); border-radius: 6px; font-size: 11px; font-weight: 700; }
.trx-no-badge { display: inline-block; padding: 4px 10px; background: var(--blue-light); color: var(--blue); border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
.waktu-cell { display: flex; flex-direction: column; gap: 1px; }
.waktu-date { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
.waktu-time { font-size: 11px; color: var(--text-muted); }
.amount-omzet  { font-weight: 700; color: var(--blue); }
.amount-modal  { font-weight: 600; color: var(--orange); }
.amount-profit { font-weight: 700; color: #065f46; }
.amount-loss   { font-weight: 700; color: var(--red); }
.margin-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.margin-good { background: var(--green-light); color: #065f46; }
.margin-ok   { background: #fffbeb; color: #92400e; }
.margin-low  { background: #fef2f2; color: #991b1b; }
.empty-state { padding: 56px 0 !important; text-align: center; }
.empty-icon  { color: var(--text-muted); margin-bottom: 12px; }
.empty-text  { color: var(--text-muted); font-size: 13px; font-weight: 500; }
.table-footer { display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; border-top: 1px solid var(--border-light); background: #f8fafc; }
.footer-info  { font-size: 12px; color: var(--text-muted); font-weight: 500; }
.footer-total { font-size: 13px; color: var(--text-secondary); }
.footer-total strong { font-weight: 700; }

@media (max-width: 1200px) { .summary-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 768px)  { .profit-container { padding: 16px; } .summary-grid { grid-template-columns: 1fr; } .filter-controls { flex-direction: column; align-items: stretch; } .date-input { width: 100%; } }
</style>