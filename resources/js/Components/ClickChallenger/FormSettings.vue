<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    mode: "classic",
});

const saveSettings = () => {
    form.post(route("clickchallenger.roleplay"));
};
</script>

<template>
    <form @submit.prevent="saveSettings" class="container">
        <div class="mb-3 text-primary">
            <label class="form-label fw-semibold d-block text-capitalize">Escolha o modo de jogo</label>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="radio"
                    id="mode-classic"
                    value="classic"
                    v-model="form.mode"
                />
                <label class="form-check-label" for="mode-classic">
                    Clasico
                </label>
            </div>

            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="radio"
                    id="mode-zen"
                    value="zen"
                    v-model="form.mode"
                />
                <label class="form-check-label" for="mode-zen">
                    Zen
                </label>
            </div>

            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="radio"
                    id="mode-survival"
                    value="survival"
                    v-model="form.mode"
                />
                <label class="form-check-label" for="mode-survival">
                    Sobrevivência
                </label>
            </div>
        </div>
        <div class="d-grid">
            <button
                type="submit"
                class="btn btn-accent text-white flex-grow-1 fw-bold rounded-3 shadow text-capitalize"
            >
                Iniciar jogo
            </button>
        </div>
    </form>
</template>
