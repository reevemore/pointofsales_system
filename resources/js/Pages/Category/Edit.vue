<template>
    <Head title="Edit Kategori - POSCok" />

    <AuthenticatedLayout>
        <div class="container-xxl">
            <!-- Page Title -->
            <div class="page-title d-print-none mb-5">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                        Edit Kategori
                        <span class="page-desc text-muted fs-7 fw-semibold mt-2">Update informasi kategori {{ category.nama }}</span>
                    </h1>
                    <Link :href="route('categories.index')" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-2 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Kembali
                    </Link>
                </div>
            </div>

            <!-- Flash Message -->
            <div v-if="$page.props.flash?.success"
                class="alert alert-success d-flex align-items-center mb-5">
                <i class="ki-duotone ki-shield-tick fs-2hx text-success me-3">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <div>{{ $page.props.flash.success }}</div>
            </div>

            <!-- Form -->
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card card-flush">
                        <div class="card-header border-0 pt-6">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Informasi Kategori</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Edit data kategori yang dipilih</span>
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <form @submit.prevent="submit">
                                <div class="row g-5">
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold required">Nama Kategori</label>
                                        <input v-model="form.nama" type="text"
                                            class="form-control form-control-solid"
                                            :class="{ 'is-invalid': form.errors.nama }"
                                            placeholder="Contoh: Minuman, Makanan, dll" />
                                        <div class="invalid-feedback">{{ form.errors.nama }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Status</label>
                                        <div class="d-flex align-items-center mt-3">
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <input v-model="form.aktif" class="form-check-input" type="checkbox" />
                                                <label class="form-check-label fw-bold text-gray-600 ms-3">
                                                    {{ form.aktif ? 'Aktif' : 'Non-aktif' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Deskripsi</label>
                                        <textarea v-model="form.deskripsi"
                                            class="form-control form-control-solid"
                                            rows="4"
                                            placeholder="Deskripsi kategori (opsional)"></textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-8">
                                    <Link :href="route('categories.index')" class="btn btn-light me-3">
                                        <i class="ki-duotone ki-cross fs-2 me-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Batal
                                    </Link>
                                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                        <i class="ki-duotone ki-check fs-2 me-2" v-if="!form.processing">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <span v-if="form.processing">Menyimpan...</span>
                                        <span v-else>Update Kategori</span>
                                    </button>
                                </div>
                            </form>
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

const props = defineProps({ category: Object })

const form = useForm({
    nama: props.category.nama,
    deskripsi: props.category.deskripsi ?? '',
    aktif: props.category.aktif,
})

const submit = () => {
    form.put(route('categories.update', props.category.id))
}
</script>