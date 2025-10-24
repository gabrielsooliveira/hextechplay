<script setup>
import { useI18n } from "vue-i18n";
// Assumindo que você tem o FontAwesome instalado e globalmente acessível
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const { t } = useI18n();

const props = defineProps({
    game: { type: Object, required: true },
    MODE_CONFIGS: { type: Object, required: true },
    currentHighScore: { type: Number, required: true },
});

const emit = defineEmits(["play-again"]);

function handlePlayAgain() {
    emit("play-again");
}
</script>

<template>
    <div
        v-if="game.gamePaused && !game.showGameOver"
        class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-75 d-flex align-items-center justify-content-center"
        style="z-index: 50"
    >
        <span class="text-white h3">{{ t("labels.paused") }}</span>
    </div>

    <div
        v-if="game.showGameOver"
        class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-90 d-flex align-items-center justify-content-center z-3"
        style="z-index: 60"
    >
        <div
            class="modal-content bg-dark text-white py-3 w-75 rounded-3 shadow-lg border border-warning"
        >
            <div class="text-center">
                <h2 class="mb-2 text-warning display-4 fw-bold">
                    {{ t("labels.game_over") }}
                </h2>
                <p class="lead mb-1">
                    {{ t("labels.final_score") }}:
                    <strong class="text-success">{{ game.score }}</strong>
                </p>
                <p class="text-muted small">
                    {{ t("labels.record") }}
                    {{ MODE_CONFIGS[game.mode].name }}:
                    <strong class="text-info">{{ currentHighScore }}</strong>
                </p>

                <button
                    class="btn btn-primary btn-lg mt-3"
                    @click="handlePlayAgain"
                >
                    <i class="fas fa-rotate-left me-2"></i>
                    {{ t("labels.play_again") }}
                </button>
            </div>
        </div>
    </div>
</template>
