<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import ModalDialog from "@/js/Components/Modals/ModalDialog.vue";
import LoreQuestionForm from "@/js/Components/LoreQuestion/FormSettings.vue";
import ClickChallengerForm from "@/js/Components/ClickChallenger/FormSettings.vue";

import LoreBackground from "@/assets/images/lorequestion.png";
import ClickBackground from "@/assets/images/clickchallenger.png";

const isModalVisible = ref(false);
const showGuide = ref(false);
const page = usePage();

const openModal = () => {
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
};

const gameMetadata = computed(() => {
    const currentRoute = page.props.currentRoute;
    if (currentRoute?.includes("clickchallenger")) {
        return {
            id: "clickchallenger",
            title: "Click Challenger",
            description: "Teste sua velocidade e reflexos perante ondas de minions ou no deserto de Shurima.",
            bg: ClickBackground,
            icon: "fas fa-mouse-pointer",
            guide: [
                "O objetivo é clicar o mais rápido possível no alvo que aparecer.",
                "Cada rodada dura 15 segundos de pura intensidade.",
                "Não clique antes da hora ou perderá precisão!",
                "A velocidade aumenta progressivamente.",
                "Compare seu tempo médio de reação no final."
            ]
        };
    }
    return {
        id: "lorequestion",
        title: "Perguntas de Lore",
        description: "Você conhece realmente a história de Runeterra? Prove seus conhecimentos.",
        bg: LoreBackground,
        icon: "fas fa-book-open",
        guide: [
            "Responda as perguntas sobre o universo de LoL antes que o tempo acabe.",
            "Cada pergunta tem um tempo limite de 15 segundos.",
            "Acumule acertos para subir no ranking de historiadores.",
            "No final, veja o detalhamento de cada resposta."
        ]
    };
});
</script>

<template>
    <Head>
        <title>{{ gameMetadata.title }}</title>
        <meta name="description" :content="gameMetadata.description" />
    </Head>

    <div class="game-background-overlay" :style="{ backgroundImage: `url(${gameMetadata.bg})` }"></div>

    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3 game-title animate-pop-in">
                {{ gameMetadata.title }}
            </h1>
            <p class="lead text-light opacity-75 mb-5">
                {{ gameMetadata.description }}
            </p>

            <div class="row g-4 justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="glass-panel h-100 p-4 d-flex flex-column align-items-center animate-pop-in" style="animation-delay: 0.1s">
                        <div class="mb-4">
                            <div class="stat-badge py-3 px-4">
                                <font-awesome-icon :icon="gameMetadata.icon" class="fa-2x text-warning" />
                            </div>
                        </div>
                        <h3 class="fw-bold mb-3">Modo Normal</h3>
                        <p class="opacity-75 small mb-4">
                            A experiência clássica para testar suas habilidades de forma casual e divertida.
                        </p>
                        <button
                            @click="openModal"
                            class="btn game-btn w-100 py-3 mt-auto"
                        >
                            <font-awesome-icon icon="fas fa-play" class="me-2" /> Jogar Agora
                        </button>
                    </div>
                </div>

                <div class="col-md-5 col-lg-4 opacity-75">
                    <div class="glass-panel h-100 p-4 d-flex flex-column align-items-center animate-pop-in" style="animation-delay: 0.2s">
                        <div class="mb-4">
                            <div class="stat-badge py-3 px-4">
                                <font-awesome-icon icon="fas fa-trophy" class="fa-2x text-secondary" />
                            </div>
                        </div>
                        <h3 class="fw-bold mb-3">Competitivo</h3>
                        <p class="opacity-75 small mb-4">
                            Dispute contra as lendas de Runeterra em partidas ranqueadas.
                        </p>
                        <button class="btn btn-secondary w-100 py-3 mt-auto rounded-pill border-0 disabled" style="background: rgba(255,255,255,0.1)">
                            Em Breve
                        </button>
                    </div>
                </div>
            </div>

            <button
                class="btn game-btn-primary mt-5 rounded-circle shadow-lg"
                style="width: 60px; height: 60px"
                @click="showGuide = true"
            >
                <font-awesome-icon icon="fas fa-question" size="lg"></font-awesome-icon>
            </button>
        </div>
    </div>

    <ModalDialog :isVisible="showGuide" @close="showGuide = false" title="Guia do Jogador">
        <div class="text-white p-2">
            <h4 class="text-warning mb-4 fw-bold border-bottom border-warning border-opacity-25 pb-2">
                Como Jogar
            </h4>
            <ul class="list-unstyled">
                <li v-for="(step, i) in gameMetadata.guide" :key="i" class="mb-3 d-flex align-items-start">
                    <font-awesome-icon icon="fas fa-chevron-right" class="text-warning mt-1 me-3 small" />
                    <span class="opacity-75">{{ step }}</span>
                </li>
            </ul>
        </div>
    </ModalDialog>

    <ModalDialog
        :isVisible="isModalVisible"
        @close="closeModal"
        title="Configurações de Partida"
    >
        <ClickChallengerForm v-if="gameMetadata.id === 'clickchallenger'" />
        <LoreQuestionForm v-else />
    </ModalDialog>
</template>
