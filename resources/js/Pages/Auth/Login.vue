<script setup>
import { ref } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

const isView = ref(false);

const form = useForm({
    email: "",
    password: "",
});

const toggleView = () => {
    isView.value = !isView.value;
};
</script>

<template>
    <Head>
        <title>Login</title>
    </Head>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="card p-5 shadow rounded-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">
                    Faça o login para continuar
                </h2>
            </div>

            <form @submit.prevent="form.post(route('login_store'))">
                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email"
                        >Email</label
                    >
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        class="form-control text-lowercase"
                        required
                        autocomplete="email"
                    />
                </div>

                <!-- Senha -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password"
                        >Senha</label
                    >
                    <div class="input-group">
                        <input
                            :type="isView ? 'text' : 'password'"
                            id="password"
                            v-model="form.password"
                            class="form-control"
                            required
                        />
                        <button
                            type="button"
                            class="btn btn-primary text-white"
                            @click="toggleView"
                        >
                            <font-awesome-icon
                                :icon="
                                    isView
                                        ? 'fa-solid fa-eye-slash'
                                        : 'fa-solid fa-eye'
                                "
                            />
                        </button>
                    </div>
                </div>

                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <Link class="text-decoration-none small" href="#"
                        >Esqueceu sua senha?</Link
                    >
                </div>

                <div class="d-grid mb-2">
                    <button
                        type="submit"
                        class="btn btn-primary fw-semibold py-2 text-white"
                        :disabled="form.processing"
                    >
                        Entrar
                    </button>
                </div>
            </form>

            <div class="text-center mt-3">
                <span class="text-muted small">Não tem uma conta?</span>
                <Link
                    :href="route('register')"
                    class="text-decoration-none fw-semibold ms-1 small"
                >
                    Registre-se
                </Link>
            </div>
        </div>
    </div>
</template>