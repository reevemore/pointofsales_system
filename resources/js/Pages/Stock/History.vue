<template>
    <Head title="Riwayat Stok - POSCok" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Riwayat Stok
                            <span class="page-desc text-muted fs-7 fw-semibold mt-2">{{ product.nama }}</span>
                        </h1>
                        <div class="text-muted fs-7 fw-semibold mt-2">
                            Stok saat ini: <span class="badge badge-light-primary fw-bold">{{ product.stok }} unit</span>
                        </div>
                    </div>
                    <Link :href="route('stock.index')" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Kembali
                    </Link>
                </div>
            </div>

            <!-- History Table -->
            <div class="card card-flush">
                <div class="card-header border-0 pt-6">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Riwayat Mutasi Stok</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">{{ mutations.data.length }} riwayat ditemukan</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-time-past fs-1 position-absolute ms-6">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input v-model="search" type="text"
                                class="form-control form-control-solid w-250px ps-14"
                                placeholder="Cari riwayat..." />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="w-25px">#</th>
                                    <th>Jenis</th>
                                    <th>Qty</th>
                                    <th>Stok Sebelum</th>
                                    <th>Stok Sesudah</th>
                                    <th>Keterangan</th>
                                    <th>Oleh</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredMutations.length === 0">
                                    <td colspan="8" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-time-past fs-2hx text-muted mb-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <div>Belum ada riwayat mutasi stok</div>
                                    </td>
                                </tr>
                                <tr v-for="(m, i) in filteredMutations" :key="m.id">
                                    <td class="ps-4">
                                        <span class="badge badge-light-primary fw-bold">{{ i + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge fw-bold"
                                            :class="m.type === 'in' ? 'badge-light-success' : 'badge-light-danger'">
                                            <i class="ki-duotone ki-plus fs-4 me-1" v-if="m.type === 'in'">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            <i class="ki-duotone ki-minus fs-4 me-1" v-else>
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            {{ m.type === 'in' ? 'Masuk' : 'Keluar' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-gray-900">{{ m.qty }}</td>
                                    <td class="text-muted">{{ m.stok_before }}</td>
                                    <td class="fw-bold text-gray-900">{{ m.stok_after }}</td>
                                    <td class="text-muted fs-7">{{ m.keterangan ?? '-' }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ m.user?.name ?? '-' }}</span>
                                    </td>
                                    <td class="text-muted fs-7">{{ formatDate(m.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-6" v-if="mutations.last_page > 1">
                        <nav>
                            <ul class="pagination">
                                <li v-for="link in mutations.links" :key="link.label"
                                    class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                    <Link class="page-link" :href="link.url ?? '#'"
                                        v-html="link.label" />
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ product: Object, mutations: Object })

const search = ref('')

const filteredMutations = computed(() => {
    if (!search.value) return props.mutations.data
    return props.mutations.data.filter(mutation =>
        mutation.keterangan?.toLowerCase().includes(search.value.toLowerCase()) ||
        mutation.user?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
        mutation.type.toLowerCase().includes(search.value.toLowerCase())
    )
})

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})
</script>