<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const form = useForm({
    mode: "classic",
});

const saveSettings = () => {
    form.post(route("clickchallenger.roleplay"));
};

const modes = [
    { id: "classic", label: "Clássico", desc: "A experiência original de reflexo." },
    { id: "zen", label: "Zen", desc: "Foque na precisão, sem pressão." },
    { id: "survival", label: "Sobrevivência", desc: "Até onde você consegue chegar?" },
];
</script>

<template>
    <form @submit.prevent="saveSettings" class="container-fluid py-2">
        <div class="row g-3 mb-4">
            <div v-for="mode in modes" :key="mode.id" class="col-12">
                <div 
                    class="option-card d-flex align-items-center justify-content-between" 
                    :class="{ 
                        'active': form.mode === mode.id,
                        'disabled opacity-50': !isAuthenticated && form.mode !== mode.id
                    }"
                    @click="isAuthenticated ? form.mode = mode.id : null"
                    :style="!isAuthenticated ? 'cursor: not-allowed' : ''"
                >
                    <div class="text-start">
                        <div class="fw-bold">{{ mode.label }}</div>
                        <div class="small opacity-50">{{ mode.desc }}</div>
                    </div>
                    <font-awesome-icon 
                        v-if="form.mode === mode.id" 
                        icon="fas fa-check-circle" 
                        class="text-warning fs-4" 
                    />
                </div>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button
                type="submit"
                class="btn game-btn py-3 fs-5"
                :disabled="form.processing"
            >
                <font-awesome-icon icon="fas fa-bolt" class="me-2" /> Iniciar Desafio
            </button>
        </div>
    </form>
</template>
