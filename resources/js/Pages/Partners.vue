<script setup>
import { Head } from "@inertiajs/vue3";

// const props = defineProps({
//   partners: Array
// })

const partners = [
    //   {
    //     id: 1,
    //     name: "StreamerNami",
    //     platform: "Twitch",
    //     link: "https://twitch.tv/streamernami",
    //     avatar: "https://dummyimage.com/600x400/000/fff",
    //     description: "Gameplay divertida e muito amor pelo LoL!",
    //     featured: true
    //   }
];
</script>

<template>
    <Head>
        <title>Parceiros</title>
        <meta name="description" content="Nossos parceiros que ajudam o HextechPlay a crescer, promovendo nossos jogos e conectando mais jogadores à nossa comunidade. Agradecemos a todos que compartilham sua paixão e nos incentivam a continuar evoluindo!" />
        <meta name="keywords" content="HextechPlay, LoL mini games, online games, League of Legends quiz, runeterra, fun, quick games" />
        <meta property="og:title" content="HextechPlay – Mini Games and League of Legends Quizzes" />
        <meta property="og:description" content="Test your knowledge and have fun with fast-paced games inspired by Runeterra!" />
        <meta property="og:url" content="https://hextechplay.com" />
        <link rel="canonical" href="https://hextechplay.com" />
    </Head>

    <div class="container padding-navbar text-center min-vh-100">
        <h1 class="display-3 fw-bold mb-3 fade-in game-title">
            Parceiros
        </h1>
        <p class="lead text-light opacity-75 fade-in delay-200 mb-5 max-w-75 mx-auto">
            Nossos parceiros que ajudam o HextechPlay a crescer, promovendo nossos jogos e conectando mais jogadores à nossa comunidade. Agradecemos a todos que compartilham sua paixão e nos incentivam a continuar evoluindo!
        </p>

        <div class="row g-4 justify-content-center">
            <div
                v-for="partner in partners"
                :key="partner.id"
                class="col-12 col-sm-6 col-md-4 col-lg-3"
            >
                <div class="glass-panel h-100 text-center p-3 p-md-4 d-flex flex-column transition hover-scale">
                    <div class="mb-auto">
                        <img
                            :src="
                                partner.avatar ||
                                'https://dummyimage.com/600x400/000/fff'
                            "
                            alt="Avatar"
                            class="rounded-circle mb-3 object-fit-cover border border-warning border-opacity-50 shadow"
                            style="width: 80px; height: 80px"
                        />

                        <h5 class="fw-bold text-white mb-1">{{ partner.name }}</h5>
                        <p class="text-warning small mb-2 fw-semibold text-uppercase">
                            {{ partner.platform }}
                        </p>

                        <p class="small text-light opacity-75 mb-4">
                            {{
                                partner.description ||
                                "Parceiro do HextechPlay!"
                            }}
                        </p>
                    </div>

                    <a
                        :href="partner.link"
                        target="_blank"
                        class="btn game-btn py-2 w-100 mt-3"
                    >
                        Visitar Canal
                    </a>
                </div>
            </div>

            <!-- Empty state fallback when there are no partners -->
            <div v-if="partners.length === 0" class="col-12 text-center py-5">
                <div class="glass-panel p-5 mx-auto" style="max-width: 600px;">
                    <font-awesome-icon icon="fas fa-handshake" class="text-warning fs-1 mb-3 opacity-75" />
                    <h4 class="fw-bold text-white mb-2">Seja nosso primeiro parceiro!</h4>
                    <p class="text-light opacity-75 mb-4">Estamos em busca de criadores de conteúdo apaixonados pelo universo de Runeterra.</p>
                    <a href="/contact" class="btn game-btn py-2 px-4">
                        Entrar em Contato
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
