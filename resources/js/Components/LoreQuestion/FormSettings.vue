<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    difficulty: "easy",
    questionQuant: 1,
});
const saveSettings = () => {
    form.post(route("lorequestion.roleplay"));
};
</script>

<template>
    <form @submit.prevent="saveSettings" class="container">
        <div class="mb-3 text-primary">
            <label class="form-label fw-semibold d-block text-capitalize">{{
                $t("mode_phrase")
            }}</label>

            <div class="form-check form-check-inline">
                <input
                    class="form-check-input"
                    type="radio"
                    name="difficulty"
                    id="difficultyEasy"
                    value="easy"
                    v-model="form.difficulty"
                />
                <label
                    class="form-check-label text-capitalize"
                    for="difficultyEasy"
                    >{{ $t("mode.one") }}</label
                >
            </div>

            <div class="form-check form-check-inline">
                <input
                    class="form-check-input"
                    type="radio"
                    name="difficulty"
                    id="difficultyMedium"
                    value="medium"
                    v-model="form.difficulty"
                />
                <label
                    class="form-check-label text-capitalize"
                    for="difficultyMedium"
                    >{{ $t("mode.two") }}</label
                >
            </div>

            <div class="form-check form-check-inline">
                <input
                    class="form-check-input"
                    type="radio"
                    name="difficulty"
                    id="difficultyHard"
                    value="hard"
                    v-model="form.difficulty"
                />
                <label
                    class="form-check-label text-capitalize"
                    for="difficultyHard"
                    >{{ $t("mode.three") }}</label
                >
            </div>
        </div>

        <div class="mb-3 text-primary">
            <label for="questionQuant" class="form-label fw-semibold">{{
                $t("quantity_phrase")
            }}</label>
            <input
                type="range"
                class="form-range"
                min="1"
                max="10"
                id="questionQuant"
                v-model="form.questionQuant"
                name="questionQuant"
                required
            />
            <div>
                <span>{{ form.questionQuant }} / 10</span>
            </div>
        </div>

        <div class="d-grid">
            <button
                type="submit"
                class="btn btn-accent text-white flex-grow-1 fw-bold rounded-3 shadow text-capitalize"
            >
                {{ $t("button_start") }}
            </button>
        </div>
    </form>
</template>
