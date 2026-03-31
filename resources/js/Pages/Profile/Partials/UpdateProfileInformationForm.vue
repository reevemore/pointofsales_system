<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({ mustVerifyEmail: Boolean, status: String });

const user = usePage().props.auth.user;
const form = useForm({ name: user.name, email: user.email });
</script>

<template>
    <div class="card mb-5">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center justify-content-center w-40px h-40px rounded bg-light-primary">
                        <i class="ki-duotone ki-profile-circle fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </span>
                    <div>
                        <h3 class="fw-bold mb-0">Profile Information</h3>
                        <span class="text-muted fs-7">Update your name and email address</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-4">
            <form @submit.prevent="form.patch(route('profile.update'))">
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input v-model="form.name" type="text"
                            class="form-control form-control-solid"
                            :class="{ 'is-invalid': form.errors.name }"
                            placeholder="Your name" required autofocus />
                        <div class="invalid-feedback">{{ form.errors.name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input v-model="form.email" type="email"
                            class="form-control form-control-solid"
                            :class="{ 'is-invalid': form.errors.email }"
                            placeholder="your@email.com" required />
                        <div class="invalid-feedback">{{ form.errors.email }}</div>
                    </div>
                </div>
                <div v-if="mustVerifyEmail && !user.email_verified_at" class="alert alert-warning mt-4">
                    Your email is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="alert-link">Click here to resend verification.</Link>
                </div>
                <div class="d-flex align-items-center gap-3 mt-6">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">
                        <span v-if="form.processing">
                            <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                        </span>
                        <span v-else>
                            <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Save Changes
                        </span>
                    </button>
                    <Transition enter-from-class="opacity-0" leave-to-class="opacity-0"
                        enter-active-class="transition" leave-active-class="transition">
                        <span v-if="form.recentlySuccessful" class="badge badge-light-success fs-7">
                            ✅ Saved successfully!
                        </span>
                    </Transition>
                </div>
            </form>
        </div>
    </div>
</template>