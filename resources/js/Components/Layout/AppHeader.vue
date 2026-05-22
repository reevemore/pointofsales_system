<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    collapsed: Boolean,
})
const emit = defineEmits(['toggle-sidebar'])

const page  = usePage()
const auth  = computed(() => page.props.auth)
const role  = computed(() => auth.value?.role)
const toko  = computed(() => auth.value?.toko)
const user  = computed(() => auth.value?.user)

const isSuperAdmin   = computed(() => role.value === 'superadmin')
const isAdminOrKasir = computed(() => role.value === 'admin' || role.value === 'kasir')

const pageTitle    = computed(() => page.props.pageTitle)
const pageSubtitle = computed(() => page.props.pageSubtitle)

const logout = () => {
    router.post(route('logout'))
}

const roleLabel = computed(() => {
    const map = { superadmin: 'Super Admin', admin: 'Admin', kasir: 'Kasir' }
    return map[role.value] ?? role.value
})

const roleBadgeClass = computed(() => {
    const map = {
        superadmin: 'badge-light-warning',
        admin:      'badge-light-primary',
        kasir:      'badge-light-success',
    }
    return map[role.value] ?? 'badge-light-secondary'
})
</script>

<template>
    <div id="kt_app_header" class="app-header d-flex align-items-center">

        <!-- Left: Toggle + Page Title -->
        <div class="d-flex align-items-center gap-3 flex-grow-1 ps-4">

            <!-- Sidebar Toggle Button -->
            <button
                @click="emit('toggle-sidebar')"
                class="btn btn-icon btn-sm btn-light-primary"
                title="Toggle Sidebar"
            >
                <i class="ki-duotone ki-burger-menu-5 fs-2">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span>
                    <span class="path5"></span><span class="path6"></span>
                    <span class="path7"></span>
                </i>
            </button>

            <!-- Page Title -->
            <div class="d-none d-md-flex flex-column">
                <h3 class="text-gray-900 fw-bold fs-5 mb-0 lh-1">{{ pageTitle }}</h3>
                <span v-if="pageSubtitle" class="text-muted fs-7">{{ pageSubtitle }}</span>
            </div>
        </div>

        <!-- Right: Toko Info + User Menu -->
        <div class="d-flex align-items-center gap-3 pe-4">

            <!-- Nama Toko (admin & kasir) -->
            <div
                v-if="isAdminOrKasir && toko"
                class="d-none d-lg-flex align-items-center gap-2 px-3 py-2 rounded bg-light-primary"
            >
                <i class="ki-duotone ki-shop fs-4 text-primary">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span>
                    <span class="path5"></span>
                </i>
                <span class="text-primary fw-semibold fs-7">{{ toko.nama }}</span>
            </div>

            <!-- Super Admin Badge -->
            <div
                v-if="isSuperAdmin"
                class="d-none d-lg-flex align-items-center gap-2 px-3 py-2 rounded bg-light-warning"
            >
                <i class="ki-duotone ki-crown fs-4 text-warning">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <span class="text-warning fw-semibold fs-7">Super Admin</span>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button
                    class="btn btn-light d-flex align-items-center gap-2 px-3 py-2"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <!-- Avatar -->
                    <div class="symbol symbol-30px symbol-circle">
                        <span
                            class="symbol-label fw-bold fs-7"
                            :class="{
                                'bg-light-warning text-warning': isSuperAdmin,
                                'bg-light-primary text-primary': role === 'admin',
                                'bg-light-success text-success': role === 'kasir',
                            }"
                        >
                            {{ user?.name?.charAt(0)?.toUpperCase() ?? 'U' }}
                        </span>
                    </div>

                    <!-- Name + Role (hidden on mobile) -->
                    <div class="d-none d-md-flex flex-column text-start lh-sm">
                        <span class="text-gray-900 fw-semibold fs-7">{{ user?.name }}</span>
                        <span class="badge fs-8 px-1 py-0" :class="roleBadgeClass">
                            {{ roleLabel }}
                        </span>
                    </div>

                    <i class="ki-duotone ki-down fs-5 text-muted">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-3" style="min-width: 200px;">

                    <!-- User Info Header -->
                    <li class="px-4 pb-3 border-bottom mb-2">
                        <div class="fw-semibold text-gray-900 fs-6">{{ user?.name }}</div>
                        <div class="text-muted fs-7">{{ user?.email }}</div>
                        <span class="badge mt-1 fs-8" :class="roleBadgeClass">{{ roleLabel }}</span>
                    </li>

                    <!-- Profile (hanya admin & kasir) -->
                    <li v-if="isAdminOrKasir">
                        <Link :href="route('profile.edit')" class="dropdown-item d-flex align-items-center gap-2 py-2">
                            <i class="ki-duotone ki-profile-circle fs-4 text-muted">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Profil Saya
                        </Link>
                    </li>

                    <!-- Manajemen Toko (hanya superadmin) -->
                    <li v-if="isSuperAdmin">
                        <Link :href="route('stores.index')" class="dropdown-item d-flex align-items-center gap-2 py-2">
                            <i class="ki-duotone ki-shop fs-4 text-muted">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            Manajemen Toko
                        </Link>
                    </li>

                    <li><hr class="dropdown-divider my-2"></li>

                    <!-- Logout -->
                    <li>
                        <button
                            @click="logout"
                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger"
                        >
                            <i class="ki-duotone ki-exit-right fs-4 text-danger">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Keluar
                        </button>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</template>