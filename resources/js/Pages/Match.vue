<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const elos = [
    { name: 'Ferro 4', value: 10 },
    { name: 'Ferro 3', value: 15 },
    { name: 'Ferro 2', value: 20 },
    { name: 'Ferro 1', value: 25 },
    { name: 'Bronze 4', value: 30 },
    { name: 'Bronze 3', value: 35 },
    { name: 'Bronze 2', value: 40 },
    { name: 'Bronze 1', value: 45 },
    { name: 'Prata 4', value: 50 },
    { name: 'Prata 3', value: 55 },
    { name: 'Prata 2', value: 60 },
    { name: 'Prata 1', value: 65 },
    { name: 'Ouro 4', value: 70 },
    { name: 'Ouro 3', value: 75 },
    { name: 'Ouro 2', value: 80 },
    { name: 'Ouro 1', value: 85 },
    { name: 'Platina 4', value: 90 },
    { name: 'Platina 3', value: 95 },
    { name: 'Platina 2', value: 100 },
    { name: 'Platina 1', value: 105 },
    { name: 'Esmeralda 4', value: 110 },
    { name: 'Esmeralda 3', value: 120 },
    { name: 'Esmeralda 2', value: 130 },
    { name: 'Esmeralda 1', value: 140 },
    { name: 'Diamante 4', value: 150 },
    { name: 'Diamante 3', value: 160 },
    { name: 'Diamante 2', value: 170 },
    { name: 'Diamante 1', value: 180 },
    { name: 'Mestre', value: 250 },
    { name: 'Grão-Mestre', value: 350 },
    { name: 'Desafiante', value: 400 },
];

const players = ref(Array.from({ length: 10 }, (_, i) => ({
    name: `Jogador ${i + 1}`,
    elo: 10
})));

const loading = ref(false);
const matchedTeams = ref(null);
const errorMessage = ref('');

const generateMatch = async () => {
    loading.value = true;
    errorMessage.value = '';
    matchedTeams.value = null;

    try {
        const response = await axios.post('/match/generate', {
            players: players.value
        });
        matchedTeams.value = response.data;
    } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage.value = error.response.data.message;
        } else {
            errorMessage.value = "Ocorreu um erro ao gerar o emparelhamento.";
        }
    } finally {
        loading.value = false;
    }
};

const getEloName = (value) => {
    const elo = elos.find(e => e.value === value);
    return elo ? elo.name : 'Desconhecido';
};
</script>

<template>
    <Head>
        <title>Hextech Matchmaking</title>
    </Head>

    <div class="padding-navbar text-center container pb-5">
        <div class="container">
            <!-- HEADER -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-warning mb-2">MATCHMAKING</h1>
                <p class="lead text-secondary">Distribua as forças. Alcance o equilíbrio perfeito.</p>
            </div>

            <!-- ALERT ERROR -->
            <div v-if="errorMessage" class="alert alert-danger" role="alert">
                {{ errorMessage }}
            </div>

            <!-- PLAYERS FORM -->
            <div class="card bg-dark border-secondary shadow-lg mb-5">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-5">
                        <!-- BLOCK 1 -->
                        <div class="col-lg-6">
                            <div v-for="(player, index) in players.slice(0, 5)" :key="index" class="input-group mb-3">
                                <span class="input-group-text bg-dark text-secondary border-secondary">{{ index + 1 }}</span>
                                <input v-model="player.name" type="text" placeholder="Nome do Jogador" class="form-control bg-dark text-light border-secondary" />
                                <select v-model="player.elo" class="form-select bg-dark text-light border-secondary w-auto">
                                    <option v-for="elo in elos" :key="elo.value" :value="elo.value">{{ elo.name }}</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- BLOCK 2 -->
                        <div class="col-lg-6">
                            <div v-for="(player, index) in players.slice(5, 10)" :key="index + 5" class="input-group mb-3">
                                <span class="input-group-text bg-dark text-secondary border-secondary">{{ index + 6 }}</span>
                                <input v-model="player.name" type="text" placeholder="Nome do Jogador" class="form-control bg-dark text-light border-secondary" />
                                <select v-model="player.elo" class="form-select bg-dark text-light border-secondary w-auto">
                                    <option v-for="elo in elos" :key="elo.value" :value="elo.value">{{ elo.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button 
                            @click="generateMatch" 
                            :disabled="loading"
                            class="btn btn-warning btn-lg px-5 text-uppercase fw-bold shadow">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            {{ loading ? 'Calculando...' : 'FORJAR EQUIPES' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- RESULT MATCH -->
            <div v-if="matchedTeams" class="mt-5">
                <hr class="border-secondary my-5">
                <h2 class="text-center text-uppercase fw-bold text-light mb-4">Confronto Definido</h2>
                
                <div class="row g-4">
                    <!-- TIME AZUL -->
                    <div class="col-lg-6">
                        <div class="card border-primary bg-dark h-100 shadow">
                            <div class="card-header border-primary d-flex justify-content-between align-items-center py-3">
                                <h3 class="text-primary text-uppercase fw-bold m-0 flex-grow-1">
                                    🛡️ Time Azul
                                </h3>
                                <div class="text-end">
                                    <small class="text-primary text-uppercase d-block lh-1">Poder</small>
                                    <b class="fs-4">{{ matchedTeams.team1_elo }}</b>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li v-for="(player, idx) in matchedTeams.team1" :key="idx" class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ player.name }}</span>
                                    <span class="badge bg-primary rounded-pill p-2">{{ getEloName(Number(player.elo)) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- TIME VERMELHO -->
                    <div class="col-lg-6">
                        <div class="card border-danger bg-dark h-100 shadow">
                            <div class="card-header border-danger d-flex justify-content-between align-items-center py-3">
                                <h3 class="text-danger text-uppercase fw-bold m-0 flex-grow-1">
                                    ⚔️ Time Vermelho
                                </h3>
                                <div class="text-end">
                                    <small class="text-danger text-uppercase d-block lh-1">Poder</small>
                                    <b class="fs-4">{{ matchedTeams.team2_elo }}</b>
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li v-for="(player, idx) in matchedTeams.team2" :key="idx" class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ player.name }}</span>
                                    <span class="badge bg-danger rounded-pill p-2">{{ getEloName(Number(player.elo)) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="badge bg-secondary bg-opacity-50 text-light p-3 rounded-pill border border-secondary">
                        <span class="text-uppercase me-2">Desvio de Poder:</span>
                        <span class="text-warning fs-5 fw-bold">{{ matchedTeams.diff }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

