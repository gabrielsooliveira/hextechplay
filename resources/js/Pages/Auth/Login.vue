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

    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5 mt-5">
        <div class="glass-panel p-4 p-md-5 w-100 mx-auto" style="max-width: 450px;">
            <div class="text-center mb-4">
                <h2 class="fw-bold game-title mb-2">
                    HextechPlay
                </h2>
                <p class="text-light opacity-75 mb-0">Faça login para continuar</p>
            </div>

            <form @submit.prevent="form.post(route('login_store'))">
                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label fw-bold small mb-2" for="email">Email</label>
                    <input type="email" id="email" v-model="form.email" class="form-control bg-dark border-secondary text-white text-lowercase py-2" required autocomplete="email" placeholder="seu@email.com"/>
                </div>

                <!-- Senha -->
                <div class="mb-4">
                    <label class="form-label fw-bold small mb-2" for="password">
                        Senha
                    </label>
                    <div class="input-group">
                        <input :type="isView ? 'text' : 'password'" id="password" v-model="form.password" class="form-control bg-dark border-secondary text-white py-2 border-end-0" required placeholder="********"/>
                        <button type="button" class="btn btn-dark border border-secondary border-start-0 text-muted px-3" @click="toggleView">
                            <font-awesome-icon :icon="isView ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"/>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <Link class="text-warning text-decoration-none small opacity-75 hover-opacity-100 transition" href="#">
                        Esqueceu sua senha?
                    </Link>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn game-btn py-3 rounded-3" :disabled="form.processing">Entrar</button>
                </div>
            </form>

            <div class="text-center mt-4 pt-3 border-top border-secondary border-opacity-50">
                <span class="text-light opacity-50 small">Não tem uma conta?</span>
                <Link :href="route('register')" class="text-warning text-decoration-none fw-bold ms-2 small">Registre-se</Link>
            </div>
        </div>
    </div>
</template>