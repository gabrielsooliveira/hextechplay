<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { ref, onUnmounted, onMounted, onBeforeUnmount, computed } from "vue";
import LocaleSwitcher from "@/js/Components/LocaleSwitcher.vue";

// import ImageLogo from "@/assets/images/brand_capta_brindes.webp";

const page = usePage();
const user = computed(() => page.props.user);

const isMenuOpen = ref(false);
const isScrolled = ref(false);
const isMdUp = ref(window.innerWidth >= 992);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 5;
};

const handleResize = () => {
    isMdUp.value = window.innerWidth >= 992;
};
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
    isMenuOpen.value = false;
};

// Fecha se clicar fora do painel no mobile
const handleClickOutside = (event) => {
    const modalMenu = document.querySelector(".mobile-menu");
    const toggler = document.querySelector(".navbar-toggler");

    if (
        isMenuOpen.value &&
        modalMenu &&
        !modalMenu.contains(event.target) &&
        !toggler.contains(event.target)
    ) {
        closeMenu();
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    window.addEventListener("scroll", handleScroll);
    window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
    window.removeEventListener("resize", handleResize);
});
onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

const navbarClass = computed(() => {
    const baseClasses = "navbar navbar-expand-lg fixed-top px-2 z-1";
    const bgClass =
        !isMdUp.value || isScrolled.value
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
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            :href="route('home')"
                            :class="{
                                'link-accent': $page.url === '/',
                            }"
                            >{{ $t("nav_home") }}</Link
                        >
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            :href="route('games')"
                            :class="route().current('games.*') ? 'link-accent' : ''"
                            >{{ $t("nav_games") }}</Link
                        >
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            :href="route('partners')"
                            :class="route().current('partners.*') ? 'link-accent' : ''"
                            >{{ $t("nav_partners") }}</Link
                        >
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            :href="route('contact')"
                            :class="route().current('contact.*') ? 'link-accent' : ''"
                            >{{ $t("nav_contact") }}</Link
                        >
                    </li>
                </ul>
                <LocaleSwitcher />
                <!-- <div class="d-flex align-items-center">
                    <div class="dropdown ms-auto">
                        <a
                            class="nav-link dropdown-toggle d-flex align-items-center"
                            href="#"
                            id="userDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span>Gabriel</span>
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-end shadow mt-3"
                            aria-labelledby="userDropdown"
                        >
                            <li>
                                <Link
                                    class="dropdown-item"
                                    :href="route('config.index')"
                                    @click="closeMenu"
                                    >Configurações</Link
                                >
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="dropdown-item text-danger"
                                    ><font-awesome-icon
                                        icon="fa-solid fa-right-from-bracket"
                                    />
                                    Logout</Link
                                >
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
    </nav>

    <transition name="fade">
        <div
            v-if="isMenuOpen"
            class="position-fixed top-0 start-0 w-100 h-100 bg-body d-flex flex-column p-4 z-3 overflow-y-auto"
        >
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-white fw-bold mb-0">Menu</h5>
                <button class="btn btn-link link-light fs-4" @click="closeMenu">
                    <font-awesome-icon icon="fas fa-xmark" />
                </button>
            </div>

            <ul class="nav flex-column fw-semibold">
                <li class="nav-item">
                    <Link
                        class="nav-link"
                        :href="route('home')"
                        :class="{
                            'link-accent': $page.url === '/',
                        }"
                        @click="closeMenu"
                        >{{ $t("nav_home") }}</Link
                    >
                </li>
                <li class="nav-item">
                    <Link
                        class="nav-link"
                        :href="route('games')"
                        :class="route().current('games.*') ? 'link-accent' : ''"
                        @click="closeMenu"
                        >{{ $t("nav_games") }}</Link
                    >
                </li>
                <li class="nav-item">
                    <Link 
                        class="nav-link" 
                        :href="route('partners')" 
                        :class="route().current('partners.*') ? 'link-accent' : ''"
                        @click="closeMenu"
                        >{{ $t("nav_partners") }}</Link
                    >
                </li>
                <li class="nav-item">
                    <Link
                        class="nav-link"
                        :href="route('contact')"
                        :class="route().current('contact.*') ? 'link-accent' : ''"
                        @click="closeMenu"
                        >{{ $t("nav_contact") }}</Link
                    >
                </li>
                <li><hr /></li>
                <!-- <li class="nav-item">
                    <Link class="nav-link disabled" :href="route('config.index')" @click="closeMenu"
                        >Configurações</Link
                    >
                </li>
                <li class="nav-item">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="nav-link link-danger"
                        ><font-awesome-icon
                            icon="fa-solid fa-right-from-bracket"
                        />
                        Logout</Link
                    >
                </li> -->
            </ul>
        </div>
    </transition>
</template>