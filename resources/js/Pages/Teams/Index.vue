<script setup>
import { Head, Link } from "@inertiajs/vue3";
import LayoutSite from "@/js/Layouts/LayoutSite.vue";

defineProps({
    teams: Array
});
</script>

<template>
    <LayoutSite>
        <Head title="Times" />

        <div class="container py-5">
            <header class="text-center mb-5">
                <h1 class="display-4 fw-bold text-light mb-3">Times de League of Legends</h1>
                <p class="lead text-white-50">Conheça as maiores organizações do cenário competitivo mundial.</p>
                <div class="accent-line mx-auto"></div>
            </header>

            <div class="row g-4">
                <div v-for="team in teams" :key="team.id" class="col-md-6 col-lg-4">
                    <Link :href="route('teams.show', team.id)" class="text-decoration-none">
                        <div class="team-card card h-100 border-0 bg-dark-glass overflow-hidden hover-zoom-card">
                            <div class="team-banner" :style="{ background: `linear-gradient(135deg, rgba(224, 146, 11, 0.1), rgba(17, 24, 39, 0.9))` }">
                                <img v-if="team.logo" :src="team.logo" :alt="team.name" class="team-logo-img shadow-lg" />
                                <div v-else class="team-logo-placeholder">
                                    {{ team.acronym }}
                                </div>
                            </div>
                            <div class="card-body p-4 text-center">
                                <h3 class="h4 text-light fw-bold mb-1">{{ team.name }}</h3>
                                <div class="badge bg-accent-soft text-accent mb-3">{{ team.region }}</div>
                                <p class="text-white-50 small mb-4 line-clamp-2">
                                    {{ team.description || 'Nenhuma descrição disponível para esta organização.' }}
                                </p>
                                <div class="d-flex justify-content-center gap-3">
                                    <div class="stat-item">
                                        <div class="stat-value text-light">{{ team.players_count }}</div>
                                        <div class="stat-label text-white-50 small">Jogadores</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 bg-transparent text-center pb-4">
                                <span class="btn btn-outline-accent btn-sm px-4 rounded-pill">Ver Perfil</span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-if="teams.length === 0" class="text-center py-5">
                <p class="text-white-50">Nenhum time cadastrado no momento.</p>
            </div>
        </div>
    </LayoutSite>
</template>

<style scoped>
.bg-dark-glass {
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.hover-zoom-card:hover {
    transform: translateY(-10px);
    border-color: rgba(224, 146, 11, 0.4);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
}
.team-banner {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.team-logo-img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    transition: transform 0.4s ease;
}
.team-card:hover .team-logo-img {
    transform: scale(1.1) rotate(5deg);
}
.team-logo-placeholder {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    color: rgba(255, 255, 255, 0.2);
}
.accent-line {
    width: 60px;
    height: 4px;
    background: #e0920b;
    border-radius: 2px;
}
.bg-accent-soft {
    background: rgba(224, 146, 11, 0.1);
}
.text-accent {
    color: #e0920b;
}
.btn-outline-accent {
    border-color: #e0920b;
    color: #e0920b;
}
.btn-outline-accent:hover {
    background: #e0920b;
    color: white;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
