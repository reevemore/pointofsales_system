import Swal from 'sweetalert2'

// Konfigurasi default SweetAlert2 menggunakan class Metronic
const SwalMixin = Swal.mixin({
    buttonsStyling: false,
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-light me-3',
        popup: 'swal2-metronic',
    },
    reverseButtons: true,
})

export function useSwal() {
    /**
     * Konfirmasi hapus data
     */
    const confirmDelete = async (nama, tipe = 'data') => {
        return SwalMixin.fire({
            title: 'Konfirmasi Hapus',
            html: `Yakin ingin menghapus ${tipe} <strong>${nama}</strong>?<br><span class="text-muted fs-7">Tindakan ini tidak bisa dibatalkan.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        })
    }

    /**
     * Notifikasi sukses
     * @param {boolean} autoClose - true = auto close tanpa tombol OK (default: false)
     */
    const showSuccess = (pesan, judul = 'Berhasil!', autoClose = false) => {
        return SwalMixin.fire({
            title: judul,
            text: pesan,
            icon: 'success',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: !autoClose,
            ...(autoClose ? {} : { confirmButtonText: 'OK' }),
        })
    }

    /**
     * Notifikasi error
     */
    const showError = (pesan, judul = 'Gagal!') => {
        return SwalMixin.fire({
            title: judul,
            text: pesan,
            icon: 'error',
            confirmButtonText: 'OK',
        })
    }

    /**
     * Notifikasi info
     */
    const showInfo = (pesan, judul = 'Informasi') => {
        return SwalMixin.fire({
            title: judul,
            text: pesan,
            icon: 'info',
            confirmButtonText: 'OK',
        })
    }

    /**
     * Konfirmasi update stok (khusus halaman Stock)
     */
    const confirmStock = async (nama, jumlah, tipe) => {
        const aksi = tipe === 'in' ? 'Tambah' : 'Kurangi'
        const icon = tipe === 'in' ? 'question' : 'warning'
        return SwalMixin.fire({
            title: `${aksi} Stok`,
            html: `${aksi} stok <strong>${nama}</strong> sebanyak <strong>${jumlah}</strong> unit?`,
            icon,
            showCancelButton: true,
            confirmButtonText: `Ya, ${aksi}!`,
            cancelButtonText: 'Batal',
        })
    }

    /**
     * Konfirmasi logout
     */
    const confirmLogout = async () => {
        return SwalMixin.fire({
            title: 'Keluar dari Aplikasi?',
            html: `Yakin ingin keluar dari sesi ini?<br><span class="text-muted fs-7">Anda harus login kembali untuk melanjutkan.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
        })
    }

    /**
     * Notifikasi logout berhasil (auto close)
     */
    const showLogoutSuccess = () => {
        return SwalMixin.fire({
            title: 'Berhasil Keluar!',
            text: 'Anda telah berhasil keluar dari aplikasi.',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false,
        })
    }

    return { confirmDelete, showSuccess, showError, showInfo, confirmStock, confirmLogout, showLogoutSuccess }
}