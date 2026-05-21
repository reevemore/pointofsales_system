<template>
    <div id="kt_app_header" class="app-header">
        <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
            id="kt_app_header_container">

            <!-- Kiri: Toggle Sidebar -->
            <div class="d-flex align-items-center">
                <button
                    class="btn btn-icon btn-active-color-primary w-35px h-35px"
                    @click="$emit('toggle-sidebar')"
                    title="Toggle Sidebar">
                    <i class="ki-duotone ki-abstract-14 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
            </div>

            <!-- Kanan: Navbar -->
            <div class="d-flex align-items-stretch justify-content-end flex-lg-grow-1">
                <div class="app-navbar flex-shrink-0 d-flex align-items-center gap-2 gap-lg-3">

                    <!-- Search -->
                    <div class="app-navbar-item position-relative" ref="searchContainer">
                        <button
                            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                            @click="toggleSearch"
                            title="Cari Menu">
                            <i class="ki-duotone ki-magnifier fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>

                        <!-- Search Dropdown -->
                        <div v-if="searchOpen"
                            class="search-dropdown position-absolute end-0 bg-white rounded shadow-lg p-4"
                            style="top: 50px; width: 320px; z-index: 300;">

                            <!-- Input -->
                            <div class="position-relative mb-3">
                                <i class="ki-duotone ki-magnifier fs-4 position-absolute text-muted"
                                    style="top:50%;left:12px;transform:translateY(-50%);">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <input
                                    ref="searchInput"
                                    v-model="searchQuery"
                                    type="text"
                                    class="form-control form-control-sm ps-10"
                                    placeholder="Cari menu..."
                                    @keydown.escape="closeSearch"
                                    @keydown.enter="goToFirst"
                                />
                            </div>

                            <!-- Results -->
                            <div v-if="filteredMenus.length > 0">
                                <div class="text-muted fs-8 fw-semibold text-uppercase mb-2 px-1">Hasil Pencarian</div>
                                <Link
                                    v-for="item in filteredMenus"
                                    :key="item.route"
                                    :href="route(item.route)"
                                    class="d-flex align-items-center gap-3 px-3 py-2 rounded mb-1 text-decoration-none search-result-item"
                                    @click="closeSearch">
                                    <span class="d-flex align-items-center justify-content-center w-35px h-35px rounded"
                                        style="background:#f1f5ff; flex-shrink:0;">
                                        <i :class="['ki-duotone fs-4 text-primary', item.icon]">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold text-gray-800 fs-7">{{ item.label }}</div>
                                        <div class="text-muted fs-8">{{ item.section }}</div>
                                    </div>
                                </Link>
                            </div>

                            <!-- Empty -->
                            <div v-else-if="searchQuery.length > 0" class="text-center py-4">
                                <i class="ki-duotone ki-search-list fs-2x text-muted mb-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                                <div class="text-muted fs-7">Tidak ada menu ditemukan</div>
                            </div>

                            <!-- Default hint -->
                            <div v-else class="text-center py-2">
                                <div class="text-muted fs-8">Ketik nama menu yang ingin Anda cari</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="app-navbar-item">
                        <button class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative">
                            <i class="ki-duotone ki-notification-bing fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </button>
                    </div>

                    <!-- Separator -->
                    <div class="app-navbar-item d-none d-md-flex">
                        <div class="vr opacity-25 h-25px"></div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="app-navbar-item">
                        <div class="dropdown">
                            <div class="cursor-pointer d-flex align-items-center gap-2 px-1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="symbol symbol-35px">
                                    <div class="symbol-label fs-6 fw-bold text-white"
                                        :style="{ background: avatarColor }">
                                        {{ initials }}
                                    </div>
                                </div>
                                <div class="d-none d-md-flex flex-column text-start">
                                    <span class="fw-bold text-gray-800 fs-7 lh-1 mb-1">{{ user.name }}</span>
                                    <span class="fw-semibold text-muted fs-8 lh-1">
                                        {{ role === 'admin' ? 'Administrator' : 'Kasir' }}
                                    </span>
                                </div>
                            </div>

                            <div class="dropdown-menu dropdown-menu-end p-0"
                                style="min-width: 260px; border-radius: 12px; box-shadow: 0 8px 40px rgba(0,0,0,0.12);">

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

                                    <button
                                        class="dropdown-item d-flex align-items-center gap-3 rounded px-3 py-2 text-danger w-100"
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
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({ collapsed: Boolean })
defineEmits(['toggle-sidebar'])

const page = usePage()
const user = computed(() => page.props.auth.user)
const role = computed(() => page.props.auth.role)

// ─── Avatar ───────────────────────────────────────────────
const initials = computed(() =>
    user.value.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
)
const avatarColor = computed(() => {
    const colors = ['#009ef7', '#50cd89', '#f1416c', '#ffc700', '#7239ea', '#fd7e14']
    return colors[user.value.name.charCodeAt(0) % colors.length]
})

// ─── Search ───────────────────────────────────────────────
const searchOpen = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const searchContainer = ref(null)

// Semua menu yang bisa dicari — sesuaikan dengan role
const allMenus = computed(() => {
    const base = [
        { label: 'Dashboard',          route: 'dashboard',       icon: 'ki-home-2',        section: 'Utama' },
        { label: 'Transaksi',          route: 'pos.index',       icon: 'ki-basket',         section: 'Utama' },
        { label: 'Riwayat Transaksi',  route: 'pos.history',     icon: 'ki-document',       section: 'Utama' },
    ]
    const admin = [
        { label: 'Member',             route: 'members.index',   icon: 'ki-people',         section: 'Master Data' },
        { label: 'Kategori',           route: 'categories.index',icon: 'ki-category',       section: 'Master Data' },
        { label: 'Produk',             route: 'products.index',  icon: 'ki-package',        section: 'Master Data' },
        { label: 'Laporan',            route: 'laporan.index',   icon: 'ki-chart-line-up',  section: 'Laporan' },
        { label: 'Laporan Profit',     route: 'laporan.profit',  icon: 'ki-graph-up',       section: 'Laporan' },
        { label: 'Users',              route: 'users.index',     icon: 'ki-profile-circle', section: 'Setting & Permission' },
        { label: 'Manajemen Stok',     route: 'stock.index',     icon: 'ki-abstract-26',    section: 'Setting & Permission' },
        { label: 'My Profile',         route: 'profile.edit',    icon: 'ki-profile-circle', section: 'Akun' },
    ]
    return role.value === 'admin' ? [...base, ...admin] : [...base, { label: 'My Profile', route: 'profile.edit', icon: 'ki-profile-circle', section: 'Akun' }]
})

const filteredMenus = computed(() => {
    if (!searchQuery.value.trim()) return []
    const q = searchQuery.value.toLowerCase()
    return allMenus.value.filter(m =>
        m.label.toLowerCase().includes(q) || m.section.toLowerCase().includes(q)
    )
})

const toggleSearch = async () => {
    searchOpen.value = !searchOpen.value
    if (searchOpen.value) {
        searchQuery.value = ''
        await nextTick()
        searchInput.value?.focus()
    }
}

const closeSearch = () => {
    searchOpen.value = false
    searchQuery.value = ''
}

const goToFirst = () => {
    if (filteredMenus.value.length > 0) {
        router.visit(route(filteredMenus.value[0].route))
        closeSearch()
    }
}

// Tutup search saat klik di luar
const handleClickOutside = (e) => {
    if (searchContainer.value && !searchContainer.value.contains(e.target)) {
        closeSearch()
    }
}
onMounted(() => document.addEventListener('mousedown', handleClickOutside))
onUnmounted(() => document.removeEventListener('mousedown', handleClickOutside))

// ─── Logout ───────────────────────────────────────────────
const logout = () => router.post(route('logout'))
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
    }).then(r => { if (r.isConfirmed) logout() })
}
</script>

<style scoped>
.search-result-item {
    color: inherit;
    transition: background 0.15s;
}
.search-result-item:hover {
    background: #f1f5ff;
}
</style>