<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import ModalDialog from "@/js/Components/Modals/ModalDialog.vue";
import LoreQuestionForm from "@/js/Components/LoreQuestion/FormSettings.vue";
import ClickChallengerForm from "@/js/Components/ClickChallenger/FormSettings.vue";

const isModalVisible = ref(false);
const showGuide = ref(false);
const page = usePage();

const openModal = () => {
    isModalVisible.value = true;
};

const closeModal = () => {
    isModalVisible.value = false;
};

const activeForm = computed(() => {
    const currentRoute = page.props.currentRoute;
    if (currentRoute === "clickchallenger.index") {
        return "clickchallenger";
    }
    return "lorequestion";
});
</script>

<template>
    <Head>
        <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" />
        <meta
            property="og:url"
            content="https://hextechplay.com/lorequestion"
        />
        <link rel="canonical" href="https://hextechplay.com/lorequestion" />
    </Head>

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-3">
                {{ $t("page_title") }}
            </h1>
            <p class="lead text-light opacity-75">
                {{ $t("text_mode_game") }}
            </p>

            <div class="row g-4 mt-3 justify-content-center">
                <div class="col-md-4">
                    <div class="card text-bg-light h-100 shadow-lg border-0">
                        <div
                            class="card-body d-flex flex-column justify-content-between text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">Normal</h4>
                                <p class="card-text small">
                                    {{ $t("text_lorequestion_roleplay") }}
                                </p>
                            </div>
                            <button
                                @click="openModal"
                                class="btn btn-accent text-white mt-3 text-capitalize"
                            >
                                {{ $t("play") }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-bg-light h-100 shadow-lg border-0">
                        <div
                            class="card-body d-flex flex-column justify-content-between bg-gradient text-dark"
                        >
                            <div>
                                <h4 class="card-title fw-bold">
                                    {{ $t("competitive") }}
                                </h4>
                                <p class="card-text small">
                                    {{ $t("text_lorequestion_competitive") }}
                                </p>
                            </div>
                            <button class="btn btn-accent mt-3" disabled>
                                {{ $t("coming_soon") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="btn btn-sm btn-accent mt-5"
                @click="showGuide = true"
            >
                <font-awesome-icon icon="fas fa-question"></font-awesome-icon>
            </button>
        </div>
    </div>

    <ModalDialog :isVisible="showGuide" @close="showGuide = false">
        <div class="text-primary p-3">
            <h3>{{ $t("guide.title") }}</h3>
            <ul>
                <li>{{ $t("guide.item1") }}</li>
                <li>{{ $t("guide.item2") }}</li>
                <li>{{ $t("guide.item3") }}</li>
                <li>{{ $t("guide.item4") }}</li>
                <li>{{ $t("guide.item5") }}</li>
                <li>{{ $t("guide.item6") }}</li>
            </ul>
        </div>
    </ModalDialog>

    <ModalDialog
        :isVisible="isModalVisible"
        @close="closeModal"
        :title="$t('config_text')"
    >
        <ClickChallengerForm v-if="activeForm === 'clickchallenger'" />
        <LoreQuestionForm v-else />
    </ModalDialog>
</template>
