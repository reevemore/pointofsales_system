<template>
    <AuthenticatedLayout>

        <Head title="Edit Produk" />

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Edit Produk</h3>
            <Link :href="route('products.index')" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Kembali
            </Link>
        </div>

        <div class="card">
            <div class="card-body p-10">
                <form @submit.prevent="submit" enctype="multipart/form-data">

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Kode Produk</label>
                        <div class="col-lg-9">
                            <input :value="product.kode_produk" type="text"
                                class="form-control form-control-solid" disabled />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Kategori</label>
                        <div class="col-lg-9">
                            <select v-model="form.category_id" class="form-select form-select-solid">
                                <option value="">-- Pilih Kategori --</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.nama }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Nama Produk</label>
                        <div class="col-lg-9">
                            <input v-model="form.nama" type="text"
                                class="form-control form-control-solid"
                                :class="{ 'is-invalid': form.errors.nama }" />
                            <div class="invalid-feedback">{{ form.errors.nama }}</div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Deskripsi</label>
                        <div class="col-lg-9">
                            <textarea v-model="form.deskripsi"
                                class="form-control form-control-solid" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- FOTO PRODUK -->
                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Foto Produk</label>
                        <div class="col-lg-9">

                            <!-- Tampilkan foto saat ini -->
                            <div v-if="currentFotoUrl && !hapusFoto" class="mb-3 d-flex align-items-center gap-3">
                                <img :src="currentFotoUrl" alt="Foto produk"
                                    class="rounded object-fit-cover"
                                    style="width:120px;height:120px;border:2px solid #e4e6ef;" />
                                <div>
                                    <div class="text-muted fs-8 mb-2">Foto saat ini</div>
                                    <button type="button" @click="gantiMode = true"
                                        v-if="!gantiMode"
                                        class="btn btn-sm btn-light-primary me-2">
                                        Ganti Foto
                                    </button>
                                    <button type="button" @click="triggerHapusFoto"
                                        class="btn btn-sm btn-light-danger">
                                        <i class="ki-duotone ki-trash fs-6">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                        </i>
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>

                            <!-- Konfirmasi hapus foto -->
                            <div v-if="hapusFoto" class="alert alert-warning d-flex align-items-center mb-3">
                                <i class="ki-duotone ki-information-5 fs-2 me-3">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                                <div class="flex-grow-1">Foto akan dihapus saat kamu klik <strong>Update</strong>.</div>
                                <button type="button" @click="batalHapusFoto" class="btn btn-sm btn-light ms-3">Batal</button>
                            </div>

                            <!-- Preview foto baru -->
                            <div v-if="previewUrl" class="mb-3">
                                <img :src="previewUrl" alt="Preview baru"
                                    class="rounded object-fit-cover"
                                    style="width:120px;height:120px;border:2px dashed #009ef7;" />
                                <div class="text-muted fs-8 mt-1">Preview foto baru</div>
                                <button type="button" @click="clearFotoBaru"
                                    class="btn btn-sm btn-light-danger mt-2">Batal Ganti</button>
                            </div>

                            <!-- Upload area (tampil jika: tidak ada foto lama, atau mode ganti, atau foto lama dihapus) -->
                            <div v-if="showUploadArea && !previewUrl"
                                @click="$refs.fotoInput.click()"
                                @dragover.prevent
                                @drop.prevent="onDrop"
                                class="border border-dashed border-primary rounded p-6 text-center cursor-pointer"
                                style="background:#f8f9ff;">
                                <i class="ki-duotone ki-picture fs-2x text-primary mb-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div class="fw-bold text-primary">Klik atau drag foto ke sini</div>
                                <div class="text-muted fs-8 mt-1">JPG, PNG, WEBP maks 2MB — akan di-resize ke 600×600</div>
                            </div>

                            <input ref="fotoInput" type="file" accept="image/*"
                                class="d-none" @change="onFileChange" />

                            <div v-if="form.errors.foto" class="text-danger fs-7 mt-2">
                                {{ form.errors.foto }}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Harga Beli</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input v-model="form.harga_beli" type="number"
                                    class="form-control form-control-solid" min="0" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Harga Jual</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input v-model="form.harga_jual" type="number"
                                    class="form-control form-control-solid" min="0" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Stok</label>
                        <div class="col-lg-9">
                            <input v-model="form.stok" type="number"
                                class="form-control form-control-solid" min="0" />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label required fw-bold fs-6">Satuan</label>
                        <div class="col-lg-9">
                            <select v-model="form.satuan" class="form-select form-select-solid">
                                <option value="pcs">Pcs</option>
                                <option value="box">Box</option>
                                <option value="lusin">Lusin</option>
                                <option value="kg">Kg</option>
                                <option value="liter">Liter</option>
                                <option value="botol">Botol</option>
                                <option value="pak">Pak</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-3 col-form-label fw-bold fs-6">Status</label>
                        <div class="col-lg-9 d-flex align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input v-model="form.aktif" class="form-check-input" type="checkbox" />
                                <label class="form-check-label fw-bold text-gray-400 ms-3">
                                    {{ form.aktif ? 'Aktif' : 'Non-aktif' }}
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
                            <Link :href="route('products.index')" class="btn btn-light">Batal</Link>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ product: Object, categories: Array })

const fotoInput = ref(null)
const previewUrl = ref(null)
const gantiMode = ref(false)
const hapusFoto = ref(false)

const currentFotoUrl = computed(() =>
    props.product.foto ? '/storage/' + props.product.foto : null
)

const showUploadArea = computed(() =>
    !currentFotoUrl.value || gantiMode.value || hapusFoto.value
)

const form = useForm({
    _method: 'PUT',
    category_id: props.product.category_id,
    nama: props.product.nama,
    deskripsi: props.product.deskripsi ?? '',
    foto: null,
    hapus_foto: false,
    harga_beli: props.product.harga_beli,
    harga_jual: props.product.harga_jual,
    stok: props.product.stok,
    satuan: props.product.satuan,
    aktif: props.product.aktif,
})

const onFileChange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    form.foto = file
    previewUrl.value = URL.createObjectURL(file)
    hapusFoto.value = false
    form.hapus_foto = false
}

const onDrop = (e) => {
    const file = e.dataTransfer.files[0]
    if (!file || !file.type.startsWith('image/')) return
    form.foto = file
    previewUrl.value = URL.createObjectURL(file)
    hapusFoto.value = false
    form.hapus_foto = false
}

const clearFotoBaru = () => {
    form.foto = null
    previewUrl.value = null
    gantiMode.value = false
    if (fotoInput.value) fotoInput.value.value = ''
}

const triggerHapusFoto = () => {
    hapusFoto.value = true
    form.hapus_foto = true
    form.foto = null
    previewUrl.value = null
    gantiMode.value = false
}

const batalHapusFoto = () => {
    hapusFoto.value = false
    form.hapus_foto = false
}

const submit = () => {
    form.post(route('products.update', props.product.id), {
        forceFormData: true,
    })
}
</script>