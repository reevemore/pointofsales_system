<template>
    <Head title="Manajemen Member" />

    <AuthenticatedLayout>

        <!-- Header -->
        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Manajemen Member</h3>
            <Link :href="route('members.create')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Tambah Member
            </Link>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input v-model="search" type="text"
                            class="form-control form-control-solid w-250px ps-14"
                            placeholder="Cari member..." />
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-25px">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Poin</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredMembers.length === 0">
                                <td colspan="8" class="text-center text-muted py-10">
                                    Belum ada data member
                                </td>
                            </tr>
                            <tr v-for="(member, i) in filteredMembers" :key="member.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold">
                                        {{ member.kode_member }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-900 fw-bold">{{ member.nama }}</span>
                                </td>
                                <td>{{ member.telepon ?? '-' }}</td>
                                <td>{{ member.email ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-light-warning">
                                        {{ member.poin }} poin
                                    </span>
                                </td>
                                <td>
                                    <span class="badge"
                                        :class="member.status === 'aktif' ? 'badge-light-success' : 'badge-light-danger'">
                                        {{ member.status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <Link :href="route('members.poin', member.id)"
                                        class="btn btn-sm btn-light-warning me-2">
                                        🎯 Poin
                                    </Link>
                                    <Link :href="route('members.edit', member.id)"
                                        class="btn btn-icon btn-light-primary btn-sm me-2">
                                        <i class="ki-duotone ki-pencil fs-4">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </Link>
                                    <button @click="hapus(member)"
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

const props = defineProps({ members: Object })

const { confirmDelete, showSuccess } = useSwal()
const page = usePage()
const search = ref('')

const filteredMembers = computed(() => {
    if (!search.value) return props.members.data
    return props.members.data.filter(m =>
        m.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        m.kode_member.toLowerCase().includes(search.value.toLowerCase())
    )
})

onMounted(() => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
})

const hapus = async (member) => {
    const result = await confirmDelete(member.nama, 'member')
    if (result.isConfirmed) {
        router.delete(route('members.destroy', member.id), {
            onSuccess: () => showSuccess(`Member ${member.nama} berhasil dihapus.`),
        })
    }
}
</script>