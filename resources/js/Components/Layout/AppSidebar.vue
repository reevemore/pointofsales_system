<script setup>
import { computed, watch, nextTick } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const props = defineProps({
    collapsed: Boolean,
})
const emit = defineEmits(['update:collapsed'])

const page = usePage()
const role = computed(() => page.props.auth?.role)
const toko = computed(() => page.props.auth?.toko)

const isSuperAdmin   = computed(() => role.value === 'superadmin')
const isAdmin        = computed(() => role.value === 'admin')
const isKasir        = computed(() => role.value === 'kasir')
const isAdminOrKasir = computed(() => isAdmin.value || isKasir.value)

const isMobile = computed(() => typeof window !== 'undefined' && window.innerWidth < 992)

const isActive = (components) => {
    if (Array.isArray(components)) return components.includes(page.component)
    return page.component === components
}

// Auto-scroll sidebar ke active menu item setiap navigasi
const scrollToActive = () => {
    nextTick(() => {
        const wrapper = document.getElementById('kt_app_sidebar_menu_wrapper')
        const activeLink = document.querySelector('#kt_app_sidebar_menu .menu-link.active')
        if (wrapper && activeLink) {
            const wrapperTop = wrapper.getBoundingClientRect().top
            const activeTop  = activeLink.getBoundingClientRect().top
            const offset     = activeTop - wrapperTop + wrapper.scrollTop - (wrapper.clientHeight / 2) + (activeLink.clientHeight / 2)
            wrapper.scrollTo({ top: offset, behavior: 'instant' })
        }
    })
}

// Jalankan saat pertama kali load dan setiap navigasi selesai
router.on('finish', () => scrollToActive())
scrollToActive()
</script>

<template>
    <!-- Overlay mobile -->
    <div
        v-if="isMobile && !props.collapsed"
        @click="emit('update:collapsed', true)"
        class="sidebar-overlay"
    />

    <div
        id="kt_app_sidebar"
        class="app-sidebar flex-column"
        :class="props.collapsed ? 'sidebar-collapsed' : ''"
    >
        <!-- Logo -->
        <div class="app-sidebar-logo d-flex align-items-center" id="kt_app_sidebar_logo">
            <a :href="isSuperAdmin ? route('stores.index') : route('dashboard')" class="d-flex align-items-center">
                <img
                    v-show="!props.collapsed"
                    alt="Logo"
                    src="/metronic/media/logos/keenthemes-dark.svg"
                    class="h-25px"
                />
                <img
                    v-show="props.collapsed"
                    alt="Logo"
                    src="/metronic/media/logos/default-small-dark.svg"
                    class="h-20px"
                />
            </a>
        </div>

        <!-- Info Toko (admin & kasir) -->
        <div
            v-if="isAdminOrKasir && toko && !props.collapsed"
            class="sidebar-store-info mx-3 mb-2 rounded"
        >
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-35px symbol-light-primary flex-shrink-0">
                    <span class="symbol-label fw-bold fs-6 text-primary">
                        {{ toko.nama.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-semibold fs-7 text-truncate">{{ toko.nama }}</div>
                    <div class="text-gray-400 fs-8 text-capitalize">{{ role }}</div>
                </div>
            </div>
        </div>

        <!-- Info Superadmin -->
        <div
            v-if="isSuperAdmin && !props.collapsed"
            class="sidebar-store-info mx-3 mb-2 rounded"
        >
            <div class="d-flex align-items-center gap-3">
                <div class="symbol symbol-35px symbol-light-warning flex-shrink-0">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-crown fs-4 text-warning">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </span>
                </div>
                <div>
                    <div class="text-white fw-semibold fs-7">Super Admin</div>
                    <div class="text-gray-400 fs-8">Manajemen Sistem</div>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
            <div
                id="kt_app_sidebar_menu_wrapper"
                class="app-sidebar-wrapper hover-scroll-overlay-y my-2"
                data-kt-scroll="true"
                data-kt-scroll-activate="true"
                data-kt-scroll-height="auto"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu"
                data-kt-scroll-offset="5px"
            >
                <div
                    id="kt_app_sidebar_menu"
                    class="menu menu-column menu-rounded menu-sub-indention px-3"
                    data-kt-menu="true"
                >
                    <!-- ==================== -->
                    <!-- SUPERADMIN MENU      -->
                    <!-- ==================== -->
                    <template v-if="isSuperAdmin">
                        <div class="menu-item" v-show="!props.collapsed">
                            <div class="menu-content pt-8 pb-2">
                                <span class="menu-section text-muted text-uppercase fs-11 ls-1">Manajemen</span>
                            </div>
                        </div>

                        <div class="menu-item" :title="props.collapsed ? 'Manajemen Toko' : ''">
                            <Link
                                :href="route('stores.index')"
                                class="menu-link"
                                :class="{ active: isActive(['Stores/Index', 'Stores/Create', 'Stores/Edit']) }"
                            >
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-shop fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Manajemen Toko</span>
                            </Link>
                        </div>
                    </template>

                    <!-- ==================== -->
                    <!-- ADMIN & KASIR MENU   -->
                    <!-- ==================== -->
                    <template v-if="isAdminOrKasir">

                        <!-- Section: Utama -->
                        <div class="menu-item" v-show="!props.collapsed">
                            <div class="menu-content pt-8 pb-2">
                                <span class="menu-section text-muted text-uppercase fs-11 ls-1">Utama</span>
                            </div>
                        </div>

                        <!-- Dashboard (admin only) -->
                        <div v-if="isAdmin" class="menu-item" :title="props.collapsed ? 'Dashboard' : ''">
                            <Link
                                :href="route('dashboard')"
                                class="menu-link"
                                :class="{ active: isActive('Dashboard') }"
                            >
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-home-2 fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Dashboard</span>
                            </Link>
                        </div>

                        <!-- Transaksi -->
                        <div class="menu-item" :title="props.collapsed ? 'Transaksi' : ''">
                            <Link
                                :href="route('pos.index')"
                                class="menu-link"
                                :class="{ active: isActive('POS/Index') }"
                            >
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-basket fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Transaksi</span>
                            </Link>
                        </div>

                        <!-- Riwayat Transaksi -->
                        <div class="menu-item" :title="props.collapsed ? 'Riwayat Transaksi' : ''">
                            <Link
                                :href="route('pos.history')"
                                class="menu-link"
                                :class="{ active: isActive('POS/History') }"
                            >
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-document fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Riwayat Transaksi</span>
                            </Link>
                        </div>

                        <!-- ADMIN ONLY -->
                        <template v-if="isAdmin">

                            <!-- Section: Master Data -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">Master Data</span>
                                </div>
                            </div>

                            <!-- Produk -->
                            <div class="menu-item" :title="props.collapsed ? 'Produk' : ''">
                                <Link
                                    :href="route('products.index')"
                                    class="menu-link"
                                    :class="{ active: isActive(['Product/Index','Product/Create','Product/Edit']) }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-package fs-2">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Produk</span>
                                </Link>
                            </div>

                            <!-- Kategori -->
                            <div class="menu-item" :title="props.collapsed ? 'Kategori' : ''">
                                <Link
                                    :href="route('categories.index')"
                                    class="menu-link"
                                    :class="{ active: isActive(['Category/Index','Category/Create','Category/Edit']) }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-element-11 fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Kategori</span>
                                </Link>
                            </div>

                            <!-- Member -->
                            <div class="menu-item" :title="props.collapsed ? 'Member' : ''">
                                <Link
                                    :href="route('members.index')"
                                    class="menu-link"
                                    :class="{ active: isActive(['Member/Index','Member/Create','Member/Edit']) }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-people fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Member</span>
                                </Link>
                            </div>

                            <!-- Stok -->
                            <div class="menu-item" :title="props.collapsed ? 'Stok' : ''">
                                <Link
                                    :href="route('stock.index')"
                                    class="menu-link"
                                    :class="{ active: isActive(['Stock/Index','Stock/History']) }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-cube-2 fs-2">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Stok</span>
                                </Link>
                            </div>

                            <!-- Section: Laporan -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">Laporan</span>
                                </div>
                            </div>

                            <!-- Laporan Penjualan -->
                            <div class="menu-item" :title="props.collapsed ? 'Laporan Penjualan' : ''">
                                <Link
                                    :href="route('laporan.index')"
                                    class="menu-link"
                                    :class="{ active: isActive('Laporan/Index') }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-chart-line fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Laporan Penjualan</span>
                                </Link>
                            </div>

                            <!-- Laporan Profit -->
                            <div class="menu-item" :title="props.collapsed ? 'Laporan Profit' : ''">
                                <Link
                                    :href="route('laporan.profit')"
                                    class="menu-link"
                                    :class="{ active: isActive('Laporan/Profit') }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-chart-pie-4 fs-2">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Laporan Profit</span>
                                </Link>
                            </div>

                            <!-- Section: Pengaturan -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">Pengaturan</span>
                                </div>
                            </div>

                            <!-- Users -->
                            <div class="menu-item" :title="props.collapsed ? 'Users' : ''">
                                <Link
                                    :href="route('users.index')"
                                    class="menu-link"
                                    :class="{ active: isActive(['Users/Index','Users/Create','Users/Edit']) }"
                                >
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-profile-user fs-2">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Users</span>
                                </Link>
                            </div>

                        </template>
                        <!-- END ADMIN ONLY -->

                    </template>
                    <!-- END ADMIN & KASIR -->

                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="app-sidebar-footer" v-show="!props.collapsed">
            <div class="text-gray-500 fs-8 text-center">
                POS Multi-Toko &copy; {{ new Date().getFullYear() }}
            </div>
        </div>

    </div>
</template>

<style>
/* =====================================================
   APP SIDEBAR — Complete Styles (global, override Metronic)
   ===================================================== */
#kt_app_sidebar.app-sidebar {
    display: flex;
    flex-direction: column;
    width: 265px;
    min-width: 265px;
    background-color: #1e1e2d;
    transition: width 0.3s ease, min-width 0.3s ease;
    overflow: hidden;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 200;
}

#kt_app_sidebar.app-sidebar.sidebar-collapsed {
    width: 70px;
    min-width: 70px;
}

/* Logo area */
#kt_app_sidebar .app-sidebar-logo {
    padding: 0 1.5rem;
    height: 70px;
    min-height: 70px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
}

/* Store info badge */
#kt_app_sidebar .sidebar-store-info {
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.07);
    margin-top: 0.5rem;
}

/* Menu wrapper */
#kt_app_sidebar .app-sidebar-menu {
    flex: 1;
    overflow: hidden;
}

#kt_app_sidebar .app-sidebar-wrapper {
    height: 100%;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.1) transparent;
}

#kt_app_sidebar .app-sidebar-wrapper::-webkit-scrollbar {
    width: 4px;
}
#kt_app_sidebar .app-sidebar-wrapper::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 4px;
}

/* =====================================================
   MENU LINK — Override Metronic dengan !important
   ===================================================== */
#kt_app_sidebar .menu-link {
    display: flex !important;
    align-items: center !important;
    padding: 0.65rem 0.75rem !important;
    border-radius: 0.475rem !important;
    color: #9899ac !important;
    text-decoration: none !important;
    transition: background-color 0.2s, color 0.2s !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    background-color: transparent !important;
}

#kt_app_sidebar .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.07) !important;
    color: #ffffff !important;
}

#kt_app_sidebar .menu-link.active {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
}

/* Icon warna ikut parent */
#kt_app_sidebar .menu-link .menu-icon,
#kt_app_sidebar .menu-link .menu-icon i,
#kt_app_sidebar .menu-link .menu-icon svg {
    color: inherit !important;
}

#kt_app_sidebar .menu-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 2rem !important;
    min-width: 2rem !important;
    margin-right: 0.75rem !important;
    color: inherit !important;
}

#kt_app_sidebar.sidebar-collapsed .menu-icon {
    margin-right: 0 !important;
}

#kt_app_sidebar .menu-title {
    font-size: 0.925rem !important;
    font-weight: 500 !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    color: inherit !important;
}

/* Section labels */
#kt_app_sidebar .menu-section {
    font-size: 0.7rem !important;
    letter-spacing: 0.08rem !important;
    font-weight: 600 !important;
    color: #4b4b6b !important;
    display: block !important;
    padding: 0 0.75rem !important;
}

/* Sidebar Footer */
#kt_app_sidebar .app-sidebar-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    flex-shrink: 0;
}

/* Mobile overlay */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 109;
}

/* Collapsed: hide section labels */
#kt_app_sidebar.sidebar-collapsed .menu-section {
    display: none !important;
}

/* Symbol styles (Metronic-like) */
#kt_app_sidebar .symbol {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#kt_app_sidebar .symbol-35px { width: 35px; height: 35px; }
#kt_app_sidebar .symbol-label {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.475rem;
    background-color: rgba(255, 255, 255, 0.1);
}
#kt_app_sidebar .symbol-light-primary .symbol-label { background-color: rgba(13, 110, 253, 0.15); }
#kt_app_sidebar .symbol-light-warning .symbol-label  { background-color: rgba(255, 193, 7, 0.15); }

/* Responsive */
@media (max-width: 991px) {
    #kt_app_sidebar.app-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease, width 0.3s ease;
    }
    #kt_app_sidebar.app-sidebar:not(.sidebar-collapsed) {
        transform: translateX(0);
        width: 265px;
    }
}
</style>