<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    mustVerifyEmail: boolean;
    status?: string;
}>();

interface Flash {
    status?: string;
}

const page = usePage();
const flash = computed(() => (page.props.flash as Flash) ?? {});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Profile
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Kelola informasi akun Anda</span>
                    </h1>
                </div>
            </div>

            <!-- Flash Messages -->
            <div v-if="flash.status" class="alert alert-success d-flex align-items-center mb-5">
                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-3">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>{{ flash.status }}</div>
            </div>

            <div class="row g-6">
                <!-- Update Profile Information -->
                <div class="col-xl-8">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Informasi Profile</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Update nama dan email Anda</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl" />
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="col-xl-4">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Ubah Password</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Pastikan password kuat</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="col-12">
                    <div class="card card-flush border-danger">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-danger">Hapus Akun</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Tindakan ini tidak dapat dibatalkan</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>