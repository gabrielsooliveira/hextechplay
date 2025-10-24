<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import GameTarget from "@/js/Components/ClickChallenger/Game/GameTarget.vue";
import GameOverlay from "@/js/Components/ClickChallenger/Game/GameOverlay.vue";

const areaRef = ref(null);

const props = defineProps({
    game: { type: Object, required: true },
    targets: { type: Array, required: true },
    popups: { type: Array, required: true },
    MODE_CONFIGS: { type: Object, required: true },
    currentHighScore: { type: Number, required: true },
});

const emit = defineEmits([
    "target-click",
    "miss-click",
    "play-again",
    "toggle-pause",
    "update:dimensions",
]);

function updateAndEmitDimensions() {
    if (areaRef.value) {
        const width = areaRef.value.clientWidth;
        const height = areaRef.value.clientHeight;

        emit("update:dimensions", { width, height });
    }
}

function handleMissClick() {
    if (
        props.game.gameActive &&
        !props.game.gamePaused &&
        !props.game.showGameOver
    ) {
        emit("miss-click");
    }
}

function handleTargetClick(target) {
    emit("target-click", target);
}

function handlePlayAgain() {
    emit("play-again");
}

onMounted(() => {
    updateAndEmitDimensions();
    window.addEventListener("resize", updateAndEmitDimensions);
});

onUnmounted(() => {
    window.removeEventListener("resize", updateAndEmitDimensions);
});
</script>

<template>
    <div
        ref="areaRef"
        class="w-100 position-relative rounded-3 shadow-lg border border-warning overflow-hidden my-0 mx-auto"
        :style="{
            minHeight: '420px',
        }"
        @click="handleMissClick"
    >
        <GameTarget
            v-for="t in targets"
            :key="t.id"
            :target="t"
            @click.stop="handleTargetClick(t)"
        />

        <div
            v-for="p in popups"
            :key="p.id"
            class="points-popup position-absolute fw-bolder text-white"
            :class="[p.color]"
            :style="{
                left: p.x + 'px',
                top: p.y + 'px',
                transform: 'translate(-50%, -50%)',
                fontSize: '1.2rem',
                animation: 'popup-animate 0.8s ease-out forwards',
                pointerEvents: 'none',
                zIndex: 20,
            }"
        >
            {{ p.text }}
        </div>

        <GameOverlay
            :game="game"
            :MODE_CONFIGS="MODE_CONFIGS"
            :currentHighScore="currentHighScore"
            @play-again="handlePlayAgain"
        />
    </div>
</template>

<style>
@keyframes popup-animate {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -100px) scale(1.2);
    }
}
</style>
