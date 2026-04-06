<template>
    <Head title="Tambah User" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Tambah User
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Buat akun user baru untuk sistem</span>
                    </h1>
                    <Link :href="route('users.index')" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Kembali
                    </Link>
                </div>
            </div>

            <!-- Form -->
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Informasi User</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Lengkapi data user baru</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold required">Nama Lengkap</label>
                                    <input v-model="form.name" type="text"
                                        class="form-control form-control-solid"
                                        placeholder="Masukkan nama lengkap" />
                                    <div v-if="form.errors.name" class="text-danger fs-8 mt-1">{{ form.errors.name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold required">Email</label>
                                    <input v-model="form.email" type="email"
                                        class="form-control form-control-solid"
                                        placeholder="user@example.com" />
                                    <div v-if="form.errors.email" class="text-danger fs-8 mt-1">{{ form.errors.email }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold required">Password</label>
                                    <input v-model="form.password" type="password"
                                        class="form-control form-control-solid"
                                        placeholder="Minimal 8 karakter" />
                                    <div v-if="form.errors.password" class="text-danger fs-8 mt-1">{{ form.errors.password }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold required">Konfirmasi Password</label>
                                    <input v-model="form.password_confirmation" type="password"
                                        class="form-control form-control-solid"
                                        placeholder="Ulangi password" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold required">Role</label>
                                    <select v-model="form.role" class="form-select form-select-solid">
                                        <option value="">-- Pilih Role --</option>
                                        <option v-for="r in roles" :key="r.id" :value="r.name">
                                            {{ r.name.toUpperCase() }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.role" class="text-danger fs-8 mt-1">{{ form.errors.role }}</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-8">
                                <button @click="submit" :disabled="form.processing"
                                    class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2 me-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Simpan User
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({ roles: Array })

const { showSuccess, showError } = useSwal()

const form = useForm({
    name: '', email: '', password: '', password_confirmation: '', role: '',
})

const submit = () => {
    form.post(route('users.store'), {
        onSuccess: () => showSuccess(`User ${form.name} berhasil ditambahkan.`),
        onError: () => showError('Periksa kembali isian form.'),
    })
}
</script>