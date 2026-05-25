<template>
    <div id="kt_app_root" class="d-flex flex-column flex-root app-root">
        <div id="kt_app_page" class="app-page flex-column flex-column-fluid">

            <!-- HEADER -->
            <AppHeader
                :collapsed="collapsed"
                :style="{ left: headerLeft }"
                @toggle-sidebar="toggleSidebar"
            />

            <!-- WRAPPER -->
            <div id="kt_app_wrapper" class="app-wrapper flex-row flex-row-fluid">

                <!-- SIDEBAR -->
                <AppSidebar v-model:collapsed="collapsed" />

                <!-- MAIN CONTENT -->
                <div
                    id="kt_app_main"
                    class="app-main flex-column flex-row-fluid"
                    :style="{ marginLeft: mainMargin }"
                >
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
import { computed } from 'vue'
import AppHeader  from '@/Components/Layout/AppHeader.vue'
import AppSidebar from '@/Components/Layout/AppSidebar.vue'
import AppFooter  from '@/Components/Layout/AppFooter.vue'
import { useSidebar } from '@/Composables/useSidebar'

// Pakai singleton composable — state tidak reset saat navigasi Inertia
const { collapsed, toggle: toggleSidebar } = useSidebar()

const isMobile = () => typeof window !== 'undefined' && window.innerWidth < 992

const mainMargin = computed(() => {
    if (isMobile()) return '0px'
    return collapsed.value ? '70px' : '265px'
})

const headerLeft = computed(() => {
    if (isMobile()) return '0px'
    return collapsed.value ? '70px' : '265px'
})
</script>

<style>
/* =====================================================
   GLOBAL LAYOUT — Reset & Base
   ===================================================== */
*,
*::before,
*::after {
    box-sizing: border-box;
}

body {
    margin: 0 !important;
    padding: 0 !important;
    background: #f5f5f8;
    font-family: Inter, Helvetica, sans-serif;
}

#kt_app_root,
#kt_app_page {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 100vh;
}

/* HEADER — fixed, left reaktif via :style binding dari Vue */
#kt_app_header {
    position: fixed !important;
    top: 0;
    right: 0;
    /* left diatur via :style binding di template agar reaktif */
    height: 70px;
    z-index: 150;
    background: #ffffff;
    border-bottom: 1px solid #f1f1f4;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: left 0.3s ease;
}

/* WRAPPER */
#kt_app_wrapper {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 100vh;
}

/* MAIN CONTENT — offset from top (header) */
#kt_app_main {
    min-width: 0;
    overflow-x: hidden;
    margin-top: 70px !important;
    transition: margin-left 0.3s ease;
    min-height: calc(100vh - 70px);
}

/* CONTENT AREA */
#kt_app_content {
    padding: 1.5rem 0;
}

/* MOBILE */
@media (max-width: 991px) {
    #kt_app_header {
        left: 0 !important;
        transition: none;
    }
    #kt_app_main {
        margin-left: 0 !important;
    }
}

</style>