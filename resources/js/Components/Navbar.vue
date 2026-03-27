<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onUnmounted, onMounted, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useWindowSize } from "@/js/Composables/useWindowSize";
import { useClickOutside } from "@/js/Composables/useClickOutside";
import LocaleSwitcher from "@/js/Components/LocaleSwitcher.vue";

const { t } = useI18n();
const { isDesktop, isMobile } = useWindowSize();

const page = usePage();
const user = computed(() => page.props.user);

const isMenuOpen = ref(false);
const isScrolled = ref(false);

const menuRef = ref(null);
const togglerRef = ref(null);

const navLinks = computed(() => [
    { name: t("nav_home"), route: "home", active: page.url === "/" },
    { name: t("nav_games"), route: "games", active: route().current("games.*") },
    { name: t("nav_partners"), route: "partners", active: route().current("partners.*") },
    { name: t("nav_contact"), route: "contact", active: route().current("contact.*") },
]);

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
            : "bg-transparent";

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
                class="collapse navbar-collapse d-none d-lg-block flex-wrap align-items-center justify-content-between"
            >
                <ul class="nav justify-content-center fw-semibold">
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
                <LocaleSwitcher />
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

            <ul class="nav flex-column fw-semibold">
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
                <li><hr /></li>
            </ul>
        </div>
    </transition>
</template>