<template>
    <div id="kt_app_root" class="d-flex flex-column flex-root app-root">
        <div id="kt_app_page" class="app-page flex-column flex-column-fluid">

            <!-- HEADER -->
            <AppHeader />

            <!-- WRAPPER -->
            <div id="kt_app_wrapper" class="app-wrapper flex-row flex-row-fluid">

                <!-- SIDEBAR -->
                <AppSidebar v-model:collapsed="collapsed" />

                <!-- MAIN CONTENT -->
                <div id="kt_app_main" class="app-main flex-column flex-row-fluid"
                    :style="{ marginLeft: sidebarWidth }">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content flex-column-fluid py-6">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                <slot />
                            </div>
                        </div>
                    </div>
                    <AppFooter />
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppHeader from '@/Components/Layout/AppHeader.vue'
import AppSidebar from '@/Components/Layout/AppSidebar.vue'
import AppFooter from '@/Components/Layout/AppFooter.vue'

const collapsed = ref(false)
const sidebarWidth = computed(() => collapsed.value ? '70px' : '265px')
</script>

<style>
body {
    margin: 0 !important;
    padding: 0 !important;
    background: #f5f5f8;
}

#kt_app_root,
#kt_app_page {
    margin: 0 !important;
    padding: 0 !important;
}

/* SIDEBAR: fixed, full height, z-index tinggi */
#kt_app_sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0;
    height: 100vh;
    z-index: 200;
}

/* HEADER: fixed, mulai dari kanan sidebar */
#kt_app_header {
    position: fixed !important;
    top: 0;
    left: var(--sidebar-width, 265px); /* ← ikut lebar sidebar */
    right: 0;
    height: 70px;
    z-index: 150;
    background: #fff;
    border-bottom: 1px solid #f1f1f4;
    transition: left 0.3s ease;
}

/* WRAPPER */
#kt_app_wrapper {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 100vh;
}

/* MAIN CONTENT */
#kt_app_main {
    min-width: 0;
    overflow-x: hidden;
    margin-top: 70px !important;     /* ruang untuk fixed header */
    transition: margin-left 0.3s ease;
}

@media (max-width: 991px) {
    #kt_app_header {
        left: 0 !important; /* di mobile header full width */
    }
    #kt_app_main {
        margin-left: 0 !important;
    }
}
</style>