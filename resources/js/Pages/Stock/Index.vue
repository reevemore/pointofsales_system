<template>
    <Head title="Manajemen Stok" />

    <AuthenticatedLayout>
        <div class="container-xxl">

            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Stok
                </h1>
                <span class="text-muted fw-semibold fs-7 my-1">Kelola inventori dan stok produk</span>
            </div>
            <!--end::Page title-->

            <!--begin::Alert Stok Menipis-->
            <div v-if="lowStock.length" class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-6">
                <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 mb-5 mb-sm-0">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                </span>
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <h5 class="mb-1">Stok Menipis!</h5>
                    <span class="fs-7">
                        <span v-for="(p, i) in lowStock" :key="p.id">
                            <strong>{{ p.nama }}</strong> ({{ p.stok }} sisa)
                            <span v-if="i < lowStock.length - 1">, </span>
                        </span>
                    </span>
                </div>
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <i class="ki-duotone ki-cross fs-2 text-danger"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>
            <!--end::Alert-->

            <div class="row g-6">

                <!--begin::Form Update Stok-->
                <div class="col-lg-4">
                    <div class="card card-flush h-lg-100">
                        <!--begin::Card header-->
                        <div class="card-header border-0 pt-6">
                            <div class="card-title flex-column">
                                <span class="card-label fw-bold text-dark">Update Stok</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Tambah atau kurangi stok produk</span>
                            </div>
                        </div>
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <!--begin::Produk-->
                            <div class="mb-5">
                                <label class="form-label required fw-semibold">Produk</label>
                                <select v-model="form.product_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Produk">
                                    <option value="">-- Pilih Produk --</option>
                                    <option v-for="p in products" :key="p.id" :value="p.id">
                                        {{ p.nama }} (Stok: {{ p.stok }})
                                    </option>
                                </select>
                                <div v-if="form.errors.product_id" class="fv-plugins-message-container invalid-feedback">
                                    {{ form.errors.product_id }}
                                </div>
                            </div>
                            <!--end::Produk-->

                            <!--begin::Jenis-->
                            <div class="mb-5">
                                <label class="form-label required fw-semibold">Jenis Mutasi</label>
                                <div class="d-flex gap-4">
                                    <label class="form-check form-check-custom form-check-solid form-check-sm d-flex align-items-center gap-2 cursor-pointer">
                                        <input class="form-check-input" type="radio" v-model="form.type" value="in" />
                                        <span class="form-check-label text-success fw-bold">Tambah (Restock)</span>
                                    </label>
                                    <label class="form-check form-check-custom form-check-solid form-check-sm d-flex align-items-center gap-2 cursor-pointer">
                                        <input class="form-check-input" type="radio" v-model="form.type" value="out" />
                                        <span class="form-check-label text-danger fw-bold">Kurangi (Koreksi)</span>
                                    </label>
                                </div>
                            </div>
                            <!--end::Jenis-->

                            <!--begin::Jumlah-->
                            <div class="mb-5">
                                <label class="form-label required fw-semibold">Jumlah</label>
                                <input v-model="form.qty" type="number" min="1"
                                    class="form-control form-control-solid"
                                    placeholder="Masukkan jumlah..." />
                                <div v-if="form.errors.qty" class="fv-plugins-message-container invalid-feedback">
                                    {{ form.errors.qty }}
                                </div>
                            </div>
                            <!--end::Jumlah-->

                            <!--begin::Keterangan-->
                            <div class="mb-7">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <input v-model="form.keterangan" type="text"
                                    class="form-control form-control-solid"
                                    placeholder="Misal: Restock dari supplier, Koreksi stok..." />
                            </div>
                            <!--end::Keterangan-->

                            <!--begin::Submit-->
                            <button @click="submit" :disabled="form.processing"
                                class="btn btn-primary w-100">
                                <span v-if="!form.processing">
                                    <i class="ki-duotone ki-check fs-2 me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Simpan
                                </span>
                                <span v-else class="indicator-progress d-block">
                                    Menyimpan...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <!--end::Submit-->

                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <!--end::Form Update Stok-->

                <!--begin::Tabel Stok-->
                <div class="col-lg-8">
                    <div class="card card-flush h-lg-100">
                        <!--begin::Card header-->
                        <div class="card-header border-0 pt-6">
                            <div class="card-title flex-column">
                                <span class="card-label fw-bold text-dark">Daftar Stok Produk</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">{{ products.length }} produk tercatat</span>
                            </div>
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
                        <!--end::Card header-->

                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold text-muted fs-7 text-uppercase">
                                            <th class="w-25px ps-4">#</th>
                                            <th class="min-w-200px">Nama Produk</th>
                                            <th class="min-w-80px">Stok</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-80px text-end pe-4">Riwayat</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody>
                                        <!--begin::Empty state-->
                                        <tr v-if="filteredProducts.length === 0">
                                            <td colspan="5" class="text-center py-10">
                                                <i class="ki-duotone ki-box fs-3x text-muted mb-4">
                                                    <span class="path1"></span><span class="path2"></span>
                                                </i>
                                                <div class="text-muted fw-semibold">Belum ada data produk</div>
                                            </td>
                                        </tr>
                                        <!--end::Empty state-->

                                        <!--begin::Row-->
                                        <tr v-for="(p, i) in filteredProducts" :key="p.id">
                                            <!--begin::No-->
                                            <td class="ps-4">
                                                <span class="text-gray-600 fw-semibold fs-7">{{ i + 1 }}</span>
                                            </td>
                                            <!--end::No-->

                                            <!--begin::Produk-->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-4">
                                                        <span class="symbol-label bg-light-warning">
                                                            <i class="ki-duotone ki-cube-2 fs-2 text-warning">
                                                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                                            </i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-900 fw-bold text-hover-primary fs-6">{{ p.nama }}</span>
                                                        <span class="text-muted fw-semibold fs-7">ID: {{ p.id }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <!--end::Produk-->

                                            <!--begin::Stok-->
                                            <td>
                                                <span class="fw-bolder fs-5"
                                                    :class="p.stok <= 5 ? 'text-danger' : 'text-success'">
                                                    {{ p.stok }}
                                                </span>
                                                <div class="text-muted fw-semibold fs-8">unit</div>
                                            </td>
                                            <!--end::Stok-->

                                            <!--begin::Status-->
                                            <td>
                                                <span class="badge fw-bold fs-8 px-3 py-2"
                                                    :class="p.stok === 0 ? 'badge-light-danger' : p.stok <= 5 ? 'badge-light-warning' : 'badge-light-success'">
                                                    {{ p.stok === 0 ? 'Habis' : p.stok <= 5 ? 'Menipis' : 'Aman' }}
                                                </span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Action-->
                                            <td class="text-end pe-4">
                                                <Link :href="route('stock.history', p.id)"
                                                    class="btn btn-sm btn-light-primary fw-bold">
                                                    <i class="ki-duotone ki-time-past fs-4 me-1">
                                                        <span class="path1"></span><span class="path2"></span>
                                                    </i>
                                                    Riwayat
                                                </Link>
                                            </td>
                                            <!--end::Action-->
                                        </tr>
                                        <!--end::Row-->
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                            </div>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <!--end::Tabel Stok-->

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({
    products: Array,
    lowStock: Array
})

const { confirmStock, showSuccess, showError } = useSwal()
const page = usePage()
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

onMounted(() => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
})

const submit = async () => {
    if (!form.product_id) {
        showError('Pilih produk terlebih dahulu.', 'Produk Belum Dipilih')
        return
    }
    if (!form.qty || form.qty < 1) {
        showError('Jumlah harus lebih dari 0.', 'Jumlah Tidak Valid')
        return
    }

    const produk = props.products.find(p => p.id === form.product_id)
    const result = await confirmStock(produk?.nama ?? 'produk', form.qty, form.type)

    if (result.isConfirmed) {
        form.post(route('stock.store'), {
            onSuccess: () => {
                form.reset('qty', 'keterangan')
                showSuccess('Stok berhasil diperbarui!')
            },
        })
    }
}
</script>