<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import CardGame from "@/js/Components/Cards/CardGame.vue";
import JsonLd from "@/js/Components/Seo/JsonLd.vue";
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

    <JsonLd :schema="{
        '@type': 'CollectionPage',
        'name': 'Jogos e Minigames de LoL - HextechPlay',
        'url': 'https://hextechplay.com/games',
        'description': 'Escolha entre nossos diversos minigames inspirados em League of Legends. Jogue quizzes, wordlol e muito mais.'
    }" />

    <section class="padding-navbar text-center container">
        <h1 class="display-3 fw-bold mb-3">
            Escolha seu Jogo
        </h1>
        <p class="lead text-light opacity-75">
            Diversão rápida e desafiadora te espera. Jogue agora e divirta-se com os clássicos!
        </p>
    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4" v-for="game in games" :key="game.id">
                <CardGame :key="game.id" :title="game.title" :description="game.description" :image="game.image" :route="route(game.route)" :players="game.players" :rating="game.rating" />
            </div>
        </div>
    </section>

    <!-- SEO Content Section -->
    <section class="container py-5 border-top border-secondary mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-light opacity-75">
                <h2 class="h3 fw-bold text-accent mb-3">Descubra os Melhores Minigames de League of Legends</h2>
                <p class="mb-4">
                    Na HextechPlay, nós reunimos a paixão pelo universo de Runeterra com desafios rápidos que você pode jogar a qualquer momento. Se você é um fã assíduo da Lore de League of Legends, nossos jogos foram feitos sob medida para você testar seus conhecimentos e reflexos.
                </p>

                <h3 class="h4 fw-semibold text-white mb-3">Como os Minigames ajudam você a conhecer mais de Runeterra?</h3>
                <p class="mb-4">
                    Ao jogar nossas modalidades, você explora detalhes fascinantes da história dos campeões e das regiões do mapa, desde as areias de Shurima até o gelo de Freljord. Nossos jogos como o LoreQuestion desafiam a sua memória sobre eventos canônicos, enquanto o WordLoL exercita sua capacidade de dedução de nomes e feitiços. E se quiser testar sua mecânica, o Click Challenger é ideal para simular o "kiting" e aumentar o seu APM (Ações por Minuto).
                </p>
            </div>
        </div>
    </section>
</template>
