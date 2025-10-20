<template>
    <div class="stats-panel bg-card p-3 rounded">
        <h5>📊 Estatísticas Detalhadas</h5>
        <div class="row">
            <div class="col-6">
                <div class="stat-name small">Precisão</div>
                <div class="stat-value h5">{{ accuracy }}</div>
            </div>
            <div class="col-6">
                <div class="stat-name small">Tempo Médio de Reação</div>
                <div class="stat-value h5">{{ avgReaction }}ms</div>
            </div>
            <div class="col-6 mt-2">
                <div class="stat-name small">Alvos Acertados</div>
                <div class="stat-value h5">{{ stats.targetsHit }}</div>
            </div>
            <div class="col-6 mt-2">
                <div class="stat-name small">Alvos Perdidos</div>
                <div class="stat-value h5">{{ stats.targetsMissed }}</div>
            </div>
        </div>

        <div class="chart-container mt-3">
            <div
                class="chart-line d-flex align-items-end"
                style="height: 130px"
            >
                <div
                    v-for="(s, i) in recentScores"
                    :key="i"
                    class="chart-bar"
                    :style="{
                        height:
                            maxScore > 0 ? (s / maxScore) * 100 + '%' : '0%',
                    }"
                ></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
const props = defineProps({
    stats: { type: Object, required: true },
    scoreHistory: { type: Array, default: () => [] },
});

const accuracy = computed(() => {
    const total = props.stats.totalTargets || 0;
    return total > 0
        ? Math.round((props.stats.targetsHit / total) * 100) + "%"
        : "0%";
});
const avgReaction = computed(() => {
    const arr = props.stats.reactionTimes || [];
    if (!arr.length) return 0;
    return Math.round(arr.reduce((a, b) => a + b, 0) / arr.length);
});

const recentScores = computed(() => props.scoreHistory.slice(-20));
const maxScore = computed(() => Math.max(...props.scoreHistory, 1));
</script>

<style scoped>
.bg-card {
    background: rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.06);
}
.chart-bar {
    flex: 1;
    margin: 0 2px;
    border-radius: 2px;
    background: linear-gradient(to top, #c89b3c, #f59e0b);
    transition: height 0.3s;
}
</style>
