<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="card border border-danger">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center justify-content-center w-40px h-40px rounded bg-light-danger">
                        <i class="ki-duotone ki-trash fs-2 text-danger">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i>
                    </span>
                    <div>
                        <h3 class="fw-bold mb-0 text-danger">Delete Account</h3>
                        <span class="text-muted fs-7">Permanently delete your account and all data</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-4">
            <p class="text-muted fs-7 mb-4">
                Once your account is deleted, all resources and data will be permanently removed.
                Please download any data you wish to retain before proceeding.
            </p>
            <button @click="confirmUserDeletion" class="btn btn-danger">
                <i class="ki-duotone ki-trash fs-4 me-1">
                    <span class="path1"></span><span class="path2"></span>
                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                Delete Account
            </button>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div v-if="confirmingUserDeletion" class="modal fade show d-block" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title fw-bold text-danger">⚠️ Delete Account</h4>
                    <button type="button" class="btn-close" @click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-7 mb-4">
                        This action is <strong>permanent and irreversible</strong>.
                        Enter your password to confirm deletion.
                    </p>
                    <label class="form-label fw-semibold">Password</label>
                    <input ref="passwordInput" v-model="form.password"
                        type="password" class="form-control form-control-solid"
                        :class="{ 'is-invalid': form.errors.password }"
                        placeholder="Enter your password"
                        @keyup.enter="deleteUser" />
                    <div class="invalid-feedback">{{ form.errors.password }}</div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button @click="closeModal" class="btn btn-light">Cancel</button>
                    <button @click="deleteUser" class="btn btn-danger"
                        :disabled="form.processing">
                        <span v-if="form.processing">
                            <span class="spinner-border spinner-border-sm me-2"></span>Deleting...
                        </span>
                        <span v-else>Yes, Delete My Account</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>