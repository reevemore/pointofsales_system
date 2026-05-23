<template>
    <Head title="Struk Transaksi" />
    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Struk Transaksi</h3>
            <div class="d-flex gap-2">
                <button @click="printStruk" class="btn btn-primary">
                    <i class="ki-duotone ki-printer fs-2 me-1">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Cetak Struk
                </button>
                <Link :href="route('pos.history')" class="btn btn-light">
                    <i class="ki-duotone ki-arrow-left fs-2 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Riwayat
                </Link>
                <Link :href="route('pos.index')" class="btn btn-success">
                    <i class="ki-duotone ki-plus fs-2 me-1"></i>
                    Transaksi Baru
                </Link>
            </div>
        </div>

        <!-- Struk Preview -->
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div id="struk-area" class="card shadow-sm">
                    <div class="card-body p-8" style="font-family: 'Courier New', monospace;">

                        <!-- Header Toko -->
                        <div class="text-center mb-4">
                            <div class="fw-bolder fs-4 text-dark">{{ storeName }}</div>
                            <div class="text-muted fs-7">Point of Sale</div>
                            <div class="border-bottom border-dashed my-3"></div>
                        </div>

                        <!-- Info Transaksi -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between fs-7">
                                <span class="text-muted">No. Transaksi</span>
                                <span class="fw-bold">{{ trx.no_transaksi }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mt-1">
                                <span class="text-muted">Kasir</span>
                                <span>{{ trx.user?.name ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mt-1">
                                <span class="text-muted">Tanggal</span>
                                <span>{{ formatDate(trx.created_at) }}</span>
                            </div>
                            <div v-if="trx.member" class="d-flex justify-content-between fs-7 mt-1">
                                <span class="text-muted">Member</span>
                                <span>{{ trx.member.nama }}</span>
                            </div>
                        </div>

                        <div class="border-bottom border-dashed my-3"></div>

                        <!-- Item List -->
                        <div class="mb-3">
                            <div v-for="item in trx.items" :key="item.id" class="mb-2">
                                <div class="fw-semibold fs-7 text-dark">{{ item.product?.nama ?? '-' }}</div>
                                <div class="d-flex justify-content-between fs-7 text-muted">
                                    <span>{{ item.qty }} x {{ formatRupiah(item.harga_satuan) }}</span>
                                    <span class="text-dark fw-bold">{{ formatRupiah(item.subtotal) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="border-bottom border-dashed my-3"></div>

                        <!-- Subtotal / Diskon / Total -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between fs-7">
                                <span class="text-muted">Subtotal</span>
                                <span>{{ formatRupiah(trx.subtotal) }}</span>
                            </div>
                            <div v-if="trx.diskon > 0" class="d-flex justify-content-between fs-7 mt-1">
                                <span class="text-muted">Diskon</span>
                                <span class="text-danger">- {{ formatRupiah(trx.diskon) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bolder fs-6 mt-2">
                                <span>TOTAL</span>
                                <span class="text-primary">{{ formatRupiah(trx.total) }}</span>
                            </div>
                        </div>

                        <div class="border-bottom border-dashed my-3"></div>

                        <!-- Bayar / Kembali -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between fs-7">
                                <span class="text-muted">Bayar ({{ trx.metode_bayar?.toUpperCase() }})</span>
                                <span>{{ formatRupiah(trx.bayar) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-7 mt-1">
                                <span class="text-muted">Kembali</span>
                                <span class="fw-bold text-success">{{ formatRupiah(trx.kembali) }}</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <div class="border-bottom border-dashed mb-3"></div>
                            <div class="text-muted fs-8">Terima kasih atas kunjungan Anda!</div>
                            <div class="text-muted fs-8">Barang yang sudah dibeli tidak dapat dikembalikan.</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ transaction: Object })
const page = usePage()

const trx = computed(() => props.transaction ?? {})
const storeName = computed(() => trx.value.user?.store?.nama ?? page.props.auth?.user?.store?.nama ?? 'Toko')

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})

const printStruk = () => {
    const el = document.getElementById('struk-area')
    if (!el) return
    const win = window.open('', '_blank', 'width=400,height=600')
    win.document.write(`
        <html><head><title>Struk - ${trx.value.no_transaksi}</title>
        <style>
            body { font-family: 'Courier New', monospace; padding: 16px; font-size: 12px; }
            .border-dashed { border-top: 1px dashed #ccc; margin: 8px 0; }
            .text-center { text-align: center; }
            .d-flex { display: flex; justify-content: space-between; }
            .fw-bold { font-weight: bold; }
            .text-muted { color: #888; }
            @media print { body { padding: 0; } }
        </style>
        </head><body>${el.innerHTML}
        <script>window.onload = function() { window.print(); window.close(); }<\/script>
        </body></html>
    `)
    win.document.close()
}

// Auto-print saat halaman pertama kali dibuka (setelah transaksi)
onMounted(() => {
    const fromTransaction = document.referrer.includes('/pos') || window.history.length <= 2
    if (fromTransaction) {
        setTimeout(() => printStruk(), 800)
    }
})
</script>