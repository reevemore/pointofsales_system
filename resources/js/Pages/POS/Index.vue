<template>
    <Head title="Point of Sale" />

    <AuthenticatedLayout>
        <div class="row g-5">

            <!-- Kiri: Daftar Produk -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <input v-model="search" type="text"
                                class="form-control form-control-solid w-300px"
                                placeholder="Cari produk..." />
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-4">
                            <div v-if="filteredProducts.length === 0" class="col-12 text-center text-muted py-10">
                                Tidak ada produk
                            </div>
                            <div v-for="product in filteredProducts" :key="product.id" class="col-6 col-md-4">
                                <div class="card card-bordered card-hover cursor-pointer h-100"
                                    @click="addToCart(product)">

                                    <!-- Foto Produk -->
                                    <div class="card-img-top overflow-hidden"
                                        style="height:130px;background:#f1f5ff;">
                                        <img v-if="product.foto"
                                            :src="'/storage/' + product.foto"
                                            :alt="product.nama"
                                            class="w-100 h-100 object-fit-cover" />
                                        <div v-else
                                            class="w-100 h-100 d-flex align-items-center justify-content-center"
                                            style="font-size:3rem;">
                                            📦
                                        </div>
                                    </div>

                                    <div class="card-body p-3 text-center">
                                        <div class="fs-7 fw-bold text-gray-800 mb-1 text-truncate">{{ product.nama }}</div>
                                        <div class="text-muted fs-8 mb-1">{{ product.category?.nama }}</div>
                                        <div class="text-success fw-bold fs-7">{{ formatRupiah(product.harga_jual) }}</div>
                                        <div class="mt-1">
                                            <span class="badge badge-light-primary fs-8">Stok: {{ product.stok }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Keranjang -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title fw-bold">🛒 Keranjang</h3>
                        <div class="card-toolbar">
                            <button @click="clearCart" class="btn btn-sm btn-light-danger" v-if="cart.length">
                                Kosongkan
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <div v-if="cart.length === 0" class="text-center text-muted py-10">
                            Belum ada produk dipilih
                        </div>

                        <div v-for="(item, i) in cart" :key="i"
                            class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                            <!-- Thumbnail kecil di keranjang -->
                            <div class="me-3 flex-shrink-0">
                                <img v-if="item.foto"
                                    :src="'/storage/' + item.foto"
                                    :alt="item.nama"
                                    class="rounded object-fit-cover"
                                    style="width:40px;height:40px;" />
                                <div v-else
                                    class="rounded d-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;background:#f1f5ff;font-size:1.2rem;">
                                    📦
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-gray-800 fs-7">{{ item.nama }}</div>
                                <div class="text-muted fs-8">{{ formatRupiah(item.harga_jual) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button @click="decQty(i)" class="btn btn-sm btn-icon btn-light">-</button>
                                <span class="fw-bold w-20px text-center">{{ item.qty }}</span>
                                <button @click="incQty(i, item)" class="btn btn-sm btn-icon btn-light">+</button>
                                <button @click="removeItem(i)" class="btn btn-sm btn-icon btn-light-danger">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                        </div>

                        <div v-if="cart.length" class="mt-4">

                            <!-- Member -->
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-7">Member (Opsional)</label>
                                <select v-model="form.member_id" class="form-select form-select-sm form-select-solid"
                                    @change="onMemberChange">
                                    <option value="">-- Tanpa Member --</option>
                                    <option v-for="m in members" :key="m.id" :value="m.id">
                                        {{ m.nama }} ({{ m.poin }} poin)
                                    </option>
                                </select>
                            </div>

                            <!-- Info & Redeem Poin -->
                            <div v-if="selectedMember" class="mb-4 p-4 bg-light-warning rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold fs-7">🎯 Poin Member</span>
                                    <span class="badge badge-light-warning fw-bold">{{ selectedMember.poin }} poin</span>
                                </div>
                                <div class="text-muted fs-8 mb-3">
                                    1 poin = {{ formatRupiah(nilaiPerPoin) }} diskon &nbsp;|&nbsp;
                                    Maks redeem: {{ formatRupiah(selectedMember.poin * nilaiPerPoin) }}
                                </div>
                                <label class="form-label fw-bold fs-8">Redeem Poin (opsional)</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input v-model="form.redeem_poin" type="number" min="0"
                                        :max="selectedMember.poin"
                                        class="form-control form-control-sm form-control-solid w-100px" />
                                    <span class="text-success fw-bold fs-7">
                                        = {{ formatRupiah(form.redeem_poin * nilaiPerPoin) }} diskon
                                    </span>
                                    <button @click="form.redeem_poin = selectedMember.poin"
                                        class="btn btn-sm btn-light-warning">Semua</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold fs-7">Diskon (Rp)</label>
                                <input v-model="form.diskon" type="number" min="0"
                                    class="form-control form-control-sm form-control-solid" />
                            </div>

                            <div class="bg-light rounded p-4 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold">{{ formatRupiah(subtotal) }}</span>
                                </div>
                                <div v-if="form.diskon > 0" class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Diskon</span>
                                    <span class="fw-bold text-danger">- {{ formatRupiah(form.diskon) }}</span>
                                </div>
                                <div v-if="form.redeem_poin > 0" class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Diskon Poin ({{ form.redeem_poin }} poin)</span>
                                    <span class="fw-bold text-warning">- {{ formatRupiah(form.redeem_poin * nilaiPerPoin) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fs-5 fw-bolder border-top pt-2">
                                    <span>Total</span>
                                    <span class="text-primary">{{ formatRupiah(total) }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold fs-7">Uang Bayar (Rp)</label>
                                <input v-model="form.bayar" type="number" min="0"
                                    class="form-control form-control-solid"
                                    :class="{ 'border-danger': form.bayar < total && form.bayar > 0 }" />
                                <div v-if="form.bayar >= total && form.bayar > 0" class="mt-2 text-success fw-bold">
                                    Kembalian: {{ formatRupiah(form.bayar - total) }}
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="form-label fw-bold fs-7">Metode Pembayaran</label>
                                <div class="d-flex gap-3">
                                    <label v-for="m in metodes" :key="m.value"
                                        class="d-flex align-items-center gap-2 cursor-pointer">
                                        <input type="radio" v-model="form.metode_bayar" :value="m.value" />
                                        <span>{{ m.label }}</span>
                                    </label>
                                </div>
                            </div>

                            <button @click="submit"
                                :disabled="form.processing || form.bayar < total"
                                class="btn btn-primary w-100 btn-lg">
                                <span v-if="form.processing">Memproses...</span>
                                <span v-else>💳 Bayar {{ formatRupiah(total) }}</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Struk -->
        <div v-if="receipt" class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div id="struk" class="p-6" style="font-family: monospace;">
                            <div class="text-center mb-4">
                                <div class="fs-4 fw-bolder">🏪 POS APP</div>
                                <div class="text-muted fs-8">Jl. Contoh No. 1, Surabaya</div>
                                <div class="text-muted fs-8">Telp: 08123456789</div>
                                <div class="border-bottom border-dashed my-3"></div>
                                <div class="fs-8 text-muted">{{ receipt.no_transaksi }}</div>
                                <div class="fs-8 text-muted">{{ formatDate(receipt.created_at) }}</div>
                                <div class="fs-8 text-muted">Kasir: {{ receipt.user?.name }}</div>
                                <div v-if="receipt.member" class="fs-8 text-muted">Member: {{ receipt.member.nama }}</div>
                            </div>
                            <div class="border-bottom border-dashed mb-3"></div>
                            <div v-for="item in receipt.items" :key="item.id" class="mb-2">
                                <div class="fw-bold fs-7">{{ item.product.nama }}</div>
                                <div class="d-flex justify-content-between fs-8 text-muted">
                                    <span>{{ item.qty }} x {{ formatRupiah(item.harga_satuan) }}</span>
                                    <span>{{ formatRupiah(item.subtotal) }}</span>
                                </div>
                            </div>
                            <div class="border-top border-dashed my-3"></div>
                            <div class="d-flex justify-content-between fs-7 mb-1">
                                <span>Subtotal</span><span>{{ formatRupiah(receipt.subtotal) }}</span>
                            </div>
                            <div v-if="receipt.diskon > 0" class="d-flex justify-content-between fs-7 mb-1 text-danger">
                                <span>Diskon</span><span>- {{ formatRupiah(receipt.diskon) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bolder fs-5 mb-1">
                                <span>TOTAL</span><span>{{ formatRupiah(receipt.total) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 text-muted mb-1">
                                <span>Bayar ({{ receipt.metode_bayar.toUpperCase() }})</span>
                                <span>{{ formatRupiah(receipt.bayar) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 text-success fw-bold">
                                <span>Kembali</span><span>{{ formatRupiah(receipt.kembali) }}</span>
                            </div>
                            <div class="border-top border-dashed my-3"></div>
                            <div class="text-center text-muted fs-8">Terima kasih sudah berbelanja! 🙏</div>
                        </div>
                    </div>
                    <div class="modal-footer gap-3 justify-content-center">
                        <button @click="printStruk" class="btn btn-primary btn-sm">🖨️ Print Struk</button>
                        <button @click="receipt = null" class="btn btn-light btn-sm">Transaksi Baru</button>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ products: Array, members: Array })
const page  = usePage()

const nilaiPerPoin = 1000
const search  = ref('')
const cart    = ref([])
const receipt = ref(null)

const filteredProducts = computed(() => {
    if (!search.value) return props.products
    return props.products.filter(p =>
        p.nama.toLowerCase().includes(search.value.toLowerCase())
    )
})

const selectedMember = computed(() =>
    props.members.find(m => m.id === form.member_id) ?? null
)

const onMemberChange = () => { form.redeem_poin = 0 }

const addToCart = (product) => {
    const existing = cart.value.find(i => i.id === product.id)
    if (existing) {
        if (existing.qty < product.stok) existing.qty++
    } else {
        cart.value.push({ ...product, qty: 1 })
    }
}

const incQty     = (i, item) => {
    const original = props.products.find(p => p.id === item.id)
    if (cart.value[i].qty < original.stok) cart.value[i].qty++
}
const decQty     = (i) => { if (cart.value[i].qty > 1) cart.value[i].qty--; else cart.value.splice(i, 1) }
const removeItem = (i) => cart.value.splice(i, 1)
const clearCart  = () => cart.value = []

const subtotal = computed(() => cart.value.reduce((s, i) => s + i.harga_jual * i.qty, 0))
const total    = computed(() =>
    Math.max(0, subtotal.value - Number(form.diskon) - (form.redeem_poin * nilaiPerPoin))
)

const form = useForm({
    member_id:    '',
    diskon:       0,
    bayar:        0,
    metode_bayar: 'tunai',
    redeem_poin:  0,
    items:        [],
})

const metodes = [
    { value: 'tunai',    label: 'Tunai' },
    { value: 'transfer', label: 'Transfer' },
    { value: 'qris',     label: 'QRIS' },
]

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})

const submit = () => {
    form.items = cart.value.map(i => ({ product_id: i.id, qty: i.qty }))
    form.post(route('pos.store'), {
        onSuccess: () => {
            const trx = page.props.flash?.transaction
            if (trx) receipt.value = trx
            clearCart()
            form.reset()
        }
    })
}

const printStruk = () => {
    const content = document.getElementById('struk').innerHTML
    const win = window.open('', '_blank', 'width=400,height=600')
    win.document.write(`
        <html><head><title>Struk</title>
        <style>
            body { font-family: monospace; font-size: 12px; padding: 16px; }
            .d-flex { display: flex; }
            .justify-content-between { justify-content: space-between; }
            .text-center { text-align: center; }
            .fw-bold, .fw-bolder { font-weight: bold; }
            .text-muted { color: #888; }
            .text-danger { color: #f1416c; }
            .text-success { color: #50cd89; }
            .border-dashed { border-top: 1px dashed #ccc; margin: 8px 0; }
            .mb-1{margin-bottom:4px}.mb-2{margin-bottom:8px}
            .mb-3{margin-bottom:12px}.mb-4{margin-bottom:16px}.my-3{margin:12px 0}
            .fs-4{font-size:16px}.fs-5{font-size:14px}.fs-7{font-size:12px}.fs-8{font-size:11px}
        </style>
        </head><body>${content}</body></html>
    `)
    win.document.close()
    win.print()
}
</script>