<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = useForm({
    // Data toko
    nama: '',
    alamat: '',
    telepon: '',
    email: '',
    // Data admin toko
    admin_name: '',
    admin_email: '',
    admin_password: '',
    admin_password_confirmation: '',
})

const submit = () => {
    form.post(route('stores.store'), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Tambah Toko" />
    <AuthenticatedLayout>
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-2 mb-6">
            <Link :href="route('stores.index')" class="text-muted text-hover-primary fs-6">
                Manajemen Toko
            </Link>
            <i class="ki-duotone ki-right fs-6 text-muted"></i>
            <span class="text-gray-900 fs-6 fw-semibold">Tambah Toko</span>
        </div>

        <div class="row g-6">
            <!-- Form Toko -->
            <div class="col-lg-6">
                <div class="card card-bordered h-100">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px symbol-light-primary">
                                    <span class="symbol-label">
                                        <i class="ki-duotone ki-shop fs-2 text-primary">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="card-label fw-bold fs-5 mb-0">Informasi Toko</h3>
                                    <span class="text-muted fs-7">Data utama toko</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Nama Toko</label>
                            <input
                                v-model="form.nama"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.nama }"
                                placeholder="Contoh: Toko Sumber Rejeki"
                            />
                            <div v-if="form.errors.nama" class="invalid-feedback">
                                {{ form.errors.nama }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea
                                v-model="form.alamat"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.alamat }"
                                rows="3"
                                placeholder="Alamat lengkap toko"
                            ></textarea>
                            <div v-if="form.errors.alamat" class="invalid-feedback">
                                {{ form.errors.alamat }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input
                                v-model="form.telepon"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.telepon }"
                                placeholder="Contoh: 031-12345678"
                            />
                            <div v-if="form.errors.telepon" class="invalid-feedback">
                                {{ form.errors.telepon }}
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Email Toko</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.email }"
                                placeholder="email@toko.com"
                            />
                            <div v-if="form.errors.email" class="invalid-feedback">
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Admin Toko -->
            <div class="col-lg-6">
                <div class="card card-bordered h-100">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center gap-3">
                                <div class="symbol symbol-40px symbol-light-success">
                                    <span class="symbol-label">
                                        <i class="ki-duotone ki-profile-circle fs-2 text-success">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="card-label fw-bold fs-5 mb-0">Akun Admin Toko</h3>
                                    <span class="text-muted fs-7">Otomatis dibuat saat toko disimpan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-6 p-4">
                            <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-7 text-gray-700">
                                        Akun admin ini akan digunakan oleh pengelola toko untuk login dan mengelola data tokonya.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Nama Admin</label>
                            <input
                                v-model="form.admin_name"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.admin_name }"
                                placeholder="Nama lengkap admin"
                            />
                            <div v-if="form.errors.admin_name" class="invalid-feedback">
                                {{ form.errors.admin_name }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Email Admin</label>
                            <input
                                v-model="form.admin_email"
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.admin_email }"
                                placeholder="admin@toko.com"
                            />
                            <div v-if="form.errors.admin_email" class="invalid-feedback">
                                {{ form.errors.admin_email }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Password Admin</label>
                            <input
                                v-model="form.admin_password"
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.admin_password }"
                                placeholder="Minimal 8 karakter"
                            />
                            <div v-if="form.errors.admin_password" class="invalid-feedback">
                                {{ form.errors.admin_password }}
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label required fw-semibold">Konfirmasi Password</label>
                            <input
                                v-model="form.admin_password_confirmation"
                                type="password"
                                class="form-control"
                                placeholder="Ulangi password"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mt-6">
            <Link :href="route('stores.index')" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Batal
            </Link>
            <button
                @click="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                <i v-else class="ki-duotone ki-check fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Simpan Toko & Buat Admin
            </button>
        </div>
    </AuthenticatedLayout>
</template>