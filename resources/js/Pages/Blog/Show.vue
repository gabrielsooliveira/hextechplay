<script setup>
import { Head, Link } from "@inertiajs/vue3";
import JsonLd from "@/js/Components/Seo/JsonLd.vue";

const props = defineProps({
    post: Object
});
</script>

<template>
    <Head>
        <title>{{ post.meta_title || post.title }}</title>
        <meta name="description" :content="post.meta_description || post.excerpt" />
        <link rel="canonical" :href="`https://hextechplay.com/blog/${post.slug}`" />
    </Head>

    <JsonLd :schema="{
        '@type': 'BlogPosting',
        'headline': post.title,
        'image': post.featured_image,
        'description': post.excerpt,
        'datePublished': post.created_at,
        'dateModified': post.updated_at,
        'url': `https://hextechplay.com/blog/${post.slug}`
    }" />

    <article class="container padding-navbar py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-light">
                <Link :href="route('blog.index')" class="text-accent mb-4 d-inline-block">&larr; Voltar para o Blog</Link>
                
                <h1 class="display-4 fw-bold mb-3">{{ post.title }}</h1>
                <div v-if="post.category" class="mb-4">
                    <span class="badge bg-accent">{{ post.category.name }}</span>
                </div>

                <img v-if="post.featured_image" :src="post.featured_image" :alt="post.title" class="img-fluid rounded mb-4" loading="eager">

                <!-- O v-html será usado para renderizar o conteúdo rico do post -->
                <div class="post-content opacity-90" v-html="post.content"></div>
            </div>
        </div>
    </article>
</template>

<style scoped>
.post-content :deep(h2) {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-size: 1.75rem;
    font-weight: 600;
}
.post-content :deep(h3) {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}
</style>
