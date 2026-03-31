<template>
    <Head title="Manajemen Stok - POSCok" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Manajemen Stok
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Kelola inventori dan stok produk</span>
                    </h1>
                </div>
            </div>

            <!-- Alert Stok Menipis -->
            <div v-if="lowStock.length" class="alert alert-danger d-flex align-items-center mb-6">
                <i class="ki-duotone ki-warning-2 fs-2hx text-danger me-4">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
                <div>
                    <div class="fw-bold">⚠️ Stok Menipis!</div>
                    <div class="fs-7">
                        <span v-for="(p, i) in lowStock" :key="p.id">
                            <strong>{{ p.nama }}</strong> ({{ p.stok }} sisa)
                            <span v-if="i < lowStock.length - 1">, </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Flash Message -->
            <div v-if="$page.props.flash?.success"
                class="alert alert-success d-flex align-items-center mb-5">
                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-3">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>{{ $page.props.flash.success }}</div>
            </div>

            <div class="row g-6">

                <!-- Form Update Stok -->
                <div class="col-lg-4">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Update Stok</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Tambah atau kurangi stok produk</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-5">
                                <label class="form-label fw-bold required">Produk</label>
                                <select v-model="form.product_id" class="form-select form-select-solid">
                                    <option value="">-- Pilih Produk --</option>
                                    <option v-for="p in products" :key="p.id" :value="p.id">
                                        {{ p.nama }} (Stok: {{ p.stok }})
                                    </option>
                                </select>
                                <div v-if="form.errors.product_id" class="text-danger fs-8 mt-1">
                                    {{ form.errors.product_id }}
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bold required">Jenis</label>
                                <div class="d-flex gap-4">
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="radio" v-model="form.type" value="in" />
                                        <span class="text-success fw-bold">➕ Tambah (Restock)</span>
                                    </label>
                                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="radio" v-model="form.type" value="out" />
                                        <span class="text-danger fw-bold">➖ Kurangi (Koreksi)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bold required">Jumlah</label>
                                <input v-model="form.qty" type="number" min="1"
                                    class="form-control form-control-solid" />
                                <div v-if="form.errors.qty" class="text-danger fs-8 mt-1">
                                    {{ form.errors.qty }}
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="form-label fw-bold">Keterangan</label>
                                <input v-model="form.keterangan" type="text"
                                    class="form-control form-control-solid"
                                    placeholder="Misal: Restock dari supplier, Koreksi stok..." />
                            </div>

                            <button @click="submit" :disabled="form.processing"
                                class="btn btn-primary w-100">
                                <i class="ki-duotone ki-check fs-2 me-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabel Produk & Stok -->
                <div class="col-lg-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Daftar Stok Produk</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">{{ products.length }} produk tercatat</span>
                            </h3>
                            <div class="card-toolbar">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <input v-model="search" type="text"
                                        class="form-control form-control-solid w-250px ps-14"
                                        placeholder="Cari produk..." />
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted fs-7 text-uppercase">
                                            <th class="w-25px">#</th>
                                            <th>Nama Produk</th>
                                            <th>Stok</th>
                                            <th>Status</th>
                                            <th class="text-end">Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="filteredProducts.length === 0">
                                            <td colspan="5" class="text-center text-muted py-10">
                                                <i class="ki-duotone ki-box fs-2hx text-muted mb-3">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                                <div>Belum ada data produk</div>
                                            </td>
                                        </tr>
                                        <tr v-for="(p, i) in filteredProducts" :key="p.id">
                                            <td class="ps-4">
                                                <span class="badge badge-light-primary fw-bold">{{ i + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-40px me-4">
                                                        <span class="symbol-label bg-light-info text-info fw-bold">
                                                            📦
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-gray-900">{{ p.nama }}</span>
                                                        <div class="text-muted fs-7">ID: {{ p.id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bolder fs-5"
                                                    :class="p.stok <= 5 ? 'text-danger' : 'text-success'">
                                                    {{ p.stok }}
                                                </span>
                                                <div class="text-muted fs-8">unit</div>
                                            </td>
                                            <td>
                                                <span class="badge fw-bold"
                                                    :class="p.stok === 0 ? 'badge-light-danger' : p.stok <= 5 ? 'badge-light-warning' : 'badge-light-success'">
                                                    {{ p.stok === 0 ? 'Habis' : p.stok <= 5 ? 'Menipis' : 'Aman' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <Link :href="route('stock.history', p.id)"
                                                    class="btn btn-sm btn-light-info">
                                                    <i class="ki-duotone ki-time-past fs-2 me-1">
                                                        <span class="path1"></span><span class="path2"></span>
                                                    </i>
                                                    Riwayat
                                                </Link>
                                            </td>
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
import { computed, ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    products: Array,
    lowStock: Array
})

const search = ref('')

const filteredProducts = computed(() => {
    if (!search.value) return props.products
    return props.products.filter(product =>
        product.nama.toLowerCase().includes(search.value.toLowerCase())
    )
})

const form = useForm({
    product_id: '',
    type:       'in',
    qty:        1,
    keterangan: '',
})

const submit = () => {
    form.post(route('stock.store'), {
        onSuccess: () => form.reset('qty', 'keterangan'),
    })
}
</script>