<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import CardGame from "@/js/Components/Cards/CardGame.vue";
import JsonLd from "@/js/Components/Seo/JsonLd.vue";
import HeroBackground from "@/assets/images/wallpaper.jpg";
import Ezreal from "@/assets/images/home-image-2.png";
import Logo from "@/assets/images/icon.png";
import LolQuestionBackground from "@/assets/images/lorequestion.png";
import WordLoLBackground from "@/assets/images/wordlol.png";
import ClickChallengerBackground from "@/assets/images/clickchallenger.png";

const stats = computed(() => [
    {
        value: "250",
        label: "Jogadores Ativos",
        icon: "users",
    },
    {
        value: "3",
        label: "Mini Games",
        icon: "gamepad",
    },
    {
        value: "850",
        label: "Partidas Jogadas",
        icon: "trophy",
    },
    {
        value: "90",
        label: "Índice de Satisfação",
        icon: "star",
        suffix: "%",
    },
]);

const featuredGames = computed(() => [
    {
        id: 1,
        title: "Perguntas de Runeterra",
        description: "Teste seus conhecimentos sobre o vasto universo de League of Legends com o nosso quiz sobre a Lore oficial.",
        image: LolQuestionBackground,
        route: "lorequestion.index",
        players: 2500,
        rating: 4.8,
    },
    {
        id: 2,
        title: "WordLoL",
        description: "Adivinhe nomes de locais, feitiços ou campeões de League of Legends com limite de tempo e dicas dinâmicas.",
        image: WordLoLBackground,
        route: "wordlol.game",
        players: 1800,
        rating: 4.6,
    },
    {
        id: 3,
        title: "Click Challenger",
        description: "Adivinhe nomes de locais, feitiços ou campeões de League of Legends com limite de tempo e dicas dinâmicas.",
        image: ClickChallengerBackground,
        route: "wordlol.game",
        players: 1800,
        rating: 4.6,
    },
]);

const recentAchievements = computed(() => [
    {
        id: 1,
        title: "Lendas de Runeterra",
        description: "Você dominou as questões da história do continente de Shurima",
        icon: "crown",
        rarity: "legendary",
        rarityColor: "gradient-legendary",
    },
    {
        id: 2,
        title: "Mais Rápido Que Ahri",
        description: "Você adivinhou a palavra misteriosa em menos de 10 segundos",
        icon: "bolt",
        rarity: "epic",
        rarityColor: "gradient-epic",
    },
    {
        id: 3,
        title: "Explorador Novato",
        description: "Você obteve sua primeira pontuação nas rankeadas",
        icon: "map",
        rarity: "rare",
        rarityColor: "gradient-rare",
    },
]);

const features = computed(() => [
    {
        icon: "gamepad",
        text: "Mini games 100% Gratuitos",
    },
    {
        icon: "globe",
        text: "Baseados no Universo do Lolzinho",
    },
    {
        icon: "bolt",
        text: "Atualizações Constantes",
    },
]);

</script>

<template>
    <Head>
        <title>Inicio</title>
        <meta name="description" content="HextechPlay - Jogue mini games rápidos e divertidos inspirados em League of Legends! Teste seus conhecimentos e desafie seus amigos no HextechPlay." />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seu conhecimento e divirta-se com jogos rápidos inspirados em Runeterra!" />
        <meta property="og:url" content="https://hextechplay.com" />
        <link rel="canonical" href="https://hextechplay.com" />
    </Head>

    <JsonLd :schema="{
        '@type': 'WebSite',
        'name': 'HextechPlay',
        'url': 'https://hextechplay.com',
        'description': 'HextechPlay - Jogue mini games rápidos e divertidos inspirados em League of Legends!',
        'potentialAction': {
            '@type': 'SearchAction',
            'target': 'https://hextechplay.com/search?q={search_term_string}',
            'query-input': 'required name=search_term_string'
        }
    }" />

    <section
        class="d-flex align-items-center justify-content-center hero-background-base min-vh-100"
        :style="{ '--hero-bg-url': `url(${HeroBackground})` }"
    >
        <div class="container text-center text-light">
            <img
                :src="Logo"
                class="img-fluid hover-zoom mb-4"
                width="280"
                alt="Logo oficial da plataforma HextechPlay de minigames do LoL"
            />

            <h1 class="display-1 fw-bold mb-4">
                Jogue e divirta-se na HextechPlay!
            </h1>

            <p class="hero-subtitle lead mb-5">
                Mini-games divertidos para desafiar seus amigos e explorar Runeterra.
            </p>

            <Link
                :href="route('games')"
                class="btn btn-accent btn-lg fw-semibold"
            >
                Jogar agora
            </Link>
        </div>
    </section>

    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <img
                    :src="Ezreal"
                    class="img-fluid hover-zoom"
                    alt="Ezreal de League of Legends, explorador e personagem dos minigames HextechPlay"
                    loading="lazy"
                />
            </div>
            <div class="col-lg-6 order-lg-1">
                <h2 class="display-5 fw-semibold mb-4">
                    O que é a HextechPlay?
                </h2>
                <p class="text-light opacity-75 lead mb-4">
                    HextechPlay é uma plataforma de mini-games rápida e divertida, inspirada no universo de LoL. Aqui você pode testar seu conhecimento, desafiar seus amigos e se diverte.
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
                    Começar Agora
                </Link>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">
                Jogos em Destaque
            </h2>
            <p class="text-light opacity-75 lead">
                Explore os mini-games mais populares da plataforma
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

    <!-- FAQ Section for SEO -->
    <section class="container py-5 mb-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="display-4 fw-bold mb-3">
                    Dúvidas Frequentes sobre a HextechPlay
                </h2>
                <p class="text-light opacity-75 lead">
                    Tudo o que você precisa saber sobre nossa plataforma de quizzes e minigames de League of Legends
                </p>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <h3 class="h4 text-accent fw-bold">Como funcionam os minigames de LoL?</h3>
                    <p class="text-light opacity-75">
                        Nossos minigames são rápidos e testam seus conhecimentos sobre o universo de Runeterra. Você pode jogar quizzes sobre a história (Lore), tentar adivinhar nomes no WordLoL, e competir para ver quem tem os reflexos mais rápidos no Click Challenger.
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="h4 text-accent fw-bold">É necessário pagar para jogar no HextechPlay?</h3>
                    <p class="text-light opacity-75">
                        Não! Todos os nossos minigames e quizzes focados em League of Legends são 100% gratuitos. Basta acessar, escolher o seu jogo favorito e começar a testar seus conhecimentos.
                    </p>
                </div>
                <div class="mb-4">
                    <h3 class="h4 text-accent fw-bold">A plataforma é atualizada com novos campeões?</h3>
                    <p class="text-light opacity-75">
                        Sim, a equipe da HextechPlay trabalha constantemente para incluir novos campeões, histórias e atualizações da Lore oficial de League of Legends em nossos minigames.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
