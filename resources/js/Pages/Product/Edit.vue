<template>
    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Edit Produk</h3>
            <Link :href="route('products.index')" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                Kembali
            </Link>
        </div>

        <div class="card">
            <div class="card-body p-10">
                <form @submit.prevent="submit">

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
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ product: Object, categories: Array })

const form = useForm({
    category_id: props.product.category_id,
    nama: props.product.nama,
    deskripsi: props.product.deskripsi ?? '',
    harga_beli: props.product.harga_beli,
    harga_jual: props.product.harga_jual,
    stok: props.product.stok,
    satuan: props.product.satuan,
    aktif: props.product.aktif,
})

const submit = () => {
    form.put(route('products.update', props.product.id))
}
</script>