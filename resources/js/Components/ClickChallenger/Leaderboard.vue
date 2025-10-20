<template>
    <div>
        <h5 class="mb-2">🏆 Placar de Líderes</h5>

        <div class="mb-2">
            <input
                v-model="playerName"
                class="form-control mb-2"
                placeholder="Digite seu nome"
                maxlength="20"
            />
            <button
                class="btn btn-success w-100"
                :disabled="!playerName"
                @click="save"
            >
                Salvar Pontuação
            </button>
        </div>

        <ul class="list-unstyled leaderboard-list" v-if="loading === false">
            <li
                v-for="(item, idx) in scores"
                :key="item.id"
                :class="[
                    'd-flex',
                    'align-items-center',
                    'p-2',
                    'rounded',
                    { 'current-player': item.id === currentPlayerId },
                ]"
            >
                <strong class="me-2">{{ idx + 1 }}º</strong>
                <div class="flex-fill">
                    <div>{{ item.name }}</div>
                    <small class="text-muted"
                        >Modo: {{ translateMode(item.mode) }}</small
                    >
                </div>
                <div class="ms-2">
                    <strong>{{ item.score }}</strong>
                </div>
            </li>
        </ul>

        <div v-else>Carregando...</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    highScore: Number,
    currentPlayerId: String,
});
const emit = defineEmits(["save-score"]);

const scores = ref([]);
const loading = ref(false);
const playerName = ref(localStorage.getItem("clickRushPlayerName") || "");

function translateMode(m) {
    return m === "classic"
        ? "Clássico"
        : m === "zen"
        ? "Zen"
        : m === "survival"
        ? "Sobrevivência"
        : "Diário";
}

async function load() {
    loading.value = true;
    try {
        const res = await axios.get("/api/scores?limit=40");
        scores.value = res.data.data || res.data || [];
    } catch (e) {
        console.warn("Erro carregando leaderboard", e);
    } finally {
        loading.value = false;
    }
}

async function save() {
    // emit to parent to actually call backend
    localStorage.setItem("clickRushPlayerName", playerName.value);
    emit("save-score", { name: playerName.value });
}

// public method
onMounted(() => load());

/* expose load to parent */
defineExpose({ load });
</script>

<style scoped>
.leaderboard-list li.current-player {
    background: rgba(255, 154, 158, 0.12);
    border: 1px solid rgba(255, 154, 158, 0.25);
}
.leaderboard-list li {
    margin-bottom: 8px;
    background: rgba(0, 0, 0, 0.06);
}
</style>
