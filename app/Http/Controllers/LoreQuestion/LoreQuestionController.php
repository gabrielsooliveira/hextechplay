<?php

namespace App\Http\Controllers\LoreQuestion;

use App\DTOs\AnswerDTO;
use App\DTOs\GameSettingsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoreQuestion\AwserRequest;
use App\Http\Requests\LoreQuestion\SettingsRequest;
use App\UseCases\LoreQuestion\FinishGameUseCase;
use App\UseCases\LoreQuestion\StartRoleplayGameUseCase;
use App\UseCases\LoreQuestion\StartGameUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class LoreQuestionController extends Controller
{
    public function __construct(
        protected FinishGameUseCase $finishGameUseCase,
        protected StartRoleplayGameUseCase $startRoleplayGame,
        protected StartGameUseCase $startGame
    ) {
    }

    public function index()
    {
        return inertia('Menu', [
            'currentRoute' => Route::currentRouteName()
        ]);
    }

    public function roleplay(SettingsRequest $request)
    {
        $settings = GameSettingsDTO::fromArray($request->validated());
        $this->startRoleplayGame->execute($settings);

        return inertia('LoreQuestion/Game');
    }

    public function startGame(): JsonResponse
    {
        $locale = str_starts_with(app()->getLocale(), 'pt') ? 'pt' : app()->getLocale();
        $questions = $this->startGame->execute($locale);

        return response()->json([
            'questions' => $questions
        ]);
    }

    public function finishGame(AwserRequest $request, FinishGameUseCase $useCase)
    {
        $locale = str_starts_with(app()->getLocale(), 'pt') ? 'pt' : app()->getLocale();

        $answers = collect($request->validated('answers'))
            ->map(fn($resp) => AnswerDTO::fromArray($resp))
            ->toArray();

        $result = $useCase->execute($answers, $locale);

        if (auth()->check() && count($result->correctAnswers) > 0) {
            $achievementService = app(\App\Services\AchievementService::class);
            $unlockedBadges = $achievementService->incrementStatAndCheck(auth()->user(), 'LoreQuestion', 'correct_answers', count($result->correctAnswers));
            // Since this is an API call, we could return it in the json
            $resultArray = $result->toArray();
            $resultArray['new_badges'] = $unlockedBadges;
            return response()->json($resultArray);
        }

        return response()->json($result->toArray());
    }
}
