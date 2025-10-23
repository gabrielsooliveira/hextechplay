<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import { useI18n } from "vue-i18n";

import CardGame from "@/js/Components/Cards/CardGame.vue";
import LolQuestionBackground from "@/assets/images/lorequestion.png";
import WordLoLBackground from "@/assets/images/wordlol.png";
import ClickChallengerBackground from "@/assets/images/clickchallenger.png";

const { t } = useI18n();

const games = ref([
    {
        id: 1,
        title: t("lorequestion_title"),
        description: t("lorequestion_desc"),
        image: LolQuestionBackground,
        route: "lorequestion.index",
        players: 2500,
        rating: 4.8,
    },
    {
        id: 2,
        title: t("wordlol_title"),
        description: t("wordlol_desc"),
        image: WordLoLBackground,
        route: "wordlol.game",
        players: 1800,
        rating: 4.6,
    },
    {
        id: 3,
        title: t("clickchallenger_title"),
        description: t("clickchallenger_desc"),
        image: ClickChallengerBackground,
        route: "clickchallenger.index",
        players: 5800,
        rating: 4.6,
    },
]);
</script>

<template>
    <Head>
        <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" />
        <meta property="og:url" content="https://hextechplay.com/games" />
        <link rel="canonical" href="https://hextechplay.com/games" />
    </Head>

    <section class="padding-navbar text-center container">
        <h1 class="display-3 fw-bold mb-3">
            {{ t("choose_game") }}
        </h1>
        <p class="lead text-light opacity-75">
            {{ t("choose_game_subtitle") }}
        </p>

        <div class="d-flex justify-content-center flex-wrap mt-4">
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon
                    icon="fas fa-gamepad"
                    class="text-accent me-1"
                ></font-awesome-icon>
                {{ games.length }} {{ t("games") }}
            </div>
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon
                    icon="fas fa-users"
                    class="text-accent me-1"
                ></font-awesome-icon>
                {{
                    games
                        .reduce((sum, g) => sum + g.players, 0)
                        .toLocaleString()
                }}
                {{ t("players") }}
            </div>
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon
                    icon="fas fa-star"
                    class="text-accent me-1"
                ></font-awesome-icon>
                {{
                    (
                        games.reduce((sum, g) => sum + g.rating, 0) /
                        games.length
                    ).toFixed(1)
                }}
                {{ t("rating") }}
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col-6">
                <h3 class="text-light mb-0">
                    {{ $t("all_games") }}
                </h3>
            </div>
        </div>
        <div class="row">
            <div
                class="col-lg-4 col-md-6 mb-4"
                v-for="game in games"
                :key="game.id"
            >
                <CardGame
                    :key="game.id"
                    :title="game.title"
                    :description="game.description"
                    :image="game.image"
                    :route="route(game.route)"
                    :players="game.players"
                    :rating="game.rating"
                />
            </div>
        </div>
    </section>
</template>
