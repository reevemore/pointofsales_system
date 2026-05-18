<template>
    <Head title="Transaksi" />

    <AuthenticatedLayout>
        <div class="row g-5">

            <!-- Kiri: Scan / Input Kode Produk -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title fw-bold">
                            <i class="ki-duotone ki-scan-barcode fs-2 me-2">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                                <span class="path5"></span><span class="path6"></span>
                                <span class="path7"></span><span class="path8"></span>
                            </i>
                            Scan / Input Kode Produk
                        </h3>
                    </div>
                    <div class="card-body pt-4">

                        <!-- Input Barcode -->
                        <div class="d-flex gap-3 align-items-center mb-4">
                            <div class="position-relative flex-grow-1">
                                <input
                                    ref="barcodeInput"
                                    v-model="kodeInput"
                                    @keyup.enter="cariProduk"
                                    type="text"
                                    class="form-control form-control-solid form-control-lg ps-12"
                                    placeholder="ketik kode (contoh: PRD0001)..."
                                    :class="{ 'border-danger': errorMsg }"
                                    autocomplete="off"
                                />
                                <span class="position-absolute top-50 translate-middle-y ms-4"
                                    style="left:0; color:#a1a5b7; pointer-events:none;">
                                    <i class="ki-duotone ki-scan-barcode fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span><span class="path6"></span>
                                        <span class="path7"></span><span class="path8"></span>
                                    </i>
                                </span>
                            </div>
                            <button @click="cariProduk"
                                class="btn btn-primary btn-lg px-6"
                                :disabled="isSearching">
                                <span v-if="isSearching"
                                    class="spinner-border spinner-border-sm me-2"></span>
                                <span v-else>Tambah</span>
                            </button>
                        </div>

                        <!-- Notifikasi Error -->
                        <transition name="fade">
                            <div v-if="errorMsg"
                                class="alert alert-danger d-flex align-items-center gap-3 py-3 mb-4">
                                <i class="ki-duotone ki-cross-circle fs-2 text-danger">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div>{{ errorMsg }}</div>
                                <button @click="errorMsg = ''" class="btn-close ms-auto" />
                            </div>
                        </transition>

                        <!-- Notifikasi Sukses -->
                        <transition name="fade">
                            <div v-if="successMsg"
                                class="alert alert-success d-flex align-items-center gap-3 py-3 mb-4">
                                <i class="ki-duotone ki-check-circle fs-2 text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div>{{ successMsg }}</div>
                            </div>
                        </transition>

                        <!-- Panduan -->
                        <div class="notice d-flex bg-light-primary rounded border-primary
                            border border-dashed p-5">
                            <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">
                                        <strong>Cara penggunaan:</strong><br>
                                        Arahkan ketik kode produk
                                        (contoh: <code>PRD0001</code>) lalu tekan <kbd>Enter</kbd>.
                                        Produk akan langsung masuk ke keranjang.
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
                            <button @click="clearCart"
                                class="btn btn-sm btn-light-danger"
                                v-if="cart.length">
                                Kosongkan
                            </button>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <div v-if="cart.length === 0"
                            class="text-center text-muted py-10">
                            Belum ada produk dipilih
                        </div>

                        <div v-for="(item, i) in cart" :key="i"
                            class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
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
                                <div class="text-muted fs-8">{{ item.kode_produk }}</div>
                                <div class="text-muted fs-8">{{ formatRupiah(item.harga_jual) }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button @click="decQty(i)"
                                    class="btn btn-sm btn-icon btn-light">-</button>
                                <span class="fw-bold w-20px text-center">{{ item.qty }}</span>
                                <button @click="incQty(i, item)"
                                    class="btn btn-sm btn-icon btn-light">+</button>
                                <button @click="removeItem(i)"
                                    class="btn btn-sm btn-icon btn-light-danger">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                        </div>

                        <div v-if="cart.length" class="mt-4">

                            <!-- Member -->
                            <div class="mb-4">
                                <label class="form-label fw-bold fs-7">Member (Opsional)</label>
                                <select v-model="form.member_id"
                                    class="form-select form-select-sm form-select-solid"
                                    @change="onMemberChange">
                                    <option value="">-- Tanpa Member --</option>
                                    <option v-for="m in members" :key="m.id" :value="m.id">
                                        {{ m.nama }} ({{ m.poin }} poin)
                                    </option>
                                </select>
                            </div>

                            <!-- Info & Redeem Poin -->
                            <div v-if="selectedMember"
                                class="mb-4 p-4 bg-light-warning rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold fs-7">🎯 Poin Member</span>
                                    <span class="badge badge-light-warning fw-bold">
                                        {{ selectedMember.poin }} poin
                                    </span>
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
                                <div v-if="form.diskon > 0"
                                    class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Diskon</span>
                                    <span class="fw-bold text-danger">
                                        - {{ formatRupiah(form.diskon) }}
                                    </span>
                                </div>
                                <div v-if="form.redeem_poin > 0"
                                    class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">
                                        Diskon Poin ({{ form.redeem_poin }} poin)
                                    </span>
                                    <span class="fw-bold text-warning">
                                        - {{ formatRupiah(form.redeem_poin * nilaiPerPoin) }}
                                    </span>
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
                                <div v-if="form.bayar >= total && form.bayar > 0"
                                    class="mt-2 text-success fw-bold">
                                    Kembalian: {{ formatRupiah(form.bayar - total) }}
                                </div>
                            </div>

                            <button @click="bukaModalPembayaran"
                                :disabled="cart.length === 0"
                                class="btn btn-primary w-100 btn-lg">
                                💳 Bayar — {{ formatRupiah(total) }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Struk -->
        <div v-if="receipt" class="modal fade show d-block"
            style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div id="struk" class="p-6" style="font-family: monospace;">
                            <div class="text-center mb-4">
                                <div class="fs-4 fw-bolder">🏪 POS APP</div>
                                <div class="text-muted fs-8">Jl. Bungkal Gg II No 25B Sambikerep, Surabaya</div>
                                <div class="text-muted fs-8">Telp: 08123456789</div>
                                <div class="border-bottom border-dashed my-3"></div>
                                <div class="fs-8 text-muted">{{ receipt.no_transaksi }}</div>
                                <div class="fs-8 text-muted">{{ formatDate(receipt.created_at) }}</div>
                                <div class="fs-8 text-muted">Kasir: {{ receipt.user?.name }}</div>
                                <div v-if="receipt.member" class="fs-8 text-muted">
                                    Member: {{ receipt.member.nama }}
                                </div>
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
                                <span>Subtotal</span>
                                <span>{{ formatRupiah(receipt.subtotal) }}</span>
                            </div>
                            <div v-if="receipt.diskon > 0"
                                class="d-flex justify-content-between fs-7 mb-1 text-danger">
                                <span>Diskon</span>
                                <span>- {{ formatRupiah(receipt.diskon) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bolder fs-5 mb-1">
                                <span>TOTAL</span>
                                <span>{{ formatRupiah(receipt.total) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 text-muted mb-1">
                                <span>Bayar ({{ receipt.metode_bayar.toUpperCase() }})</span>
                                <span>{{ formatRupiah(receipt.bayar) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 text-success fw-bold">
                                <span>Kembali</span>
                                <span>{{ formatRupiah(receipt.kembali) }}</span>
                            </div>
                            <div class="border-top border-dashed my-3"></div>
                            <div class="text-center text-muted fs-8">
                                Terima kasih sudah berbelanja! 🙏
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-3 justify-content-center">
                        <button @click="printStruk" class="btn btn-primary btn-sm">
                            🖨️ Print Struk
                        </button>
                        <button @click="startNewTransaction" class="btn btn-light btn-sm">
                            Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PaymentModal Component -->
        <PaymentModal
            :show="showPaymentModal"
            :total="total"
            :items="itemsForMidtrans"
            @close="showPaymentModal = false"
            @paid-tunai="handleTunai"
            @paid-transfer="handleTransfer"
            @paid-qris="handleQris"
            @print-receipt="printStruk"
        />

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PaymentModal from '@/Components/PaymentModal.vue'

const props = defineProps({ products: Array, members: Array })
const page  = usePage()

const nilaiPerPoin     = 1000
const cart             = ref([])
const receipt          = ref(null)
const showPaymentModal = ref(false)

// ── State barcode ─────────────────────────────────────────────
const kodeInput    = ref('')
const isSearching  = ref(false)
const errorMsg     = ref('')
const successMsg   = ref('')
const barcodeInput = ref(null)

// ── Helper ambil XSRF token dari cookie ──────────────────────
function getXsrfToken() {
    return decodeURIComponent(
        document.cookie
            .split('; ')
            .find(r => r.startsWith('XSRF-TOKEN='))
            ?.split('=')[1] ?? ''
    )
}

// ── onMounted: ambil CSRF cookie lalu fokus input ────────────
onMounted(async () => {
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' })
    } catch (e) {
        // tidak fatal, lanjut saja
    }
    nextTick(() => barcodeInput.value?.focus())
})

// ── Cari produk by kode (async, tanpa reload) ────────────────
async function cariProduk() {
    const kode = kodeInput.value.trim()
    if (!kode) return

    errorMsg.value    = ''
    successMsg.value  = ''
    isSearching.value = true

    try {
        const res  = await fetch(`/api/products/by-kode/${encodeURIComponent(kode)}`, {
            credentials: 'include',
            headers: {
                'Accept':       'application/json',
                'X-XSRF-TOKEN': getXsrfToken(),
            },
        })
        const data = await res.json()

        if (!res.ok || !data.product) {
            errorMsg.value = `Produk dengan kode "${kode}" tidak ditemukan.`
            kodeInput.value = ''
            barcodeInput.value?.focus()
            return
        }

        const product = data.product

        if (product.stok <= 0) {
            errorMsg.value  = `Produk "${product.nama}" stok habis!`
            kodeInput.value = ''
            barcodeInput.value?.focus()
            return
        }

        addToCart(product)
        successMsg.value = `✅ "${product.nama}" ditambahkan ke keranjang.`
        setTimeout(() => { successMsg.value = '' }, 2500)

    } catch (e) {
        errorMsg.value = 'Terjadi kesalahan jaringan. Coba lagi.'
    } finally {
        isSearching.value = false
        kodeInput.value   = ''
        barcodeInput.value?.focus()
    }
}

// ── Cart helpers ─────────────────────────────────────────────
const addToCart = (product) => {
    const existing = cart.value.find(i => i.id === product.id)
    if (existing) {
        if (existing.qty < product.stok) existing.qty++
        else errorMsg.value = `Stok "${product.nama}" tidak mencukupi!`
    } else {
        cart.value.push({ ...product, qty: 1 })
    }
}

const incQty = (i, item) => {
    if (cart.value[i].qty < item.stok) cart.value[i].qty++
    else errorMsg.value = `Stok "${item.nama}" tidak mencukupi!`
}
const decQty     = (i) => { if (cart.value[i].qty > 1) cart.value[i].qty--; else cart.value.splice(i, 1) }
const removeItem = (i) => cart.value.splice(i, 1)
const clearCart  = () => { cart.value = []; errorMsg.value = ''; barcodeInput.value?.focus() }

// ── Computed ─────────────────────────────────────────────────
const selectedMember = computed(() =>
    props.members.find(m => m.id === form.member_id) ?? null
)
const itemsForMidtrans = computed(() =>
    cart.value.map(item => ({
        id:    item.id,
        name:  item.nama,
        price: Math.round(item.harga_jual),
        qty:   item.qty,
    }))
)
const subtotal = computed(() => cart.value.reduce((s, i) => s + i.harga_jual * i.qty, 0))
const total    = computed(() =>
    Math.max(0, subtotal.value - Number(form.diskon) - (form.redeem_poin * nilaiPerPoin))
)

// ── Form ─────────────────────────────────────────────────────
const form = useForm({
    member_id:         '',
    diskon:            0,
    bayar:             0,
    metode_bayar:      'tunai',
    redeem_poin:       0,
    items:             [],
    payment_method:    'tunai',
    midtrans_order_id: null,
    payment_status:    'paid',
})

const onMemberChange = () => { form.redeem_poin = 0 }

// ── Format helpers ────────────────────────────────────────────
const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})

// ── Payment handlers ──────────────────────────────────────────
function bukaModalPembayaran() {
    receipt.value = null
    showPaymentModal.value = true
}
function handleTunai() {
    showPaymentModal.value = false
    simpanTransaksi('tunai')
}
function handleTransfer({ orderId, pending }) {
    simpanTransaksi('transfer', {
        midtrans_order_id: orderId,
        payment_status:    pending ? 'pending' : 'paid',
    })
}
function handleQris({ orderId }) {
    simpanTransaksi('qris', { midtrans_order_id: orderId, payment_status: 'paid' })
}

function simpanTransaksi(paymentMethod = 'tunai', extraData = {}) {
    form.items             = cart.value.map(i => ({ product_id: i.id, qty: i.qty }))
    form.payment_method    = paymentMethod
    form.metode_bayar      = paymentMethod
    form.midtrans_order_id = extraData.midtrans_order_id ?? null
    form.payment_status    = extraData.payment_status    ?? 'paid'

    form.post(route('pos.store'), {
        onSuccess: () => {
            resetTransaction()
            const trx = page.props.flash?.transaction
            if (trx) receipt.value = trx
            showPaymentModal.value = false
            if (paymentMethod === 'tunai') printStruk()
        }
    })
}

function startNewTransaction() { receipt.value = null; resetTransaction() }

function resetTransaction() {
    clearCart()
    form.reset()
    form.member_id         = ''
    form.diskon            = 0
    form.bayar             = 0
    form.metode_bayar      = 'tunai'
    form.redeem_poin       = 0
    form.items             = []
    form.payment_method    = 'tunai'
    form.midtrans_order_id = null
    form.payment_status    = 'paid'
}

const printStruk = () => {
    const content = document.getElementById('struk')?.innerHTML
    if (!content) return
    receipt.value = null
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

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from,  .fade-leave-to      { opacity: 0; }
</style>