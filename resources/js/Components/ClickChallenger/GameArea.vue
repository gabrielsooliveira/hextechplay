<template>
    <div
        ref="areaRef"
        class="game-area position-relative rounded"
        :style="{ minHeight: '420px' }"
    >
        <!-- targets are rendered dynamically as absolutely positioned divs -->
        <div
            v-for="t in targets"
            :key="t.id"
            class="target"
            :class="[t.shapeClass, { mini: t.mini }]"
            :style="{
                left: t.x + 'px',
                top: t.y + 'px',
                width: t.size + 'px',
                height: t.size + 'px',
                background: t.color,
                color: '#111',
            }"
            @click="onTargetClick(t)"
        >
            <font-awesome-icon :icon="t.iconFa" v-if="t.iconFa" />
            <span v-else>{{ t.iconText }}</span>
        </div>

        <!-- points popups -->
        <div
            v-for="p in popups"
            :key="p.id"
            class="points-popup"
            :style="{ left: p.left + 'px', top: p.top + 'px', color: p.color }"
        >
            {{ p.text }}
        </div>

        <!-- Game overlay (game over) handled by parent -->
    </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted, onUnmounted } from "vue";

/* props & emits */
const props = defineProps({
    customization: { type: Object, required: true },
    mode: { type: String, default: "classic" },
    gameActive: { type: Boolean, default: false },
    gamePaused: { type: Boolean, default: false },
});
const emit = defineEmits(["hit", "miss", "spawned"]);

/* local refs */
const areaRef = ref(null);

/* state */
let nextTargetId = 1;
const targets = reactive([]);
const popups = reactive([]);
let spawnIntervalId = null;

/* target types (copied & trimmed) */
const baseTargetTypes = [
    {
        id: "pink",
        color: props.customization.colors.pink,
        points: 3,
        size: 70,
        duration: 2500,
        weight: 35,
    },
    {
        id: "blue",
        color: props.customization.colors.blue,
        points: 5,
        size: 60,
        duration: 2000,
        weight: 30,
    },
    {
        id: "green",
        color: props.customization.colors.green,
        points: 10,
        size: 50,
        duration: 1500,
        weight: 20,
    },
    {
        id: "gold",
        color: props.customization.colors.gold,
        points: 15,
        size: 62,
        duration: 1800,
        weight: 4,
        effect: "doublePoints",
        iconText: "2x",
    },
    {
        id: "violet",
        color: props.customization.colors.violet,
        points: 5,
        size: 66,
        duration: 2000,
        weight: 3,
        effect: "slowMotion",
        iconText: "⏱️",
    },
    {
        id: "multi",
        color: props.customization.colors.multi,
        points: 5,
        size: 64,
        duration: 1600,
        weight: 4,
        effect: "multiHit",
        iconText: "✨",
    },
    {
        id: "red",
        color: "#FF3B30",
        points: -10,
        size: 62,
        duration: 1700,
        weight: 4,
        iconText: "⚠️",
    },
    {
        id: "moving",
        color: "#FFA500",
        points: 7,
        size: 58,
        duration: 2200,
        weight: 6,
        effect: "moving",
        iconText: "🌀",
    },
    {
        id: "explosive",
        color: "#00FFFF",
        points: 20,
        size: 64,
        duration: 900,
        weight: 4,
        effect: "explosive",
        iconText: "💥",
    },
];

function pickWeighted(arr, rng = Math.random) {
    const total = arr.reduce((s, it) => s + it.weight, 0);
    let r = rng() * total;
    let i = 0;
    while (i < arr.length && (r -= arr[i].weight) > 0) i++;
    return arr[Math.max(0, i)];
}

/* behavior: create target */
function createTarget() {
    if (!props.gameActive || props.gamePaused) return;
    const area = areaRef.value;
    if (!area) return;

    const tdef = pickWeighted(baseTargetTypes);
    const size = tdef.size;
    const maxX = Math.max(0, area.offsetWidth - size);
    const maxY = Math.max(0, area.offsetHeight - size);
    const x = Math.floor(Math.random() * maxX);
    const y = Math.floor(Math.random() * maxY);

    const t = {
        id: nextTargetId++,
        typeId: tdef.id,
        color: tdef.color,
        points: tdef.points,
        size,
        x,
        y,
        createdAt: Date.now(),
        duration: tdef.duration,
        effect: tdef.effect || null,
        mini: tdef.id === "explosive",
        shapeClass:
            props.customization.shape !== "circle"
                ? props.customization.shape
                : "",
        iconFa: null,
        iconText: tdef.iconText || "",
    };
    targets.push(t);
    emit("spawned");
    // set remove timer
    setTimeout(() => {
        // find and remove if still present
        const idx = targets.findIndex((tt) => tt.id === t.id);
        if (idx !== -1) {
            // if explosive: call explode
            if (t.effect === "explosive") {
                explodeTarget(t);
            } else {
                targets.splice(idx, 1);
                emit("miss");
            }
        }
    }, Math.max(300, t.duration));
}

/* hit handling */
function onTargetClick(t) {
    if (!props.gameActive || props.gamePaused) return;
    const idx = targets.findIndex((tt) => tt.id === t.id);
    if (idx === -1) return;
    // reaction time
    const reaction = Date.now() - t.createdAt;
    // remove target
    targets.splice(idx, 1);
    // handle effects
    if (t.effect) {
        activatePowerUp(t.effect);
    }
    // emit hit with payload
    emit("hit", { points: t.points, type: t.typeId, reactionTime: reaction });
    // popup
    createPointsPopup({
        left: t.x + t.size / 2,
        top: t.y + t.size / 2,
        text: (t.points > 0 ? "+" : "") + t.points,
    });
}

/* basic explosion (spawn 3 minis) */
function explodeTarget(t) {
    const area = areaRef.value;
    const baseLeft = t.x;
    const baseTop = t.y;
    for (let i = 0; i < 3; i++) {
        const mini = {
            id: nextTargetId++,
            typeId: "mini-expl-" + i,
            color: "#00FFFF",
            points: 6,
            size: 30,
            x: Math.min(
                area.offsetWidth - 30,
                Math.max(0, baseLeft + (i - 1) * 25 + (Math.random() * 30 - 15))
            ),
            y: Math.min(
                area.offsetHeight - 30,
                Math.max(0, baseTop + (i - 1) * 25 + (Math.random() * 30 - 15))
            ),
            createdAt: Date.now(),
            duration: 1200 + i * 200,
            effect: null,
            mini: true,
            shapeClass:
                props.customization.shape !== "circle"
                    ? props.customization.shape
                    : "",
            iconText: "💥",
        };
        targets.push(mini);
        setTimeout(() => {
            const idx = targets.findIndex((tt) => tt.id === mini.id);
            if (idx !== -1) {
                targets.splice(idx, 1);
                emit("miss");
            }
        }, mini.duration);
    }
}

/* powerup placeholder */
function activatePowerUp(effect) {
    // emit a hit with special achievement or do local behavior
    // for simplicity just show toast via emit
    emit("hit", { points: 0, type: "powerup", achievement: effect });
}

/* popups */
function createPointsPopup({ left, top, text }) {
    const id = "p" + Date.now() + Math.random();
    const color = text.includes("-") ? "#FF3B30" : "#a1c4fd";
    const entry = { id, left, top, text, color };
    popups.push(entry);
    setTimeout(() => {
        const idx = popups.findIndex((p) => p.id === id);
        if (idx !== -1) popups.splice(idx, 1);
    }, 900);
}

/* spawn control */
function start(mode = "classic") {
    stop();
    // simpler spawn rate logic:
    let spawnRate = 800;
    if (mode === "zen") spawnRate = 1000;
    spawnIntervalId = setInterval(() => {
        createTarget();
    }, spawnRate);
}

function stop() {
    if (spawnIntervalId) {
        clearInterval(spawnIntervalId);
        spawnIntervalId = null;
    }
    targets.splice(0, targets.length);
}

function pause() {
    // stop spawning but keep current targets
    if (spawnIntervalId) clearInterval(spawnIntervalId);
    spawnIntervalId = null;
}

function resume() {
    if (!spawnIntervalId) start(props.mode);
}

/* watchers */
watch(
    () => props.gameActive,
    (v) => {
        if (v) start(props.mode);
        else stop();
    }
);

onUnmounted(() => {
    stop();
});

onMounted(() => {
    // adjust target colors if customization changes (watch could be added)
});
</script>

<style lang="scss" scoped>
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

.points-popup {
    position: absolute;
    font-weight: 800;
    pointer-events: none;
    animation: floatUp 1s forwards;
    z-index: 50;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

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
</style>
