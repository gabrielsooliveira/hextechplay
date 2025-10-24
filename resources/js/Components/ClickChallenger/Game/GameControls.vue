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
            class="btn btn-success flex-fill fw-bold shadow text-white"
            :disabled="game.gameActive"
            @click="handleStartGame"
        >
            {{ $t("labels.start") }} ({{ MODE_CONFIGS[game.mode].name }})
        </button>

        <button
            class="btn btn-secondary text-white flex-fill shadow"
            :disabled="!game.gameActive || game.showGameOver"
            @click="handleTogglePause"
        >
            <font-awesome-icon
                :icon="[
                    game.gamePaused ? 'fas fa-play' : 'fas fa-pause',
                    'me-1',
                ]"
            ></font-awesome-icon>
            <span v-if="game.gamePaused"> {{ $t("labels.continue") }}</span>
            <span v-else> {{ $t("labels.pause") }}</span>
        </button>

        <Link
            :href="route('clickchallenger.index')"
            class="btn btn-outline-secondary text-white shadow"
        >
            {{ $t("labels.menu") }}
        </Link>

        <button
            v-if="game.mode === 'zen' && game.gameActive"
            class="btn btn-danger shadow text-white"
            @click="handleEndGame"
        >
            {{ $t("labels.end_game") }}
        </button>
    </div>
</template>
