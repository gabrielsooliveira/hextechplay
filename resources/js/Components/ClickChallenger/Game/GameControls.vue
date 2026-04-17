<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    MODE_CONFIGS: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["start-game", "toggle-pause", "end-game"]);

function handleStartGame() {
    emit("start-game", props.game.mode);
}

function handleTogglePause() {
    emit("toggle-pause");
}

function handleEndGame() {
    emit("end-game");
}
</script>

<template>
    <div
        class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-3 mb-4"
    >
        <button
            class="btn game-btn-success flex-fill fw-bold shadow text-white"
            :disabled="game.gameActive"
            @click="handleStartGame"
        >
            Iniciar ({{ MODE_CONFIGS[game.mode].name }})
        </button>

        <button
            class="btn game-btn-primary text-white flex-fill shadow border-0"
            :disabled="!game.gameActive || game.showGameOver"
            @click="handleTogglePause"
        >
            <font-awesome-icon
                :icon="[
                    game.gamePaused ? 'fas fa-play' : 'fas fa-pause',
                    'me-1',
                ]"
            ></font-awesome-icon>
            <span v-if="game.gamePaused"> Continuar</span>
            <span v-else> Pausar</span>
        </button>

        <Link
            :href="route('clickchallenger.index')"
            class="btn game-btn text-white shadow border-0"
        >
            Menu
        </Link>

        <button
            v-if="game.mode === 'zen' && game.gameActive"
            class="btn game-btn-danger shadow text-white border-0"
            @click="handleEndGame"
        >
            Encerrar Jogo
        </button>
    </div>
</template>
