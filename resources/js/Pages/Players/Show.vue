<script setup>
import { Head, Link } from "@inertiajs/vue3";
import LayoutSite from "@/js/Layouts/LayoutSite.vue";
import TitlesPanel from "@/js/Components/Profile/TitlesPanel.vue";

defineProps({
    player: Object
});
</script>

<template>
    <LayoutSite>
        <Head :title="player.nickname" />

        <div class="player-profile-header py-5 bg-dark">
            <div class="container mt-5">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-center mb-4 mb-lg-0">
                        <div class="player-photo-large-wrapper shadow-lg">
                            <img v-if="player.photo" :src="player.photo" :alt="player.nickname" class="img-fluid" />
                            <div v-else class="player-photo-placeholder-large">
                                <font-awesome-icon icon="fas fa-user" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 text-center text-lg-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3 gap-2">
                            <span class="badge bg-accent px-3 py-2">{{ player.role }}</span>
                            <span class="badge bg-dark-glass text-white-50 px-3 py-2 border border-white-10">{{ player.nationality }}</span>
                        </div>
                        <h1 class="display-2 fw-bold text-light mb-1">{{ player.nickname }}</h1>
                        <h2 class="h4 text-white-50 mb-4">{{ player.name }}</h2>
                        
                        <div v-if="player.team" class="mt-4 p-3 rounded bg-dark-glass d-inline-flex align-items-center border border-white-10">
                            <Link :href="route('teams.show', player.team.id)" class="text-decoration-none d-flex align-items-center">
                                <img :src="player.team.logo" :alt="player.team.name" class="team-logo-small me-3" />
                                <div>
                                    <div class="text-white-50 small">Atualmente em</div>
                                    <div class="text-accent fw-bold">{{ player.team.name }}</div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-12">
                    <TitlesPanel :titles="player.titles" title="Conquistas Individuais" />
                </div>
            </div>
        </div>
    </LayoutSite>
</template>

<style scoped>
.player-profile-header {
    background: radial-gradient(circle at top right, rgba(224, 146, 11, 0.1), transparent), 
                linear-gradient(180deg, #111827 0%, #0F172A 100%) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}
.player-photo-large-wrapper {
    width: 300px;
    height: 300px;
    margin: 0 auto;
    border-radius: 30px;
    overflow: hidden;
    background: #1f2937;
    border: 4px solid rgba(224, 146, 11, 0.2);
}
.player-photo-large-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
}
.player-photo-placeholder-large {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8rem;
    color: rgba(255, 255, 255, 0.05);
}
.bg-dark-glass {
    background: rgba(17, 24, 39, 0.6);
    backdrop-filter: blur(10px);
}
.border-white-10 {
    border-color: rgba(255, 255, 255, 0.1) !important;
}
.team-logo-small {
    width: 50px;
    height: 50px;
    object-fit: contain;
}
.bg-accent {
    background: #e0920b;
}
.text-accent {
    color: #e0920b;
}
</style>
