<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const form = useForm({
    difficulty: "medium",
    questionQuant: 5,
});

const saveSettings = () => {
    form.post(route("guesstherank.game"));
};

const difficulties = [
    { id: "easy", label: "Bronze - Prata", desc: "Jogadas mais óbvias" },
    { id: "medium", label: "Ouro - Platina", desc: "Jogadas equilibradas" },
    { id: "hard", label: "Mestre+", desc: "Jogadas de alto nível" },
];
</script>

<template>
    <form @submit.prevent="saveSettings" class="container-fluid py-2">
        <div v-if="!isAuthenticated" class="alert bg-warning bg-opacity-10 border-warning text-warning mb-4 small">
            <font-awesome-icon icon="fas fa-info-circle" class="me-2" />
            Faça login para selecionar dificuldades e quantidade de perguntas! Jogando como convidado com valores padrão.
        </div>

        <label class="form-label fw-bold text-warning text-uppercase small mb-3">Selecione a Dificuldade</label>
        
        <div class="row g-3 mb-4">
            <div v-for="diff in difficulties" :key="diff.id" class="col-12 col-md-4">
                <div 
                    class="option-card text-center" 
                    :class="{ 
                        'active': form.difficulty === diff.id,
                        'disabled opacity-50': !isAuthenticated && form.difficulty !== diff.id
                    }"
                    @click="isAuthenticated ? form.difficulty = diff.id : null"
                    :style="!isAuthenticated ? 'cursor: not-allowed' : ''"
                >
                    <div class="fw-bold mb-1">{{ diff.label }}</div>
                    <div class="small opacity-50">{{ diff.desc }}</div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="questionQuant" class="form-label fw-bold text-warning text-uppercase small mb-0">Quantidade de Perguntas</label>
                <span class="badge bg-warning text-dark fw-bold px-3 rounded-pill">{{ form.questionQuant }}</span>
            </div>
            <input
                type="range"
                class="form-range custom-range"
                min="3"
                max="10"
                id="questionQuant"
                v-model="form.questionQuant"
                :disabled="!isAuthenticated"
                :style="!isAuthenticated ? 'opacity: 0.5; cursor: not-allowed' : ''"
            />
        </div>

        <div class="d-grid mt-4">
            <button
                type="submit"
                class="btn game-btn py-3 fs-5"
                :disabled="form.processing"
            >
                <font-awesome-icon icon="fas fa-play" class="me-2" /> Iniciar como {{ isAuthenticated ? 'Pro' : 'Convidado' }}
            </button>
        </div>
    </form>
</template>
