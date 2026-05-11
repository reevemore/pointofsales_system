<template>
    <Head title="Masuk" />

    <div class="login-root">

        <!-- Left panel / branding -->
        <div class="left-panel">
            <div class="brand">
                <div class="brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 22V12h6v10" stroke="#fff" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="brand-name">POS<span>OWIII</span></span>
            </div>

            <div class="left-content">
                <div class="left-badge">Point of Sale System</div>
                <h2 class="left-title">Kelola toko Anda<br>lebih mudah &amp; cepat</h2>
                <p class="left-desc">Platform manajemen penjualan lengkap untuk bisnis Anda. Pantau pendapatan, stok, dan transaksi dalam satu tempat.</p>

                <div class="stat-row">
                    <div class="stat-card">
                        <span class="stat-icon blue">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                        </span>
                        <div>
                            <div class="stat-val">Transaksi</div>
                            <div class="stat-lbl">Real-time</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-icon green">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="1" fill="currentColor"/></svg>
                        </span>
                        <div>
                            <div class="stat-val">Laporan</div>
                            <div class="stat-lbl">Otomatis</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-icon amber">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="currentColor" stroke-width="2"/><circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="currentColor" stroke-width="2"/></svg>
                        </span>
                        <div>
                            <div class="stat-val">Member</div>
                            <div class="stat-lbl">Terintegrasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="left-footer">© 2025 POSSYS · All rights reserved</div>
        </div>

        <!-- Right panel / form -->
        <div class="right-panel">
            <div class="form-wrap">

                <div class="form-header">
                    <h1 class="form-title">Selamat Datang</h1>
                    <p class="form-sub">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <div v-if="status" class="alert-ok">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="flex-shrink:0"><path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="form">

                    <div class="fg">
                        <label class="flabel">Alamat Email</label>
                        <div class="finput-wrap" :class="{ err: form.errors.email }">
                            <span class="ficon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.8"/><polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                            </span>
                            <input v-model="form.email" type="email" placeholder="email@domain.com"
                                class="finput" autofocus autocomplete="username" />
                        </div>

                    </div>

                    <div class="fg">
                        <div class="label-row">
                            <label class="flabel">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="forgot">
                                Lupa password?
                            </Link>
                        </div>
                        <div class="finput-wrap" :class="{ err: form.errors.password }">
                            <span class="ficon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="1.8"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </span>
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                placeholder="Masukkan password" class="finput" autocomplete="current-password" />
                            <button type="button" class="eye-btn" @click="showPassword = !showPassword">
                                <svg v-if="!showPassword" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                                <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </button>
                        </div>

                    </div>

                    <div class="remember-row">
                        <label class="chk-label" @click="form.remember = !form.remember">
                            <div class="chk-box" :class="{ on: form.remember }">
                                <svg v-if="form.remember" width="10" height="10" viewBox="0 0 24 24" fill="none"><polyline points="20 6 9 17 4 12" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span class="chk-txt">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="form.processing">
                        <span v-if="!form.processing" class="btn-inner">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="10 17 15 12 10 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="15" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            Masuk ke Dashboard
                        </span>
                        <span v-else class="spin-wrap">
                            <span class="spinner"></span> Memproses...
                        </span>
                    </button>



                </form>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, watch } from 'vue'                       
import { useForm, Link, Head } from '@inertiajs/vue3'
import Swal from 'sweetalert2'                         

defineProps({ canResetPassword: Boolean, status: String })

const form = useForm({ email: '', password: '', remember: false })
const showPassword = ref(false)

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),

        //  onError: dipanggil otomatis jika ada error dari server
        onError: (errors) => {
            // Jika email tidak ditemukan / kredensial tidak cocok
            if (errors.email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Tidak Ditemukan',
                    text: 'Email yang Anda masukkan tidak terdaftar. Silakan periksa kembali.',
                    confirmButtonColor: '#6366f1',
                    confirmButtonText: 'Coba Lagi',
                })
                return
            }

            // Jika password salah 
            if (errors.password) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Salah',
                    text: 'Password yang Anda masukkan tidak sesuai. Silakan periksa kembali.',
                    confirmButtonColor: '#6366f1',
                    confirmButtonText: 'Coba Lagi',
                })
                return
            }

            // Fallback: error umum lainnya
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Terjadi kesalahan. Silakan coba beberapa saat lagi.',
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'OK',
            })
        },
    })
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.login-root {
    min-height: 100vh;
    display: flex;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: #f9fafb;
}

/* ── Left Panel ── */
.left-panel {
    width: 420px;
    flex-shrink: 0;
    background: #1e2129;
    display: flex;
    flex-direction: column;
    padding: 2.5rem 2.25rem;
    position: relative;
    overflow: hidden;
}
.left-panel::before {
    content: '';
    position: absolute;
    top: -120px; right: -120px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(99,102,241,0.12);
    pointer-events: none;
}
.left-panel::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(16,185,129,0.07);
    pointer-events: none;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 3.5rem;
    position: relative;
    z-index: 1;
}
.brand-icon {
    width: 38px; height: 38px;
    background: #6366f1;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.brand-name {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #fff;
}
.brand-name span { color: #818cf8; }

.left-content { flex: 1; position: relative; z-index: 1; }
.left-badge {
    display: inline-block;
    background: rgba(99,102,241,0.15);
    color: #818cf8;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 20px;
    margin-bottom: 1.25rem;
    border: 1px solid rgba(99,102,241,0.2);
}
.left-title {
    font-size: 1.65rem;
    font-weight: 700;
    color: #f9fafb;
    line-height: 1.4;
    margin-bottom: 1rem;
}
.left-desc {
    font-size: 13.5px;
    color: rgba(255,255,255,0.4);
    line-height: 1.75;
    margin-bottom: 2.25rem;
}

.stat-row { display: flex; flex-direction: column; gap: 10px; }
.stat-card {
    display: flex;
    align-items: center;
    gap: 13px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 10px;
    padding: 12px 14px;
}
.stat-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-icon.blue { background: rgba(59,130,246,0.15); color: #60a5fa; }
.stat-icon.green { background: rgba(16,185,129,0.15); color: #34d399; }
.stat-icon.amber { background: rgba(245,158,11,0.15); color: #fbbf24; }
.stat-val { font-size: 13px; font-weight: 600; color: #f1f5f9; }
.stat-lbl { font-size: 11px; color: rgba(255,255,255,0.32); margin-top: 1px; }

.left-footer {
    font-size: 11.5px;
    color: rgba(255,255,255,0.18);
    margin-top: 2rem;
    position: relative;
    z-index: 1;
}

/* ── Right Panel ── */
.right-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem;
}
.form-wrap { width: 100%; max-width: 420px; }

.form-header { margin-bottom: 2rem; }
.form-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.3px;
    margin-bottom: 6px;
}
.form-sub { font-size: 14px; color: #6b7280; }

.alert-ok {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 13.5px;
    color: #15803d;
    margin-bottom: 1.5rem;
}

/* Fields */
.fg { margin-bottom: 1.25rem; }
.flabel {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 7px;
}
.label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 7px;
}
.label-row .flabel { margin-bottom: 0; }
.forgot {
    font-size: 12.5px;
    color: #6366f1;
    text-decoration: none;
    font-weight: 500;
    transition: color .2s;
}
.forgot:hover { color: #4f46e5; text-decoration: underline; }

.finput-wrap {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    transition: border-color .2s, box-shadow .2s;
}
.finput-wrap:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}
.finput-wrap.err {
    border-color: #f87171;
    box-shadow: 0 0 0 3px rgba(248,113,113,0.1);
}
.ficon {
    padding: 0 12px;
    color: #9ca3af;
    display: flex; align-items: center;
    flex-shrink: 0;
}
.finput {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    padding: 12px 12px 12px 0;
    font-size: 14px;
    color: #111827;
    font-family: inherit;
}
.finput::placeholder { color: #d1d5db; }
.eye-btn {
    padding: 0 13px;
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    display: flex; align-items: center;
    transition: color .2s;
}
.eye-btn:hover { color: #6366f1; }
.errmsg { display: block; font-size: 12px; color: #ef4444; margin-top: 5px; }

/* Remember */
.remember-row { margin-bottom: 1.5rem; }
.chk-label { display: flex; align-items: center; gap: 9px; cursor: pointer; user-select: none; }
.chk-box {
    width: 17px; height: 17px;
    border: 1.5px solid #d1d5db;
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: all .2s;
    background: #fff;
}
.chk-box.on { background: #6366f1; border-color: #6366f1; }
.chk-txt { font-size: 13.5px; color: #4b5563; }

/* Submit */
.submit-btn {
    width: 100%;
    padding: 13px;
    background: #6366f1;
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: background .2s, transform .1s, box-shadow .2s;
    margin-bottom: 1.25rem;
}
.submit-btn:not(:disabled):hover {
    background: #4f46e5;
    box-shadow: 0 4px 16px rgba(99,102,241,0.35);
}
.submit-btn:not(:disabled):active { transform: scale(0.99); }
.submit-btn:disabled { opacity: .6; cursor: not-allowed; }
.btn-inner { display: flex; align-items: center; justify-content: center; gap: 8px; }
.spin-wrap { display: flex; align-items: center; justify-content: center; gap: 8px; }
.spinner {
    width: 15px; height: 15px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg) } }



/* ── Responsive ── */
@media (max-width: 768px) {
    .left-panel { display: none; }
    .right-panel { background: #fff; }
}
</style>