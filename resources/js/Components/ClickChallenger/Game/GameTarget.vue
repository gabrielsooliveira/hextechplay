<script setup>
import CoinImage from "@/assets/images/clickchallenger/coin.png";
import MushroomImage from "@/assets/images/clickchallenger/mushroom.png";

const props = defineProps({
    target: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="position-absolute rounded-circle shadow-sm d-flex align-items-center justify-content-center fw-bold text-white"
        :style="{
            left: target.x + 'px',
            top: target.y + 'px',
            width: target.size + 'px',
            height: target.size + 'px',
            backgroundColor: target.color,
            transition: 'transform 0.65s',
            cursor: 'pointer',
            zIndex: 10,
            border: target.type === 'red' ? '3px solid #ff7a7a' : 'none',
        }"
    >
        <img
            v-if="target.type === 'normal'"
            :src="CoinImage"
            class="imagem-not-selectable"
            alt="Gold Coin"
            :style="{
                width: '80%',
                height: '80%',
                objectFit: 'contain',
                WebkitUserDrag: 'none',
                MozUserDrag: 'none',
                OUserDrag: 'none',
                pointerEvents: 'none',
            }"
        />
        <img
            v-else
            :src="MushroomImage"
            class="imagem-not-selectable"
            alt="Teemo Mushroom"
            :style="{
                width: '80%',
                height: '80%',
                objectFit: 'contain',
                WebkitUserDrag: 'none',
                MozUserDrag: 'none',
                OUserDrag: 'none',
                pointerEvents: 'none',
            }"
        />
    </div>
</template>
