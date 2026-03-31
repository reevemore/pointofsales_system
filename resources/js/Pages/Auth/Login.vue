<template>
    <Head title="Masuk" />

    <div class="d-flex flex-column flex-root" id="kt_app_root" style="min-height: 100vh;">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid" style="min-height: 100vh;">

            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center"
                style="background-color: #1e1e2d; min-height: 100vh;">
                <div class="d-flex flex-column flex-center py-15 px-5 px-md-15 w-100">
                    <a href="/" class="mb-12">
                        <img alt="Logo" src="/metronic/media/logos/keenthemes-dark.svg" class="h-75px" />
                    </a>
                    <img class="mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="/metronic/media/misc/outdoor.png" alt="" />
                    <h1 class="text-white fs-2qx fw-bolder text-center mb-7">
                        Fast, Efficient and Productive
                    </h1>
                    <div class="text-white fs-base text-center">
                        Sistem Point of Sale modern untuk bisnis kamu
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12"
                 style="min-height: 100vh;">
                <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">

                    <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
                        <form class="form w-100" @submit.prevent="submit">

                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">Masuk</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Sistem POS App</div>
                            </div>

                            <div v-if="status" class="mb-4 text-success fw-semibold text-center">
                                {{ status }}
                            </div>

                            <div class="fv-row mb-8">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Email"
                                    class="form-control bg-transparent"
                                    :class="{ 'is-invalid': form.errors.email }"
                                    autofocus
                                    autocomplete="username"
                                />
                                <div class="invalid-feedback" v-if="form.errors.email">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div class="fv-row mb-3">
                                <input
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Password"
                                    class="form-control bg-transparent"
                                    :class="{ 'is-invalid': form.errors.password }"
                                    autocomplete="current-password"
                                />
                                <div class="invalid-feedback" v-if="form.errors.password">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div class="form-check">
                                    <input
                                        v-model="form.remember"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="rememberMe"
                                    />
                                    <label class="form-check-label text-gray-500" for="rememberMe">
                                        Ingat Saya
                                    </label>
                                </div>
                                <Link v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="link-primary">
                                    Lupa password?
                                </Link>
                            </div>

                            <div class="d-grid mb-10">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing">
                                    <span v-if="!form.processing">Masuk</span>
                                    <span v-else>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Memproses...
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
// Menambahkan 'Head' pada import
import { useForm, Link, Head } from '@inertiajs/vue3'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>