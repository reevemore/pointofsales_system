<template>
    <Head title="Laporan Transaksi" />

    <AuthenticatedLayout>

        <!--begin::Page title-->
        <div class="d-flex flex-wrap flex-stack mb-6">
            <div class="d-flex flex-column justify-content-center">
                <h3 class="fw-bold my-2 d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-dollar fs-2 text-success">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Laporan Transaksi
                </h3>
                <span class="text-muted fw-semibold fs-7">Analisis penjualan dan performa toko</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-light-success fw-bold fs-8 px-3 py-2">
                    <span class="bullet bullet-dot bg-success me-2 h-6px w-6px"></span>
                    Live Data
                </span>
            </div>
        </div>
        <!--end::Page title-->

        <!--begin::Filter Card-->
        <div class="card card-flush mb-6">
            <div class="card-body py-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="symbol symbol-35px">
                        <span class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-filter fs-3 text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <span class="fw-bold text-dark fs-6 d-block">Filter Periode</span>
                        <span class="text-muted fw-semibold fs-7">Pilih rentang tanggal untuk laporan</span>
                    </div>
                </div>
                <div class="d-flex align-items-end gap-4 flex-wrap">
                    <div>
                        <label class="form-label fw-semibold fs-7 text-uppercase text-muted mb-2">Dari Tanggal</label>
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-calendar fs-3 position-absolute ms-3 text-gray-400">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input v-model="filter.from" type="date" class="form-control form-control-solid ps-10 w-180px" />
                        </div>
                    </div>
                    <i class="ki-duotone ki-arrow-right fs-2 text-muted mb-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div>
                        <label class="form-label fw-semibold fs-7 text-uppercase text-muted mb-2">Sampai Tanggal</label>
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-calendar fs-3 position-absolute ms-3 text-gray-400">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input v-model="filter.to" type="date" class="form-control form-control-solid ps-10 w-180px" />
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <button @click="applyFilter" class="btn btn-primary">
                            <i class="ki-duotone ki-magnifier fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Tampilkan
                        </button>
                        <a :href="pdfUrl" class="btn btn-danger">
                            <i class="ki-duotone ki-file-down fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Export PDF
                        </a>
                        <a :href="excelUrl" class="btn btn-success">
                            <i class="ki-duotone ki-file-down fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Filter Card-->

        <!--begin::Summary Cards-->
        <div class="row g-5 mb-6">

            <!--begin::Total Transaksi-->
            <div class="col-sm-12 col-xl-4">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ transactions.length }}</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">transaksi</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-receipt fs-2 text-primary me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Total Transaksi
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                                <div class="bg-primary rounded h-8px" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Transaksi-->

            <!--begin::Total Pendapatan-->
            <div class="col-sm-12 col-xl-4">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ formatRupiah(total) }}</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">total revenue</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-dollar fs-2 text-success me-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                    Total Pendapatan
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Pendapatan-->

            <!--begin::Rata-rata-->
            <div class="col-sm-12 col-xl-4">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">
                                {{ transactions.length ? formatRupiah(total / transactions.length) : 'Rp 0' }}
                            </span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">average</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-graph-up fs-2 text-info me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Rata-rata per Transaksi
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-info rounded">
                                <div class="bg-info rounded h-8px" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Rata-rata-->

        </div>
        <!--end::Summary Cards-->

        <!--begin::Table Card-->
        <div class="card card-flush">
            <div class="card-header border-0 pt-6">
                <div class="card-title flex-column">
                    <span class="card-label fw-bold text-dark">Detail Transaksi</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        <span class="bullet bullet-dot bg-success me-1 h-6px w-6px"></span>
                        {{ filteredTransactions.length }} transaksi ditemukan
                    </span>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input v-model="search" type="text"
                            class="form-control form-control-solid w-280px ps-14"
                            placeholder="Cari no. transaksi, kasir, member..." />
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th class="w-25px ps-4">#</th>
                                <th class="min-w-140px">No. Transaksi</th>
                                <th class="min-w-120px">Kasir</th>
                                <th class="min-w-110px">Member</th>
                                <th class="min-w-70px text-center">Items</th>
                                <th class="min-w-100px text-end">Total</th>
                                <th class="min-w-100px text-end">Bayar</th>
                                <th class="min-w-100px text-end">Kembali</th>
                                <th class="min-w-90px text-center">Metode</th>
                                <th class="min-w-120px pe-4">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredTransactions.length === 0">
                                <td colspan="10" class="text-center py-10">
                                    <i class="ki-duotone ki-document fs-3x text-muted mb-4">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <div class="text-muted fw-semibold">Tidak ada transaksi pada periode ini</div>
                                </td>
                            </tr>
                            <tr v-for="(trx, i) in filteredTransactions" :key="trx.id">
                                <td class="ps-4">
                                    <span class="text-gray-600 fw-semibold fs-7">{{ i + 1 }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold fs-8 px-3">{{ trx.no_transaksi }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="symbol symbol-circle symbol-30px">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-8">
                                                {{ trx.user?.name?.charAt(0) ?? '?' }}
                                            </span>
                                        </div>
                                        <span class="text-gray-800 fw-semibold fs-7">{{ trx.user?.name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span v-if="trx.member?.nama" class="badge badge-light-warning fw-bold fs-8">{{ trx.member.nama }}</span>
                                    <span v-else class="text-muted fs-7">-</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light fw-bold fs-8">{{ trx.items.length }} item</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-success fw-bold fs-7">{{ formatRupiah(trx.total) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-gray-700 fw-semibold fs-7">{{ formatRupiah(trx.bayar) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-primary fw-semibold fs-7">{{ formatRupiah(trx.kembali) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge fw-bold fs-8 px-3 py-2"
                                        :class="{
                                            'badge-light-success':  trx.metode_bayar === 'tunai',
                                            'badge-light-primary':  trx.metode_bayar === 'transfer',
                                            'badge-light-warning':  trx.metode_bayar === 'qris',
                                        }">
                                        {{ trx.metode_bayar.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <span class="text-gray-800 fw-semibold d-block fs-7">{{ formatDateOnly(trx.created_at) }}</span>
                                    <span class="text-muted fw-semibold fs-8">{{ formatTimeOnly(trx.created_at) }}</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="filteredTransactions.length > 0">
                            <tr class="fw-bold bg-light">
                                <td colspan="5" class="ps-4 text-gray-600 fs-7">Total ({{ filteredTransactions.length }} transaksi)</td>
                                <td class="text-end text-success fw-bolder">{{ formatRupiah(total) }}</td>
                                <td colspan="4" class="pe-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Table Card-->

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    transactions: Array,
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

const total = computed(() =>
    props.transactions.reduce((s, t) => s + t.total, 0)
)

const applyFilter = () => {
    router.get(route('laporan.index'), filter.value, { preserveState: true })
}

const pdfUrl = computed(() =>
    route('laporan.pdf') + `?from=${filter.value.from}&to=${filter.value.to}`
)
const excelUrl = computed(() =>
    route('laporan.excel') + `?from=${filter.value.from}&to=${filter.value.to}`
)

const formatRupiah  = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val ?? 0)
const formatDateOnly = (val) => new Date(val).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
const formatTimeOnly = (val) => new Date(val).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
</script>