<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head>
        <title>ClickChallenger</title>
        <meta name="description" content="ClickChallenger é um jogo da plataforma HextechPlay que testa seus reflexos e suas habilidades." />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seus reflexos e precisão no ClickChallenger." />
        <meta property="og:url" content="https://hextechplay.com/games" />
        <link rel="canonical" href="https://hextechplay.com/games" />
    </Head>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <font-awesome-icon icon="fa-solid fa-ban" class="display-1" />
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <h2 class="mb-3">Página Não Encontrada</h2>
            <p class="text-muted mb-4">
                A página que você tentou acessar não existe ou foi movida.
            </p>

            <Link href="/" class="btn btn-primary px-4 py-2">
                <font-awesome-icon icon="fa-solid fa-house" class="me-2" />
                Voltar para Início
            </Link>
        </div>
    </div>
</template>
