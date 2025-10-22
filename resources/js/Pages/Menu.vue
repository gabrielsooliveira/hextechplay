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
        <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" />
        <meta
            property="og:url"
            content="https://hextechplay.com/lorequestion"
        />
        <link rel="canonical" href="https://hextechplay.com/lorequestion" />
    </Head>

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3 fade-in">
                {{ $t("page_title") }}
            </h1>
            <p class="lead text-light opacity-75 fade-in delay-200">
                {{ $t("text_mode_game") }}
            </p>

            <div class="row g-4 mt-3 justify-content-center">
                <div class="col-md-4">
                    <div
                        class="game-card card text-bg-light h-100 shadow-lg border-0 animate__animated animate__fadeInUp"
                    >
                        <div
                            class="card-body d-flex flex-column justify-content-between bg-gradient text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">Roleplay</h4>
                                <p class="card-text small">
                                    {{ $t("text_lorequestion_roleplay") }}
                                </p>
                            </div>
                            <button
                                @click="openModal"
                                class="btn btn-warning text-white mt-3 text-capitalize play-btn"
                            >
                                {{ $t("play") }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div
                        class="game-card card text-bg-light h-100 shadow-lg border-0 animate__animated animate__fadeInUp"
                    >
                        <div
                            class="card-body d-flex flex-column justify-content-between bg-gradient text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">
                                    {{ $t("competitive") }}
                                </h4>
                                <p class="card-text small">
                                    {{ $t("text_lorequestion_competitive") }}
                                </p>
                            </div>
                            <button class="btn btn-warning mt-3" disabled>
                                {{ $t("coming_soon") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="btn btn-sm btn-warning mt-5"
                @click="showGuide = true"
            >
                <font-awesome-icon icon="fas fa-question"></font-awesome-icon>
            </button>
        </div>
    </div>

    <ModalDialog :isVisible="showGuide" @close="showGuide = false">
        <div class="text-primary p-3">
            <h3>{{ $t("guide.title") }}</h3>
            <ul>
                <li>{{ $t("guide.item1") }}</li>
                <li>{{ $t("guide.item2") }}</li>
                <li>{{ $t("guide.item3") }}</li>
                <li>{{ $t("guide.item4") }}</li>
                <li>{{ $t("guide.item5") }}</li>
                <li>{{ $t("guide.item6") }}</li>
            </ul>
        </div>
    </ModalDialog>

    <ModalDialog
        :isVisible="isModalVisible"
        @close="closeModal"
        :title="$t('config_text')"
    >
        <!-- Renderiza dinamicamente -->
        <ClickChallengerForm v-if="activeForm === 'clickchallenger'" />
        <LoreQuestionForm v-else />
    </ModalDialog>
</template>

<style scoped>
/* Fundo animado com gradiente */
.animated-bg {
    background: linear-gradient(-45deg, #0d1b2a, #1b263b, #415a77, #778da9);
    background-size: 400% 400%;
    animation: gradientShift 10s ease infinite;
    z-index: 1;
}
@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Cards com efeito de hover e entrada */
.game-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation-delay: var(--delay, 0s);
}
.game-card:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 0 30px rgba(255, 193, 7, 0.4);
}

/* Botão Play com brilho */
.play-btn {
    position: relative;
    overflow: hidden;
}
.play-btn::after {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    transition: left 0.75s;
}
.play-btn:hover::after {
    left: 100%;
}

/* Animações */
.animate__animated {
    animation-duration: 0.8s;
}
</style>
