<script setup>
import { computed } from "vue";

const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    time: {
        type: [String, Number],
        required: true,
    },
    MODE_CONFIGS: {
        type: Object,
        required: true,
    },
});

const timeDisplay = computed(() => {
    if (typeof props.time === "string" || isNaN(props.time)) {
        return props.time;
    }

    return Number(props.time).toFixed(0);
});
</script>

<template>
    <div
        class="d-flex justify-content-between p-3 mb-3 glass-panel text-white shadow-lg border-0"
    >
        <div class="text-start">
            <small class="text-muted">Modo</small>
            <h4 class="mb-0 text-warning">
                {{ MODE_CONFIGS[game.mode].name }}
            </h4>
        </div>

        <div class="text-center">
            <small class="text-muted">Pontuação Final</small>
            <h4 class="mb-0 text-success">{{ game.score }}</h4>
        </div>

        <div class="text-end">
            <small class="text-muted" v-if="game.mode === 'classic'">Tempo</small>
            <small class="text-muted" v-else-if="game.mode === 'survival'">Vidas</small>
            <small class="text-muted" v-else>Tempo</small>

            <h4
                class="mb-0"
                :class="{
                    'text-danger': game.mode === 'survival' && game.lives <= 1,
                    'text-info': game.mode !== 'survival',
                }"
            >
                <span v-if="game.mode === 'classic'">{{ timeDisplay }}s</span>
                <span v-else-if="game.mode === 'survival'">{{
                    game.lives
                }}</span>
                <span v-else>∞</span>
            </h4>
        </div>
    </div>
</template>
