<template>
    <Head title="Manajemen User" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Manajemen User
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Kelola pengguna sistem POS</span>
                    </h1>
                    <Link :href="route('users.create')" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Tambah User
                    </Link>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card card-flush">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <input v-model="search" type="text"
                                class="form-control form-control-solid w-250px ps-14"
                                placeholder="Cari user..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary">{{ filteredUsers.length }} User</span>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted fs-7 text-uppercase">
                                    <th class="w-25px">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredUsers.length === 0">
                                    <td colspan="5" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-user fs-2hx text-muted mb-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <div>Belum ada data user</div>
                                    </td>
                                </tr>
                                <tr v-for="(user, i) in filteredUsers" :key="user.id">
                                    <td class="ps-4">
                                        <span class="badge badge-light-primary fw-bold">{{ i + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-40px me-4">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                    {{ user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-gray-900">{{ user.name }}</span>
                                                <div class="text-muted fs-7">{{ user.email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ user.email }}</td>
                                    <td>
                                        <span v-for="role in user.roles" :key="role.id"
                                            class="badge me-1 fw-bold"
                                            :class="role.name === 'admin' ? 'badge-light-danger' : 'badge-light-primary'">
                                            {{ role.name.toUpperCase() }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <Link :href="route('users.edit', user.id)"
                                            class="btn btn-sm btn-light-warning me-2">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            Edit
                                        </Link>
                                        <button @click="hapus(user)"
                                            class="btn btn-sm btn-light-danger"
                                            :disabled="user.id === $page.props.auth.user.id">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span><span class="path2"></span>
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
import { computed, ref, onMounted } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({ users: Array })

const { confirmDelete, showSuccess } = useSwal()
const page = usePage()
const search = ref('')

const filteredUsers = computed(() => {
    if (!search.value) return props.users
    return props.users.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase()) ||
        user.email.toLowerCase().includes(search.value.toLowerCase())
    )
})

onMounted(() => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
})

const hapus = async (user) => {
    const result = await confirmDelete(user.name, 'user')
    if (result.isConfirmed) {
        router.delete(route('users.destroy', user.id), {
            onSuccess: () => showSuccess(`User ${user.name} berhasil dihapus.`),
        })
    }
}
</script>