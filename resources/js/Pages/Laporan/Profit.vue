<template>
    <Head title="Laporan Profit" />

    <AuthenticatedLayout>

        <!--begin::Page title-->
        <div class="d-flex flex-wrap flex-stack mb-6">
            <div class="d-flex flex-column justify-content-center">
                <h3 class="fw-bold my-2 d-flex align-items-center gap-2">
                    <i class="ki-duotone ki-dollar fs-2 text-warning">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Laporan Profit
                </h3>
                <span class="text-muted fw-semibold fs-7">Analisis keuntungan bersih per periode</span>
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
                        <span class="text-muted fw-semibold fs-7">Pilih rentang tanggal untuk laporan profit</span>
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
                        <a :href="excelUrl" class="btn btn-success">
                            <i class="ki-duotone ki-file-down fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Export Excel
                        </a>
                        <a :href="pdfUrl" class="btn btn-danger">
                            <i class="ki-duotone ki-file-down fs-4 me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Filter Card-->

        <!--begin::Summary Cards-->
        <div class="row g-5 mb-6">

            <!--begin::Total Omzet-->
            <div class="col-sm-6 col-xl-3">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ formatRupiah(totalOmzet) }}</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">total penjualan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-dollar fs-2 text-primary me-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                    Total Omzet
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                                <div class="bg-primary rounded h-8px" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Omzet-->

            <!--begin::Total Modal-->
            <div class="col-sm-6 col-xl-3">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ formatRupiah(totalModal) }}</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">harga pokok penjualan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-package fs-2 text-warning me-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                    Total Modal (HPP)
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-warning rounded">
                                <div class="bg-warning rounded h-8px" :style="{ width: (totalOmzet ? (totalModal/totalOmzet*100) : 0) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Modal-->

            <!--begin::Total Profit-->
            <div class="col-sm-6 col-xl-3">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold lh-1 ls-n2" :class="totalProfit >= 0 ? 'text-success' : 'text-danger'">
                                {{ formatRupiah(totalProfit) }}
                            </span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">keuntungan bersih</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-graph-up fs-2 text-success me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Total Profit Bersih
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" :style="{ width: (totalOmzet ? Math.max(0, totalProfit/totalOmzet*100) : 0) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Total Profit-->

            <!--begin::Margin-->
            <div class="col-sm-6 col-xl-3">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ avgMargin }}%</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">profit margin</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">
                                    <i class="ki-duotone ki-chart-line-up fs-2 text-info me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Margin Rata-rata
                                </span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-info rounded">
                                <div class="bg-info rounded h-8px" :style="{ width: Math.min(100, Math.max(0, avgMargin)) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Margin-->

        </div>
        <!--end::Summary Cards-->

        <!--begin::Table Card-->
        <div class="card card-flush">
            <div class="card-header border-0 pt-6">
                <div class="card-title flex-column">
                    <span class="card-label fw-bold text-dark">Detail Profit per Transaksi</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">
                        <span class="bullet bullet-dot bg-success me-1 h-6px w-6px"></span>
                        {{ filteredData.length }} transaksi
                    </span>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input v-model="search" type="text"
                            class="form-control form-control-solid w-250px ps-14"
                            placeholder="Cari no. transaksi..." />
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th class="w-25px ps-4">#</th>
                                <th class="min-w-150px">No. Transaksi</th>
                                <th class="min-w-120px">Tanggal</th>
                                <th class="min-w-100px text-end">Omzet</th>
                                <th class="min-w-100px text-end">Modal (HPP)</th>
                                <th class="min-w-100px text-end">Profit</th>
                                <th class="min-w-80px text-center pe-4">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredData.length === 0">
                                <td colspan="7" class="text-center py-10">
                                    <i class="ki-duotone ki-chart-line-up fs-3x text-muted mb-4">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <div class="text-muted fw-semibold">Tidak ada data profit pada periode ini</div>
                                </td>
                            </tr>
                            <tr v-for="(row, i) in filteredData" :key="row.id">
                                <td class="ps-4">
                                    <span class="text-gray-600 fw-semibold fs-7">{{ i + 1 }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold fs-8 px-3">{{ row.no_transaksi }}</span>
                                </td>
                                <td>
                                    <span class="text-gray-800 fw-semibold d-block fs-7">{{ formatDateOnly(row.created_at) }}</span>
                                    <span class="text-muted fw-semibold fs-8">{{ formatTimeOnly(row.created_at) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-primary fw-bold fs-7">{{ formatRupiah(row.total) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-warning fw-semibold fs-7">{{ formatRupiah(row.modal) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold fs-7" :class="row.profit >= 0 ? 'text-success' : 'text-danger'">
                                        {{ formatRupiah(row.profit) }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <span class="badge fw-bold fs-8 px-3 py-2"
                                        :class="row.margin >= 20 ? 'badge-light-success' : row.margin >= 10 ? 'badge-light-warning' : 'badge-light-danger'">
                                        {{ row.margin }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="filteredData.length > 0">
                            <tr class="fw-bold bg-light">
                                <td colspan="3" class="ps-4 text-gray-600 fs-7">Total ({{ filteredData.length }} transaksi)</td>
                                <td class="text-end text-primary fw-bolder">{{ formatRupiah(totalOmzet) }}</td>
                                <td class="text-end text-warning fw-bolder">{{ formatRupiah(totalModal) }}</td>
                                <td class="text-end fw-bolder" :class="totalProfit >= 0 ? 'text-success' : 'text-danger'">
                                    {{ formatRupiah(filteredData.reduce((s, r) => s + r.profit, 0)) }}
                                </td>
                                <td class="pe-4"></td>
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

const applyFilter = () => {
    router.get(route('laporan.profit'), filter.value, { preserveState: true })
}

const excelUrl = computed(() =>
    route('laporan.profit.excel') + `?from=${filter.value.from}&to=${filter.value.to}`
)
const pdfUrl = computed(() =>
    route('laporan.profit.pdf') + `?from=${filter.value.from}&to=${filter.value.to}`
)

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val ?? 0)
const formatDateOnly = (val) => new Date(val).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
const formatTimeOnly = (val) => new Date(val).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
</script>