import { ref, watch } from 'vue'

// State di luar function = module-level singleton
// Tidak akan reset saat component di-unmount/re-mount (navigasi Inertia)
const collapsed = ref(localStorage.getItem('sidebar_collapsed') === 'true')

watch(collapsed, (val) => {
    localStorage.setItem('sidebar_collapsed', String(val))
})

export function useSidebar() {
    const toggle = () => {
        collapsed.value = !collapsed.value
    }

    return { collapsed, toggle }
}