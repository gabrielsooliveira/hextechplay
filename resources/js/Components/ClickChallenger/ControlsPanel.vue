<template>
    <div class="controls d-flex gap-2 justify-content-center mt-2">
        <button
            class="btn btn-primary"
            :disabled="gameActive"
            @click="$emit('start')"
        >
            <font-awesome-icon icon="fa fa-play" /> Iniciar Jogo
        </button>

        <button
            class="btn btn-outline-light"
            :disabled="!gameActive"
            @click="$emit('pause')"
        >
            <font-awesome-icon
                :icon="gamePaused ? 'fa fa-play' : 'fa fa-pause'"
            />
            <span v-if="gamePaused"> Continuar</span>
            <span v-else> Pausar</span>
        </button>
    </div>
</template>

<script setup>
const props = defineProps({
    gameActive: Boolean,
    gamePaused: Boolean,
});
</script>

<style scoped>
.controls .btn {
    border-radius: 999px;
    font-weight: 700;
}
</style>
