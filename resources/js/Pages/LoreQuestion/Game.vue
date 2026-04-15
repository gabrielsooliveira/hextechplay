<script setup>
import axios from "axios";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

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
        <title>ClickChallenger</title>
        <meta name="description" content="ClickChallenger é um jogo da plataforma HextechPlay que testa seus reflexos e suas habilidades." />
        <meta name="keywords" content="HextechPlay, mini games LoL, jogos online, quiz League of Legends, runeterra, diversão, jogos rápidos" />
        <meta property="og:title" content="HextechPlay – Mini Games e Quizzes de League of Legends" />
        <meta property="og:description" content="Teste seus reflexos e precisão no ClickChallenger." />
        <meta
            property="og:url"
            content="https://hextechplay.com/lorequestion/roleplay"
        />
        <link
            rel="canonical"
            href="https://hextechplay.com/lorequestion/roleplay"
        />
    </Head>

    <div
        class="d-flex justify-content-center align-items-center py-5 min-vh-100"
    >
        <div class="container">
            <div class="row justify-content-center">
                <div class="mt-3">
                    <div
                        v-if="loading"
                        class="text-center text-secondary text-light animate-fade"
                    >
                        <div class="spinner-border mb-3" role="status"></div>
                        <p>Carregando perguntas...</p>
                    </div>

                    <div
                        v-else-if="gameFinished"
                        class="card shadow-lg p-4 animate-fade"
                    >
                        <h2 class="card-title text-center fw-bold mb-4">
                            Fim do Jogo!
                        </h2>
                        <div class="card-body row">
                            <div class="col-lg-3">
                                <h5 class="mb-3">Resultados:</h5>
                                <ul class="list-group list-group-flush mb-4">
                                    <li
                                        class="list-group-item d-flex justify-content-between"
                                    >
                                        <span>Total de perguntas:</span>
                                        <strong>{{
                                            gameResults.total_questions
                                        }}</strong>
                                    </li>

                                    <li
                                        class="list-group-item d-flex justify-content-between text-success fw-bold hover-glow"
                                        role="button"
                                        tabindex="0"
                                        @click="selectedCarousel = 'correct'"
                                    >
                                        <span>Acertos:</span>
                                        <strong>{{
                                            gameResults.correct_answers.length
                                        }}</strong>
                                    </li>

                                    <li
                                        class="list-group-item d-flex justify-content-between text-danger fw-bold hover-glow"
                                        role="button"
                                        tabindex="0"
                                        v-if="
                                            gameResults.wrong_answers.length > 0
                                        "
                                        @click="selectedCarousel = 'wrong'"
                                    >
                                        <span>Erros:</span>
                                        <strong>{{
                                            gameResults.wrong_answers.length
                                        }}</strong>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-9">
                                <h5 class="mb-3">
                                    {{
                                        $t(
                                            selectedCarousel === "correct"
                                                ? "questions_you_got_right"
                                                : "questions_you_got_wrong"
                                        )
                                    }}
                                </h5>

                                <div
                                    v-if="
                                        selectedCarousel === 'correct' &&
                                        gameResults.correct_answers.length > 0
                                    "
                                >
                                    <div
                                        id="carouselCorrect"
                                        class="carousel slide px-5"
                                        data-bs-ride="carousel"
                                    >
                                        <div class="carousel-inner">
                                            <div
                                                v-for="(
                                                    answer, index
                                                ) in gameResults.correct_answers"
                                                :key="'correct-' + index"
                                                :class="[
                                                    'carousel-item',
                                                    { active: index === 0 },
                                                ]"
                                            >
                                                <div
                                                    class="px-auto text-center"
                                                >
                                                    <p>
                                                        {{
                                                            answer.question_text
                                                        }}
                                                    </p>
                                                    <p class="text-success">
                                                        <strong
                                                            >Sua resposta:</strong
                                                        >
                                                        {{ answer.user_answer }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            class="carousel-control-prev text-white"
                                            type="button"
                                            data-bs-target="#carouselCorrect"
                                            data-bs-slide="prev"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-caret-left"
                                                size="xl"
                                            />
                                        </button>
                                        <button
                                            class="carousel-control-next text-white"
                                            type="button"
                                            data-bs-target="#carouselCorrect"
                                            data-bs-slide="next"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-caret-right"
                                                size="xl"
                                            />
                                        </button>
                                    </div>
                                </div>

                                <div
                                    v-if="
                                        selectedCarousel === 'wrong' &&
                                        gameResults.wrong_answers.length > 0
                                    "
                                >
                                    <div
                                        id="carouselWrong"
                                        class="carousel slide px-5"
                                        data-bs-ride="carousel"
                                    >
                                        <div class="carousel-inner">
                                            <div
                                                v-for="(
                                                    error, index
                                                ) in gameResults.wrong_answers"
                                                :key="'wrong-' + index"
                                                :class="[
                                                    'carousel-item',
                                                    { active: index === 0 },
                                                ]"
                                            >
                                                <div
                                                    class="px-auto text-center"
                                                >
                                                    <p>
                                                        <strong
                                                            >Sua resposta:</strong
                                                        >
                                                        <span
                                                            class="text-decoration-line-through"
                                                            >{{
                                                                error.user_answer ===
                                                                "timeout"
                                                                    ? "Tempo esgotado"
                                                                    : error.user_answer
                                                            }}</span
                                                        >
                                                    </p>
                                                    <p>
                                                        <strong
                                                            >Resposta correta:</strong
                                                        >
                                                        <span
                                                            class="text-success"
                                                            >{{
                                                                error.correct_answer
                                                            }}</span
                                                        >
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            class="carousel-control-prev text-white"
                                            type="button"
                                            data-bs-target="#carouselWrong"
                                            data-bs-slide="prev"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-caret-left"
                                                size="xl"
                                            />
                                        </button>
                                        <button
                                            class="carousel-control-next text-white"
                                            type="button"
                                            data-bs-target="#carouselWrong"
                                            data-bs-slide="next"
                                        >
                                            <font-awesome-icon
                                                icon="fa-solid fa-caret-right"
                                                size="xl"
                                            />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-4 d-flex flex-column flex-md-row justify-content-center align-items-center gap-2"
                            >
                                <Link
                                    class="btn btn-accent btn-lg w-100"
                                    :href="route('lorequestion.roleplay')"
                                >
                                    Jogar Novamente
                                </Link>
                                <Link
                                    class="btn btn-dark btn-lg w-100"
                                    :href="route('lorequestion.index')"
                                >
                                    Voltar para os jogos
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else-if="!currentQuestion"
                        class="card shadow-lg p-4 animate-fade text-center"
                    >
                        <p class="text-danger mb-3">
                            Nenhuma pergunta encontrada.
                        </p>
                        <button class="btn btn-primary" @click="fetchQuestions">
                            Tentar Novamente
                        </button>
                    </div>

                    <div v-else class="card shadow-lg p-4 animate-fade">
                        <div class="card-body">
                            <div class="container py-4 text-center">
                                <p class="fw-bold fs-3 mb-4">
                                    {{ currentQuestion.text }}
                                </p>
                                <div
                                    class="d-inline-block py-2 rounded-1 bg-warning text-white fs-4 shadow col-3 col-lg-1"
                                >
                                    {{ timer }}s
                                </div>

                                <div
                                    class="row g-3 justify-content-center mt-3"
                                >
                                    <div
                                        v-for="(
                                            option, index
                                        ) in currentQuestion.options"
                                        :key="index"
                                        class="col-12 col-sm-10 col-md-6"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-dark w-100 py-3 fs-5 rounded-3 shadow text-wrap"
                                            :class="{
                                                'btn-primary': !selectedAnswer,
                                                'btn-success':
                                                    isSubmitting &&
                                                    selectedAnswer === option,
                                                'btn-secondary':
                                                    selectedAnswer &&
                                                    !isSubmitting,
                                            }"
                                            :disabled="isSubmitting"
                                            @click="submitAnswer(option)"
                                        >
                                            {{ option }}
                                        </button>
                                    </div>
                                </div>
                                <div v-if="isSubmitting" class="mt-4">
                                    <div
                                        class="spinner-border text-warning"
                                        role="status"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
