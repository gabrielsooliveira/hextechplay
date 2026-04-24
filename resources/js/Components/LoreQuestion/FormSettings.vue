<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    difficulty: "easy",
    questionQuant: 1,
});

const saveSettings = () => {
    form.post(route("lorequestion.roleplay"));
};

const difficulties = [
    { id: "easy", label: "Fácil", desc: "Perguntas mais simples" },
    { id: "medium", label: "Médio", desc: "Perguntas moderadas" },
    { id: "hard", label: "Difícil", desc: "Perguntas difíceis" },
];
</script>

<template>
    <form @submit.prevent="saveSettings" class="container-fluid py-2">
        <label class="form-label fw-bold text-warning text-uppercase small mb-3">Selecione a Dificuldade</label>
        
        <div class="row g-3 mb-4">
            <div v-for="diff in difficulties" :key="diff.id" class="col-12 col-md-4">
                <div 
                    class="option-card text-center" 
                    :class="{ 'active': form.difficulty === diff.id }"
                    @click="form.difficulty = diff.id"
                >
                    <div class="fw-bold mb-1">{{ diff.label }}</div>
                    <div class="small opacity-50">{{ diff.desc }}</div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="questionQuant" class="form-label fw-bold text-warning text-uppercase small mb-0">Quantidade de Perguntas</label>
                <span class="badge bg-warning text-dark fw-bold px-3 rounded-pill">{{ form.questionQuant }} / 10</span>
            </div>
            <input
                type="range"
                class="form-range custom-range"
                min="1"
                max="10"
                id="questionQuant"
                v-model="form.questionQuant"
            />
        </div>

        <div class="d-grid mt-4">
            <button
                type="submit"
                class="btn game-btn py-3 fs-5"
                :disabled="form.processing"
            >
                <font-awesome-icon icon="fas fa-rocket" class="me-2" /> Iniciar Partida
            </button>
        </div>
    </form>
</template>
