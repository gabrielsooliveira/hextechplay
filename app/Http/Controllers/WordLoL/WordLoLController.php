<?php

namespace App\Http\Controllers\WordLoL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyWord;
use App\Models\WordLolAttempt;
use Carbon\Carbon;
use App\Services\AchievementService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class WordLoLController extends Controller
{
    protected AchievementService $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function index(Request $request)
    {
        $today = Carbon::now('America/Sao_Paulo')->toDateString();
        $daily = DailyWord::with('word')->where('date', $today)->first();

        if (!$daily) {
            abort(404, "Palavra do dia ainda não definida.");
        }

        $guestId = $this->getOrCreateGuestId($request);
        $attempt = $this->getAttempt($daily, $guestId);
        $state = $attempt->state;

        $timeRemaining = $this->getTimeRemaining();

        $response = inertia('WordLoL/Game', [
            'displayWord' => $this->getDisplayWord($state),
            'guessed' => $state['guessed'],
            'wrongLetters' => $state['wrongLetters'],
            'wrong' => $state['wrong'],
            'lost' => $state['lost'],
            'won' => $state['won'],
            'finished' => $state['finished'],
            'attempts' => $state['attempts'],
            'maxAttempts' => $state['maxAttempts'],
            'word' => $state['lost'] || $state['won'] ? $state['word'] : null,
            'timeRemaining' => $timeRemaining
        ]);

        if (!$request->cookie('wordlol_guest_id')) {
            $response->withCookie(cookie()->forever('wordlol_guest_id', $guestId));
        }

        return $response;
    }

    public function guess(Request $request)
    {
        $today = Carbon::now('America/Sao_Paulo')->toDateString();
        $daily = DailyWord::with('word')->where('date', $today)->first();

        if (!$daily) {
            return response()->json(['error' => 'Palavra do dia não encontrada.'], 404);
        }

        $guestId = $this->getOrCreateGuestId($request);
        $attempt = $this->getAttempt($daily, $guestId);
        $state = $attempt->state;

        if ($state['finished']) {
            return response()->json([
                'error' => 'Você já jogou hoje! Volte amanhã para a próxima palavra.',
                'displayWord' => $this->getDisplayWord($state),
                'guessed' => $state['guessed'],
                'wrongLetters' => $state['wrongLetters'],
                'wrong' => $state['wrong'],
                'lost' => $state['lost'],
                'won' => $state['won'],
                'maxAttempts' => $state['maxAttempts'],
                'finished' => $state['finished'],
                'word' => $state['lost'] || $state['won'] ? $state['word'] : null,
            ]);
        }

        $letter = strtoupper($request->input('letter', ''));
        $wordInput = strtoupper($request->input('word', ''));

        if ($wordInput) {
            $state['attempts']++;
            if ($wordInput === $state['word']) {
                $state['won'] = true;
            } else {
                $state['wrong']++;
                if ($state['wrong'] >= $state['maxAttempts']) {
                    $state['lost'] = true;
                }
            }
        } elseif ($letter && !in_array($letter, $state['guessed'])) {
            $state['attempts']++;
            $state['guessed'][] = $letter;

            if (strpos($state['word'], $letter) !== false) {
                if ($this->hasWon($state)) {
                    $state['won'] = true;
                }
            } else {
                $state['wrongLetters'][] = $letter;
                $state['wrong']++;
                if ($state['wrong'] >= $state['maxAttempts']) {
                    $state['lost'] = true;
                }
            }
        }

        if ($state['lost'] || $state['won']) {
            $state['finished'] = true;

            if ($state['won'] && auth()->check()) {
                $unlockedBadges = $this->achievementService->incrementStatAndCheck(auth()->user(), 'WordLoL', 'wins');
                if (!empty($unlockedBadges)) {
                    $request->session()->flash('new_badges', $unlockedBadges);
                }
            }
        }

        $attempt->state = $state;
        $attempt->finished = $state['finished'];
        $attempt->save();

        $timeRemaining = $this->getTimeRemaining();

        $response = response()->json([
            'displayWord' => $this->getDisplayWord($state),
            'guessed' => $state['guessed'],
            'wrongLetters' => $state['wrongLetters'],
            'wrong' => $state['wrong'],
            'lost' => $state['lost'],
            'won' => $state['won'],
            'finished' => $state['finished'],
            'attempts' => $state['attempts'],
            'maxAttempts' => $state['maxAttempts'],
            'word' => $state['lost'] || $state['won'] ? $state['word'] : null,
            'timeRemaining' => $timeRemaining
        ]);

        if (!$request->cookie('wordlol_guest_id')) {
            $response->withCookie(cookie()->forever('wordlol_guest_id', $guestId));
        }

        return $response;
    }

    private function getOrCreateGuestId(Request $request)
    {
        return $request->cookie('wordlol_guest_id') ?? (string) Str::uuid();
    }

    private function getAttempt(DailyWord $daily, string $guestId)
    {
        $userId = auth()->id();

        // 1. Tentar encontrar por usuário
        if ($userId) {
            $attempt = WordLolAttempt::where('user_id', $userId)
                ->where('daily_word_id', $daily->id)
                ->first();

            if ($attempt) {
                return $attempt;
            }

            // 2. Se não encontrou por usuário, tentar migrar do guest_id se existir
            $guestAttempt = WordLolAttempt::where('guest_id', $guestId)
                ->where('daily_word_id', $daily->id)
                ->whereNull('user_id')
                ->first();

            if ($guestAttempt) {
                $guestAttempt->user_id = $userId;
                $guestAttempt->save();
                return $guestAttempt;
            }
        } else {
            // 3. Tentar encontrar por guest_id
            $attempt = WordLolAttempt::where('guest_id', $guestId)
                ->where('daily_word_id', $daily->id)
                ->whereNull('user_id')
                ->first();

            if ($attempt) {
                return $attempt;
            }
        }

        // 4. Se não existir, criar novo
        return WordLolAttempt::create([
            'user_id' => $userId,
            'guest_id' => $userId ? null : $guestId,
            'daily_word_id' => $daily->id,
            'state' => [
                'date' => $daily->date->toDateString(),
                'word' => strtoupper($daily->word->name),
                'guessed' => [],
                'wrongLetters' => [],
                'wrong' => 0,
                'attempts' => 0,
                'maxAttempts' => $daily->word->max_attempts,
                'lost' => false,
                'won' => false,
                'finished' => false,
            ]
        ]);
    }

    private function getDisplayWord(array $state): string
    {
        $word = $state['word'];
        $guessed = $state['guessed'];

        $display = '';
        for ($i = 0; $i < strlen($word); $i++) {
            $char = $word[$i];
            if (in_array($char, $guessed) || $state['lost'] || $state['won'] || $char === ' ') {
                $display .= $char;
            } else {
                $display .= '_';
            }
            if ($i < strlen($word) - 1) {
                $display .= ' ';
            }
        }

        return $display;
    }

    private function hasWon(array $state): bool
    {
        $word = str_replace(' ', '', $state['word']);
        $guessed = $state['guessed'];

        foreach (str_split($word) as $char) {
            if (!in_array($char, $guessed)) {
                return false;
            }
        }

        return true;
    }

    private function getTimeRemaining(): array
    {
        $now = Carbon::now('America/Sao_Paulo');
        $nextMidnight = $now->copy()->addDay()->startOfDay();
        $diff = $nextMidnight->diff($now);

        return [
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s
        ];
    }
}
