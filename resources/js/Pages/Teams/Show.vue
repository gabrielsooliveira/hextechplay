<script setup>
import { Head, Link } from "@inertiajs/vue3";
import LayoutSite from "@/js/Layouts/LayoutSite.vue";
import TitlesPanel from "@/js/Components/Profile/TitlesPanel.vue";

defineProps({
    team: Object
});
</script>

<template>
    <LayoutSite>
        <Head :title="team.name" />

        <div class="team-hero py-5" :style="{ background: `linear-gradient(rgba(17, 24, 39, 0.8), rgba(17, 24, 39, 1)), url(${team.logo}) center/cover fixed` }">
            <div class="container mt-5">
                <div class="row align-items-center">
                    <div class="col-lg-3 text-center text-lg-start mb-4 mb-lg-0">
                        <img :src="team.logo" :alt="team.name" class="img-fluid team-profile-logo shadow-lg" />
                    </div>
                    <div class="col-lg-9 text-center text-lg-start">
                        <div class="badge bg-accent-soft text-accent px-3 py-2 mb-3">{{ team.region }}</div>
                        <h1 class="display-3 fw-bold text-light mb-2">{{ team.name }}</h1>
                        <p class="lead text-white-50 mb-4">{{ team.description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-5">
                <!-- Players Section -->
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-4">
                        <h2 class="h3 text-light fw-bold mb-0">Line-up Atual</h2>
                        <div class="ms-3 flex-grow-1 border-bottom border-white-10"></div>
                    </div>

                    <div class="row g-4">
                        <div v-for="player in team.players" :key="player.id" class="col-md-6">
                            <Link :href="route('players.show', player.id)" class="text-decoration-none">
                                <div class="player-card card border-0 bg-dark-glass hover-lift h-100">
                                    <div class="card-body d-flex align-items-center p-3">
                                        <div class="player-photo-mini me-3">
                                            <img v-if="player.photo" :src="player.photo" :alt="player.nickname" />
                                            <div v-else class="player-placeholder-mini">{{ player.nickname.charAt(0) }}</div>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 text-light fw-bold">{{ player.nickname }}</h5>
                                            <div class="d-flex align-items-center">
                                                <span class="text-accent small fw-semibold me-2">{{ player.role }}</span>
                                                <span class="text-white-50 small">{{ player.name }}</span>
                                            </div>
                                        </div>
                                        <div class="ms-auto text-white-50">
                                            <font-awesome-icon icon="fas fa-chevron-right" />
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Sidebar / Titles -->
                <div class="col-lg-4">
                    <TitlesPanel :titles="team.titles" title="Conquistas do Time" />
                </div>
            </div>
        </div>
    </LayoutSite>
</template>

<style scoped>
.team-hero {
    min-height: 400px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(224, 146, 11, 0.2);
}
.team-profile-logo {
    width: 180px;
    height: 180px;
    object-fit: contain;
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 20px;
}
.bg-dark-glass {
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
}
.player-photo-mini {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
}
.player-photo-mini img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.player-placeholder-mini {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: rgba(255, 255, 255, 0.2);
}
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateX(10px);
    border-color: rgba(224, 146, 11, 0.3);
    background: rgba(255, 255, 255, 0.08);
}
.bg-accent-soft {
    background: rgba(224, 146, 11, 0.1);
}
.text-accent {
    color: #e0920b;
}
.border-white-10 {
    border-color: rgba(255, 255, 255, 0.1) !important;
}
</style>
