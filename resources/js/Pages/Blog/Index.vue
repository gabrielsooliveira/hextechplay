<script setup>
import { Head, Link } from "@inertiajs/vue3";
import JsonLd from "@/js/Components/Seo/JsonLd.vue";

defineProps({
    posts: Object
});
</script>

<template>
    <Head>
        <title>Blog de LoL e Minigames</title>
        <meta name="description" content="Leia os melhores artigos sobre a história de League of Legends, dicas de quizzes e novidades dos nossos minigames." />
        <link rel="canonical" href="https://hextechplay.com/blog" />
    </Head>

    <JsonLd :schema="{
        '@type': 'Blog',
        'name': 'HextechPlay Blog',
        'url': 'https://hextechplay.com/blog',
        'description': 'Blog da HextechPlay focado no universo de League of Legends.'
    }" />

    <section class="padding-navbar text-center container">
        <h1 class="display-3 fw-bold mb-3">Blog de Runeterra</h1>
        <p class="lead text-light opacity-75">
            Acompanhe as últimas novidades, guias e segredos da lore de League of Legends.
        </p>
    </section>

    <section class="container py-5">
        <div class="row">
            <div class="col-md-6 mb-4" v-for="post in posts.data" :key="post.id">
                <div class="card bg-secondary text-light h-100 border-0">
                    <img v-if="post.featured_image" :src="post.featured_image" :alt="post.title" class="card-img-top" loading="lazy">
                    <div class="card-body">
                        <span v-if="post.category" class="badge bg-accent mb-2">{{ post.category.name }}</span>
                        <h2 class="h4 card-title">{{ post.title }}</h2>
                        <p class="card-text opacity-75">{{ post.excerpt }}</p>
                        <Link :href="route('blog.show', post.slug)" class="btn btn-sm btn-outline-accent mt-3">Leia mais</Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
