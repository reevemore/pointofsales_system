<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center justify-content-center w-40px h-40px rounded bg-light-warning">
                        <i class="ki-duotone ki-lock fs-2 text-warning">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </span>
                    <div>
                        <h3 class="fw-bold mb-0">Update Password</h3>
                        <span class="text-muted fs-7">Use a long, random password to stay secure</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-4">
            <form @submit.prevent="updatePassword">
                <div class="row g-5">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Passwordmu</label>
                        <input ref="currentPasswordInput" v-model="form.current_password"
                            type="password" class="form-control form-control-solid"
                            :class="{ 'is-invalid': form.errors.current_password }"
                            placeholder="••••••••" autocomplete="current-password" />
                        <div class="invalid-feedback">{{ form.errors.current_password }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pw Baru</label>
                        <input ref="passwordInput" v-model="form.password"
                            type="password" class="form-control form-control-solid"
                            :class="{ 'is-invalid': form.errors.password }"
                            placeholder="••••••••" autocomplete="new-password" />
                        <div class="invalid-feedback">{{ form.errors.password }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fix Pw</label>
                        <input v-model="form.password_confirmation"
                            type="password" class="form-control form-control-solid"
                            :class="{ 'is-invalid': form.errors.password_confirmation }"
                            placeholder="••••••••" autocomplete="new-password" />
                        <div class="invalid-feedback">{{ form.errors.password_confirmation }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-6">
                    <button type="submit" class="btn btn-warning" :disabled="form.processing">
                        <span v-if="form.processing">
                            <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                        </span>
                        <span v-else>
                            <i class="ki-duotone ki-lock fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Update Password
                        </span>
                    </button>
                    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0"
                        enter-active-class="transition" leave-active-class="transition">
                        <span v-if="form.recentlySuccessful" class="badge badge-light-success fs-7">
                            ✅ Password updated!
                        </span>
                    </Transition>
                </div>
            </form>
        </div>
    </div>
</template>