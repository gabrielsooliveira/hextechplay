<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <footer class="mt-3 border-top border-primary">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-8 mb-4 text-lg-start text-center">
                    <div class="d-flex align-items-center mb-3">
                        <span class="fw-bold fs-4">HextechPlay</span>
                    </div>
                    <p class="opacity-75 mb-3">
                        A plataforma definitiva de mini-games inspirada no universo de League of Legends. Teste suas habilidades e conhecimento sobre Runeterra.
                    </p>
                    <div
                        class="d-flex justify-content-lg-start justify-content-center"
                    >
                        <a
                            href="https://discord.com/"
                            class="btn btn-outline-light btn-sm me-2 disabled"
                            target="_blank"
                            ><font-awesome-icon icon="fab fa-discord"
                        /></a>
                        <a
                            href="https://x.com/"
                            class="btn btn-outline-light btn-sm me-2 disabled"
                            target="_blank"
                            ><font-awesome-icon icon="fab fa-twitter"
                        /></a>
                        <a
                            href="https://www.youtube.com/"
                            class="btn btn-outline-light btn-sm me-2 disabled"
                            target="_blank"
                            ><font-awesome-icon icon="fab fa-youtube"
                        /></a>
                        <a
                            href="https://www.facebook.com/people/HextechPlay/61581496198338/"
                            target="_blank"
                            class="btn btn-outline-light btn-sm"
                            ><font-awesome-icon icon="fab fa-facebook"
                        /></a>
                    </div>
                    <div class="mt-5">
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

                <!-- Community -->
                <!-- <div class="col-lg-2 mb-4">
                    <h6 class="text-warning text-uppercase fw-semibold mb-3">
                        Comunidade
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <Link
                                href="#"
                                class="text-light text-decoration-none"
                                >Leaderboard</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                href="#"
                                class="text-light text-decoration-none"
                                >Conquistas</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                href="#"
                                class="text-light text-decoration-none"
                                >Torneios</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                href="#"
                                class="text-light text-decoration-none"
                                >Fórum</Link
                            >
                        </li>
                    </ul>
                </div> -->

                <!-- Support -->
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="text-warning text-uppercase fw-semibold mb-3">
                        Suporte
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <Link
                                :href="route('contact')"
                                class="text-light text-decoration-none"
                                >Central de Ajuda</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                :href="route('contact')"
                                class="text-light text-decoration-none"
                                >Contato</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                :href="route('contact')"
                                class="text-light text-decoration-none"
                                >Reportar Bug</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                :href="route('contact')"
                                class="text-light text-decoration-none"
                                >Feedback</Link
                            >
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <!-- <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="text-warning text-uppercase fw-semibold mb-3">Newsletter</h6>
          <p class="opacity-75 small mb-3">
            Receba novidades sobre novos jogos e eventos.
          </p>
          <form class="d-flex">
            <input type="email" class="form-control form-control-sm me-2 bg-dark text-light border border-primary" placeholder="Seu e-mail">
            <button class="btn btn-accent btn-sm"><font-awesome-icon icon="fas fa-paper-plane" /></button>
          </form>
        </div> -->

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="text-warning text-uppercase fw-semibold mb-3">
                        Jogos
                    </h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <Link
                                :href="route('lorequestion.index')"
                                class="text-light text-decoration-none"
                                >LoreQuestion</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                :href="route('wordlol.game')"
                                class="text-light text-decoration-none"
                                >WordLoL</Link
                            >
                        </li>
                        <li class="mb-2">
                            <Link
                                :href="route('clickchallenger.index')"
                                class="text-light text-decoration-none"
                                >ClickChallenger</Link
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary my-4" />

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="opacity-75">© 2025 HextechPlay</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <Link
                        :href="route('privacy.policy')"
                        class="text-light text-decoration-none me-3 small"
                        >Política de Privacidade</Link
                    >
                    <Link
                        :href="route('terms')"
                        class="text-light text-decoration-none small"
                        >Termos de Uso</Link
                    >
                </div>
            </div>
        </div>
    </footer>
</template>
