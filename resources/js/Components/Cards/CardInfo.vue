<script setup>
defineProps({
    icon: { type: String, required: true },
    value: { type: String, required: true },
    label: { type: String, required: true },
    suffix: { type: String, default: "" },
    borderColor: { type: String, default: "primary" },
    bagdeColor: { type: String, default: "primary" },
    bagdeText: { type: String, default: "" },
});
</script>

<template>
    <div class="card text-center h-100" :class="`border-${borderColor}`">
        <div class="card-body">
            <div class="mb-3">
                <font-awesome-icon
                    :icon="`fas fa-${icon}`"
                    class="text-accent fs-4"
                />
            </div>
            <div class="text-gradient fw-bold mb-2">
                {{ value }} {{ suffix }}
            </div>
            <div class="text-light">
                {{ label }}
            </div>
            <span
                v-if="bagdeText"
                class="mt-3"
                :class="`badge text-bg-${bagdeColor}`"
            >
                {{ bagdeText }}
            </span>
        </div>
    </div>
</template>
