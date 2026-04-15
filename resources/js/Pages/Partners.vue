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

    <div class="container padding-navbar text-center">
        <h1 class="display-3 fw-bold mb-3 fade-in">
            Parceiros
        </h1>
        <p class="lead text-light opacity-75 fade-in delay-200">
            Nossos parceiros que ajudam o HextechPlay a crescer, promovendo nossos jogos e conectando mais jogadores à nossa comunidade. Agradecemos a todos que compartilham sua paixão e nos incentivam a continuar evoluindo!
        </p>

        <div class="row g-4">
            <div
                v-for="partner in partners"
                :key="partner.id"
                class="col-12 col-sm-6 col-md-4 col-lg-3"
            >
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body">
                        <img
                            :src="
                                partner.avatar ||
                                'https://dummyimage.com/600x400/000/fff'
                            "
                            alt="Avatar"
                            class="rounded-circle mb-3 object-fit-cover"
                            style="width: 80px; height: 80px"
                        />

                        <h5 class="card-title mb-1">{{ partner.name }}</h5>
                        <p class="text-muted small mb-2">
                            {{ partner.platform }}
                        </p>

                        <p class="card-text small text-secondary mb-3">
                            {{
                                partner.description ||
                                "Parceiro do HextechPlay!"
                            }}
                        </p>

                        <a
                            :href="partner.link"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Visitar Canal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
