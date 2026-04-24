<script setup>
import axios from "axios";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import LoreBackground from "@/assets/images/lorequestion.png";

const selectedCarousel = ref("correct");
const loading = ref(true);
const questions = ref([]);
const currentQuestionIndex = ref(0);
const selectedAnswer = ref(null);
const isSubmitting = ref(false);
const gameFinished = ref(false);
const gameResults = ref(null);
const timer = ref(15);
const timerInterval = ref(null);
const userAnswers = ref([]);
const currentQuestion = ref(null);

function startTimer() {
    clearInterval(timerInterval.value);
    timer.value = 15;

    timerInterval.value = setInterval(() => {
        if (timer.value > 0) {
            timer.value--;
        } else {
            clearInterval(timerInterval.value);
            submitAnswer("timeout");
        }
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval.value);
}

async function fetchQuestions() {
    loading.value = true;
    try {
        const response = await axios.get(route("startGame"));
        questions.value = response.data.questions;
        if (questions.value.length > 0) {
            currentQuestion.value = questions.value[currentQuestionIndex.value];
            startTimer();
        }
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
}

function submitAnswer(answerValue) {
    stopTimer();
    isSubmitting.value = true;

    userAnswers.value.push({
        question_id: currentQuestion.value.uuid,
        answer: answerValue,
    });

    if (currentQuestionIndex.value + 1 >= questions.value.length) {
        setTimeout(async () => {
            await finishGame();
        }, 1500);
    } else {
        setTimeout(() => {
            currentQuestionIndex.value++;
            currentQuestion.value = questions.value[currentQuestionIndex.value];
            selectedAnswer.value = null;
            isSubmitting.value = false;
            startTimer();
        }, 1500);
    }
}

async function finishGame() {
    isSubmitting.value = true;
    try {
        const response = await axios.post(route("finishGame"), {
            answers: userAnswers.value,
        });
        gameResults.value = response.data;
        gameFinished.value = true;
    } catch (error) {
        console.error(error);
    } finally {
        isSubmitting.value = false;
    }
}

onMounted(() => {
    fetchQuestions();
});
</script>

<template>
    <Head>
        <title>LoreQuestions</title>
        <meta name="description" content="Teste seus conhecimentos sobre o universo de League of Legends no Quiz de Lore da HextechPlay." />
        <meta name="keywords" content="HextechPlay, lore lol, quiz league of legends, runeterra, historia lol" />
        <meta property="og:title" content="HextechPlay – Quiz de Lore" />
        <meta property="og:description" content="Você conhece realmente a história de Runeterra?" />
        <meta property="og:url" content="https://hextechplay.com/lorequestion/roleplay" />
        <link rel="canonical" href="https://hextechplay.com/lorequestion/roleplay" />
    </Head>

    <div class="game-background-overlay" :style="{ backgroundImage: `url(${LoreBackground})` }"></div>

    <div class="d-flex justify-content-center align-items-center py-5 min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="mt-3">
                    <div v-if="loading" class="text-center text-secondary text-light animate-fade">
                        <div class="spinner-border mb-3" role="status"></div>
                        <p>Carregando perguntas...</p>
                    </div>

                    <div v-else-if="gameFinished" class="glass-panel shadow-lg p-4 animate-fade">
                        <h2 class="card-title text-center fw-bold mb-4 game-title">
                            Fim do Jogo!
                        </h2>
                        <div class="card-body row">
                            <div class="col-lg-3">
                                <h5 class="mb-3">Resultados:</h5>
                                <ul class="list-group list-group-flush mb-4">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Total de perguntas:</span>
                                        <strong>{{ gameResults.total_questions }}</strong>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between text-success fw-bold hover-glow" role="button" tabindex="0" @click="selectedCarousel = 'correct'">
                                        <span>Acertos:</span>
                                        <strong>{{ gameResults.correct_answers.length }}</strong>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between text-danger fw-bold hover-glow" role="button" tabindex="0" v-if="gameResults.wrong_answers.length > 0" @click="selectedCarousel = 'wrong'">
                                        <span>Erros:</span>
                                        <strong>{{ gameResults.wrong_answers.length }}</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-9">
                                <h5 class="mb-3 text-white">
                                    {{ selectedCarousel === "correct" ? "Questões que você acertou" : "Questões que você errou" }}
                                </h5>

                                <div v-if="selectedCarousel === 'correct' && gameResults.correct_answers.length > 0">
                                    <div id="carouselCorrect" class="carousel slide px-5" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div v-for="(answer, index) in gameResults.correct_answers" :key="'correct-' + index" :class="['carousel-item', index === 0 ? 'active' : '']">
                                                <div class="card bg-dark bg-opacity-50 text-white border border-success border-opacity-25 rounded-4 shadow-sm">
                                                    <div class="card-body p-4 text-center">
                                                        <h5 class="card-title mb-4 game-title fs-4">
                                                            {{ answer.question_text }}
                                                        </h5>
                                                        <div>
                                                            <div class="small opacity-75 mb-1 text-uppercase">Sua Resposta:</div>
                                                            <span class="badge bg-success fs-5 px-4 pill shadow-sm">
                                                                {{ answer.user_answer }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev text-white" type="button" data-bs-target="#carouselCorrect" data-bs-slide="prev">
                                            <font-awesome-icon icon="fa-solid fa-caret-left" size="2xl"/>
                                        </button>
                                        <button class="carousel-control-next text-white" type="button" data-bs-target="#carouselCorrect" data-bs-slide="next">
                                            <font-awesome-icon icon="fa-solid fa-caret-right" size="2xl"/>
                                        </button>
                                    </div>
                                </div>

                                <div v-else-if="selectedCarousel === 'wrong' && gameResults.wrong_answers.length > 0">
                                    <div id="carouselWrong" class="carousel slide px-5" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div v-for="(answer, index) in gameResults.wrong_answers" :key="'wrong-' + index" :class="['carousel-item', index === 0 ? 'active' : '']">
                                                <div class="card bg-dark bg-opacity-50 text-white border border-danger border-opacity-25 rounded-4 shadow-sm">
                                                    <div class="card-body p-4 text-center">
                                                        <h5 class="card-title mb-4 game-title fs-4">
                                                            {{ answer.question_text }}
                                                        </h5>
                                                        <div class="row g-3">
                                                            <div class="col-6 border-end border-white border-opacity-10">
                                                                <div class="small opacity-75 mb-1 text-uppercase">Sua Resposta:</div>
                                                                <span class="badge bg-danger fs-6 px-3 py-2 text-decoration-line-through opacity-75">
                                                                    {{ answer.user_answer === 'timeout' ? 'Tempo Esgotado' : answer.user_answer }}
                                                                </span>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="small opacity-75 mb-1 text-uppercase text-success">Resposta Correta:</div>
                                                                <span class="badge bg-success fs-6 px-3 py-2">
                                                                    {{ answer.correct_answer }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev text-white" type="button" data-bs-target="#carouselWrong" data-bs-slide="prev">
                                            <font-awesome-icon icon="fa-solid fa-caret-left" size="2xl"/>
                                        </button>
                                        <button class="carousel-control-next text-white" type="button" data-bs-target="#carouselWrong" data-bs-slide="next">
                                            <font-awesome-icon icon="fa-solid fa-caret-right" size="2xl"/>
                                        </button>
                                </div>
                            </div>
                            </div>

                            <div class="mt-4 d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                                <Link class="btn game-btn btn-lg w-100" :href="route('lorequestion.roleplay')">
                                    <font-awesome-icon icon="fas fa-redo" class="me-2"/> Jogar Novamente
                                </Link>
                                <Link class="btn game-btn-primary btn-lg w-100" :href="route('lorequestion.index')">
                                    Voltar para os jogos
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="!currentQuestion" class="glass-panel shadow-lg p-4 animate-fade text-center">
                        <p class="text-danger mb-3">
                            Nenhuma pergunta encontrada.
                        </p>
                        <button class="btn btn-primary" @click="fetchQuestions">
                            Tentar Novamente
                        </button>
                    </div>

                    <div v-else class="glass-panel shadow-lg p-4 animate-fade">
                        <div class="card-body">
                            <div class="container py-4 text-center">
                                <p class="fw-bold fs-3 mb-4 text-white animate-pop-in">
                                    {{ currentQuestion.text }}
                                </p>
                                <div class="d-inline-block py-2 px-4 rounded-pill bg-warning text-dark fw-bold fs-4 shadow-lg mb-4">
                                    {{ timer }}s
                                </div>

                                <div class="row g-3 justify-content-center mt-3">
                                    <div v-for="(option, index) in currentQuestion.options" :key="index" class="col-12">
                                        <button type="button" class="btn w-100 py-3 fs-5 rounded-3 shadow text-wrap" :class="{'game-btn': !selectedAnswer, 'game-btn-success': isSubmitting && selectedAnswer === option, 'btn-secondary': selectedAnswer && !isSubmitting}" :disabled="isSubmitting" @click="submitAnswer(option)">
                                            {{ option }}
                                        </button>
                                    </div>
                                </div>
                                <div v-if="isSubmitting" class="mt-4">
                                    <div class="spinner-border text-warning" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
