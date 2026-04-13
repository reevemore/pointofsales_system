<template>
  <!-- Modal Pembayaran -->
  <Teleport to="body">
    <div v-if="show" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1055;">
      <div class="modal-dialog modal-dialog-centered" :class="modalSize">
        <div class="modal-content">

          <!-- Header -->
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
              <span v-if="step === 'method'">💳 Pilih Metode Pembayaran</span>
              <span v-else-if="step === 'transfer'">🏦 Pembayaran Transfer Bank</span>
              <span v-else-if="step === 'qris'">📱 Pembayaran QRIS</span>
            </h5>
            <button type="button" class="btn-close" @click="closeModal" :disabled="isLoading"></button>
          </div>

          <div class="modal-body pt-2">

            <!-- ===================== STEP 1: Pilih Metode ===================== -->
            <div v-if="step === 'method'">
              <p class="text-muted mb-3">
                Total Tagihan:
                <strong class="text-dark fs-5">{{ formatRupiah(total) }}</strong>
              </p>

              <div class="d-grid gap-2">
                <!-- Tunai -->
                <button class="btn btn-outline-success btn-lg d-flex align-items-center gap-3"
                        @click="pilihTunai">
                  <span class="fs-3">💵</span>
                  <div class="text-start">
                    <div class="fw-semibold">Tunai</div>
                    <small class="text-muted">Bayar langsung, cetak struk otomatis</small>
                  </div>
                </button>

                <!-- Transfer -->
                <button class="btn btn-outline-primary btn-lg d-flex align-items-center gap-3"
                        @click="pilihTransfer"
                        :disabled="isLoading">
                  <span class="fs-3">🏦</span>
                  <div class="text-start">
                    <div class="fw-semibold">Transfer Bank (VA)</div>
                    <small class="text-muted">BCA, BNI, BRI, Mandiri, dll</small>
                  </div>
                  <span v-if="isLoading && activeMethod === 'transfer'" class="spinner-border spinner-border-sm ms-auto"></span>
                </button>

                <!-- QRIS -->
                <button class="btn btn-outline-danger btn-lg d-flex align-items-center gap-3"
                        @click="pilihQris"
                        :disabled="isLoading">
                  <span class="fs-3">📱</span>
                  <div class="text-start">
                    <div class="fw-semibold">QRIS</div>
                    <small class="text-muted">GoPay, OVO, Dana, ShopeePay, dll</small>
                  </div>
                  <span v-if="isLoading && activeMethod === 'qris'" class="spinner-border spinner-border-sm ms-auto"></span>
                </button>
              </div>
            </div>

            <!-- ===================== STEP 2a: Transfer / Snap ===================== -->
            <div v-else-if="step === 'transfer'">
              <div class="text-center py-3">
                <div class="spinner-border text-primary mb-3" v-if="isLoading"></div>
                <p class="text-muted" v-if="isLoading">Membuka halaman pembayaran Midtrans...</p>

                <div v-if="!isLoading && snapToken">
                  <p class="text-success fw-semibold">
                    ✅ Pilih bank dan salin nomor Virtual Account
                  </p>
                  <p class="text-muted small">
                    Halaman Midtrans akan muncul. Setelah pembayaran berhasil, transaksi akan otomatis tercatat.
                  </p>
                  <button class="btn btn-primary" @click="openSnap">
                    Buka Halaman Pembayaran
                  </button>
                </div>
              </div>

              <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-sm btn-outline-secondary" @click="step = 'method'" :disabled="isLoading">
                  ← Kembali
                </button>
              </div>
            </div>

            <!-- ===================== STEP 2b: QRIS ===================== -->
            <div v-else-if="step === 'qris'">
              <div class="text-center">
                <div v-if="isLoading" class="py-4">
                  <div class="spinner-border text-danger mb-3"></div>
                  <p class="text-muted">Generating QRIS...</p>
                </div>

                <div v-else-if="qrisData">
                  <p class="mb-1 text-muted">Scan QRIS di bawah ini:</p>
                  <p class="fw-bold fs-5 text-danger">{{ formatRupiah(total) }}</p>

                  <!-- Gambar QR dari Midtrans -->
                  <div class="border rounded p-2 d-inline-block mb-3 bg-white">
                    <img :src="qrisData.qr_image_url"
                         alt="QRIS"
                         width="220" height="220"
                         style="display:block;"
                         @error="handleQrError" />
                  </div>

                  <div class="alert alert-info py-2 small text-start">
                    <strong>Order ID:</strong> {{ qrisData.order_id }}<br>
                    <strong>Berlaku hingga:</strong> {{ formatExpiry(qrisData.expire_time) }}
                  </div>

                  <!-- Status polling -->
                  <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <div class="spinner-border spinner-border-sm text-secondary" v-if="isPolling"></div>
                    <small class="text-muted">
                      {{ isPolling ? 'Menunggu konfirmasi pembayaran...' : 'Klik tombol di bawah setelah scan' }}
                    </small>
                  </div>

                  <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary" @click="step = 'method'" :disabled="isPolling">
                      ← Kembali
                    </button>
                    <button class="btn btn-sm btn-success" @click="cekStatusQris" :disabled="isPolling">
                      🔄 Cek Status Bayar
                    </button>
                  </div>
                </div>

                <div v-else-if="errorMsg" class="text-danger py-3">
                  <p>{{ errorMsg }}</p>
                  <button class="btn btn-sm btn-outline-danger" @click="pilihQris">Coba Lagi</button>
                </div>
              </div>
            </div>

          </div><!-- end modal-body -->
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onUnmounted } from 'vue'
import axios from 'axios'

// ─── Props ──────────────────────────────────────────────────
const props = defineProps({
  show:  { type: Boolean, default: false },
  total: { type: Number, required: true },
  items: { type: Array, required: true },  // [{id, name, price, qty}]
})

// ─── Emits ──────────────────────────────────────────────────
const emit = defineEmits([
  'close',
  'paid-tunai',     // kasir klik Tunai → lanjut alur lama (cetak struk)
  'paid-transfer',  // transfer berhasil → simpan transaksi + cetak
  'paid-qris',      // qris berhasil    → simpan transaksi + cetak
  'print-receipt',
])

// ─── State ──────────────────────────────────────────────────
const step         = ref('method')   // method | transfer | qris | success
const isLoading    = ref(false)
const isPolling    = ref(false)
const activeMethod = ref(null)
const snapToken    = ref(null)
const currentOrderId = ref(null)
const qrisData     = ref(null)
const errorMsg     = ref(null)

let pollInterval = null

// ─── Computed ────────────────────────────────────────────────
const modalSize = computed(() => step.value === 'qris' ? 'modal-md' : 'modal-sm')

// ─── Watchers ────────────────────────────────────────────────
watch(() => props.show, (val) => {
  if (!val) resetState()
})

// ─── Methods ─────────────────────────────────────────────────

function resetState() {
  step.value         = 'method'
  isLoading.value    = false
  isPolling.value    = false
  activeMethod.value = null
  snapToken.value    = null
  currentOrderId.value = null
  qrisData.value     = null
  errorMsg.value     = null
  stopPolling()
}

function closeModal() {
  emit('close')
}

// ── Tunai: langsung emit, alur lama tetap berjalan ──────────
function pilihTunai() {
  emit('paid-tunai')
}

// ── Transfer: minta snap token ke backend, lalu buka Snap ───
async function pilihTransfer() {
  activeMethod.value = 'transfer'
  isLoading.value    = true
  step.value         = 'transfer'
  errorMsg.value     = null

  try {
    const { data } = await axios.post(route('payment.transfer'), {
      items: props.items,
      total: props.total,
    })

    if (!data.success) throw new Error(data.message)

    snapToken.value      = data.snap_token
    currentOrderId.value = data.order_id
    isLoading.value      = false

    // Buka Snap popup Midtrans otomatis
    openSnap()

  } catch (e) {
    isLoading.value = false
    errorMsg.value  = e.message || 'Terjadi kesalahan'
    step.value      = 'method'
    alert('❌ ' + errorMsg.value)
  }
}

function openSnap() {
  if (!snapToken.value) return

  window.snap.pay(snapToken.value, {
    onSuccess: (result) => {
        emit('paid-transfer', { orderId: currentOrderId.value, result })
        closeModal()
      },
      onPending: (result) => {
        // Pembayaran sedang diproses, tapi VA sudah diberikan
        emit('paid-transfer', { orderId: currentOrderId.value, result, pending: true })
        closeModal()
    },
    onClose: () => {
      // Pengguna menutup popup tanpa bayar
      step.value = 'method'
    },
  })
}

// ── QRIS: generate QR via Core API ──────────────────────────
async function pilihQris() {
  activeMethod.value = 'qris'
  isLoading.value    = true
  step.value         = 'qris'
  errorMsg.value     = null
  qrisData.value     = null

  try {
    const { data } = await axios.post(route('payment.qris'), {
      items: props.items,
      total: props.total,
    })

    if (!data.success) throw new Error(data.message)

    qrisData.value       = data
    currentOrderId.value = data.order_id
    isLoading.value      = false

    // Auto-polling setiap 5 detik
    startPolling()

  } catch (e) {
    isLoading.value = false
    errorMsg.value = e.response?.data?.message || e.message || 'Gagal generate QRIS'
  }
}

async function cekStatusQris() {
  if (!currentOrderId.value) return
  isPolling.value = true

  try {
    const { data } = await axios.get(route('payment.status', currentOrderId.value))

    if (data.paid) {
      stopPolling()
      emit('paid-qris', { orderId: currentOrderId.value })
      closeModal()
    } else {
      alert('⏳ Pembayaran belum diterima. Status: ' + data.status)
    }
  } catch (e) {
    console.error(e)
  } finally {
    isPolling.value = false
  }
}

function startPolling() {
  stopPolling()
  pollInterval = setInterval(async () => {
    if (!currentOrderId.value) return
    try {
      const { data } = await axios.get(route('payment.status', currentOrderId.value))
      if (data.paid) {
        stopPolling()
        emit('paid-qris', { orderId: currentOrderId.value })
        closeModal()
      }
    } catch (_) {}
  }, 5000)
}

function stopPolling() {
  if (pollInterval) {
    clearInterval(pollInterval)
    pollInterval = null
  }
}

// ─── Helpers ─────────────────────────────────────────────────
function formatRupiah(val) {
  return 'Rp ' + Number(val).toLocaleString('id-ID')
}

function formatExpiry(str) {
  if (!str) return '-'
  return new Date(str).toLocaleString('id-ID', {
    dateStyle: 'medium', timeStyle: 'short'
  })
}

function handleQrError() {
  errorMsg.value = 'Gagal memuat gambar QR. Coba refresh atau gunakan metode lain.'
}

onUnmounted(() => stopPolling())
</script>