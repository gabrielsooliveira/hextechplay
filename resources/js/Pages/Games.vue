<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import CardGame from "@/js/Components/Cards/CardGame.vue";
import LolQuestionBackground from "@/assets/images/lorequestion.png";
import WordLoLBackground from "@/assets/images/wordlol.png";
import ClickChallengerBackground from "@/assets/images/clickchallenger.png";

const games = computed(() => [
    {
        id: 1,
        title: "LoreQuestions",
        description: "Teste seus conhecimentos sobre a rica história e o vasto universo de League of Legends com o nosso Lore Quiz oficial. Desafie-se em nível mundial para ser o maior pesquisador de Runeterra.",
        image: LolQuestionBackground,
        route: "lorequestion.index",
        players: 2500,
        rating: 4.8,
    },
    {
        id: 2,
        title: "WordLoL",
        description: "Adivinhe nomes de locais, feitiços ou campeões de League of Legends antes que as chances de acertar ou tempo acabe.",
        image: WordLoLBackground,
        route: "wordlol.game",
        players: 1800,
        rating: 4.6,
    },
    {
        id: 3,
        title: "Click Challenger",
        description: "Jogue o click challenger e teste sua velocidade perante uma horda de minions na sua lane ou no deserto de shurima.",
        image: ClickChallengerBackground,
        route: "clickchallenger.index",
        players: 5800,
        rating: 4.6,
    },
]);

</script>

<template>
    <Head>
        <title>Jogos</title>
        <meta name="description" content="HextechPlay - plataforma de mini-games inspirada no universo de League of Legends – jogue, divirta-se e desafie seus amigos!" />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seus conhecimentos e divirta-se com jogos rápidos inspirados em Runeterra!" />
        <meta property="og:url" content="https://hextechplay.com/games" />
        <link rel="canonical" href="https://hextechplay.com/games" />
    </Head>

    <section class="padding-navbar text-center container">
        <h1 class="display-3 fw-bold mb-3">
            Escolha seu Jogo
        </h1>
        <p class="lead text-light opacity-75">
            Diversão rápida e desafiadora te espera. Jogue agora e divirta-se com os clássicos!
        </p>

        <div class="d-flex justify-content-center flex-wrap mt-4">
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon
                    icon="fas fa-gamepad"
                    class="text-accent me-1"
                ></font-awesome-icon>
                {{ games.length }} Jogos
            </div>
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon icon="fas fa-users" class="text-accent me-1"></font-awesome-icon>
                {{ games.reduce((sum, g) => sum + g.players, 0).toLocaleString() }}
                Jogadores Diários
            </div>
            <div class="stat-badge me-2 mb-2">
                <font-awesome-icon icon="fas fa-star" class="text-accent me-1"></font-awesome-icon>
                {{ (games.reduce((sum, g) => sum + g.rating, 0) / games.length).toFixed(1) }}
                Média de Avaliação
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" v-for="game in games" :key="game.id">
                <CardGame :key="game.id" :title="game.title" :description="game.description" :image="game.image" :route="route(game.route)" :players="game.players" :rating="game.rating" />
            </div>
        </div>
    </section>
</template>
