<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import { Head, router } from "@inertiajs/vue3";
import ModalDialog from "@/js/Components/Modals/ModalDialog.vue";
import WordBackground from "@/assets/images/wordlol.png";

const props = defineProps({
    displayWord: String,
    guessed: Array,
    wrongLetters: Array,
    wrong: Number,
    maxAttempts: Number,
    lost: Boolean,
    won: Boolean,
    word: String,
    attempts: Number,
    timeRemaining: Object,
});

const userInput = ref("");
const loading = ref(false);
const inputError = ref("");
const isModalVisible = ref(false);
const showGuide = ref(false);

const getLetterBoxClass = (char) => {
    if (props.won) {
        return "bg-success text-white border-success";
    }

    if (props.lost) {
        return "bg-secondary text-white border-secondary";
    }

    if (char !== "_") {
        return "bg-primary text-white border-primary";
    }

    return "bg-dark border-light";
};

const makeGuess = async () => {
    if (!userInput.value.trim() || loading.value) return;

    const input = userInput.value.trim().toUpperCase();

    if (input.length === 1 && !/^[A-Z]$/.test(input)) {
        inputError.value = "Letra inválida!";
        return;
    }

    if (input.length === 1 && props.guessed.includes(input)) {
        inputError.value = "A letra já foi escolhida!";
        return;
    }

    loading.value = true;
    inputError.value = "";

    try {
        const payload =
            input.length === 1 ? { letter: input } : { word: input };

        const response = await fetch(route("wordlol.game.guess"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (data.error) {
            inputError.value = data.error;
        } else {
            router.reload({
                only: [
                    "displayWord",
                    "guessed",
                    "wrongLetters",
                    "wrong",
                    "lost",
                    "attempts",
                    "won",
                    "word",
                ],
            });
        }
    } catch (error) {
        inputError.value = "Ocorreu um erro. Tente novamente.";
        console.error("Erro:", error);
    } finally {
        loading.value = false;
        userInput.value = "";
    }
};

const clearError = () => {
    inputError.value = "";
};

const liveTime = ref({ ...props.timeRemaining });
let timer;

onMounted(() => {
    timer = setInterval(() => {
        if (liveTime.value.seconds > 0) {
            liveTime.value.seconds--;
        } else if (liveTime.value.minutes > 0) {
            liveTime.value.minutes--;
            liveTime.value.seconds = 59;
        } else if (liveTime.value.hours > 0) {
            liveTime.value.hours--;
            liveTime.value.minutes = 59;
            liveTime.value.seconds = 59;
        }
    }, 1000);
});

onUnmounted(() => clearInterval(timer));

const formattedTime = computed(() => {
    const { hours, minutes, seconds } = liveTime.value;
    const pad = (n) => n.toString().padStart(2, "0");
    return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
});

watch(
    () => [props.won, props.lost],
    ([won, lost]) => {
        if (won || lost) {
            isModalVisible.value = true;
        }
    },
    { immediate: true }
);

const closeModal = () => {
    isModalVisible.value = false;
};

const shareOnTwitter = () => {
    const messagesWin = [
        "Uau! Você desvendou a palavra secreta!",
        "GG! Adivinhou a palavra como um verdadeiro mestre!",
        "Incrível! Sua dedução foi impecável dessa vez!",
    ];

    const messagesLose = [
        "Foi quase! Na próxima a palavra não escapa.",
        "A palavra venceu dessa vez... Tente de novo!",
        "Não desanime! Toda grande lenda já perdeu uma batalha.",
    ];

    const message = props.won
        ? messagesWin[Math.floor(Math.random() * messagesWin.length)]
        : messagesLose[Math.floor(Math.random() * messagesLose.length)];

    const attemptsInfo = props.attempts + " tentativas.";
    const errorsInfo = "Erros: " + props.wrong + "/" + props.maxAttempts;
    const gameUrl = window.location.origin;

    let text = "";
    if (props.won) {
        text = `${message}\n${attemptsInfo}\n${errorsInfo}\nAdivinhe a palavra de hoje e teste seus conhecimentos em: ${gameUrl} #HextechPlay #WordLoL #LoL`;
    } else {
        text = `${message}\nAdivinhe a palavra de hoje e teste seus conhecimentos em: ${gameUrl} #HextechPlay #WordLoL #LoL`;
    }

    const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(
        text
    )}`;
    window.open(twitterUrl, "_blank");
};
</script>

<template>
    <Head>
        <title>WordLoL - Adivinhe a palavra de League of Legends!</title>
        <meta name="description" content="Jogue WordLoL, um divertido jogo de adivinhar palavras baseado no universo de League of Legends. Tente adivinhar a palavra secreta com dicas e um número limitado de tentativas!" />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seu conhecimento e divirta-se com jogos rápidos inspirados em Runeterra!" />
        <meta property="og:url" content="https://hextechplay.com/wordlol" />
        <link rel="canonical" href="https://hextechplay.com/wordlol" />
    </Head>

    <div class="game-background-overlay" :style="{ backgroundImage: `url(${WordBackground})` }"></div>

    <div
        class="min-vh-100 d-flex align-items-center justify-content-center py-4"
    >
        <div class="container">
            <div class="glass-panel w-100 mx-auto" style="max-width: 800px;">
                <div class="card-body text-center p-4">
                    <h2 class="fw-bold mb-4 game-title">
                        <font-awesome-icon
                            icon="fa-solid fa-gamepad"
                            class="text-warning"
                        />
                        WordLoL
                    </h2>

                    <div
                        class="d-flex flex-wrap justify-content-center gap-2 mb-4"
                    >
                        <div
                            v-for="(char, index) in displayWord.split(' ')"
                            :key="`char-${index}`"
                            class="letter-box border border-light rounded text-uppercase fw-bold d-flex align-items-center justify-content-center fs-2"
                            :class="[getLetterBoxClass(char, index), char !== '_' ? 'filled' : '']"
                            style="width: 3.5rem; height: 4.5rem; margin: 0 4px;"
                        >
                            {{ char !== "_" ? char : "" }}
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-dark rounded p-3 h-100">
                                <small class="text-white d-block opacity-75">CHANCES RESTANTES</small>

                                <div class="fs-4 fw-bold text-warning">
                                    {{ wrong }} / {{ maxAttempts }}
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="bg-dark rounded p-3 h-100">
                                <small class="text-white d-block opacity-75">TENTATIVAS</small>
                                <div class="fs-4 fw-bold text-warning">
                                    {{ attempts }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="input-group input-group-lg">
                        <input
                            type="text"
                            v-model="userInput"
                            class="form-control text-center text-uppercase text-warning fw-bold placeholder-text"
                            :class="{ 'is-invalid': inputError }"
                            placeholder="Digite letra ou palavra"
                            :disabled="lost || won"
                            :maxlength="props.displayWord.split(' ').length"
                            @keyup.enter="makeGuess"
                            @input="clearError"
                        />

                        <button
                            class="btn game-btn px-4"
                            @click="makeGuess"
                            :disabled="lost || won || !userInput.trim()"
                        >
                            <span v-if="!loading">
                                <font-awesome-icon icon="fas fa-check" class="me-1" /> Tentar
                            </span>
                            <span v-else>
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </span>
                        </button>
                    </div>

                    <div v-if="inputError" class="text-danger small mb-3">
                        {{ inputError }}
                    </div>

                    <div class="mt-4 text-start">
                        <h6 class="text-danger mb-2 fw-bold">
                            ❌ Letras Erradas
                        </h6>

                        <div class="bg-dark rounded p-3 min-height-60">
                            <div
                                v-if="wrongLetters.length > 0"
                                class="d-flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="letter in wrongLetters"
                                    :key="`wrong-${letter}`"
                                    class="badge bg-danger fs-6 px-2 py-1"
                                >
                                    {{ letter }}
                                </span>
                            </div>

                            <small v-else class="text-warning">Nenhuma ainda</small>
                        </div>
                    </div>

                    <button
                        class="btn btn-sm game-btn-primary mt-5 px-3 py-2 rounded-circle"
                        @click="showGuide = true"
                    >
                        <font-awesome-icon
                            icon="fas fa-question"
                        ></font-awesome-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <ModalDialog :isVisible="showGuide" @close="showGuide = false">
        <div class="text-primary">
            <h3>Como Jogar WordLoL</h3>
            <ul>
                <li>Tente adivinhar a palavra antes de atingir o número máximo de erros.</li>
                <li>Digite uma letra por vez ou tente adivinhar a palavra inteira.</li>
                <li>Palavras já tentadas não podem ser repetidas.</li>
                <li>Compartilhe seus resultados no Twitter ao finalizar.</li>
                <li>Você tem um tempo limitado para cada palavra, fique atento!</li>
            </ul>
        </div>
    </ModalDialog>

    <ModalDialog :isVisible="isModalVisible" @close="closeModal">
        <template #default>
            <div class="text-center py-1">
                <template v-if="props.won">
                    <h3 class="text-success">🎉 Parabéns! Você Venceu!</h3>
                    <p class="text-dark">
                        A palavra era: {{ props.word }}
                    </p>
                </template>

                <template v-else-if="props.lost">
                    <h3 class="text-danger">😢 Não foi dessa vez...</h3>
                    <p class="text-dark">
                        A palavra era: {{ props.word }}
                    </p>
                </template>

                <hr class="my-4" />

                <div class="text-center">
                    <p class="mb-2 text-primary fw-bold">
                        Próxima pergunta em
                    </p>
                    <h4 class="text-warning">{{ formattedTime }}</h4>
                </div>

                <hr class="my-4" />

                <div class="text-center">
                    <p class="text-primary fw-bold mb-2">
                        Compartilhe seu resultado
                    </p>
                    <button
                        @click="shareOnTwitter"
                        class="btn btn-outline-primary fw-bold rounded-pill px-4"
                    >
                        <font-awesome-icon
                            icon="fa-brands fa-x-twitter"
                            class="me-2"
                        />
                        Compartilhar no X
                    </button>
                </div>
            </div>
        </template>
    </ModalDialog>
</template>
