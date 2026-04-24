<?php

namespace Database\Seeders;

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
            ['name' => 'Iniciante das Palavras', 'description' => 'Adivinhe 1 palavra no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'bronze', 'game' => 'WordLoL', 'requirement_type' => 'wins', 'requirement_value' => 1],
            ['name' => 'Mestre das Palavras', 'description' => 'Adivinhe 10 palavras no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'gold', 'game' => 'WordLoL', 'requirement_type' => 'wins', 'requirement_value' => 10],
            ['name' => 'Lenda das Palavras', 'description' => 'Adivinhe 50 palavras no WordLoL', 'icon' => 'fas fa-keyboard', 'tier' => 'diamond', 'game' => 'WordLoL', 'requirement_type' => 'wins', 'requirement_value' => 50],

            // LoreQuestion Badges
            ['name' => 'Estudante de Runeterra', 'description' => 'Acerte 10 perguntas de Lore', 'icon' => 'fas fa-book', 'tier' => 'bronze', 'game' => 'LoreQuestion', 'requirement_type' => 'correct_answers', 'requirement_value' => 10],
            ['name' => 'Historiador', 'description' => 'Acerte 50 perguntas de Lore', 'icon' => 'fas fa-book-open', 'tier' => 'gold', 'game' => 'LoreQuestion', 'requirement_type' => 'correct_answers', 'requirement_value' => 50],
            ['name' => 'Ryze Reencarnado', 'description' => 'Acerte 200 perguntas de Lore', 'icon' => 'fas fa-scroll', 'tier' => 'challenger', 'game' => 'LoreQuestion', 'requirement_type' => 'correct_answers', 'requirement_value' => 200],

            // ClickChallenger Badges
            ['name' => 'Pontuador', 'description' => 'Alcance 1.000 pontos no ClickChallenger', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'bronze', 'game' => 'ClickChallenger', 'requirement_type' => 'score', 'requirement_value' => 1000],
            ['name' => 'Pontuador', 'description' => 'Alcance 10.000 pontos no ClickChallenger', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'gold', 'game' => 'ClickChallenger', 'requirement_type' => 'score', 'requirement_value' => 10000],
            ['name' => 'Pontuador', 'description' => 'Alcance 100.000 pontos no ClickChallenger', 'icon' => 'fas fa-mouse-pointer', 'tier' => 'diamond', 'game' => 'ClickChallenger', 'requirement_type' => 'score', 'requirement_value' => 100000],
        ];

        foreach ($badges as $badge) {
            \App\Models\Badge::updateOrCreate(
                ['name' => $badge['name'], 'tier' => $badge['tier'], 'game' => $badge['game']],
                $badge
            );
        }
    }
}
