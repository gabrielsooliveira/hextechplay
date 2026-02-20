<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { useI18n } from "vue-i18n";

import CardInfo from "@/js/Components/Cards/CardInfo.vue";
import CardGame from "@/js/Components/Cards/CardGame.vue";
import HeroBackground from "@/assets/images/wallpaper.jpg";
import Ezreal from "@/assets/images/home-image-2.png";
import Logo from "@/assets/images/icon.png";
import LolQuestionBackground from "@/assets/images/lorequestion.png";
import WordLoLBackground from "@/assets/images/wordlol.png";

const { t } = useI18n();

const stats = computed(() => [
    {
        value: "250",
        label: t("stats.active_players"),
        icon: "users",
    },
    {
        value: "3",
        label: t("stats.mini_games"),
        icon: "gamepad",
    },
    {
        value: "850",
        label: t("stats.matches_played"),
        icon: "trophy",
    },
    {
        value: "90",
        label: t("stats.satisfaction"),
        icon: "star",
        suffix: "%",
    },
]);

const featuredGames = computed(() => [
    {
        id: 1,
        title: t("featured_games.lorequestion.title"),
        description: t("featured_games.lorequestion.description"),
        image: LolQuestionBackground,
        route: "lorequestion.index",
        players: 2500,
        rating: 4.8,
    },
    {
        id: 2,
        title: t("featured_games.wordlol.title"),
        description: t("featured_games.wordlol.description"),
        image: WordLoLBackground,
        route: "wordlol.game",
        players: 1800,
        rating: 4.6,
    },
]);

const recentAchievements = computed(() => [
    {
        id: 1,
        title: t("achievements.master_lore.title"),
        description: t("achievements.master_lore.description"),
        icon: "crown",
        rarity: "legendary",
        rarityColor: "gradient-legendary",
    },
    {
        id: 2,
        title: t("achievements.speedster.title"),
        description: t("achievements.speedster.description"),
        icon: "bolt",
        rarity: "epic",
        rarityColor: "gradient-epic",
    },
    {
        id: 3,
        title: t("achievements.explorer.title"),
        description: t("achievements.explorer.description"),
        icon: "map",
        rarity: "rare",
        rarityColor: "gradient-rare",
    },
]);

const features = computed(() => [
    {
        icon: "gamepad",
        text: t("home_feature_free_games"),
    },
    {
        icon: "globe",
        text: t("home_feature_lol_universe"),
    },
    {
        icon: "mobile-alt",
        text: t("home_feature_multiplatform"),
    },
    {
        icon: "bolt",
        text: t("home_feature_updates"),
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
        <meta property="og:url" content="https://hextechplay.com" />
        <link rel="canonical" href="https://hextechplay.com" />
    </Head>

    <section
        class="d-flex align-items-center justify-content-center hero-background-base min-vh-100"
        :style="{ '--hero-bg-url': `url(${HeroBackground})` }"
    >
        <div class="container text-center text-light">
            <img
                :src="Logo"
                class="img-fluid hover-zoom mb-4"
                width="280"
                alt="HextechPlay Logo"
            />

            <h1 class="display-1 fw-bold mb-4">
                {{ $t("play_phrase") }}
            </h1>

            <p class="hero-subtitle lead mb-5">
                {{ $t("play_phrase_sub") }}
            </p>

            <Link
                :href="route('games')"
                class="btn btn-accent btn-lg fw-semibold"
            >
                {{ $t("play_button") }}
            </Link>
        </div>
    </section>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">
                {{ $t("featured_games_title") }}
            </h2>
            <p class="text-light opacity-75 lead">
                {{ $t("featured_games_subtitle") }}
            </p>
        </div>
        <div class="row justify-content-center">
            <div
                class="col-lg-4 col-md-6 mb-4"
                v-for="game in featuredGames"
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

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">
                {{ $t("community_title") }}
            </h2>
            <p class="text-light opacity-75 lead">
                {{ $t("community_subtitle") }}
            </p>
        </div>
        <div class="row">
            <div
                class="col-lg-3 col-md-6 mb-4"
                v-for="stat in stats"
                :key="stat.label"
            >
                <CardInfo
                    :key="stat.label"
                    :icon="stat.icon"
                    :value="stat.value"
                    :label="stat.label"
                    :suffix="stat.suffix"
                />
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <img
                    :src="Ezreal"
                    class="img-fluid hover-zoom"
                    :alt="$t('home_about_image_alt')"
                    loading="lazy"
                />
            </div>
            <div class="col-lg-6 order-lg-1">
                <h3 class="display-5 fw-semibold mb-4">
                    {{ $t("home_about_title") }}
                </h3>
                <p class="text-light opacity-75 lead mb-4">
                    {{ $t("home_about_description") }}
                </p>
                <div class="features-list">
                    <div
                        class="feature-item d-flex align-items-center mb-3"
                        v-for="(feat, index) in features"
                        :key="index"
                    >
                        <div class="fs-5 me-3">
                            <font-awesome-icon
                                :icon="`fas fa-${feat.icon}`"
                                class="text-accent"
                            ></font-awesome-icon>
                        </div>
                        <span class="text-light">{{ feat.text }}</span>
                    </div>
                </div>
                <Link :href="route('games')" class="btn btn-accent btn-lg mt-3">
                    <font-awesome-icon
                        icon="fas fa-rocket"
                        class="me-2"
                    ></font-awesome-icon>
                    {{ $t("get_started_button") }}
                </Link>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row text-center mb-5">
            <h2 class="section-title display-4 fw-bold text-gradient mb-3">
                {{ $t("achievements_title") }}
            </h2>
            <p class="section-subtitle text-light opacity-75 lead">
                {{ $t("achievements_subtitle") }}
            </p>
        </div>
        <div class="row">
            <div
                class="col-lg-4 col-md-6 mb-4"
                v-for="achievement in recentAchievements"
                :key="achievement.id"
            >
                <CardInfo
                    :key="achievement.id"
                    :icon="achievement.icon"
                    :value="achievement.title"
                    :label="achievement.description"
                    :bagdeColor="achievement.rarityColor"
                    :bagdeText="
                        achievement.rarity === 'legendary'
                            ? $t('rarity_legendary')
                            : achievement.rarity === 'epic'
                            ? $t('rarity_epic')
                            : $t('rarity_rare')
                    "
                />
            </div>
        </div>
    </section>
</template>
