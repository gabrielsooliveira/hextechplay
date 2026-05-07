<script setup>
import { Head, Link } from "@inertiajs/vue3";
import LayoutSite from "@/js/Layouts/LayoutSite.vue";

defineProps({
    players: Array
});
</script>

<template>
    <LayoutSite>
        <Head title="Jogadores" />

        <div class="container py-5">
            <header class="text-center mb-5">
                <h1 class="display-4 fw-bold text-light mb-3">Lendas de Runeterra</h1>
                <p class="lead text-white-50">Os jogadores que fazem a história do League of Legends.</p>
                <div class="accent-line mx-auto"></div>
            </header>

            <div class="row g-4">
                <div v-for="player in players" :key="player.id" class="col-md-6 col-lg-3">
                    <Link :href="route('players.show', player.id)" class="text-decoration-none">
                        <div class="player-card card h-100 border-0 bg-dark-glass overflow-hidden hover-zoom-card">
                            <div class="player-photo-wrapper">
                                <img v-if="player.photo" :src="player.photo" :alt="player.nickname" class="player-photo" />
                                <div v-else class="player-photo-placeholder">
                                    <font-awesome-icon icon="fas fa-user" />
                                </div>
                                <div class="player-role-badge">
                                    <span class="badge bg-accent">{{ player.role }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4 text-center">
                                <h3 class="h4 text-light fw-bold mb-1">{{ player.nickname }}</h3>
                                <p class="text-white-50 small mb-3">{{ player.name }}</p>
                                
                                <div v-if="player.team" class="team-mini d-flex align-items-center justify-content-center">
                                    <img :src="player.team.logo" :alt="player.team.name" class="team-logo-tiny me-2" />
                                    <span class="text-accent small fw-bold">{{ player.team.acronym }}</span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-if="players.length === 0" class="text-center py-5">
                <p class="text-white-50">Nenhum jogador cadastrado no momento.</p>
            </div>
        </div>
    </LayoutSite>
</template>

<style scoped>
.bg-dark-glass {
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s ease;
}
.hover-zoom-card:hover {
    transform: translateY(-10px);
    border-color: rgba(224, 146, 11, 0.4);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
}
.player-photo-wrapper {
    height: 250px;
    background: linear-gradient(180deg, rgba(224, 146, 11, 0.05) 0%, rgba(17, 24, 39, 1) 100%);
    position: relative;
    overflow: hidden;
}
.player-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
    transition: transform 0.5s ease;
}
.player-card:hover .player-photo {
    transform: scale(1.1);
}
.player-photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.05);
}
.player-role-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
}
.team-logo-tiny {
    width: 20px;
    height: 20px;
    object-fit: contain;
}
.accent-line {
    width: 60px;
    height: 4px;
    background: #e0920b;
    border-radius: 2px;
}
.bg-accent {
    background: #e0920b;
}
.text-accent {
    color: #e0920b;
}
</style>
