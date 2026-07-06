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
        DB::statement('DELETE FROM articles');

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
            ['Techiman Terriers', '#4b0082', '#ffffff'],
            ['Obuasi Orbits', '#ffd700', '#000000'],
            ['Tarkwa Titans', '#c0c0c0', '#000000'],
            ['Berekum Blues', '#0000ff', '#ffffff'],
            ['Dormaa Dragons', '#006400', '#ffffff'],
            ['Aba Warriors', '#ff4500', '#ffffff'],
            ['Lagos Legends', '#ffffff', '#008000'],
            ['Cairo Cobras', '#ff0000', '#000000'],
            ['Casablanca Cats', '#ff0000', '#00ff00'],
            ['Nairobi Nightmares', '#000000', '#ffffff'],
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
                'home_stadium' => $t[0] . ' Arena',
                'city' => 'City ' . ($index + 1),
                'founded_year' => '2020',
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
            $positions = ['GK', 'DEF', 'DEF', 'DEF', 'DEF', 'MID', 'MID', 'MID', 'MID', 'FWD', 'FWD'];
            foreach ($positions as $pIndex => $pos) {
                Player::create([
                    'team_id' => $team->id,
                    'name' => $t[0] . ' Player ' . ($pIndex + 1),
                    'position' => $pos,
                    'goals' => rand(0, 15),
                    'assists' => rand(0, 10),
                    'yellow_cards' => rand(0, 5),
                    'red_cards' => rand(0, 1),
                    'appearances' => rand(5, 20),
                    'rating' => rand(70, 95),
                    'nationality' => '🇬🇭',
                    'number' => rand(1, 99),
                ]);
            }
        }

        // Create some finished games for standings
        for ($i = 0; $i < 30; $i++) {
            $hIndex = rand(0, 19);
            $aIndex = rand(0, 19);
            while($hIndex == $aIndex) $aIndex = rand(0, 19);

            $h = $teamModels[$hIndex];
            $a = $teamModels[$aIndex];
            Game::create([
                'home_team_id' => $h->id,
                'away_team_id' => $a->id,
                'home_score' => rand(0, 4),
                'away_score' => rand(0, 4),
                'kickoff' => now()->subDays(rand(1, 20)),
                'matchweek' => rand(1, 5),
                'status' => 'finished',
                'venue' => $h->home_stadium,
            ]);
        }

        // Create upcoming games for predictions
        for ($i = 0; $i < 10; $i++) {
            $hIndex = rand(0, 19);
            $aIndex = rand(0, 19);
            while($hIndex == $aIndex) $aIndex = rand(0, 19);

            $h = $teamModels[$hIndex];
            $a = $teamModels[$aIndex];
            Game::create([
                'home_team_id' => $h->id,
                'away_team_id' => $a->id,
                'kickoff' => now()->addDays(rand(1, 7)),
                'matchweek' => 6,
                'status' => 'upcoming',
                'venue' => $h->home_stadium,
            ]);
        }

        Article::create([
            'title' => 'League Season 2024/25 Officially Kicks Off!',
            'content' => 'The highly anticipated football season has started with some thrilling matches across the country.',
            'tag' => 'Announcement',
            'is_published' => true,
        ]);

        Article::create([
            'title' => 'Transfer Window Summary',
            'content' => 'Many teams have strengthened their squads during the off-season. Here is a look at the biggest moves.',
            'tag' => 'Transfer',
            'is_published' => true,
        ]);
    }
}
