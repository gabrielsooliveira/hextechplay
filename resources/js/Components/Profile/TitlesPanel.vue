<script setup>
defineProps({
    titles: Array,
    title: {
        type: String,
        default: "Títulos e Conquistas"
    }
});
</script>

<template>
    <div class="titles-panel card h-100 border-accent-subtle bg-dark-glass p-4">
        <h4 class="mb-4 text-accent d-flex align-items-center">
            <font-awesome-icon icon="fas fa-trophy" class="me-3" />
            {{ title }}
        </h4>
        <div class="row g-3">
            <div v-for="title in titles" :key="title.id" class="col-md-6">
                <div class="title-item d-flex align-items-center p-3 rounded bg-dark-soft border border-white-10 hover-lift">
                    <div class="title-icon me-3 text-gold fs-3">
                        <font-awesome-icon icon="fas fa-award" />
                    </div>
                    <div>
                        <h6 class="mb-0 text-light fw-bold">{{ title.name }}</h6>
                        <small class="text-accent-dim">{{ title.year }}</small>
                        <p v-if="title.description" class="small text-white-50 mb-0 mt-1">{{ title.description }}</p>
                    </div>
                </div>
            </div>
            <div v-if="titles.length === 0" class="col-12 text-center py-5 opacity-50">
                <font-awesome-icon icon="fas fa-box-open" class="fs-1 mb-3" />
                <p>Nenhuma conquista registrada ainda.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-dark-glass {
    background: rgba(17, 24, 39, 0.7);
    backdrop-filter: blur(10px);
}
.bg-dark-soft {
    background: rgba(255, 255, 255, 0.03);
}
.border-accent-subtle {
    border: 1px solid rgba(224, 146, 11, 0.3);
}
.border-white-10 {
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.text-gold {
    color: #C89B3C;
}
.text-accent-dim {
    color: rgba(224, 146, 11, 0.8);
}
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.07);
    border-color: rgba(224, 146, 11, 0.5);
}
</style>
