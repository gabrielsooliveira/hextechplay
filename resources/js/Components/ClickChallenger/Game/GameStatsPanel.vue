<script setup>
const props = defineProps({
    game: {
        type: Object,
        required: true,
    },
    MODE_CONFIGS: {
        type: Object,
        required: true,
    },
    accuracy: {
        type: String,
        required: true,
    },
    avgReaction: {
        type: [Number, String],
        required: true,
    },
    currentHighScore: {
        type: Number,
        required: true,
    },
    recentScores: {
        type: Array,
        required: true,
    },
    maxScore: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="stats-panel bg-dark p-4 rounded-3 shadow-lg text-white">
        <h5 class="mb-3 text-white fw-bold">{{ $t("stats.title") }}</h5>

        <div class="row g-3 text-center">
            <div class="col-6 col-sm-3">
                <div class="p-2 bg-primary rounded">
                    <small class="text-muted d-block">{{
                        $t("stats.combo")
                    }}</small>
                    <div class="h5 fw-bold text-info">x{{ game.combo }}</div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="p-2 bg-primary rounded">
                    <small class="text-muted d-block">{{
                        $t("stats.accuracy")
                    }}</small>
                    <div class="h5 fw-bold text-warning">
                        {{ accuracy }}
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="p-2 bg-primary rounded">
                    <small class="text-muted d-block">{{
                        $t("stats.reaction_time")
                    }}</small>
                    <div class="h5 fw-bold text-light">{{ avgReaction }}ms</div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="p-2 bg-primary rounded">
                    <small class="text-muted d-block"
                        >{{ $t("stats.highscore") }} ({{
                            MODE_CONFIGS[game.mode].name
                        }})</small
                    >
                    <div class="h5 fw-bold text-success">
                        {{ currentHighScore }}
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-container mt-4 pt-3 border-top border-secondary">
            <small class="text-muted d-block mb-1">{{
                $t("stats.recent_score")
            }}</small>
            <div
                class="chart-line d-flex align-items-end justify-content-between gap-1 p-1 bg-primary rounded w-100"
                style="height: 100px"
            >
                <div
                    v-for="(s, i) in recentScores"
                    :key="i"
                    class="chart-bar bg-warning rounded-top shadow-sm w-100"
                    style="transition: height 0.3s"
                    :style="{
                        height:
                            maxScore > 0 ? (s / maxScore) * 100 + '%' : '0%',
                    }"
                ></div>
            </div>
        </div>
    </div>
</template>
