<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('DELETE FROM predictions');
        DB::statement('DELETE FROM comments');
        DB::statement('DELETE FROM votes');
        DB::statement('DELETE FROM players');
        DB::statement('DELETE FROM games');
        DB::statement('DELETE FROM users');
        DB::statement('DELETE FROM owners');
        DB::statement('DELETE FROM payments');
        DB::statement('DELETE FROM teams');

        $teams = [
            ['Accra Lions', '#ff0000', '#ffffff'],
            ['Cape Coast Stars', '#0000ff', '#ffff00'],
            ['Kumasi Warriors', '#00ff00', '#000000'],
            ['Tamale Tigers', '#ffa500', '#000000'],
            ['Sekondi Sailors', '#00ced1', '#ffffff'],
            ['Koforidua Kings', '#800080', '#ffffff'],
            ['Ho Hurricanes', '#ff69b4', '#000000'],
            ['Sunyani Suns', '#ffd700', '#ff4500'],
            ['Bolga Bulls', '#8b4513', '#ffffff'],
            ['Wa Wizards', '#2f4f4f', '#ffffff'],
        ];

        $teamModels = [];
        foreach ($teams as $index => $t) {
            $team = Team::create([
                'reference_code' => 'APX-2025-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'team_name' => $t[0],
                'team_size' => '11',
                'division' => 'premier',
                'primary_color' => $t[1],
                'secondary_color' => $t[2],
                'registration_status' => 'approved',
                'password' => Hash::make('password'),
            ]);
            $teamModels[] = $team;

            User::create([
                'name' => $t[0] . ' Manager',
                'email' => strtolower(str_replace(' ', '', $t[0])) . '@league.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'team_id' => $team->id,
            ]);

            // Add players for each team
            $positions = ['GK', 'DEF', 'DEF', 'MID', 'MID', 'MID', 'FWD', 'FWD'];
            foreach ($positions as $pos) {
                Player::create([
                    'team_id' => $team->id,
                    'name' => 'Player ' . rand(1, 1000),
                    'position' => $pos,
                    'goals' => rand(0, 10),
                    'rating' => rand(70, 95),
                    'nationality' => '🇬🇭',
                ]);
            }
        }

        // Create some finished games for standings
        for ($i = 0; $i < 10; $i++) {
            $h = $teamModels[rand(0, 4)];
            $a = $teamModels[rand(5, 9)];
            Game::create([
                'home_team_id' => $h->id,
                'away_team_id' => $a->id,
                'home_score' => rand(0, 4),
                'away_score' => rand(0, 4),
                'kickoff' => now()->subDays(rand(1, 10)),
                'matchweek' => 1,
                'status' => 'finished',
                'venue' => $h->team_name . ' Ground',
            ]);
        }

        // Create upcoming games for predictions
        for ($i = 0; $i < 5; $i++) {
            $h = $teamModels[rand(0, 9)];
            $a = $teamModels[rand(0, 9)];
            if ($h->id == $a->id) continue;
            Game::create([
                'home_team_id' => $h->id,
                'away_team_id' => $a->id,
                'kickoff' => now()->addDays(rand(1, 5)),
                'matchweek' => 2,
                'status' => 'upcoming',
                'venue' => $h->team_name . ' Ground',
            ]);
        }

        Article::create([
            'title' => 'League Season 2024/25 Officially Kicks Off!',
            'content' => 'The highly anticipated football season has started with some thrilling matches across the country.',
            'tag' => 'Announcement',
            'is_published' => true,
        ]);
    }
}
