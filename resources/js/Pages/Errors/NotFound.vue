<script setup>
import { Head, Link } from "@inertiajs/vue3";
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
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <font-awesome-icon icon="fa-solid fa-ban" class="display-1" />
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <h2 class="mb-3">{{ $t("page_not_found") }}</h2>
            <p class="text-muted mb-4">
                {{ $t("page_not_found_description") }}
            </p>

            <Link href="/" class="btn btn-primary px-4 py-2">
                <font-awesome-icon icon="fa-solid fa-house" class="me-2" />
                {{ $t("home") }}
            </Link>
        </div>
    </div>
</template>
