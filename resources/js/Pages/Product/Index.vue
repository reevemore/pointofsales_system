<template>
    <Head title="Master Produk" />

    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Master Produk</h3>
            <div class="d-flex gap-2">
                <button @click="printSemuaBarcode" class="btn btn-light-success">
                    <i class="ki-duotone ki-printer fs-2">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Print Semua Barcode
                </button>
                <Link :href="route('products.create')" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Tambah Produk
                </Link>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input v-model="search" type="text"
                            class="form-control form-control-solid w-250px ps-14"
                            placeholder="Cari produk..." />
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>#</th>
                                <th>Foto</th>
                                <th>Kode & Barcode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredProducts.length === 0">
                                <td colspan="9" class="text-center text-muted py-10">
                                    Belum ada data produk
                                </td>
                            </tr>
                            <tr v-for="(product, i) in filteredProducts" :key="product.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <div class="symbol symbol-50px">
                                        <img v-if="product.foto"
                                            :src="'/storage/' + product.foto"
                                            :alt="product.nama"
                                            class="object-fit-cover rounded"
                                            style="width:50px;height:50px;" />
                                        <div v-else
                                            class="symbol-label fs-4 rounded"
                                            style="width:50px;height:50px;background:#f1f5ff;display:flex;align-items:center;justify-content:center;">
                                            📦
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Kode teks -->
                                    <span class="badge badge-light-info fw-bold mb-2 d-block" style="width:fit-content">
                                        {{ product.kode_produk }}
                                    </span>
                                    <!-- Gambar barcode -->
                                    <svg :id="'barcode-' + product.id" class="d-block"></svg>
                                    <!-- Tombol print per produk -->
                                    <button @click="printBarcode(product)"
                                        class="btn btn-xs btn-light-primary mt-1"
                                        style="font-size:11px;padding:3px 8px;">
                                        <i class="ki-duotone ki-printer fs-6 me-1">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        Print
                                    </button>
                                </td>
                                <td>
                                    <span class="text-gray-900 fw-bold d-block">{{ product.nama }}</span>
                                    <span class="text-muted fs-7">{{ product.satuan }}</span>
                                </td>
                                <td>{{ product.category?.nama ?? '-' }}</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ formatRupiah(product.harga_jual) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge"
                                        :class="product.stok > 0 ? 'badge-light-primary' : 'badge-light-danger'">
                                        {{ product.stok }} {{ product.satuan }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge"
                                        :class="product.aktif ? 'badge-light-success' : 'badge-light-danger'">
                                        {{ product.aktif ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <Link :href="route('products.edit', product.id)"
                                        class="btn btn-icon btn-light-primary btn-sm me-2">
                                        <i class="ki-duotone ki-pencil fs-4">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </Link>
                                    <button @click="hapus(product)"
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
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useSwal } from '@/Composables/useSwal'

const props = defineProps({ products: Object })

const { confirmDelete, showSuccess } = useSwal()
const page = usePage()
const search = ref('')

const filteredProducts = computed(() => {
    if (!search.value) return props.products.data
    return props.products.data.filter(p =>
        p.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        p.kode_produk.toLowerCase().includes(search.value.toLowerCase())
    )
})

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(val)
}

// Load JsBarcode dari CDN lalu render semua barcode
function loadJsBarcode() {
    return new Promise((resolve) => {
        if (window.JsBarcode) return resolve()
        const script = document.createElement('script')
        script.src = 'https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js'
        script.onload = resolve
        document.head.appendChild(script)
    })
}

async function renderSemua() {
    await loadJsBarcode()
    await nextTick()
    filteredProducts.value.forEach(product => {
        const el = document.getElementById('barcode-' + product.id)
        if (el) {
            try {
                JsBarcode(el, product.kode_produk, {
                    format: 'CODE128',
                    width: 1.5,
                    height: 40,
                    displayValue: false,
                    margin: 0,
                })
            } catch(e) {}
        }
    })
}

onMounted(async () => {
    if (page.props.flash?.success) {
        showSuccess(page.props.flash.success)
    }
    await renderSemua()
})

// Re-render barcode setiap kali hasil search berubah
watch(filteredProducts, async () => {
    await renderSemua()
}, { flush: 'post' })

// Print barcode 1 produk
function printBarcode(product) {
    const el = document.getElementById('barcode-' + product.id)
    if (!el) return
    const svgData = new XMLSerializer().serializeToString(el)
    const win = window.open('', '_blank', 'width=400,height=300')
    win.document.write(`
        <html><head><title>Barcode - ${product.kode_produk}</title>
        <style>
            body { font-family: monospace; text-align: center; padding: 20px; }
            .label { border: 1px dashed #ccc; display: inline-block; padding: 16px 24px; border-radius: 8px; }
            .nama { font-size: 13px; font-weight: bold; margin-bottom: 6px; }
            .kode { font-size: 11px; color: #666; margin-top: 4px; }
            .harga { font-size: 13px; font-weight: bold; color: #333; margin-top: 2px; }
        </style>
        </head><body>
        <div class="label">
            <div class="nama">${product.nama}</div>
            ${svgData}
            <div class="kode">${product.kode_produk}</div>
            <div class="harga">${formatRupiah(product.harga_jual)}</div>
        </div>
        <script>window.onload = function() { window.print(); }<\/script>
        </body></html>
    `)
    win.document.close()
}

// Print semua barcode sekaligus
function printSemuaBarcode() {
    const items = filteredProducts.value.map(product => {
        const el = document.getElementById('barcode-' + product.id)
        const svgData = el ? new XMLSerializer().serializeToString(el) : ''
        return `
            <div class="label">
                <div class="nama">${product.nama}</div>
                ${svgData}
                <div class="kode">${product.kode_produk}</div>
                <div class="harga">${formatRupiah(product.harga_jual)}</div>
            </div>
        `
    }).join('')

    const win = window.open('', '_blank', 'width=800,height=600')
    win.document.write(`
        <html><head><title>Print Semua Barcode</title>
        <style>
            body { font-family: monospace; padding: 16px; }
            .grid { display: flex; flex-wrap: wrap; gap: 12px; }
            .label { border: 1px dashed #ccc; display: inline-block; padding: 12px 16px;
                     border-radius: 8px; text-align: center; page-break-inside: avoid; }
            .nama { font-size: 12px; font-weight: bold; margin-bottom: 4px; max-width: 150px; }
            .kode { font-size: 10px; color: #666; margin-top: 2px; }
            .harga { font-size: 11px; font-weight: bold; margin-top: 2px; }
            @media print { body { padding: 0; } }
        </style>
        </head><body>
        <div class="grid">${items}</div>
        <script>window.onload = function() { window.print(); }<\/script>
        </body></html>
    `)
    win.document.close()
}

const hapus = async (product) => {
    const result = await confirmDelete(product.nama, 'produk')
    if (result.isConfirmed) {
        router.delete(route('products.destroy', product.id), {
            onSuccess: () => showSuccess(`Produk ${product.nama} berhasil dihapus.`),
        })
    }
}
</script>