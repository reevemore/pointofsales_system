<template>
    <Head title="Kategori Produk" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Kategori Produk
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Kelola kategori produk toko</span>
                    </h1>
                    <Link :href="route('categories.create')" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Tambah Kategori
                    </Link>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="card card-flush">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input v-model="search" type="text"
                                class="form-control form-control-solid w-250px ps-14"
                                placeholder="Cari kategori..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">{{ filteredCategories.length }} Kategori</span>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="w-25px">#</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredCategories.length === 0">
                                    <td colspan="5" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-tag fs-2hx text-muted mb-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <div>Belum ada data kategori</div>
                                    </td>
                                </tr>
                                <tr v-for="(cat, i) in filteredCategories" :key="cat.id">
                                    <td class="ps-4">
                                        <span class="badge badge-light-primary fw-bold">{{ i + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-40px me-4">
                                                <span class="symbol-label bg-light-info text-info fw-bold">
                                                    🏷️
                                                </span>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-gray-900">{{ cat.nama }}</span>
                                                <div class="text-muted fs-7">ID: {{ cat.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ cat.deskripsi ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge fw-bold"
                                            :class="cat.aktif ? 'badge-light-success' : 'badge-light-danger'">
                                            {{ cat.aktif ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <Link :href="route('categories.edit', cat.id)"
                                            class="btn btn-sm btn-light-warning me-2">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            Edit
                                        </Link>
                                        <button @click="hapus(cat)"
                                            class="btn btn-sm btn-light-danger">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span><span class="path2"></span>
                                                <span class="path3"></span><span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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

const props = defineProps({ categories: Object })

const { confirmDelete, showSuccess } = useSwal()
const page = usePage()
const search = ref('')

const filteredCategories = computed(() => {
    if (!search.value) return props.categories.data
    return props.categories.data.filter(c =>
        c.nama.toLowerCase().includes(search.value.toLowerCase())
    )
})

onMounted(() => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
})

const hapus = async (cat) => {
    const result = await confirmDelete(cat.nama, 'kategori')
    if (result.isConfirmed) {
        router.delete(route('categories.destroy', cat.id), {
            onSuccess: () => showSuccess(`Kategori ${cat.nama} berhasil dihapus.`),
        })
    }
}
</script>