<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    message: "",
});

function submitForm() {
    form.post("/contact/send", {
        onSuccess: () => {
            form.name = "";
            form.email = "";
            form.message = "";
        },
    });
}
</script>

<template>
    <Head>
        <title>{{ $t("page_title") }}</title>
        <meta name="description" :content="$t('page_description')" />
        <meta name="keywords" :content="$t('page_keywords')" />
        <meta property="og:title" :content="$t('og_title')" />
        <meta property="og:description" :content="$t('og_description')" />
        <meta property="og:url" content="https://hextechplay.com/contact" />
        <link rel="canonical" href="https://hextechplay.com/contact" />
    </Head>

    <section class="py-5 mt-5 d-flex align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-3 fw-bold mb-3 fade-in">
                        {{ $t("page_title") }}
                    </h1>
                    <p class="mb-5">{{ $t("page_description") }}</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form
                        @submit.prevent="submitForm"
                        class="shadow-lg p-4 rounded bg-primary"
                    >
                        <div class="mb-3">
                            <label for="name" class="form-label">{{
                                $t("form_name")
                            }}</label>
                            <input
                                type="text"
                                v-model="form.name"
                                id="name"
                                class="form-control"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{
                                $t("form_email")
                            }}</label>
                            <input
                                type="email"
                                v-model="form.email"
                                id="email"
                                class="form-control"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">{{
                                $t("form_message")
                            }}</label>
                            <textarea
                                v-model="form.message"
                                id="message"
                                class="form-control"
                                rows="5"
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            {{ $t("form_submit") }}
                        </button>
                    </form>

                    <div class="mt-5 text-center">
                        <a href="https://ko-fi.com/E1E41N7UJO" target="_blank"
                            ><img
                                height="36"
                                style="border: 0px; height: 36px"
                                src="https://storage.ko-fi.com/cdn/kofi5.png?v=6"
                                border="0"
                                alt="Buy Me a Coffee at ko-fi.com"
                        /></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
