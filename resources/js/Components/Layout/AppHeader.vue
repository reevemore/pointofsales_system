<template>
    <div id="kt_app_header" class="app-header"
        style="background:#fff; border-bottom: 1px solid #f1f1f4; min-height: 65px;">
        <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
            id="kt_app_header_container">

            <!-- Kiri: Page Title -->
            <div class="d-flex align-items-center">
                <div class="d-flex flex-column">
                    <h3 class="text-gray-900 fw-bold my-0 fs-6 lh-1 mb-1">
                        {{ pageTitle }}
                    </h3>
                    <span class="text-muted fw-semibold fs-8">
                        {{ pageSubtitle }}
                    </span>
                </div>
            </div>

            <!-- Kanan: Navbar -->
            <div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1">
                <div class="app-navbar flex-shrink-0 d-flex align-items-center gap-3">

                    <!-- User Dropdown -->
                    <div class="app-navbar-item">
                        <div class="dropdown">

                            <!-- Trigger -->
                            <button class="btn btn-flex align-items-center gap-3 px-3 py-2"
                                style="background:transparent; border:none;"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                <!-- Avatar -->
                                <div class="symbol symbol-35px">
                                    <div class="symbol-label fs-6 fw-bold text-white"
                                        :style="{ background: avatarColor }">
                                        {{ initials }}
                                    </div>
                                </div>

                                <!-- Nama & Role -->
                                <div class="d-none d-md-flex flex-column text-start">
                                    <span class="fw-bold text-gray-800 fs-7 lh-1 mb-1">
                                        {{ user.name }}
                                    </span>
                                    <span class="fw-semibold text-muted fs-8 lh-1">
                                        {{ role === 'admin' ? 'Administrator' : 'Kasir' }}
                                    </span>
                                </div>

                                <i class="ki-duotone ki-down fs-7 text-muted ms-1">
                                    <span class="path1"></span>
                                </i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu dropdown-menu-end p-0"
                                style="min-width: 260px; border-radius: 12px; box-shadow: 0 8px 40px rgba(0,0,0,0.12);">

                                <!-- Header Profil -->
                                <div class="d-flex align-items-center px-5 py-4"
                                    style="background: linear-gradient(135deg, #1e1e2d 0%, #2d2d43 100%); border-radius: 12px 12px 0 0;">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label fs-4 fw-bold text-white"
                                            :style="{ background: avatarColor }">
                                            {{ initials }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white fs-6">{{ user.name }}</div>
                                        <div class="text-white opacity-75 fs-8">{{ user.email }}</div>
                                        <span class="badge mt-1"
                                            :style="{ background: role === 'admin' ? '#009ef7' : '#50cd89', color: '#fff' }">
                                            {{ role === 'admin' ? 'Administrator' : 'Kasir' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2 px-2">
                                    <Link :href="route('profile.edit')"
                                        class="dropdown-item d-flex align-items-center gap-3 rounded px-3 py-2 mb-1">
                                        <span class="d-flex align-items-center justify-content-center w-30px h-30px rounded"
                                            style="background:#f1f5ff;">
                                            <i class="ki-duotone ki-profile-circle fs-5 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold text-gray-800 fs-7">My Profile</div>
                                            <div class="text-muted fs-8">Pengaturan akun</div>
                                        </div>
                                    </Link>

                                    <div class="separator my-2"></div>

                                    <!--  Tombol Logout dengan SweetAlert2 -->
                                    <button
                                        class="dropdown-item d-flex align-items-center gap-3 rounded px-3 py-2 text-danger"
                                        @click="confirmLogout">
                                        <span class="d-flex align-items-center justify-content-center w-30px h-30px rounded"
                                            style="background:#fff5f5;">
                                            <i class="ki-duotone ki-exit-right fs-5 text-danger">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        <div>
                                            <div class="fw-semibold fs-7">Sign Out</div>
                                            <div class="fs-8 opacity-75">Keluar dari aplikasi</div>
                                        </div>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'  //  Import SweetAlert2

const page = usePage()
const user = computed(() => page.props.auth.user)
const role = computed(() => page.props.auth.role)

const pageTitle = computed(() => page.props.pageTitle ?? 'Dashboard')
const pageSubtitle = computed(() => page.props.pageSubtitle ?? '')

const initials = computed(() => {
    return user.value.name
        .split(' ')
        .map(w => w[0])
        .slice(0, 2)
        .join('')
        .toUpperCase()
})

const avatarColor = computed(() => {
    const colors = ['#009ef7', '#50cd89', '#f1416c', '#ffc700', '#7239ea', '#fd7e14']
    const index = user.value.name.charCodeAt(0) % colors.length
    return colors[index]
})

//  Fungsi logout lama (tetap ada, dipanggil setelah konfirmasi)
const logout = () => router.post(route('logout'))

//  Fungsi baru: tampilkan SweetAlert2 sebelum logout
const confirmLogout = () => {
    Swal.fire({
        title: 'Keluar dari Aplikasi?',
        text: 'Apakah kamu yakin ingin keluar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f1416c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            logout()
        }
    })
}
</script>