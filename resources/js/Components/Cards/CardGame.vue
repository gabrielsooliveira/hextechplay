<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    title: { type: String, required: true },
    description: { type: String, required: true },
    image: { type: String, required: true },
    route: { type: String, required: true },
    players: { type: Number, required: true },
    rating: { type: Number, required: true },
});
</script>

<template>
    <div class="card card-game h-100">
        <div class="card-img-wrapper position-relative">
            <img
                :src="image"
                class="card-img-top"
                :alt="title"
                loading="lazy"
            />
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ title }}</span>
            </div>
            <p class="card-text text-light text-truncate opacity-75 mb-3">
                {{ description }}
            </p>
            <div class="d-flex justify-content-between align-items-center">
                <Link :href="route" class="btn btn-sm btn-accent">
                    <font-awesome-icon icon="fas fa-play" />
                    Jogar
                </Link>
            </div>
        </div>
    </div>
</template>
