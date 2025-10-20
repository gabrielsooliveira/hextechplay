<template>
    <div
        class="game-over-modal position-fixed inset-0 d-flex align-items-center justify-content-center"
        style="z-index: 1050"
    >
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-dark text-white p-3 rounded">
                <div class="text-center">
                    <h2 class="mb-2 text-warning">FIM DE JOGO!</h2>
                    <div class="final-score">
                        Pontuação: <strong>{{ score }}</strong>
                    </div>
                    <div class="final-high">
                        Recorde: <strong>{{ highScore }}</strong>
                    </div>
                    <button
                        class="btn btn-success mt-3"
                        @click="$emit('play-again')"
                    >
                        Jogar Novamente
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    score: Number,
    highScore: Number,
});
</script>

<style scoped>
.game-over-modal {
    background: rgba(0, 0, 0, 0.6);
}
</style>
