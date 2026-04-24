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
        <title>Contato</title>
        <meta name="description" content="Entre em contato com a equipe HextechPlay para sugestões, dúvidas ou parcerias." />
        <meta name="keywords" content="contato, HextechPlay, suporte, sugestões, parcerias" />
        <meta property="og:title" content="Contato HextechPlay" />
        <meta property="og:description" content="Envie sua mensagem para o HextechPlay e fale com nossa equipe!" />
        <meta property="og:url" content="https://hextechplay.com/contact" />
        <link rel="canonical" href="https://hextechplay.com/contact" />
    </Head>

    <section class="py-5 mt-5 d-flex align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-3 fw-bold mb-3 fade-in game-title">
                        Contato
                    </h1>
                    <p class="mb-5 text-light opacity-75">Entre em contato com a equipe HextechPlay para sugestões, dúvidas ou parcerias.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form
                        @submit.prevent="submitForm"
                        class="glass-panel p-4 p-md-5"
                    >
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-warning text-uppercase small mb-2">Nome</label>
                            <input
                                type="text"
                                v-model="form.name"
                                id="name"
                                class="form-control bg-dark border-secondary text-white py-2"
                                placeholder="Seu nome completo"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-warning text-uppercase small mb-2">Email</label>
                            <input
                                type="email"
                                v-model="form.email"
                                id="email"
                                class="form-control bg-dark border-secondary text-white py-2"
                                placeholder="seu@email.com"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label fw-bold text-warning text-uppercase small mb-2">Mensagem</label>
                            <textarea
                                v-model="form.message"
                                id="message"
                                class="form-control bg-dark border-secondary text-white py-2"
                                rows="5"
                                placeholder="Como podemos ajudar?"
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="btn game-btn w-100 py-3 rounded-3 mt-2">
                            Enviar Mensagem
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
