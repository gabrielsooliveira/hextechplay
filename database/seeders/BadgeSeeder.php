<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // WordLoL Badges
            ['name' => 'Iniciante das Palavras', 'description' => 'Adivinhe 1 palavra no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'bronze', 'game' => 'WordLoL', 'mode' => null, 'requirement_type' => 'wins', 'requirement_value' => 1],
            ['name' => 'Mestre das Palavras', 'description' => 'Adivinhe 10 palavras no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'gold', 'game' => 'WordLoL', 'mode' => null, 'requirement_type' => 'wins', 'requirement_value' => 10],
            ['name' => 'Lenda das Palavras', 'description' => 'Adivinhe 50 palavras no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'diamond', 'game' => 'WordLoL', 'mode' => null, 'requirement_type' => 'wins', 'requirement_value' => 50],

            // LoreQuestion Badges
            ['name' => 'Estudante de Runeterra', 'description' => 'Acerte 10 perguntas de LoreQuestion no nivel facil', 'icon' => 'fas fa-book', 'tier' => 'bronze', 'game' => 'LoreQuestion', 'mode' => 'easy', 'requirement_type' => 'correct_answers', 'requirement_value' => 10],
            ['name' => 'Historiador', 'description' => 'Acerte 10 perguntas de LoreQuestion no nivel medio', 'icon' => 'fas fa-book-open', 'tier' => 'gold', 'game' => 'LoreQuestion', 'mode' => 'medium', 'requirement_type' => 'correct_answers', 'requirement_value' => 10],
            ['name' => 'Ryze Reencarnado', 'description' => 'Acerte 10 perguntas de LoreQuestion no nivel dificil', 'icon' => 'fas fa-scroll', 'tier' => 'challenger', 'game' => 'LoreQuestion', 'mode' => 'hard', 'requirement_type' => 'correct_answers', 'requirement_value' => 10],

            // ClickChallenger Badges (Survival Mode)
            ['name' => 'Pontuador', 'description' => 'Alcance 1.000 pontos no ClickChallenger (Sobrevivência)', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'bronze', 'game' => 'ClickChallenger', 'mode' => 'survival', 'requirement_type' => 'score', 'requirement_value' => 1000],
            ['name' => 'Pontuador', 'description' => 'Alcance 10.000 pontos no ClickChallenger (Sobrevivência)', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'gold', 'game' => 'ClickChallenger', 'mode' => 'survival', 'requirement_type' => 'score', 'requirement_value' => 10000],
            ['name' => 'Pontuador', 'description' => 'Alcance 100.000 pontos no ClickChallenger (Sobrevivência)', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'diamond', 'game' => 'ClickChallenger', 'mode' => 'survival', 'requirement_type' => 'score', 'requirement_value' => 100000],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name'], 'tier' => $badge['tier'], 'game' => $badge['game'], 'mode' => $badge['mode']],
                $badge
            );
        }
    }
}
