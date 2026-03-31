<template>
    <Head title="Laporan Transaksi - POSCok" />

    <AuthenticatedLayout>
        <div class="laporan-container">
            <!-- Page Header -->
            <div class="page-header mb-8">
                <div class="header-content">
                    <div class="header-text">
                        <h1 class="page-title">Laporan Transaksi</h1>
                        <p class="page-subtitle">Analisis penjualan dan performa toko</p>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="filter-title">Filter Periode</h3>
                            <p class="filter-subtitle">Pilih rentang tanggal untuk laporan</p>
                        </div>
                    </div>
                    <div class="filter-controls">
                        <div class="date-group">
                            <label class="date-label">Dari Tanggal</label>
                            <div class="date-input-wrapper">
                                <svg class="date-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <input v-model="filter.from" type="date" class="date-input" />
                            </div>
                        </div>
                        <div class="date-separator">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                        <div class="date-group">
                            <label class="date-label">Sampai Tanggal</label>
                            <div class="date-input-wrapper">
                                <svg class="date-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <input v-model="filter.to" type="date" class="date-input" />
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button
                                @click="applyFilter"
                                style="display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 20px;background:#3b82f6 !important;color:#ffffff !important;border:none;border-radius:8px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(59,130,246,.35);transition:background .2s;"
                                onmouseover="this.style.background='#2563eb'"
                                onmouseout="this.style.background='#3b82f6'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                                Tampilkan
                            </button>
                            <a
                                :href="pdfUrl"
                                style="display:inline-flex;align-items:center;gap:7px;height:40px;padding:0 20px;background:#ef4444 !important;color:#ffffff !important;border-radius:8px;font-family:inherit;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 2px 8px rgba(239,68,68,.35);transition:background .2s;"
                                onmouseover="this.style.background='#dc2626'"
                                onmouseout="this.style.background='#ef4444'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                </svg>
                                Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid mb-6">
                <!-- Total Transaksi -->
                <div class="summary-card summary-card--blue">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Total Transaksi</span>
                            <span class="summary-value">{{ transactions.length }}</span>
                            <span class="summary-unit">transaksi</span>
                        </div>
                    </div>
                    <div class="summary-trend">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="30" viewBox="0 0 60 30" fill="none">
                            <polyline points="0,25 10,20 20,22 30,12 40,15 50,8 60,5" stroke="currentColor" stroke-width="2" fill="none" opacity="0.3"/>
                        </svg>
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div class="summary-card summary-card--green">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Total Pendapatan</span>
                            <span class="summary-value summary-value--sm">{{ formatRupiah(total) }}</span>
                            <span class="summary-unit">total revenue</span>
                        </div>
                    </div>
                    <div class="summary-trend">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="30" viewBox="0 0 60 30" fill="none">
                            <polyline points="0,28 10,22 20,18 30,14 40,10 50,7 60,3" stroke="currentColor" stroke-width="2" fill="none" opacity="0.3"/>
                        </svg>
                    </div>
                </div>

                <!-- Rata-rata -->
                <div class="summary-card summary-card--purple">
                    <div class="summary-card-bg"></div>
                    <div class="summary-card-content">
                        <div class="summary-icon summary-icon--purple">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>
                        <div class="summary-info">
                            <span class="summary-label">Rata-rata per Transaksi</span>
                            <span class="summary-value summary-value--sm">
                                {{ transactions.length ? formatRupiah(total / transactions.length) : 'Rp 0' }}
                            </span>
                            <span class="summary-unit">average</span>
                        </div>
                    </div>
                    <div class="summary-trend">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="30" viewBox="0 0 60 30" fill="none">
                            <polyline points="0,20 10,18 20,15 30,17 40,12 50,10 60,8" stroke="currentColor" stroke-width="2" fill="none" opacity="0.3"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-header-left">
                        <h3 class="table-card-title">Detail Transaksi</h3>
                        <span class="table-card-count">
                            <span class="count-dot"></span>
                            {{ transactions.length }} transaksi ditemukan
                        </span>
                    </div>
                    <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input v-model="search" type="text" class="search-input" placeholder="Cari no. transaksi, kasir, member..." />
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="trx-table">
                        <thead>
                            <tr>
                                <th class="th-no">#</th>
                                <th>No. Transaksi</th>
                                <th>Kasir</th>
                                <th>Member</th>
                                <th class="text-center">Items</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Bayar</th>
                                <th class="text-right">Kembali</th>
                                <th class="text-center">Metode</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredTransactions.length === 0">
                                <td colspan="10" class="empty-state">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>
                                    <p class="empty-text">Tidak ada transaksi pada periode ini</p>
                                </td>
                            </tr>
                            <tr v-for="(trx, i) in filteredTransactions" :key="trx.id" class="trx-row">
                                <td><span class="row-no">{{ i + 1 }}</span></td>
                                <td>
                                    <span class="trx-no-badge">{{ trx.no_transaksi }}</span>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">{{ trx.user?.name?.charAt(0) ?? '?' }}</div>
                                        <span class="user-name">{{ trx.user?.name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span v-if="trx.member?.nama" class="member-badge">{{ trx.member.nama }}</span>
                                    <span v-else class="text-muted-cell">-</span>
                                </td>
                                <td class="text-center">
                                    <span class="items-badge">{{ trx.items.length }} item</span>
                                </td>
                                <td class="text-right">
                                    <span class="amount-total">{{ formatRupiah(trx.total) }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="amount-bayar">{{ formatRupiah(trx.bayar) }}</span>
                                </td>
                                <td class="text-right">
                                    <span class="amount-kembali">{{ formatRupiah(trx.kembali) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="metode-badge"
                                        :class="{
                                            'metode-tunai': trx.metode_bayar === 'tunai',
                                            'metode-transfer': trx.metode_bayar === 'transfer',
                                            'metode-qris': trx.metode_bayar === 'qris',
                                        }">
                                        {{ trx.metode_bayar.toUpperCase() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="waktu-cell">
                                        <span class="waktu-date">{{ formatDateOnly(trx.created_at) }}</span>
                                        <span class="waktu-time">{{ formatTimeOnly(trx.created_at) }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div v-if="filteredTransactions.length > 0" class="table-footer">
                    <span class="footer-info">Menampilkan {{ filteredTransactions.length }} dari {{ transactions.length }} transaksi</span>
                    <span class="footer-total">Total: <strong>{{ formatRupiah(filteredTransactions.reduce((sum, t) => sum + (parseFloat(t.total) || 0), 0)) }}</strong></span>
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
    transactions: Array,
    total:        Number,
    from:         String,
    to:           String,
})

const search = ref('')
const filter = ref({ from: props.from, to: props.to })

const filteredTransactions = computed(() => {
    if (!search.value) return props.transactions
    return props.transactions.filter(trx =>
        trx.no_transaksi.toLowerCase().includes(search.value.toLowerCase()) ||
        trx.user?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
        trx.member?.nama?.toLowerCase().includes(search.value.toLowerCase())
    )
})

const applyFilter = () => {
    router.get(route('laporan.index'), filter.value, { preserveState: true })
}

const pdfUrl = computed(() =>
    route('laporan.pdf') + `?from=${filter.value.from}&to=${filter.value.to}`
)

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
/* ─── Fonts ─────────────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* ─── Root Variables ────────────────────────────────── */
:root {
    --font: 'Plus Jakarta Sans', sans-serif;
    --radius: 14px;
    --radius-sm: 8px;
    --radius-xs: 6px;

    --bg: #f4f6fb;
    --surface: #ffffff;
    --border: #e8ecf2;
    --border-light: #f0f3f8;

    --text-primary: #0f172a;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;

    --blue: #3b82f6;
    --blue-light: #eff6ff;
    --blue-mid: #dbeafe;

    --green: #10b981;
    --green-light: #f0fdf4;
    --green-mid: #d1fae5;

    --purple: #8b5cf6;
    --purple-light: #faf5ff;
    --purple-mid: #ede9fe;

    --red: #ef4444;
    --red-light: #fef2f2;

    --amber: #f59e0b;
    --amber-light: #fffbeb;

    --shadow-sm: 0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.04);
    --shadow: 0 4px 12px rgba(0,0,0,.06), 0 2px 6px rgba(0,0,0,.04);
    --shadow-md: 0 8px 24px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.05);
}

/* ─── Layout ────────────────────────────────────────── */
.laporan-container {
    font-family: var(--font);
    padding: 32px;
    background: var(--bg);
    min-height: 100vh;
}

/* ─── Page Header ───────────────────────────────────── */
.page-header { margin-bottom: 28px; }
.header-content {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
}
.page-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 4px;
    letter-spacing: -0.4px;
}
.page-subtitle {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
}
.header-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--green-light);
    border: 1px solid var(--green-mid);
    padding: 6px 12px;
    border-radius: 20px;
}
.live-dot {
    width: 7px; height: 7px;
    background: var(--green);
    border-radius: 50%;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.3); }
}
.live-text { font-size: 12px; font-weight: 600; color: var(--green); }

/* ─── Filter Card ───────────────────────────────────── */
.filter-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.filter-card-inner { padding: 22px 24px; }
.filter-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}
.filter-icon {
    width: 36px; height: 36px;
    background: var(--blue-light);
    color: var(--blue);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
}
.filter-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 2px;
}
.filter-subtitle { font-size: 12px; color: var(--text-muted); margin: 0; }
.filter-controls {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
}
.date-group { display: flex; flex-direction: column; gap: 6px; }
.date-label { font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.4px; }
.date-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.date-icon {
    position: absolute;
    left: 10px;
    color: var(--text-muted);
    pointer-events: none;
}
.date-input {
    height: 40px;
    padding: 0 12px 0 34px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: var(--font);
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
    background: #fafbfc;
    width: 180px;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.date-input:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    background: white;
}
.date-separator { color: var(--text-muted); padding-bottom: 10px; }
.filter-actions { display: flex; gap: 10px; padding-bottom: 0; }

.btn-tampilkan {
    display: flex; align-items: center; gap: 7px;
    height: 40px; padding: 0 18px;
    background: var(--blue);
    color: white;
    border: none; border-radius: var(--radius-sm);
    font-family: var(--font); font-size: 13px; font-weight: 600;
    cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 2px 8px rgba(59,130,246,.3);
}
.btn-tampilkan:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(59,130,246,.35);
}
.btn-tampilkan:active { transform: translateY(0); }

.btn-export {
    display: flex; align-items: center; gap: 7px;
    height: 40px; padding: 0 18px;
    background: var(--red);
    color: white;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-family: var(--font); font-size: 13px; font-weight: 600;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 2px 8px rgba(239,68,68,.3);
}
.btn-export:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(239,68,68,.35);
    color: white;
}

/* ─── Summary Cards ─────────────────────────────────── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
.summary-card {
    position: relative;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    overflow: hidden;
    padding: 22px 20px;
    box-shadow: var(--shadow-sm);
    transition: transform .2s, box-shadow .2s;
}
.summary-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.summary-card--blue { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-color: #bfdbfe; }
.summary-card--green { background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%); border-color: #a7f3d0; }
.summary-card--purple { background: linear-gradient(135deg, #faf5ff 0%, #ede9fe 100%); border-color: #ddd6fe; }

.summary-card-bg {
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at top right, rgba(255,255,255,0.7) 0%, transparent 60%);
    pointer-events: none;
}
.summary-card-content { position: relative; display: flex; align-items: flex-start; gap: 14px; }
.summary-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.summary-icon--blue { background: rgba(59,130,246,.15); color: var(--blue); }
.summary-icon--green { background: rgba(16,185,129,.15); color: var(--green); }
.summary-icon--purple { background: rgba(139,92,246,.15); color: var(--purple); }

.summary-info { display: flex; flex-direction: column; }
.summary-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-secondary); margin-bottom: 4px; }
.summary-value { font-size: 28px; font-weight: 800; color: var(--text-primary); line-height: 1.1; letter-spacing: -0.5px; }
.summary-value--sm { font-size: 20px; }
.summary-unit { font-size: 11px; color: var(--text-muted); margin-top: 4px; font-weight: 500; }

.summary-trend {
    position: absolute;
    bottom: 10px; right: 14px;
    opacity: 0.5;
}
.summary-card--blue .summary-trend { color: var(--blue); }
.summary-card--green .summary-trend { color: var(--green); }
.summary-card--purple .summary-trend { color: var(--purple); }

/* ─── Table Card ────────────────────────────────────── */
.table-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.table-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-light);
    gap: 16px;
    flex-wrap: wrap;
}
.table-header-left { display: flex; flex-direction: column; gap: 4px; }
.table-card-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin: 0; }
.table-card-count {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; color: var(--text-muted); font-weight: 500;
}
.count-dot { width: 6px; height: 6px; background: var(--green); border-radius: 50%; }

/* Search */
.search-wrapper { position: relative; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
.search-input {
    height: 38px;
    width: 280px;
    padding: 0 12px 0 36px;
    border: 1.5px solid var(--border);
    border-radius: 20px;
    font-family: var(--font);
    font-size: 13px;
    color: var(--text-primary);
    background: #fafbfc;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.search-input:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    background: white;
}
.search-input::placeholder { color: var(--text-muted); }

/* Table */
.table-wrapper { overflow-x: auto; }
.trx-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.trx-table thead tr {
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
}
.trx-table th {
    padding: 11px 14px;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
    white-space: nowrap;
    text-align: left;
}
.trx-table th.text-center { text-align: center; }
.trx-table th.text-right { text-align: right; }
.th-no { width: 44px; }

.trx-row { border-bottom: 1px solid var(--border-light); transition: background .15s; }
.trx-row:last-child { border-bottom: none; }
.trx-row:hover { background: #fafbff; }
.trx-table td { padding: 13px 14px; vertical-align: middle; }
.trx-table td.text-center { text-align: center; }
.trx-table td.text-right { text-align: right; }

/* Row elements */
.row-no {
    display: inline-flex; align-items: center; justify-content: center;
    width: 24px; height: 24px;
    background: var(--blue-light);
    color: var(--blue);
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
}
.trx-no-badge {
    display: inline-block;
    padding: 4px 10px;
    background: var(--blue-light);
    color: var(--blue);
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
    letter-spacing: 0.2px;
}
.user-cell { display: flex; align-items: center; gap: 8px; }
.user-avatar {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, var(--blue), #6366f1);
    color: white;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    flex-shrink: 0;
}
.user-name { font-weight: 600; color: var(--text-primary); }
.member-badge {
    display: inline-block;
    padding: 3px 9px;
    background: var(--amber-light);
    color: #92400e;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
}
.text-muted-cell { color: var(--text-muted); }
.items-badge {
    display: inline-block;
    padding: 3px 9px;
    background: var(--border-light);
    color: var(--text-secondary);
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
}
.amount-total { font-weight: 700; color: #065f46; }
.amount-bayar { color: var(--text-secondary); font-weight: 500; }
.amount-kembali { font-weight: 600; color: var(--blue); }

.metode-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.4px;
}
.metode-tunai { background: var(--green-light); color: #065f46; }
.metode-transfer { background: var(--blue-light); color: #1e40af; }
.metode-qris { background: var(--amber-light); color: #92400e; }

.waktu-cell { display: flex; flex-direction: column; gap: 1px; }
.waktu-date { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
.waktu-time { font-size: 11px; color: var(--text-muted); }

/* Empty state */
.empty-state { padding: 56px 0 !important; text-align: center; }
.empty-icon { color: var(--text-muted); margin-bottom: 12px; }
.empty-text { color: var(--text-muted); font-size: 13px; font-weight: 500; }

/* Table footer */
.table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 24px;
    border-top: 1px solid var(--border-light);
    background: #f8fafc;
}
.footer-info { font-size: 12px; color: var(--text-muted); font-weight: 500; }
.footer-total { font-size: 13px; color: var(--text-secondary); }
.footer-total strong { color: var(--text-primary); font-weight: 700; }

/* ─── Responsive ────────────────────────────────────── */
@media (max-width: 1024px) {
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .laporan-container { padding: 16px; }
    .summary-grid { grid-template-columns: 1fr; }
    .filter-controls { flex-direction: column; align-items: stretch; }
    .date-input { width: 100%; }
    .search-input { width: 200px; }
}
</style>