<template>
    <!-- Overlay mobile -->
    <div v-if="isMobile && !props.collapsed"
        @click="emit('update:collapsed', true)"
        style="position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:109;" />

    <div id="kt_app_sidebar" class="app-sidebar flex-column"
        :class="props.collapsed ? 'sidebar-collapsed' : ''">

        <!-- Logo -->
        <div class="app-sidebar-logo px-6 d-flex align-items-center"
            id="kt_app_sidebar_logo">
            <a href="/">
                <!-- Logo full (expanded) -->
                <img v-show="!props.collapsed"
                    alt="Logo"
                    src="/metronic/media/logos/keenthemes-dark.svg"
                    class="h-25px" />
                <!-- Logo mini (collapsed) -->
                <img v-show="props.collapsed"
                    alt="Logo"
                    src="/metronic/media/logos/default-small-dark.svg"
                    class="h-20px" />
            </a>
        </div>

        <!-- Menu -->
        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
            <div id="kt_app_sidebar_menu_wrapper"
                class="app-sidebar-wrapper hover-scroll-overlay-y my-2"
                data-kt-scroll="true"
                data-kt-scroll-activate="true"
                data-kt-scroll-height="auto"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu"
                data-kt-scroll-offset="5px">

                <div id="kt_app_sidebar_menu"
                    class="menu menu-column menu-rounded menu-sub-indention px-3"
                    data-kt-menu="true">

                    <!-- Dashboard -->
                    <div class="menu-item" :title="props.collapsed ? 'Dashboard' : ''">
                        <Link :href="route('dashboard')" class="menu-link"
                            :class="{ active: $page.component === 'Dashboard' }">
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
                        <Link :href="route('pos.index')" class="menu-link"
                            :class="{ active: $page.component === 'POS/Index' }">
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
                        <Link :href="route('pos.history')" class="menu-link"
                            :class="{ active: $page.component === 'POS/History' }">
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
                        <div class="menu-item pt-5" v-show="!props.collapsed">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-8 ls-1">Master Data</span>
                            </div>
                        </div>

                        <!-- Member -->
                        <div class="menu-item" :title="props.collapsed ? 'Member' : ''">
                            <Link :href="route('members.index')" class="menu-link"
                                :class="{ active: $page.component.startsWith('Member') }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-people fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Member</span>
                            </Link>
                        </div>

                        <!-- Kategori -->
                        <div class="menu-item" :title="props.collapsed ? 'Kategori' : ''">
                            <Link :href="route('categories.index')" class="menu-link"
                                :class="{ active: $page.component.startsWith('Category') }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-category fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Kategori</span>
                            </Link>
                        </div>

                        <!-- Produk -->
                        <div class="menu-item" :title="props.collapsed ? 'Produk' : ''">
                            <Link :href="route('products.index')" class="menu-link"
                                :class="{ active: $page.component.startsWith('Product') }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-package fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Produk</span>
                            </Link>
                        </div>

                        <!-- Section: Laporan -->
                        <div class="menu-item pt-5" v-show="!props.collapsed">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-8 ls-1">Laporan</span>
                            </div>
                        </div>

                        <!-- Laporan -->
                        <div class="menu-item" :title="props.collapsed ? 'Laporan' : ''">
                            <Link :href="route('laporan.index')" class="menu-link"
                                :class="{ active: $page.component === 'Laporan/Index' }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-chart-line-up fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Laporan</span>
                            </Link>
                        </div>

                        <!-- Laporan Profit -->
                        <div class="menu-item" :title="props.collapsed ? 'Laporan Profit' : ''">
                            <Link :href="route('laporan.profit')" class="menu-link"
                                :class="{ active: $page.component === 'Laporan/Profit' }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-graph-up fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Laporan Profit</span>
                            </Link>
                        </div>

                        <!-- Section: Setting -->
                        <div class="menu-item pt-5" v-show="!props.collapsed">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-8 ls-1">Setting & Permission</span>
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="menu-item" :title="props.collapsed ? 'Users' : ''">
                            <Link :href="route('users.index')" class="menu-link"
                                :class="{ active: $page.component.startsWith('Users') }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-profile-circle fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Users</span>
                            </Link>
                        </div>

                        <!-- Manajemen Stok -->
                        <div class="menu-item" :title="props.collapsed ? 'Manajemen Stok' : ''">
                            <Link :href="route('stock.index')" class="menu-link"
                                :class="{ active: $page.component.startsWith('Stock') }">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-abstract-26 fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title" v-show="!props.collapsed">Manajemen Stok</span>
                            </Link>
                        </div>

                    </template>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({ collapsed: Boolean })
const emit = defineEmits(['update:collapsed'])

const page = usePage()
const isAdmin = computed(() => page.props.auth?.role === 'admin')
const isMobile = ref(window.innerWidth < 992)

const onResize = () => { isMobile.value = window.innerWidth < 992 }
onMounted(() => window.addEventListener('resize', onResize))
onUnmounted(() => window.removeEventListener('resize', onResize))
</script>

<style scoped>
#kt_app_sidebar {
    width: 265px;
    background-color: #1e1e2d;
    transition: width 0.3s ease;
    overflow: hidden;
}
#kt_app_sidebar.sidebar-collapsed {
    width: 70px;
}

.app-sidebar-logo {
    height: 70px;
    flex-shrink: 0;
    border-bottom: 1px solid #2d2d43;
}

.menu-heading {
    color: #4b5675;
}

.menu-link {
    color: #9899ac !important;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 2px;
}
.menu-link:hover,
.menu-link.active {
    background-color: #2d2d43 !important;
    color: #ffffff !important;
}
.menu-link.active .ki-duotone,
.menu-link:hover .ki-duotone {
    color: #009ef7 !important;
}
.menu-link .menu-icon .ki-duotone {
    color: #4b5675;
}
</style>