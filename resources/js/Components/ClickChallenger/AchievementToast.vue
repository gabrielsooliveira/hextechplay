<template>
    <div
        v-show="visible"
        class="achievement-badge position-fixed bottom-0 start-0 m-3 p-2 rounded bg-card d-flex align-items-center"
        style="z-index: 2000"
    >
        <i class="fa fa-trophy me-2"></i>
        <span>{{ text }}</span>
    </div>
</template>

<script setup>
import { ref } from "vue";
const visible = ref(false);
const text = ref("");

function show(t) {
    text.value = t;
    visible.value = true;
    setTimeout(() => (visible.value = false), 3000);
}

defineExpose({ show });
</script>

<style scoped>
.bg-card {
    background: rgba(0, 0, 0, 0.14);
    border: 1px solid rgba(255, 215, 0, 0.12);
    color: #ffd700;
}
</style>
