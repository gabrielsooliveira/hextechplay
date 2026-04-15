<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});
</script>

<template>
    <Head>
        <title>Registro</title>
    </Head>

    <div class="container d-flex justify-content-center align-items-center mb-5 mt-5">
        <div class="card p-5 shadow rounded-3 col-12 col-md-8 col-lg-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Crie sua conta</h2>
            </div>

            <form @submit.prevent="form.post(route('register_store'))">
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="name">Nome Completo</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="form-control"
                        :class="{'is-invalid': form.errors.name}"
                        required
                        autofocus
                    />
                    <div class="invalid-feedback" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        class="form-control text-lowercase"
                        :class="{'is-invalid': form.errors.email}"
                        required
                    />
                    <div class="invalid-feedback" v-if="form.errors.email">
                        {{ form.errors.email }}
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password">Senha</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        class="form-control"
                        :class="{'is-invalid': form.errors.password}"
                        required
                    />
                    <div class="invalid-feedback" v-if="form.errors.password">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password_confirmation">Confirmar Senha</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        class="form-control"
                        required
                    />
                </div>

                <!-- Terms -->
                <div class="mb-4 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="terms"
                        v-model="form.terms"
                        :class="{'is-invalid': form.errors.terms}"
                        required
                    />
                    <label class="form-check-label small" for="terms">
                        Eu li e aceito os 
                        <a :href="route('terms')" target="_blank" class="text-decoration-none fw-semibold">Termos de Uso</a> 
                        e a 
                        <a :href="route('privacy.policy')" target="_blank" class="text-decoration-none fw-semibold">Política de Privacidade</a>.
                    </label>
                    <div class="invalid-feedback" v-if="form.errors.terms">
                        {{ form.errors.terms }}
                    </div>
                </div>

                <div class="d-grid mb-2">
                    <button
                        type="submit"
                        class="btn btn-primary fw-semibold py-2 text-white"
                        :disabled="form.processing"
                    >
                        Criar Conta
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <span class="text-muted small">Já possui uma conta?</span>
                <Link
                    :href="route('login')"
                    class="text-decoration-none fw-semibold ms-1 small"
                >
                    Faça login
                </Link>
            </div>
        </div>
    </div>
</template>
