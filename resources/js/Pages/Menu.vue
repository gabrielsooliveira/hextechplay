<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import ModalDialog from "@/js/Components/Modals/ModalDialog.vue";
import LoreQuestionForm from "@/js/Components/LoreQuestion/FormSettings.vue";
import ClickChallengerForm from "@/js/Components/ClickChallenger/FormSettings.vue";

const isModalVisible = ref(false);
const showGuide = ref(false);
const page = usePage();

const openModal = () => {
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
};

const activeForm = computed(() => {
    const currentRoute = page.props.currentRoute;
    if (currentRoute === "clickchallenger.index") {
        return "clickchallenger";
    }
    return "lorequestion";
});
</script>

<template>
    <Head>
        <title>ClickChallenger</title>
        <meta name="description" content="ClickChallenger é um jogo da plataforma HextechPlay que testa seus reflexos e suas habilidades." />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seus reflexos e precisão no ClickChallenger." />
        <meta
            property="og:url"
            content="https://hextechplay.com/lorequestion"
        />
        <link rel="canonical" href="https://hextechplay.com/lorequestion" />
    </Head>

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3">
                ClickChallenger
            </h1>
            <p class="lead text-light opacity-75">
                Escolha o modo de jogo
            </p>

            <div class="row g-4 mt-3 justify-content-center">
                <div class="col-md-4">
                    <div class="card text-bg-light h-100 shadow-lg border-0">
                        <div
                            class="card-body d-flex flex-column justify-content-between text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">Normal</h4>
                                <p class="card-text small">
                                    Teste seus reflexos de forma divertida e casual.
                                </p>
                            </div>
                            <button
                                @click="openModal"
                                class="btn btn-accent text-white mt-3 text-capitalize"
                            >
                                Jogar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-bg-light h-100 shadow-lg border-0">
                        <div
                            class="card-body d-flex flex-column justify-content-between bg-gradient text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">
                                    Competitivo
                                </h4>
                                <p class="card-text small">
                                    Dispute com outros jogadores em partidas classificatórias. (Em breve)
                                </p>
                            </div>
                            <button class="btn btn-accent mt-3" disabled>
                                Em breve
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="btn btn-sm btn-accent mt-5"
                @click="showGuide = true"
            >
                <font-awesome-icon icon="fas fa-question"></font-awesome-icon>
            </button>
        </div>
    </div>

    <ModalDialog :isVisible="showGuide" @close="showGuide = false">
        <div class="text-primary p-3">
            <h3>Como Jogar</h3>
            <ul>
                <li>O objetivo é clicar o mais rápido possível no botão que aparecer na tela, quanto mais rápido, maior sua pontuação!</li>
                <li>Você terá 15 segundos para reagir a cada rodada. Clique assim que o botão surgir, sem errar o alvo.</li>
                <li>Se clicar antes da hora, perderá pontos! Espere o momento certo para agir.</li>
                <li>A velocidade da aparição dos botões aumenta conforme o jogo avança, então mantenha o foco.</li>
                <li>No final, você verá seu tempo médio de reação e poderá comparar com suas tentativas anteriores.</li>
                <li>Dica: mantenha o dedo próximo do botão e evite distrações — a precisão e o tempo de reação são o segredo para ser o campeão do Click Challenger!</li>
            </ul>
        </div>
    </ModalDialog>

    <ModalDialog
        :isVisible="isModalVisible"
        @close="closeModal"
        title="Configurações do jogo"
    >
        <ClickChallengerForm v-if="activeForm === 'clickchallenger'" />
        <LoreQuestionForm v-else />
    </ModalDialog>
</template>
