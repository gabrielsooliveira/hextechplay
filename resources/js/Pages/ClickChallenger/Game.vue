<script setup>
import * as Tone from "tone";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import { ref, reactive, computed, onUnmounted, nextTick, onMounted } from "vue";
import GameInfoPanel from "@/js/Components/ClickChallenger/Game/GameInfoPanel.vue";
import GameArea from "@/js/Components/ClickChallenger/Game/GameArea.vue";
import GameControls from "@/js/Components/ClickChallenger/Game/GameControls.vue";
import GameStatsPanel from "@/js/Components/ClickChallenger/Game/GameStatsPanel.vue";

const page = usePage();
const { t } = useI18n();

const MODE_CONFIGS = {
    classic: {
        time: 60,
        lives: Infinity,
        spawnRate: 600,
        redChance: 0.35,
        scoreMultiplier: 1.0,
        name: t("mode.classic.name"),
        description: t("mode.classic.description"),
    },
    zen: {
        time: Infinity,
        lives: 5,
        spawnRate: 1000,
        redChance: 0.15,
        scoreMultiplier: 0.8,
        name: t("mode.zen.name"),
        description: t("mode.zen.description"),
    },
    survival: {
        time: Infinity,
        lives: 3,
        spawnRate: 500,
        redChance: 0.45,
        scoreMultiplier: 1.5,
        name: t("mode.survival.name"),
        description: t("mode.survival.description"),
    },
};

const MAX_COMBO = 5;
const GAME_AREA_WIDTH = ref(1200);
const GAME_AREA_HEIGHT = ref(420);
const previousWidth = ref(1200);
const previousHeight = ref(420);

function handleDimensionsUpdate(dimensions) {
    const newWidth = dimensions.width;
    const newHeight = dimensions.height;

    GAME_AREA_WIDTH.value = newWidth;
    GAME_AREA_HEIGHT.value = newHeight;

    const sizeChanged =
        newWidth !== previousWidth.value || newHeight !== previousHeight.value;

    if (game.gameActive && sizeChanged) {
        targets.forEach((target) => {
            const targetSize = parseInt(target.size);
            let newX_px = (newWidth * target.x_percent) / 100;
            let newY_px = (newHeight * target.y_percent) / 100;
            const maxX = Math.max(0, newWidth - targetSize);
            const maxY = Math.max(0, newHeight - targetSize);

            newX_px = Math.min(newX_px, maxX);
            newY_px = Math.min(newY_px, maxY);
            target.x = Math.round(newX_px) + "px";
            target.y = Math.round(newY_px) + "px";
        });
    }

    previousWidth.value = newWidth;
    previousHeight.value = newHeight;
}
const game = reactive({
    mode: page.props.mode,
    score: 0,
    combo: 1,
    lives: MODE_CONFIGS[page.props.mode].lives,
    timeLeft: MODE_CONFIGS[page.props.mode].time,
    gameActive: false,
    gamePaused: false,
    showGameOver: false,
    stats: {
        targetsHit: 0,
        targetsMissed: 0,
        totalTargets: 0,
        reactionTimes: [],
        scoreHistory: [],
    },

    highScores: {
        classic: parseInt(
            localStorage.getItem("clickRushHighScore_classic") || "0",
            10
        ),
        zen: parseInt(
            localStorage.getItem("clickRushHighScore_zen") || "0",
            10
        ),
        survival: parseInt(
            localStorage.getItem("clickRushHighScore_survival") || "0",
            10
        ),
    },
});

let nextTargetId = 1;
const targets = reactive([]);
const popups = reactive([]);
let spawnIntervalId = null;
let timerInterval = null;
const audioLoaded = ref(false);

function initializeAudio() {
    if (typeof Tone === "undefined") {
        audioLoaded.value = false;
        return;
    }

    try {
        const hitSynth = new Tone.Synth({
            oscillator: { type: "sine" },
            envelope: { attack: 0.005, decay: 0.1, sustain: 0.0, release: 0.1 },
        }).toDestination();

        const missSynth = new Tone.NoiseSynth({
            noise: { type: "pink" },
            envelope: { attack: 0.005, decay: 0.2, sustain: 0.0, release: 0.1 },
        }).toDestination();

        const redHitSynth = new Tone.Synth({
            oscillator: { type: "square" },
            envelope: { attack: 0.005, decay: 0.1, sustain: 0.0, release: 0.5 },
        }).toDestination();

        window.hitSynth = hitSynth;
        window.missSynth = missSynth;
        window.redHitSynth = redHitSynth;
        audioLoaded.value = true;
    } catch (error) {
        console.error("Erro ao inicializar o áudio com Tone.js:", error);
        audioLoaded.value = false;
    }
}

function playSound(type) {
    if (!audioLoaded.value) return;
    try {
        if (type === "hit" && window.hitSynth) {
            window.hitSynth.triggerAttackRelease("C5", "8n");
        } else if (type === "miss" && window.missSynth) {
            window.missSynth.triggerAttackRelease("4n");
        } else if (type === "red" && window.redHitSynth) {
            window.redHitSynth.triggerAttackRelease("F#3", "4n");
        }
    } catch (e) {
        console.error("Error sound:", e);
    }
}

// --- COMPUTED PROPERTIES ---

const timeDisplay = computed(() => (game.mode === "zen" ? "∞" : game.timeLeft));
const accuracy = computed(() => {
    const total = game.stats.totalTargets || 0;
    return total > 0
        ? Math.round((game.stats.targetsHit / total) * 100) + "%"
        : "0%";
});
const avgReaction = computed(() => {
    const arr = game.stats.reactionTimes || [];
    return arr.length
        ? Math.round(arr.reduce((a, b) => a + b, 0) / arr.length)
        : 0;
});
const recentScores = computed(() => game.stats.scoreHistory.slice(-20));
const maxScore = computed(() => Math.max(0, ...game.stats.scoreHistory));
const currentHighScore = computed(() => game.highScores[game.mode] || 0);

// --- LÓGICA DO TEMPORIZADOR ---

function startTimer() {
    clearInterval(timerInterval);
    if (game.mode !== "classic") return;

    timerInterval = setInterval(() => {
        if (!game.gameActive || game.gamePaused) return;

        if (game.timeLeft > 0) {
            game.timeLeft--;
        } else {
            endGame();
        }
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
}

// --- LÓGICA DE SPAWN E ALVOS ---

function createTarget() {
    const currentWidth = GAME_AREA_WIDTH.value;
    const currentHeight = GAME_AREA_HEIGHT.value;

    if (
        !game.gameActive ||
        game.gamePaused ||
        currentWidth <= 0 ||
        currentHeight <= 0
    )
        return;

    const { redChance } = MODE_CONFIGS[game.mode];
    const isRed = Math.random() < redChance;
    const size = Math.floor(Math.random() * 20) + 40;
    const points = isRed ? 0 : size > 50 ? 25 : 10;
    const comboInc = isRed ? 0 : 0.5;
    const x = Math.floor(Math.random() * (currentWidth - size));
    const y = Math.floor(Math.random() * (currentHeight - size));
    const x_percent = (x / currentWidth) * 100;
    const y_percent = (y / currentHeight) * 100;

    const color = isRed ? "#dc3545" : "#198754";

    const newTarget = {
        id: nextTargetId++,
        x,
        y,
        x_percent: x_percent,
        y_percent: y_percent,
        size,
        color,
        points,
        comboInc,
        type: isRed ? "red" : "normal",
        createdAt: Date.now(),
    };

    targets.push(newTarget);
    game.stats.totalTargets++;

    // Lógica de Miss: Remove o alvo automaticamente após 1.5s
    setTimeout(() => {
        const index = targets.findIndex((t) => t.id === newTarget.id);
        if (index !== -1) {
            // Se o alvo não for o vermelho (no survival, o miss não conta como red hit)
            if (targets[index].type === "normal") {
                handleMiss();
            }
            targets.splice(index, 1);
        }
    }, 1500);
}

function startSpawning(mode = "classic") {
    stopSpawning();
    const { spawnRate } = MODE_CONFIGS[mode];
    spawnIntervalId = setInterval(createTarget, spawnRate);
}

function stopSpawning() {
    if (spawnIntervalId) clearInterval(spawnIntervalId);
    spawnIntervalId = null;
    targets.splice(0, targets.length);
}

function togglePause() {
    if (!game.gameActive) return;
    game.gamePaused = !game.gamePaused;
    game.gamePaused ? stopSpawning() : startSpawning(game.mode);
}

// --- FUNÇÃO CENTRAL DE JOGO (HIT & MISS) ---

function handleMiss() {
    game.stats.targetsMissed++;
    game.combo = 1;
    playSound("miss");

    if (game.mode === "survival" && game.gameActive && !game.gamePaused) {
        game.lives--;
        if (game.lives <= 0) endGame();
    }
}

function onTargetClick(target) {
    if (!game.gameActive || game.gamePaused) return;

    // 1. Calcular tempo de reação
    const reactionTime = Date.now() - target.createdAt;
    game.stats.reactionTimes.push(reactionTime);

    // 2. Remover o alvo clicado
    const targetIndex = targets.findIndex((t) => t.id === target.id);
    if (targetIndex !== -1) {
        targets.splice(targetIndex, 1);
    } else {
        return; // Já foi removido (timeout)
    }

    const config = MODE_CONFIGS[game.mode];
    let scoreGain = 0;
    let popUpText = "";
    let popUpColor = "";

    if (target.type === "red") {
        game.combo = 1;
        playSound("red");

        switch (game.mode) {
            case "classic":
                game.score = Math.max(0, game.score - 15);
                popUpText = "-15 Pts";
                popUpColor = "text-danger";
                break;
            case "survival":
                game.lives--;
                popUpText = t("labels.life_lose");
                popUpColor = "text-danger";
                if (game.lives <= 0) {
                    endGame();
                }
                break;
            case "zen":
                game.score = Math.max(0, game.score - 5);
                popUpText = "-5 Pts";
                popUpColor = "text-danger";
                break;
        }
    } else {
        // CÍRCULO NORMAL (ACERTO)
        scoreGain = Math.round(
            target.points * game.combo * config.scoreMultiplier
        );
        game.score += scoreGain;
        game.combo = Math.min(MAX_COMBO, game.combo + target.comboInc);
        game.stats.targetsHit++;

        popUpText = `+${scoreGain}` + (game.combo > 1 ? ` x${game.combo}` : "");
        popUpColor = game.combo >= MAX_COMBO ? "text-success" : "text-primary";
        playSound("hit");
    }

    game.stats.scoreHistory.push(game.score);

    // Adiciona Popup
    popups.push({
        id: Date.now(),
        x: target.x + target.size / 2,
        y: target.y + target.size / 2,
        text: popUpText,
        color: popUpColor,
    });

    // Remove o popup após um tempo
    setTimeout(() => {
        popups.shift();
    }, 800);
}

function endGame() {
    game.gameActive = false;
    stopTimer();
    stopSpawning();
    game.showGameOver = true;

    // Atualiza High Score
    const modeKey = game.mode;
    if (game.score > game.highScores[modeKey]) {
        game.highScores[modeKey] = game.score;
        localStorage.setItem(`clickRushHighScore_${modeKey}`, game.score);
    }
}

// --- INICIALIZAÇÃO E REINÍCIO ---

function initGame(selectedMode) {
    if (typeof Tone !== "undefined" && !audioLoaded.value) {
        Tone.start().then(initializeAudio);
    }

    const modeKey = selectedMode || game.mode;
    const modeConfig = MODE_CONFIGS[modeKey];

    // Reset de estado
    game.mode = modeKey;
    game.score = 0;
    game.combo = 1;
    game.lives = modeConfig.lives;
    game.timeLeft = modeConfig.time;
    game.stats.targetsHit = 0;
    game.stats.targetsMissed = 0;
    game.stats.totalTargets = 0;
    game.stats.reactionTimes.splice(0, game.stats.reactionTimes.length);
    game.stats.scoreHistory.splice(0, game.stats.scoreHistory.length);
    game.gameActive = true;
    game.gamePaused = false;
    game.showGameOver = false;

    // HighScore garantido
    game.highScores.classic = parseInt(
        localStorage.getItem("clickRushHighScore_classic") || "0",
        10
    );
    game.highScores.zen = parseInt(
        localStorage.getItem("clickRushHighScore_zen") || "0",
        10
    );
    game.highScores.survival = parseInt(
        localStorage.getItem("clickRushHighScore_survival") || "0",
        10
    );

    startSpawning(modeKey);

    if (modeKey === "classic") {
        startTimer();
    } else {
        stopTimer();
    }
}

function handlePlayAgain() {
    game.showGameOver = false;
    nextTick(() => initGame(game.mode));
}

onUnmounted(() => {
    stopSpawning();
    stopTimer();
    if (typeof Tone !== "undefined" && audioLoaded.value) {
        Tone.Transport.stop();
    }
});

onMounted(() => {
    initGame(game.mode);
});
</script>

<template>
    <Head>
        <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" />
        <meta property="og:url" content="https://hextechplay.com/wordlol" />
        <link rel="canonical" href="https://hextechplay.com/wordlol" />
    </Head>

    <div class="container padding-navbar">
        <div class="text-center text-white mb-4">
            <h1 class="display-3 fw-bold text-white">Click Challenger</h1>
            <p class="lead text-secondary">
                {{ MODE_CONFIGS[game.mode].description }}
            </p>
        </div>

        <GameInfoPanel
            :game="game"
            :time="timeDisplay"
            :MODE_CONFIGS="MODE_CONFIGS"
        />

        <GameArea
            :game="game"
            :targets="targets"
            :popups="popups"
            :MODE_CONFIGS="MODE_CONFIGS"
            :currentHighScore="currentHighScore"
            @target-click="onTargetClick"
            @miss-click="handleMiss"
            @play-again="handlePlayAgain"
            @update:dimensions="handleDimensionsUpdate"
        />

        <GameControls
            :game="game"
            :MODE_CONFIGS="MODE_CONFIGS"
            @start-game="initGame"
            @toggle-pause="togglePause"
            @end-game="endGame"
        />

        <GameStatsPanel
            :game="game"
            :MODE_CONFIGS="MODE_CONFIGS"
            :accuracy="accuracy"
            :avg-reaction="avgReaction"
            :current-high-score="currentHighScore"
            :recent-scores="recentScores"
            :max-score="maxScore"
        />
    </div>
</template>
