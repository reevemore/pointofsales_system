<template>
    <Head title="Riwayat Transaksi - POSCok" />

    <AuthenticatedLayout>

        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">Riwayat Transaksi</h3>
            <Link :href="route('pos.index')" class="btn btn-primary">
                <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i>
                Transaksi Baru
            </Link>
        </div>

        <div class="card">
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>#</th>
                                <th>No. Transaksi</th>
                                <th>Kasir</th>
                                <th>Member</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Metode</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="transactions.data.length === 0">
                                <td colspan="8" class="text-center text-muted py-10">
                                    Belum ada transaksi
                                </td>
                            </tr>
                            <tr v-for="(trx, i) in transactions.data" :key="trx.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <span class="badge badge-light-primary fw-bold">
                                        {{ trx.no_transaksi }}
                                    </span>
                                </td>
                                <td>{{ trx.user?.name ?? '-' }}</td>
                                <td>{{ trx.member?.nama ?? '-' }}</td>
                                <td class="fw-bold text-success">{{ formatRupiah(trx.total) }}</td>
                                <td>{{ formatRupiah(trx.bayar) }}</td>
                                <td>
                                    <span class="badge"
                                        :class="{
                                            'badge-light-success': trx.metode_bayar === 'tunai',
                                            'badge-light-info': trx.metode_bayar === 'transfer',
                                            'badge-light-warning': trx.metode_bayar === 'qris',
                                        }">
                                        {{ trx.metode_bayar.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="text-muted fs-7">{{ formatDate(trx.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4" v-if="transactions.last_page > 1">
                    <nav>
                        <ul class="pagination">
                            <li v-for="link in transactions.links" :key="link.label"
                                class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                <Link class="page-link" :href="link.url ?? '#'"
                                    v-html="link.label" />
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({ transactions: Object })

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})
</script>