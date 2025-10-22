<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, reactive, computed, onUnmounted, nextTick } from "vue";
import CoinImage from "@/assets/images/clickchallenger/coin.png";
import MushroomImage from "@/assets/images/clickchallenger/mushroom.png";

const page = usePage();
const MODE_CONFIGS = {
    classic: {
        time: 60,
        lives: Infinity,
        spawnRate: 600,
        redChance: 0.35,
        scoreMultiplier: 1.0,
        name: "Clássico",
        description: "60 segundos para pontuar o máximo.",
    },
    zen: {
        time: Infinity,
        lives: 5,
        spawnRate: 1000,
        redChance: 0.15,
        scoreMultiplier: 0.8,
        name: "Zen",
        description: "Tempo infinito, relaxe e acerte.",
    },
    survival: {
        time: Infinity,
        lives: 3,
        spawnRate: 500,
        redChance: 0.45,
        scoreMultiplier: 1.5,
        name: "Sobrevivência",
        description: "3 vidas, cuidado com os círculos vermelhos!",
    },
};

const MAX_COMBO = 5;
const GAME_AREA_WIDTH = 1200;
const GAME_AREA_HEIGHT = 420;

// Estado do Jogo (Reactive)
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

// Alvos e Popups
const areaRef = ref(null);
let nextTargetId = 1;
const targets = reactive([]);
const popups = reactive([]);
let spawnIntervalId = null;
let timerInterval = null;
const audioLoaded = ref(false);

// --- Lógica de Áudio (Tone.js) ---

function initializeAudio() {
    if (typeof Tone === "undefined") {
        console.warn("Tone.js não carregado. Áudio desabilitado.");
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
        console.error("Erro ao tocar áudio:", e);
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
    if (!game.gameActive || game.gamePaused) return;

    const { redChance } = MODE_CONFIGS[game.mode];
    const isRed = Math.random() < redChance;

    // Tamanhos e Pontos
    const size = Math.floor(Math.random() * 20) + 40; // 40px a 60px
    const points = isRed ? 0 : size > 50 ? 25 : 10;
    const comboInc = isRed ? 0 : 0.5;

    // Posição
    const x = Math.floor(Math.random() * (GAME_AREA_WIDTH - size));
    const y = Math.floor(Math.random() * (GAME_AREA_HEIGHT - size));

    const color = isRed ? "#dc3545" : "#198754";

    const newTarget = {
        id: nextTargetId++,
        x,
        y,
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
        // CÍRCULO VERMELHO (PENALIDADE)
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
                popUpText = "VIDA PERDIDA!";
                popUpColor = "text-danger";
                if (game.lives <= 0) {
                    endGame();
                }
                break;
            case "zen":
                // Penalidade mínima no Zen (embora o alvo vermelho dificilmente apareça)
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
    // Inicializa o áudio na primeira interação
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
    // Limpeza do Tone.js se necessário
    if (typeof Tone !== "undefined" && audioLoaded.value) {
        Tone.Transport.stop();
    }
});
</script>

<template>
    <!-- O componente é envolvido em uma div que simula o container centralizado -->
    <div class="container padding-navbar">
        <!-- Título e Subtítulo -->
        <div class="text-center text-white mb-4">
            <h1 class="display-3 fw-bold text-white">Click Challenger</h1>
            <p class="lead text-secondary">
                {{ MODE_CONFIGS[game.mode].description }}
            </p>
        </div>

        <!-- Modo Ativo / Status do Jogo -->
        <div
            class="d-flex justify-content-between p-3 mb-3 bg-dark text-white rounded shadow-sm"
        >
            <div class="text-start">
                <small class="text-muted">Modo</small>
                <h4 class="mb-0 text-warning">
                    {{ MODE_CONFIGS[game.mode].name }}
                </h4>
            </div>
            <div class="text-center">
                <small class="text-muted">Pontuação</small>
                <h4 class="mb-0 text-success">{{ game.score }}</h4>
            </div>
            <div class="text-end">
                <small class="text-muted" v-if="game.mode === 'classic'"
                    >Tempo</small
                >
                <small class="text-muted" v-else-if="game.mode === 'survival'"
                    >Vidas</small
                >
                <small class="text-muted" v-else>Tempo</small>

                <h4
                    class="mb-0"
                    :class="{
                        'text-danger':
                            game.mode === 'survival' && game.lives <= 1,
                        'text-info': game.mode !== 'survival',
                    }"
                >
                    <span v-if="game.mode === 'classic'"
                        >{{ timeDisplay }}s</span
                    >
                    <span v-else-if="game.mode === 'survival'">{{
                        game.lives
                    }}</span>
                    <span v-else>∞</span>
                </h4>
            </div>
        </div>

        <!-- Área do Jogo -->
        <div
            ref="areaRef"
            class="game-area position-relative rounded-3 shadow-lg border border-warning"
            :style="{
                margin: '0 auto',
                overflow: 'hidden',
            }"
            @click="handleMiss"
        >
            <!-- targets are rendered dynamically as absolutely positioned divs -->
            <div
                v-for="t in targets"
                :key="t.id"
                class="target position-absolute rounded-circle shadow-sm d-flex align-items-center justify-content-center fw-bold text-white cursor-pointer"
                :style="{
                    left: t.x + 'px',
                    top: t.y + 'px',
                    width: t.size + 'px',
                    height: t.size + 'px',
                    backgroundColor: t.color,
                    transition: 'transform 0.65s',
                    cursor: 'pointer',
                    zIndex: 10,
                    border: t.type === 'red' ? '3px solid #ff7a7a' : 'none',
                }"
                @click.stop="onTargetClick(t)"
            >
                <img
                    v-if="t.type === 'normal'"
                    :src="CoinImage"
                    alt="Gold Coin"
                    style="width: 80%; height: 80%; object-fit: contain"
                />
                <img
                    v-else
                    :src="MushroomImage"
                    alt="Teemo Mushroom"
                    style="width: 90%; height: 90%; object-fit: contain"
                />
            </div>

            <!-- points popups -->
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

            <!-- Game Paused Overlay -->
            <div
                v-if="game.gamePaused"
                class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-75 d-flex align-items-center justify-content-center"
                style="z-index: 50"
            >
                <span class="text-white h3">PAUSADO</span>
            </div>

            <!-- Game Over Modal -->
            <div
                v-if="game.showGameOver"
                class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-90 d-flex align-items-center justify-content-center"
                style="z-index: 100"
            >
                <div
                    class="modal-content bg-dark text-white py-3 w-75 rounded-3 shadow-lg border border-warning"
                >
                    <div class="text-center">
                        <h2 class="mb-2 text-warning display-4 fw-bold">
                            FIM DE JOGO!
                        </h2>
                        <p class="lead mb-1">
                            Pontuação Final:
                            <strong class="text-success">{{
                                game.score
                            }}</strong>
                        </p>
                        <p class="text-muted small">
                            Recorde no modo
                            {{ MODE_CONFIGS[game.mode].name }}:
                            <strong class="text-info">{{
                                currentHighScore
                            }}</strong>
                        </p>

                        <button
                            class="btn btn-primary btn-lg mt-3"
                            @click="handlePlayAgain"
                        >
                            <font-awesome-icon
                                icon="fas fa-rotate-left"
                            ></font-awesome-icon>
                            Jogar Novamente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles e Seleção de Modo -->
        <div
            class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-3 mb-4"
        >
            <button
                class="btn btn-success flex-fill fw-bold shadow text-white"
                :disabled="game.gameActive"
                @click="initGame(game.mode)"
            >
                Iniciar ({{ MODE_CONFIGS[game.mode].name }})
            </button>

            <button
                class="btn btn-secondary text-white flex-fill shadow"
                :disabled="!game.gameActive || game.showGameOver"
                @click="togglePause()"
            >
                <font-awesome-icon
                    :icon="game.gamePaused ? 'fas fa-play' : 'fas fa-pause'"
                ></font-awesome-icon>
                <span v-if="game.gamePaused"> Continuar</span>
                <span v-else> Pausar</span>
            </button>

            <Link
                :href="route('clickchallenger.index')"
                class="btn btn-outline-secondary text-white shadow"
            >
                Menu</Link
            >

            <button
                v-if="game.mode === 'zen' && game.gameActive"
                class="btn btn-danger shadow text-white"
                @click="endGame()"
            >
                Encerrar Jogo
            </button>
        </div>

        <!-- Painel de Estatísticas -->
        <div class="stats-panel bg-dark p-4 rounded-3 shadow-lg text-white">
            <h5 class="mb-3 text-white fw-bold">📊 Estatísticas Detalhadas</h5>
            <div class="row g-3 text-center">
                <div class="col-6 col-sm-3">
                    <div class="p-2 bg-primary rounded">
                        <small class="text-muted d-block">Combo</small>
                        <div class="h5 fw-bold text-info">
                            x{{ game.combo }}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-2 bg-primary rounded">
                        <small class="text-muted d-block">Precisão</small>
                        <div class="h5 fw-bold text-warning">
                            {{ accuracy }}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-2 bg-primary rounded">
                        <small class="text-muted d-block">Tempo Reação</small>
                        <div class="h5 fw-bold text-light">
                            {{ avgReaction }}ms
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="p-2 bg-primary rounded">
                        <small class="text-muted d-block"
                            >Recorde ({{ MODE_CONFIGS[game.mode].name }})</small
                        >
                        <div class="h5 fw-bold text-success">
                            {{ currentHighScore }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Placeholder -->
            <div class="chart-container mt-4 pt-3 border-top border-secondary">
                <small class="text-muted d-block mb-1"
                    >Histórico de Pontuação Recente</small
                >
                <div
                    class="chart-line d-flex align-items-end justify-content-between gap-1 p-1 bg-primary rounded"
                    style="height: 100px; width: 100%"
                >
                    <div
                        v-for="(s, i) in recentScores"
                        :key="i"
                        class="chart-bar bg-primary rounded-top shadow-sm"
                        style="width: 100%; transition: height 0.3s"
                        :style="{
                            height:
                                maxScore > 0
                                    ? (s / maxScore) * 100 + '%'
                                    : '0%',
                        }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes floatUp {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-40px) scale(1.2);
    }
}

@keyframes popup-animate {
    0% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -150%) scale(1.2);
    }
}

.target {
    /* Efeito de click visual */
    animation: bounce-in 0.3s ease-out;
    user-select: none;
}

.target:active {
    transform: scale(0.85);
}

.cursor-pointer {
    cursor: pointer;
}

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

.game-area {
    background: rgba(0, 0, 0, 0.12);
    border-radius: 12px;
    min-height: 420px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.target {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 50%;
    transition: transform 0.08s, opacity 0.25s;
    user-select: none;
    font-weight: 700;
    border: 2px solid rgba(255, 255, 255, 0.25);
}
.target:hover {
    transform: scale(1.05);
}

.target.square {
    border-radius: 8px;
}
.target.star {
    clip-path: polygon(
        50% 0%,
        61% 35%,
        98% 35%,
        68% 57%,
        79% 91%,
        50% 70%,
        21% 91%,
        32% 57%,
        2% 35%,
        39% 35%
    );
    border-radius: 0;
}

.game-over-modal {
    background: rgba(0, 0, 0, 0.6);
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.points-popup {
    position: absolute;
    font-weight: 800;
    pointer-events: none;
    animation: floatUp 1s forwards;
    z-index: 50;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}
</style>
