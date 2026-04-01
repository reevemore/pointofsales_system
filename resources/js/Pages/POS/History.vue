<template>
    <Head title="Riwayat Transaksi" />

    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Riwayat Transaksi</h3>
            <Link :href="route('pos.index')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
                Transaksi Baru
            </Link>
        </div>

        <div class="card">

            <!-- Card Header: Search & Filter -->
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input
                            v-model="search"
                            @input="doSearch"
                            type="text"
                            class="form-control form-control-solid w-250px ps-13"
                            placeholder="Cari no. transaksi / member..."
                        />
                    </div>
                </div>
                <div class="card-toolbar gap-3">
                    <select v-model="metode" @change="doSearch" class="form-select form-select-solid w-150px">
                        <option value="">Semua Metode</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">#</th>
                                <th>No. Transaksi</th>
                                <th>Kasir</th>
                                <th>Member</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Metode</th>
                                <th class="rounded-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="transactions.data.length === 0">
                                <td colspan="8" class="text-center text-muted py-10">
                                    <i class="ki-duotone ki-document fs-2x text-muted mb-3 d-block">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Belum ada transaksi
                                </td>
                            </tr>
                            <tr v-for="(trx, i) in transactions.data" :key="trx.id">
                                <!-- Nomor baris yang benar sesuai halaman -->
                                <td class="ps-4 text-muted fs-7">
                                    {{ (transactions.current_page - 1) * transactions.per_page + i + 1 }}
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold">
                                        {{ trx.no_transaksi }}
                                    </span>
                                </td>
                                <td>{{ trx.user?.name ?? '-' }}</td>
                                <td>{{ trx.member?.nama ?? '-' }}</td>
                                <td class="fw-bold text-success">{{ formatRupiah(trx.total) }}</td>
                                <td>{{ formatRupiah(trx.bayar) }}</td>
                                <td>
                                    <span class="badge"
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap gap-3"
                    v-if="transactions.total > 0">

                    <!-- Info -->
                    <div class="text-muted fs-7">
                        Menampilkan
                        <span class="fw-bold text-gray-700">
                            {{ transactions.from ?? 0 }}&ndash;{{ transactions.to ?? 0 }}
                        </span>
                        dari
                        <span class="fw-bold text-gray-700">{{ transactions.total }}</span>
                        transaksi
                    </div>

                    <!-- Page Buttons -->
                    <ul class="pagination pagination-sm mb-0" v-if="transactions.last_page > 1">

                        <!-- Prev -->
                        <li class="page-item" :class="{ disabled: transactions.current_page === 1 }">
                            <Link class="page-link"
                                :href="buildUrl(transactions.current_page - 1)"
                                preserve-scroll>
                                <i class="ki-duotone ki-left fs-6"><span class="path1"></span><span class="path2"></span></i>
                            </Link>
                        </li>

                        <!-- Page Numbers -->
                        <template v-for="page in pageRange" :key="page">
                            <li v-if="page === '...'" class="page-item disabled">
                                <span class="page-link">…</span>
                            </li>
                            <li v-else class="page-item" :class="{ active: page === transactions.current_page }">
                                <Link class="page-link" :href="buildUrl(page)" preserve-scroll>
                                    {{ page }}
                                </Link>
                            </li>
                        </template>

                        <!-- Next -->
                        <li class="page-item" :class="{ disabled: transactions.current_page === transactions.last_page }">
                            <Link class="page-link"
                                :href="buildUrl(transactions.current_page + 1)"
                                preserve-scroll>
                                <i class="ki-duotone ki-right fs-6"><span class="path1"></span><span class="path2"></span></i>
                            </Link>
                        </li>

                    </ul>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ transactions: Object })

// Search & filter state (sync dari URL jika ada)
const urlParams = new URLSearchParams(window.location.search)
const search = ref(urlParams.get('search') ?? '')
const metode = ref(urlParams.get('metode') ?? '')

let searchTimeout = null
const doSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        router.get(route('pos.history'), {
            search: search.value || undefined,
            metode: metode.value || undefined,
        }, { preserveState: true, replace: true })
    }, 400)
}

// Build URL untuk navigasi halaman (bawa query search & filter)
const buildUrl = (page) => {
    const params = new URLSearchParams()
    if (search.value) params.set('search', search.value)
    if (metode.value) params.set('metode', metode.value)
    if (page > 1) params.set('page', page)
    const qs = params.toString()
    return route('pos.history') + (qs ? '?' + qs : '')
}

// Smart page range: 1 ... 4 5 6 ... 20
const pageRange = computed(() => {
    const current = props.transactions.current_page
    const last = props.transactions.last_page
    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1)

    const pages = []
    const addDots = () => { if (pages[pages.length - 1] !== '...') pages.push('...') }

    pages.push(1)
    if (current > 3) addDots()
    for (let p = Math.max(2, current - 1); p <= Math.min(last - 1, current + 1); p++) pages.push(p)
    if (current < last - 2) addDots()
    pages.push(last)

    return pages
})

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})
</script>

<style scoped>
.page-item.active .page-link {
    background-color: #009ef7;
    border-color: #009ef7;
    color: #fff;
}
.page-link {
    border-radius: 6px !important;
    margin: 0 2px;
    color: #3f4254;
    border: 1px solid #e4e6ef;
}
.page-link:hover {
    background-color: #f1f5ff;
    color: #009ef7;
}
.page-item.disabled .page-link {
    color: #b5b5c3;
}
</style>