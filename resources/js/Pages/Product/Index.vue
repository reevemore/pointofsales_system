<template>
    <Head title="Master Produk - POSCok" />

    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Master Produk</h3>
            <Link :href="route('products.create')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Tambah Produk
            </Link>
        </div>

        <div v-if="$page.props.flash?.success"
            class="alert alert-success d-flex align-items-center mb-5">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-3">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div>{{ $page.props.flash.success }}</div>
        </div>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
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
                            <tr class="fw-bold text-muted">
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredProducts.length === 0">
                                <td colspan="8" class="text-center text-muted py-10">
                                    Belum ada data produk
                                </td>
                            </tr>
                            <tr v-for="(product, i) in filteredProducts" :key="product.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <span class="badge badge-light-info fw-bold">
                                        {{ product.kode_produk }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-900 fw-bold d-block">{{ product.nama }}</span>
                                    <span class="text-muted fs-7">{{ product.satuan }}</span>
                                </td>
                                <td>{{ product.category?.nama ?? '-' }}</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ formatRupiah(product.harga_jual) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge"
                                        :class="product.stok > 0 ? 'badge-light-primary' : 'badge-light-danger'">
                                        {{ product.stok }} {{ product.satuan }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge"
                                        :class="product.aktif ? 'badge-light-success' : 'badge-light-danger'">
                                        {{ product.aktif ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <Link :href="route('products.edit', product.id)"
                                        class="btn btn-icon btn-light-primary btn-sm me-2">
                                        <i class="ki-duotone ki-pencil fs-4">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </Link>
                                    <button @click="confirmDelete(product)"
                                        class="btn btn-icon btn-light-danger btn-sm">
                                        <i class="ki-duotone ki-trash fs-4">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus produk
                        <strong>{{ selectedProduct?.nama }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteProduct">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ products: Object })

const search = ref('')
const selectedProduct = ref(null)

const filteredProducts = computed(() => {
    if (!search.value) return props.products.data
    return props.products.data.filter(p =>
        p.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        p.kode_produk.toLowerCase().includes(search.value.toLowerCase())
    )
})

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(val)
}

const confirmDelete = (product) => {
    selectedProduct.value = product
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
    modal.show()
}

const deleteProduct = () => {
    router.delete(route('products.destroy', selectedProduct.value.id), {
        onSuccess: () => {
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide()
        }
    })
}
</script>