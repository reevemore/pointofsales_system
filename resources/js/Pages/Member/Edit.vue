<template>
    <AuthenticatedLayout>
        <Head title="Edit Member" />

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Edit Member</h3>
            <Link :href="route('members.index')" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Kembali
            </Link>
        </div>

        <div class="card">
            <div class="card-body p-10">
                <form @submit.prevent="submit">

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Kode Member</label>
                        <div class="col-lg-9">
                            <input :value="member.kode_member" type="text"
                                class="form-control form-control-solid"
                                disabled />
                            <div class="form-text">Kode member tidak dapat diubah</div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Nama</label>
                        <div class="col-lg-9">
                            <input v-model="form.nama" type="text"
                                class="form-control form-control-solid"
                                :class="{ 'is-invalid': form.errors.nama }"
                                placeholder="Nama lengkap member" />
                            <div class="invalid-feedback">{{ form.errors.nama }}</div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Telepon</label>
                        <div class="col-lg-9">
                            <input v-model="form.telepon" type="text"
                                class="form-control form-control-solid"
                                placeholder="Nomor telepon" />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Email</label>
                        <div class="col-lg-9">
                            <input v-model="form.email" type="email"
                                class="form-control form-control-solid"
                                :class="{ 'is-invalid': form.errors.email }"
                                placeholder="Email member" />
                            <div class="invalid-feedback">{{ form.errors.email }}</div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Alamat</label>
                        <div class="col-lg-9">
                            <textarea v-model="form.alamat"
                                class="form-control form-control-solid"
                                rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Poin</label>
                        <div class="col-lg-9">
                            <input v-model="form.poin" type="number"
                                class="form-control form-control-solid"
                                min="0" />
                        </div>
                    </div>

                    <!-- STATUS TOGGLE -->
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Status</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    v-model="statusAktif"
                                    id="statusToggle"
                                />
                                <label class="form-check-label fw-semibold ms-2" for="statusToggle">
                                    {{ statusAktif ? 'Aktif' : 'Non-aktif' }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-9 offset-lg-3">
                            <button type="submit" class="btn btn-primary me-3" :disabled="form.processing">
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Update</span>
                            </button>
                            <Link :href="route('members.index')" class="btn btn-light">Batal</Link>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, useForm, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({ member: Object })

const { showSuccess, showError } = useSwal()

const statusAktif = ref(props.member.status === 'aktif')

const form = useForm({
    nama: props.member.nama,
    telepon: props.member.telepon ?? '',
    email: props.member.email ?? '',
    alamat: props.member.alamat ?? '',
    poin: props.member.poin,
    status: props.member.status,
})

watch(statusAktif, (val) => {
    form.status = val ? 'aktif' : 'nonaktif'
})

const submit = () => {
    form.put(route('members.update', props.member.id), {
        onSuccess: () => showSuccess(`Data member ${form.nama} berhasil diperbarui.`),
        onError: () => showError('Periksa kembali isian form.'),
    })
}
</script>