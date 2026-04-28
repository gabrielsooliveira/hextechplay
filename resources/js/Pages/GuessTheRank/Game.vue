<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import RankBackground from "@/assets/images/lorequestion.png"; // Using existing as placeholder

const props = defineProps({
    difficulty: String,
    questions: Number,
});

const currentQuestion = ref(1);
const gameFinished = ref(false);
const score = ref(0);

const ranks = [
    "Ferro", "Bronze", "Prata", "Ouro", "Platina", "Esmeralda", "Diamante", "Mestre", "Grão-Mestre", "Desafiante"
];

function selectRank(rank) {
    if (currentQuestion.value < props.questions) {
        currentQuestion.value++;
        score.value += Math.floor(Math.random() * 10); // Simulated scoring
    } else {
        gameFinished.value = true;
    }
}

</script>

<template>
    <Head>
        <title>Guess the Rank</title>
        <meta name="description" content="Adivinhe o elo dos jogadores em jogadas épicas de League of Legends!" />
    </Head>

    <div class="game-background-overlay" :style="{ backgroundImage: `url(${RankBackground})` }"></div>

    <div class="d-flex justify-content-center align-items-center py-5 min-vh-100">
        <div class="container text-center">
            <div v-if="!gameFinished" class="glass-panel shadow-lg p-4 animate-fade">
                <h2 class="game-title mb-4">Guess the Rank</h2>
                <p class="lead text-light mb-4">Qual é o elo dessa jogada? (Questão {{ currentQuestion }} de {{ questions }})</p>
                
                <div class="mb-5 bg-dark rounded shadow-lg overflow-hidden mx-auto" style="max-width: 700px; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center;">
                    <div class="text-secondary">
                         <font-awesome-icon icon="fas fa-play-circle" size="5x" class="opacity-50" />
                         <p class="mt-2">Simulação de vídeo da jogada...</p>
                    </div>
                </div>

                <div class="row g-2 justify-content-center">
                    <div v-for="rank in ranks" :key="rank" class="col-6 col-md-3">
                        <button @click="selectRank(rank)" class="btn game-btn w-100 py-2">
                            {{ rank }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="glass-panel shadow-lg p-4 animate-fade">
                <h2 class="game-title mb-4">Fim de Jogo!</h2>
                <p class="fs-3 text-light">Sua pontuação final: <span class="text-warning fw-bold">{{ score }}</span></p>
                
                <div class="mt-5 d-flex flex-column flex-md-row justify-content-center gap-3">
                    <Link :href="route('guesstherank.index')" class="btn game-btn btn-lg px-5">
                        Jogar Novamente
                    </Link>
                    <Link :href="route('games')" class="btn game-btn-primary btn-lg px-5">
                        Voltar para os Jogos
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
