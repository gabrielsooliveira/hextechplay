<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onUnmounted, onMounted, computed } from "vue";
import { useWindowSize } from "@/js/Composables/useWindowSize";
import { useClickOutside } from "@/js/Composables/useClickOutside";

const { isDesktop, isMobile } = useWindowSize();

const page = usePage();
const user = computed(() => page.props.auth?.user || page.props.user);

const isMenuOpen = ref(false);
const isScrolled = ref(false);

const menuRef = ref(null);
const togglerRef = ref(null);

const navLinks = computed(() => {
    // Access page.url to ensure the computed property is reactive to navigation
    const currentUrl = page.url;

    return [
        { name: "Início", route: "home", active: route().current("home") },
        { name: "Jogos", route: "games", active: route().current("games") || currentUrl.startsWith("/clickchallenger") || currentUrl.startsWith("/lorequestion") || currentUrl.startsWith("/wordlol") },
        { name: "Parceiros", route: "partners", active: route().current("partners") },
        { name: "Contato", route: "contact", active: route().current("contact") },
        { name: "Blog", route: "blog.index", active: route().current("blog") },
    ];
});

const handleScroll = () => {
    isScrolled.value = window.scrollY > 5;
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
    isMenuOpen.value = false;
};

useClickOutside(menuRef, closeMenu, [togglerRef]);

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

const navbarClass = computed(() => {
    const baseClasses = "navbar navbar-expand-lg fixed-top px-2 z-1";
    const bgClass =
        !isDesktop.value || isScrolled.value
            ? "bg-dark border-bottom border-primary"
            : "bg-transparent mt-1";

    return `${baseClasses} ${bgClass}`;
});
</script>

<template>
    <nav :class="navbarClass">
        <div class="container-fluid">
            <Link class="navbar-brand fw-bold text-accent" :href="route('home')">
                HextechPlay
            </Link>
            <button
                ref="togglerRef"
                class="btn d-lg-none border-0"
                type="button"
                @click="toggleMenu"
            >
                <font-awesome-icon icon="fas fa-bars" size="lg" />
            </button>

            <div
                class="collapse navbar-collapse d-none d-lg-flex flex-wrap align-items-center"
            >
                <ul class="nav justify-content-center fw-semibold mx-auto">
                    <li v-for="link in navLinks" :key="link.route" class="nav-item">
                        <Link
                            class="nav-link"
                            :href="route(link.route)"
                            :class="{ 'link-light' : !link.active, 'link-accent' : link.active }"
                        >
                            {{ link.name }}
                        </Link>
                    </li>
                </ul>

                <div class="d-flex gap-3 align-items-center">
                    <template v-if="user">
                        <Link :href="route('dashboard')" class="text-decoration-none text-warning small fw-bold d-flex align-items-center transition hover-opacity-75">
                            <font-awesome-icon icon="fas fa-user-circle" class="me-2 fs-5" /> {{ user.name }}
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="btn btn-outline-danger btn-sm py-1 px-3 fw-bold rounded-pill">
                            Sair
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="text-decoration-none text-light fw-semibold small transition">
                            Entrar
                        </Link>
                        <Link :href="route('register')" class="btn game-btn rounded-pill small">
                            Registrar
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <transition name="fade">
        <div
            v-if="isMenuOpen"
            ref="menuRef"
            class="mobile-menu position-fixed top-0 start-0 w-100 h-100 bg-body d-flex flex-column p-4 z-3 overflow-y-auto"
        >
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-white fw-bold mb-0">Menu</h5>
                <button class="btn btn-link link-light fs-4" @click="closeMenu">
                    <font-awesome-icon icon="fas fa-xmark" />
                </button>
            </div>

            <ul class="nav flex-column fw-semibold mb-4">
                <li v-for="link in navLinks" :key="link.route" class="nav-item">
                    <Link
                        class="nav-link"
                        :href="route(link.route)"
                        :class="{ 'link-light' : !link.active, 'link-accent' : link.active }"
                        @click="closeMenu"
                    >
                        {{ link.name }}
                    </Link>
                </li>
            </ul>

            <div class="mt-auto">
                <hr class="border-secondary opacity-50 mb-4" />
                <div class="d-flex flex-column gap-3">
                    <template v-if="user">
                        <Link :href="route('dashboard')" class="d-flex align-items-center gap-2 mb-3 text-decoration-none bg-dark bg-opacity-50 p-2 rounded-3 border border-secondary border-opacity-25" @click="closeMenu">
                            <font-awesome-icon icon="fas fa-user-circle" class="text-warning fs-1" />
                            <div>
                                <div class="text-white fw-bold lh-1 mb-1">{{ user.name }}</div>
                                <div class="small text-warning fw-bold"><font-awesome-icon icon="fas fa-id-card" class="me-1" /> Acessar Painel</div>
                            </div>
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="btn btn-outline-danger w-100 fw-bold py-2 rounded-3" @click="closeMenu">
                            Sair
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="btn btn-outline-light w-100 fw-bold py-2 rounded-3" @click="closeMenu">
                            Entrar
                        </Link>
                        <Link :href="route('register')" class="btn game-btn w-100 py-2 rounded-3" @click="closeMenu">
                            Registrar
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </transition>
</template>