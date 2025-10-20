<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import axios from "axios";

/* Subcomponents */
import GameArea from "@/js/Components/ClickChallenger/GameArea.vue";
import StatsPanel from "@/js/Components/ClickChallenger/StatsPanel.vue";
import Leaderboard from "@/js/Components/ClickChallenger/Leaderboard.vue";
import AchievementToast from "@/js/Components/ClickChallenger/AchievementToast.vue";
import ControlsPanel from "@/js/Components/ClickChallenger/ControlsPanel.vue";
import GameOverModal from "@/js/Components/ClickChallenger/GameOverModal.vue";
// import CustomizationPanel from "@/js/Components/ClickChallenger/CustomizationPanel.vue";

const gameArea = ref(null);
const leaderboard = ref(null);
const achToast = ref(null);

/* State */
const score = ref(0);
const timeLeft = ref(60);
const combo = ref(1);
const gameActive = ref(false);
const gamePaused = ref(false);
const showGameOver = ref(false);
const highScore = ref(
    parseInt(localStorage.getItem("clickRushHighScore") || "0", 10)
);
const currentPlayerId = ref(localStorage.getItem("clickRushPlayerId") || "");
const mode = ref("classic");
const lives = ref(3);
const seededRng = ref(null);

/* Stats */
const stats = reactive({
    targetsHit: 0,
    targetsMissed: 0,
    totalTargets: 0,
    reactionTimes: [],
    scoreHistory: [],
    gameStartTime: null,
});

/* Customization */
const customization = reactive(
    JSON.parse(
        localStorage.getItem("clickRushCustomization") ||
            JSON.stringify({
                theme: "default",
                shape: "circle",
                colors: {
                    pink: "#ff9a9e",
                    blue: "#1F51FF",
                    green: "#7CFC00",
                    gold: "gold",
                    violet: "#9400D3",
                    multi: "#FF69B4",
                },
            })
    )
);

/* Computed displays */
const timeDisplay = computed(() =>
    mode.value === "zen" ? "∞" : timeLeft.value
);
const difficultyLabel = ref("FÁCIL");
const dailySeedDisplay = ref("—");
const comboDisplay = computed(() => combo.value);

function updateDifficultyLabel(label) {
    difficultyLabel.value = label;
}

/* Lifecycle */
onMounted(() => {
    // load leaderboard
    leaderboard.value?.load();
});

/* Methods (bridge between GameArea and overall app) */
function initGame() {
    // Reset basic state and ask GameArea to start spawning
    mode.value = mode.value || "classic";
    if (mode.value === "zen") {
        timeLeft.value = Infinity;
    } else if (mode.value === "survival") {
        lives.value = 3;
    } else {
        timeLeft.value = 60;
    }

    score.value = 0;
    combo.value = 1;
    stats.targetsHit = 0;
    stats.targetsMissed = 0;
    stats.totalTargets = 0;
    stats.reactionTimes = [];
    stats.scoreHistory = [];
    stats.gameStartTime = Date.now();
    gameActive.value = true;
    gamePaused.value = false;
    showGameOver.value = false;

    // delegate to game area
    gameArea.value.start(mode.value);
    // timer for timeLeft if not zen
    if (mode.value !== "zen") {
        // simple timer handled inside GameArea or here; for clarity we handle it here:
        const tick = () => {
            if (!gameActive.value || gamePaused.value || mode.value === "zen")
                return;
            timeLeft.value--;
            // update difficulty label via event (GameArea could emit)
            if (timeLeft.value <= 0) {
                endGame();
            }
            // adapt difficulty for visual
            if (timeLeft.value < 15) updateDifficultyLabel("EXTREMO");
            else if (timeLeft.value < 35) updateDifficultyLabel("DIFÍCIL");
            else if (timeLeft.value < 50) updateDifficultyLabel("MÉDIO");
            else updateDifficultyLabel("FÁCIL");
        };
        // clear existing interval stored on component
        clearInterval(window.__clickrush_timer);
        window.__clickrush_timer = setInterval(tick, 1000);
    }
}

function togglePause() {
    if (!gameActive.value) return;
    gamePaused.value = !gamePaused.value;
    if (gamePaused.value) {
        gameArea.value.pause();
    } else {
        gameArea.value.resume();
    }
}

function onHit(payload) {
    // payload: { points, type, reactionTime }
    const now = Date.now();
    const points = payload.points || 0;
    // apply combo
    if (points > 0) {
        score.value += Math.round(points * combo.value);
        stats.targetsHit++;
        stats.scoreHistory.push(score.value);
        combo.value = Math.min(5, combo.value + (payload.comboInc || 0));
    } else {
        // penalty
        combo.value = 1;
        stats.targetsMissed++;
        if (mode.value === "survival") {
            lives.value--;
            if (lives.value <= 0) endGame();
        }
    }
    // record reaction
    if (payload.reactionTime) stats.reactionTimes.push(payload.reactionTime);

    // show achievement toast if present
    if (payload.achievement) achToast.value?.show(payload.achievement);

    updateStats();
}

function onMiss() {
    // simple miss
    stats.targetsMissed++;
    combo.value = 1;
    if (mode.value === "survival") {
        lives.value--;
        if (lives.value <= 0) endGame();
    }
    updateStats();
}

function onSpawned() {
    stats.totalTargets++;
    updateStats();
}

function updateStats() {
    // update computed UI or child panels by passing `stats` reactive object
    // compute accuracy or avg reaction
    // implemented in StatsPanel which receives :stats
}

function endGame() {
    gameActive.value = false;
    clearInterval(window.__clickrush_timer);
    gameArea.value.stop();
    showGameOver.value = true;
    // save highscore locally
    if (score.value > highScore.value) {
        highScore.value = score.value;
        localStorage.setItem("clickRushHighScore", String(highScore.value));
    }
    // refresh leaderboard list
    leaderboard.value?.load();
}

async function saveScore({ name }) {
    // called from Leaderboard component (will emit save-score)
    try {
        const payload = { name, score: highScore.value, mode: mode.value };
        // Endpoint expected: POST /api/scores
        await axios.post("/api/scores", payload);
        // ask leaderboard to refresh
        leaderboard.value?.load();
    } catch (e) {
        console.warn("Erro ao salvar score", e);
    }
}

function onModeChange() {
    // update daily seed if necessary
    if (mode.value === "daily") {
        const today = new Date().toISOString().slice(0, 10);
        dailySeedDisplay.value = today;
        // seeded PRNG could be passed to GameArea
    } else {
        dailySeedDisplay.value = "—";
    }
}
</script>

<template>
    <Head>
        <!-- <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" /> -->
        <meta property="og:url" content="https://hextechplay.com" />
        <link rel="canonical" href="https://hextechplay.com" />
    </Head>

    <div class="padding-navbar container">
        <!-- <div
            class="top-controls d-flex flex-wrap justify-content-center gap-2 mb-3 mt-5"
        >
            <label class="mode-info me-2 align-self-center">Modo:</label>
            <select
                class="form-select w-auto"
                v-model="mode"
                @change="onModeChange"
            >
                <option value="classic">Clássico</option>
                <option value="zen">Zen (sem tempo)</option>
                <option value="survival">Sobrevivência (3 vidas)</option>
                <option value="daily">Desafio Diário</option>
            </select>

            <div
                class="mode-info badge bg-dark text-white align-self-center"
                v-if="mode === 'survival'"
            >
                Vidas: <strong class="ms-1">{{ lives }}</strong>
            </div>

            <div class="mode-info badge bg-dark text-white align-self-center">
                Seed Diária:
                <strong class="ms-1">{{ dailySeedDisplay }}</strong>
            </div>
        </div> -->

        <achievement-toast ref="achToast" />

        <div class="main-content d-lg-flex gap-4 mt-5">
            <div class="flex-fill">
                <game-area
                    ref="gameArea"
                    :customization="customization"
                    :mode="mode"
                    :game-active="gameActive"
                    :game-paused="gamePaused"
                    @hit="onHit"
                    @miss="onMiss"
                    @spawned="onSpawned"
                    class="game-area mb-3"
                />

                <controls-panel
                    :game-active="gameActive"
                    :game-paused="gamePaused"
                    @start="initGame"
                    @pause="togglePause"
                />
            </div>

            <div class="bg-card p-3 rounded">
                <div
                    class="difficulty-box p-2 mb-3 bg-card rounded text-center"
                >
                    Modo: <strong id="difficulty">{{ difficultyLabel }}</strong>
                </div>
                <div class="d-flex gap-3 mb-3 stats">
                    <div class="stat-box p-3 rounded text-center bg-card">
                        <div class="stat-value h3">{{ score }}</div>
                        <div class="stat-label small">Pontuação</div>
                    </div>
                    <div class="stat-box p-3 rounded text-center bg-card">
                        <div class="stat-value h3">{{ timeDisplay }}</div>
                        <div class="stat-label small">Tempo</div>
                    </div>
                    <div class="stat-box p-3 rounded text-center bg-card">
                        <div class="stat-value h3">{{ combo }}x</div>
                        <div class="stat-label small">Multiplicador</div>
                    </div>
                </div>
                <h6>
                    Recorde Atual: <strong>{{ highScore }}</strong>
                </h6>
                <p class="mb-0">• Círculos verdes: +10 pontos (rápidos)</p>
                <p class="mb-0">• Círculos azuis: +5 pontos</p>
                <p class="mb-0">• Círculos rosas: +3 pontos (lentos)</p>
                <p class="mb-0">• Dourados: Power-up 2x por 10s</p>
                <p class="mb-0">• Roxos: Slow Motion</p>
                <p class="mb-0">• Rosa-claro: Multi-hit</p>
                <p class="mb-0">• Vermelhos: penalidade (evite clicar!)</p>
            </div>
        </div>

        <stats-panel
            class="mt-5"
            :stats="stats"
            :score-history="stats.scoreHistory"
        />

        <game-over-modal
            v-if="showGameOver"
            :score="score"
            :high-score="highScore"
            @play-again="initGame"
        />
    </div>
</template>
