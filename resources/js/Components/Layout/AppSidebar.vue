<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    collapsed: Boolean,
})
const emit = defineEmits(['update:collapsed'])

const page    = usePage()
const role    = computed(() => page.props.auth?.role)
const toko    = computed(() => page.props.auth?.toko)

const isSuperAdmin = computed(() => role.value === 'superadmin')
const isAdmin      = computed(() => role.value === 'admin')
const isKasir      = computed(() => role.value === 'kasir')
const isAdminOrKasir = computed(() => isAdmin.value || isKasir.value)

const isMobile = computed(() => window.innerWidth < 992)

const isActive = (components) => {
    if (Array.isArray(components)) {
        return components.includes(page.component)
    }
    return page.component === components
}
</script>

<template>
    <!-- Overlay mobile -->
    <div
        v-if="isMobile && !props.collapsed"
        @click="emit('update:collapsed', true)"
        style="position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:109;"
    />

    <div
        id="kt_app_sidebar"
        class="app-sidebar flex-column"
        :class="props.collapsed ? 'sidebar-collapsed' : ''"
    >
        <!-- Logo -->
        <div class="app-sidebar-logo px-6 d-flex align-items-center" id="kt_app_sidebar_logo">
            <a :href="isSuperAdmin ? route('stores.index') : route('dashboard')">
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

        <!-- Info Toko (hanya untuk admin & kasir) -->
        <div
            v-if="isAdminOrKasir && toko && !props.collapsed"
            class="px-6 py-3 mx-3 mb-2 rounded"
            style="background: rgba(255,255,255,0.07);"
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

        <!-- Info Superadmin Badge -->
        <div
            v-if="isSuperAdmin && !props.collapsed"
            class="px-6 py-3 mx-3 mb-2 rounded"
            style="background: rgba(255,255,255,0.07);"
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

                    <!-- ===================== -->
                    <!-- SUPERADMIN MENU       -->
                    <!-- ===================== -->
                    <template v-if="isSuperAdmin">
                        <!-- Section Label -->
                        <div class="menu-item" v-show="!props.collapsed">
                            <div class="menu-content pt-8 pb-2">
                                <span class="menu-section text-muted text-uppercase fs-11 ls-1">
                                    Manajemen
                                </span>
                            </div>
                        </div>

                        <!-- Toko -->
                        <div class="menu-item" :title="props.collapsed ? 'Manajemen Toko' : ''">
                            <Link
                                :href="route('stores.index')"
                                class="menu-link"
                                :class="{ active: isActive(['Stores/Index', 'Stores/Create', 'Stores/Edit']) }"
                            >
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-shop fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Manajemen Toko</span>
                            </Link>
                        </div>
                    </template>

                    <!-- ===================== -->
                    <!-- ADMIN & KASIR MENU    -->
                    <!-- ===================== -->
                    <template v-if="isAdminOrKasir">

                        <!-- Section: Utama -->
                        <div class="menu-item" v-show="!props.collapsed">
                            <div class="menu-content pt-8 pb-2">
                                <span class="menu-section text-muted text-uppercase fs-11 ls-1">
                                    Utama
                                </span>
                            </div>
                        </div>

                        <!-- Dashboard (hanya admin) -->
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

                        <!-- Transaksi / POS -->
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

                        <!-- ===================== -->
                        <!-- ADMIN ONLY MENU       -->
                        <!-- ===================== -->
                        <template v-if="isAdmin">

                            <!-- Section: Master Data -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">
                                        Master Data
                                    </span>
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
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span>
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
                                            <span class="path3"></span><span class="path4"></span>
                                            <span class="path5"></span>
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
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Stok</span>
                                </Link>
                            </div>

                            <!-- Section: Laporan -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">
                                        Laporan
                                    </span>
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
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title" v-show="!props.collapsed">Laporan Profit</span>
                                </Link>
                            </div>

                            <!-- Section: Pengaturan -->
                            <div class="menu-item" v-show="!props.collapsed">
                                <div class="menu-content pt-8 pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-11 ls-1">
                                        Pengaturan
                                    </span>
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
        <div class="app-sidebar-footer px-6 py-4 border-top border-gray-700" v-show="!props.collapsed">
            <div class="text-gray-500 fs-8 text-center">
                POS Multi-Toko &copy; {{ new Date().getFullYear() }}
            </div>
        </div>

    </div>
</template>