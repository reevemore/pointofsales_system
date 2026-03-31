<template>
    <AuthenticatedLayout>
        <div class="d-flex flex-wrap flex-stack mb-6">
            <div>
                <h3 class="fw-bolder my-2">Riwayat Poin: {{ member.nama }}</h3>
                <div class="text-muted fs-7">
                    Total poin saat ini:
                    <span class="badge badge-light-warning fw-bold fs-6">{{ member.poin }} poin</span>
                    = {{ formatRupiah(member.poin * 1000) }}
                </div>
            </div>
            <Link :href="route('members.index')" class="btn btn-light">← Kembali</Link>
        </div>

        <div class="card">
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>#</th>
                                <th>Jenis</th>
                                <th>Poin</th>
                                <th>Sebelum</th>
                                <th>Sesudah</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="histories.data.length === 0">
                                <td colspan="7" class="text-center text-muted py-10">
                                    Belum ada riwayat poin
                                </td>
                            </tr>
                            <tr v-for="(h, i) in histories.data" :key="h.id">
                                <td>{{ i + 1 }}</td>
                                <td>
                                    <span class="badge"
                                        :class="h.type === 'earn' ? 'badge-light-success' : 'badge-light-danger'">
                                        {{ h.type === 'earn' ? '⬆️ Earn' : '⬇️ Redeem' }}
                                    </span>
                                </td>
                                <td class="fw-bold"
                                    :class="h.type === 'earn' ? 'text-success' : 'text-danger'">
                                    {{ h.type === 'earn' ? '+' : '-' }}{{ h.poin }}
                                </td>
                                <td class="text-muted">{{ h.poin_before }}</td>
                                <td class="fw-bold">{{ h.poin_after }}</td>
                                <td class="text-muted fs-7">{{ h.keterangan ?? '-' }}</td>
                                <td class="text-muted fs-7">{{ formatDate(h.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4" v-if="histories.last_page > 1">
                    <nav>
                        <ul class="pagination">
                            <li v-for="link in histories.links" :key="link.label"
                                class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                <Link class="page-link" :href="link.url ?? '#'" v-html="link.label" />
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({ member: Object, histories: Object })

const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(val ?? 0)

const formatDate = (val) => new Date(val).toLocaleString('id-ID', {
    day: '2-digit', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
})
</script>