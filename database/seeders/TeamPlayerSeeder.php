<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Team;
use App\Models\Player;
use App\Models\Title;

class TeamPlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Teams
        $t1 = Team::create([
            'name' => 'T1',
            'acronym' => 'T1',
            'region' => 'LCK',
            'logo' => 'https://static.wikia.nocookie.net/lolesports_gamepedia_en/images/4/40/T1logo_square.png',
            'description' => 'A organização mais vitoriosa da história do League of Legends.'
        ]);

        $loud = Team::create([
            'name' => 'LOUD',
            'acronym' => 'LOUD',
            'region' => 'CBLOL',
            'logo' => 'https://static.wikia.nocookie.net/lolesports_gamepedia_en/images/2/2a/LOUDlogo_square.png',
            'description' => 'A maior organização do Brasil, dominante no cenário nacional.'
        ]);

        $g2 = Team::create([
            'name' => 'G2 Esports',
            'acronym' => 'G2',
            'region' => 'LEC',
            'logo' => 'https://static.wikia.nocookie.net/lolesports_gamepedia_en/images/7/77/G2_Esportslogo_square.png',
            'description' => 'Conhecidos pelo estilo agressivo e criativo na Europa.'
        ]);

        // Players for T1
        $faker = Player::create([
            'nickname' => 'Faker',
            'name' => 'Lee Sang-hyeok',
            'role' => 'Mid',
            'team_id' => $t1->id,
            'nationality' => 'South Korea',
            'photo' => 'https://static.wikia.nocookie.net/lolesports_gamepedia_en/images/c/c5/T1_Faker_2024_Split_1.png'
        ]);

        Player::create([
            'nickname' => 'Zeus',
            'name' => 'Choi Woo-je',
            'role' => 'Top',
            'team_id' => $t1->id,
            'nationality' => 'South Korea'
        ]);

        // Players for LOUD
        Player::create([
            'nickname' => 'Tinowns',
            'name' => 'Thiago Sartori',
            'role' => 'Mid',
            'team_id' => $loud->id,
            'nationality' => 'Brazil',
            'photo' => 'https://static.wikia.nocookie.net/lolesports_gamepedia_en/images/5/5a/LOUD_Tinowns_2024_Split_1.png'
        ]);

        Player::create([
            'nickname' => 'Robo',
            'name' => 'Leonardo Souza',
            'role' => 'Top',
            'team_id' => $loud->id,
            'nationality' => 'Brazil'
        ]);

        // Players for G2
        Player::create([
            'nickname' => 'Caps',
            'name' => 'Rasmus Winther',
            'role' => 'Mid',
            'team_id' => $g2->id,
            'nationality' => 'Denmark'
        ]);

        // Titles
        $t1->titles()->create([
            'name' => 'World Champion',
            'year' => 2023,
            'description' => 'Campeão mundial de League of Legends.'
        ]);

        $t1->titles()->create([
            'name' => 'World Champion',
            'year' => 2016,
            'description' => 'Campeão mundial de League of Legends.'
        ]);

        $loud->titles()->create([
            'name' => 'CBLOL Champion',
            'year' => 2024,
            'description' => 'Campeão da primeira etapa do CBLOL.'
        ]);

        $g2->titles()->create([
            'name' => 'MSI Champion',
            'year' => 2019,
            'description' => 'Campeão do Mid-Season Invitational.'
        ]);

        // Player Title
        $faker->titles()->create([
            'name' => 'MVP World Finals',
            'year' => 2016,
            'description' => 'Jogador mais valioso da final mundial.'
        ]);
    }
}
