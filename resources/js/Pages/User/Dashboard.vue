<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    badges: Array,
    stats: Object,
    auth: Object
});

const activeTab = ref('badges');

const profileForm = useForm({
    name: props.auth.user.name,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Toast success is handled globally if implemented, else just clear errors
        }
    });
};

const updatePassword = () => {
    passwordForm.post(route('profile.password'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

const getTierColorClass = (tier, unlocked) => {
    if (!unlocked) return 'border-secondary opacity-50 grayscale';
    
    switch (tier.toLowerCase()) {
        case 'bronze': return 'border-[#CD7F32] shadow-[0_0_15px_rgba(205,127,50,0.5)] text-[#CD7F32]';
        case 'silver': return 'border-secondary shadow-[0_0_15px_rgba(108,117,125,0.5)] text-secondary';
        case 'gold': return 'border-warning shadow-[0_0_15px_rgba(255,193,7,0.5)] text-warning';
        case 'platinum': return 'border-info shadow-[0_0_15px_rgba(13,202,240,0.5)] text-info';
        case 'diamond': return 'border-primary shadow-[0_0_15px_rgba(13,110,253,0.5)] text-primary';
        case 'master': return 'border-danger shadow-[0_0_15px_rgba(220,53,69,0.5)] text-danger';
        case 'grandmaster': return 'border-danger shadow-[0_0_20px_rgba(220,53,69,0.8)] text-danger';
        case 'challenger': return 'border-info shadow-[0_0_25px_rgba(13,202,240,0.8)] text-info';
        default: return 'border-light shadow-[0_0_10px_rgba(255,255,255,0.3)] text-light';
    }
};
</script>

<template>
    <Head>
        <title>Painel de Controle</title>
    </Head>

    <div class="container padding-navbar min-vh-100">
        <div class="row mb-5 text-center text-md-start align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold game-title mb-2">Painel de Lendas</h1>
                <p class="text-light opacity-75 lead mb-0">Gerencie sua conta e visualize suas conquistas no HextechPlay.</p>
            </div>
            <div class="col-md-4 mt-4 mt-md-0 text-md-end">
                <div class="p-3 d-inline-block text-center border-warning border-opacity-50 shadow-lg">
                    <div class="text-warning small text-uppercase fw-bold mb-1">Taxa de Conclusão</div>
                    <div class="fs-4 fw-bold text-white">{{ stats.total_badges }} / {{ stats.max_badges }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar / Tabs -->
            <div class="col-lg-3">
                <div class="p-3">
                    <ul class="nav flex-column nav-pills gap-2">
                        <li class="nav-item">
                            <button 
                                class="nav-link w-100 text-start text-light fw-semibold py-3 transition" 
                                :class="{ 'active bg-warning text-dark shadow-sm': activeTab === 'badges', 'hover-bg-secondary hover-bg-opacity-25': activeTab !== 'badges' }"
                                @click="activeTab = 'badges'"
                            >
                                <font-awesome-icon icon="fas fa-medal" class="me-2" /> Conquistas
                            </button>
                        </li>
                        <li class="nav-item">
                            <button 
                                class="nav-link w-100 text-start text-light fw-semibold py-3 transition" 
                                :class="{ 'active bg-warning text-dark shadow-sm': activeTab === 'profile', 'hover-bg-secondary hover-bg-opacity-25': activeTab !== 'profile' }"
                                @click="activeTab = 'profile'"
                            >
                                <font-awesome-icon icon="fas fa-user-cog" class="me-2" /> Conta e Perfil
                            </button>
                        </li>
                        <li><hr class="border-secondary opacity-50 my-2"></li>
                        <li class="nav-item">
                            <Link :href="route('games')" class="nav-link w-100 text-start text-light opacity-75 hover-opacity-100 hover-bg-secondary hover-bg-opacity-25 fw-semibold py-3 transition">
                                <font-awesome-icon icon="fas fa-arrow-left" class="me-2" /> Voltar aos Jogos
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <!-- Badges Tab -->
                <div v-if="activeTab === 'badges'" class="animate-pop-in">
                    <div class="glass-panel p-4 p-md-5 mb-4">
                        <h3 class="fw-bold text-white mb-4 border-bottom border-secondary border-opacity-50 pb-3">
                            <font-awesome-icon icon="fas fa-trophy" class="text-warning me-2" /> Galeria de Emblemas
                        </h3>
                        
                        <div class="row g-4">
                            <div v-for="badge in badges" :key="badge.id" class="col-sm-6 col-md-4">
                                <div class="glass-panel h-100 p-4 text-center transition"
                                     :class="getTierColorClass(badge.tier, badge.unlocked)"
                                     style="border-width: 2px !important; position: relative; overflow: hidden;"
                                >
                                    <div v-if="!badge.unlocked" class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50" style="z-index: 1;"></div>
                                    
                                    <div class="position-relative" style="z-index: 2;">
                                        <div class="mb-3">
                                            <font-awesome-icon :icon="badge.icon" class="fa-3x" />
                                        </div>
                                        <h5 class="fw-bold mb-2 text-white">{{ badge.name }}</h5>
                                        <p class="small text-light opacity-75 mb-0">{{ badge.description }}</p>
                                        
                                        <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
                                            <span v-if="badge.unlocked" class="badge bg-success bg-opacity-25 text-success border border-success">
                                                Desbloqueado
                                            </span>
                                            <span v-else class="badge bg-dark text-light border border-secondary">
                                                Bloqueado
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Tab -->
                <div v-if="activeTab === 'profile'" class="animate-pop-in">
                    <!-- Update Profile Information -->
                    <div class="glass-panel p-4 p-md-5 mb-4">
                        <h3 class="fw-bold text-white mb-4 border-bottom border-secondary border-opacity-50 pb-3">
                            Informações Pessoais
                        </h3>

                        <form @submit.prevent="updateProfile">
                            <div class="mb-4">
                                <label for="name" class="form-label text-light fw-semibold">Nome de Invocador</label>
                                <input type="text" id="name" v-model="profileForm.name" class="form-control bg-dark text-white border-secondary py-2" :class="{ 'is-invalid': profileForm.errors.name }" required>
                                <div class="invalid-feedback" v-if="profileForm.errors.name">{{ profileForm.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-light fw-semibold opacity-75">Email (Não editável)</label>
                                <input type="email" class="form-control bg-dark text-white border-secondary py-2 opacity-50" :value="auth.user.email" disabled>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn game-btn px-4 py-2" :disabled="profileForm.processing">
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Update Password -->
                    <div class="glass-panel p-4 p-md-5">
                        <h3 class="fw-bold text-white mb-4 border-bottom border-secondary border-opacity-50 pb-3">
                            Alterar Senha
                        </h3>

                        <form @submit.prevent="updatePassword">
                            <div class="mb-4">
                                <label for="current_password" class="form-label text-light fw-semibold">Senha Atual</label>
                                <input type="password" id="current_password" v-model="passwordForm.current_password" class="form-control bg-dark text-white border-secondary py-2" :class="{ 'is-invalid': passwordForm.errors.current_password }" required>
                                <div class="invalid-feedback" v-if="passwordForm.errors.current_password">{{ passwordForm.errors.current_password }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label text-light fw-semibold">Nova Senha</label>
                                <input type="password" id="password" v-model="passwordForm.password" class="form-control bg-dark text-white border-secondary py-2" :class="{ 'is-invalid': passwordForm.errors.password }" required>
                                <div class="invalid-feedback" v-if="passwordForm.errors.password">{{ passwordForm.errors.password }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label text-light fw-semibold">Confirmar Nova Senha</label>
                                <input type="password" id="password_confirmation" v-model="passwordForm.password_confirmation" class="form-control bg-dark text-white border-secondary py-2" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-warning fw-bold px-4 py-2" :disabled="passwordForm.processing">
                                    Atualizar Senha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.grayscale {
    filter: grayscale(100%);
    opacity: 0.6;
}
.hover-bg-secondary:hover {
    background-color: rgba(108, 117, 125, 0.25) !important;
}
</style>
