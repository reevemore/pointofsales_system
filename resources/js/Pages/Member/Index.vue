<template>
    <Head title="Manajemen Member - POSCok" />

    <AuthenticatedLayout>

        <!-- Header -->
        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Manajemen Member</h3>
            <Link :href="route('members.create')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"></i>
                Tambah Member
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success"
            class="alert alert-success d-flex align-items-center mb-5">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-3">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <div>{{ $page.props.flash.success }}</div>
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
    <button @click="confirmDelete(member)"
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

        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus member
                        <strong>{{ selectedMember?.nama }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteMember">Hapus</button>
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

const props = defineProps({ members: Object })

const search = ref('')
const selectedMember = ref(null)

const filteredMembers = computed(() => {
    if (!search.value) return props.members.data
    return props.members.data.filter(m =>
        m.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        m.kode_member.toLowerCase().includes(search.value.toLowerCase())
    )
})

const confirmDelete = (member) => {
    selectedMember.value = member
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
    modal.show()
}

const deleteMember = () => {
    router.delete(route('members.destroy', selectedMember.value.id), {
        onSuccess: () => {
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide()
        }
    })
}
</script>