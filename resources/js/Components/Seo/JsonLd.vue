<script setup>
import { computed } from "vue";

const props = defineProps({
    schema: {
        type: Object,
        required: true,
    },
});

const jsonLd = computed(() => {
    return JSON.stringify({
        "@context": "https://schema.org",
        ...props.schema,
    });
});
</script>

<template>
    <teleport to="head">
        <component :is="'script'" type="application/ld+json" v-html="jsonLd"></component>
    </teleport>
</template>
