<template>
    <div id="kt_app_root" class="d-flex flex-column flex-root app-root">
        <div id="kt_app_page" class="app-page flex-column flex-column-fluid">

            <!-- HEADER -->
            <AppHeader
                :collapsed="collapsed"
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
import { ref, computed, watch } from 'vue'
import AppHeader  from '@/Components/Layout/AppHeader.vue'
import AppSidebar from '@/Components/Layout/AppSidebar.vue'
import AppFooter  from '@/Components/Layout/AppFooter.vue'

const collapsed = ref(localStorage.getItem('sidebar_collapsed') === 'true')

const mainMargin = computed(() => {
    // On mobile, no left margin (sidebar overlays)
    if (typeof window !== 'undefined' && window.innerWidth < 992) return '0px'
    return collapsed.value ? '70px' : '265px'
})

const toggleSidebar = () => {
    collapsed.value = !collapsed.value
}

watch(collapsed, (val) => {
    localStorage.setItem('sidebar_collapsed', String(val))
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

/* HEADER — fixed, starts after sidebar */
#kt_app_header {
    position: fixed !important;
    top: 0;
    right: 0;
    height: 70px;
    z-index: 150;
    background: #ffffff;
    border-bottom: 1px solid #f1f1f4;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
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
    }
    #kt_app_main {
        margin-left: 0 !important;
    }
}
</style>