<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({
    stores: Array,
})

const { confirmDelete, toast } = useSwal()
const page = usePage()

const flash = page.props.flash

const handleToggle = (store) => {
    router.patch(route('stores.toggle', store.id), {}, {
        preserveScroll: true,
        onSuccess: () => toast(
            store.aktif ? 'Toko dinonaktifkan' : 'Toko diaktifkan',
            'success'
        ),
    })
}

const handleDelete = async (store) => {
    const confirmed = await confirmDelete(
        `Hapus toko "${store.nama}"?`,
        'Toko hanya bisa dihapus jika tidak memiliki user aktif.'
    )
    if (confirmed) {
        router.delete(route('stores.destroy', store.id), {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <!-- Flash Messages -->
        <div v-if="flash.success" class="alert alert-success d-flex align-items-center mb-5">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div class="d-flex flex-column">
                <span>{{ flash.success }}</span>
            </div>
        </div>
        <div v-if="flash.error" class="alert alert-danger d-flex align-items-center mb-5">
            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div class="d-flex flex-column">
                <span>{{ flash.error }}</span>
            </div>
        </div>

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-6">
            <div>
                <h1 class="fs-2 fw-bold text-gray-900 mb-1">Manajemen Toko</h1>
                <span class="text-muted fs-6">Total {{ stores.length }} toko terdaftar</span>
            </div>
            <a :href="route('stores.create')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2 me-1"></i>
                Tambah Toko
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-5 mb-6">
            <div class="col-md-4">
                <div class="card card-bordered h-100">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="symbol symbol-50px symbol-light-primary">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-shop fs-2x text-primary">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-gray-900">{{ stores.length }}</div>
                            <div class="text-muted fs-7">Total Toko</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bordered h-100">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="symbol symbol-50px symbol-light-success">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-verify fs-2x text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-gray-900">
                                {{ stores.filter(s => s.aktif).length }}
                            </div>
                            <div class="text-muted fs-7">Toko Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-bordered h-100">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="symbol symbol-50px symbol-light-danger">
                            <span class="symbol-label">
                                <i class="ki-duotone ki-cross-circle fs-2x text-danger">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-gray-900">
                                {{ stores.filter(s => !s.aktif).length }}
                            </div>
                            <div class="text-muted fs-7">Toko Nonaktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card card-bordered">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label fw-bold fs-5 text-gray-900">Daftar Toko</h3>
                </div>
            </div>
            <div class="card-body pt-0">
                <div v-if="stores.length === 0" class="text-center py-15">
                    <i class="ki-duotone ki-shop fs-5x text-muted mb-4">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <p class="text-muted fs-5">Belum ada toko. Klik "Tambah Toko" untuk memulai.</p>
                </div>

                <div v-else class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">#</th>
                                <th>Nama Toko</th>
                                <th>Kontak</th>
                                <th>Admin Toko</th>
                                <th>Total User</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th class="pe-4 rounded-end text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(store, i) in stores" :key="store.id">
                                <td class="ps-4 text-muted fs-7">{{ i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="symbol symbol-40px symbol-light-primary">
                                            <span class="symbol-label fw-bold fs-6 text-primary">
                                                {{ store.nama.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-gray-900 fs-6">{{ store.nama }}</div>
                                            <div class="text-muted fs-7">{{ store.alamat || '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-800 fs-7">{{ store.telepon || '-' }}</div>
                                    <div class="text-muted fs-7">{{ store.email || '-' }}</div>
                                </td>
                                <td>
                                    <div v-if="store.admin">
                                        <div class="fw-semibold text-gray-800 fs-7">{{ store.admin.name }}</div>
                                        <div class="text-muted fs-7">{{ store.admin.email }}</div>
                                    </div>
                                    <span v-else class="badge badge-light-warning">Belum ada admin</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-info fs-7">
                                        {{ store.users_count }} user
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge fs-7"
                                        :class="store.aktif ? 'badge-light-success' : 'badge-light-danger'"
                                    >
                                        {{ store.aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-muted fs-7">{{ store.created_at }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Toggle Aktif -->
                                        <button
                                            @click="handleToggle(store)"
                                            class="btn btn-sm"
                                            :class="store.aktif ? 'btn-light-warning' : 'btn-light-success'"
                                            :title="store.aktif ? 'Nonaktifkan' : 'Aktifkan'"
                                        >
                                            <i class="ki-duotone fs-4"
                                               :class="store.aktif ? 'ki-minus-circle' : 'ki-check-circle'">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            {{ store.aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>

                                        <!-- Edit -->
                                        
                                            :href="route('stores.edit', store.id)"
                                            class="btn btn-sm btn-light-primary"
                                            title="Edit"
                                        >
                                            <i class="ki-duotone ki-pencil fs-4">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            Edit
                                        

                                        <!-- Hapus -->
                                        <button
                                            @click="handleDelete(store)"
                                            class="btn btn-sm btn-light-danger"
                                            title="Hapus"
                                        >
                                            <i class="ki-duotone ki-trash fs-4">
                                                <span class="path1"></span><span class="path2"></span>
                                                <span class="path3"></span><span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>