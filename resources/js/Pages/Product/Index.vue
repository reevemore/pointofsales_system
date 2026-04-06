<template>
    <Head title="Master Produk" />

    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Master Produk</h3>
            <Link :href="route('products.create')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Tambah Produk
            </Link>
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
                                <th>Foto</th>
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
                                <td colspan="9" class="text-center text-muted py-10">
                                    Belum ada data produk
                                </td>
                            </tr>
                            <tr v-for="(product, i) in filteredProducts" :key="product.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <div class="symbol symbol-50px">
                                        <img v-if="product.foto"
                                            :src="'/storage/' + product.foto"
                                            :alt="product.nama"
                                            class="object-fit-cover rounded"
                                            style="width:50px;height:50px;" />
                                        <div v-else
                                            class="symbol-label fs-4 rounded"
                                            style="width:50px;height:50px;background:#f1f5ff;display:flex;align-items:center;justify-content:center;">
                                            📦
                                        </div>
                                    </div>
                                </td>
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
                                    <button @click="hapus(product)"
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

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({ products: Object })

const { confirmDelete, showSuccess } = useSwal()
const page = usePage()
const search = ref('')

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

onMounted(() => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
})

const hapus = async (product) => {
    const result = await confirmDelete(product.nama, 'produk')
    if (result.isConfirmed) {
        router.delete(route('products.destroy', product.id), {
            onSuccess: () => showSuccess(`Produk ${product.nama} berhasil dihapus.`),
        })
    }
}
</script>