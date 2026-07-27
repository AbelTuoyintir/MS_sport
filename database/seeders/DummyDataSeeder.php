<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Player;
use App\Models\Game;
use App\Models\Article;
use App\Models\User;
use App\Models\MatchEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::table('predictions')->delete();
        DB::table('comments')->delete();
        DB::table('votes')->delete();
        DB::table('match_events')->delete();
        DB::table('players')->delete();
        DB::table('games')->delete();
        DB::table('users')->delete();
        DB::table('owners')->delete();
        DB::table('payments')->delete();
        DB::table('teams')->delete();
        DB::statement('PRAGMA foreign_keys = ON');

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
            ['Tema Titans', '#333333', '#ffffff'],
            ['Obuasi Oracles', '#ffd700', '#000000'],
            ['Tarkwa Trojans', '#ffff00', '#0000ff'],
            ['Berekum Braves', '#008000', '#ffffff'],
            ['Axim Archers', '#000080', '#ffffff'],
            ['Elmina Eagles', '#ffffff', '#000000'],
            ['Winneba Wolves', '#ff4500', '#ffffff'],
            ['Nkawkaw Knights', '#800000', '#ffffff'],
            ['Bawku Bears', '#deb887', '#000000'],
            ['Yendi Yakuzas', '#4b0082', '#ffffff'],
        ];

        $teamModels = [];
        foreach ($teams as $index => $t) {
            $team = Team::create([
                'reference_code' => 'APX-2025-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'team_name' => $t[0],
                'team_size' => '25',
                'division' => 'premier',
                'primary_color' => $t[1],
                'secondary_color' => $t[2],
                'registration_status' => 'approved',
                'password' => Hash::make('password'),
                'home_stadium' => $t[0] . ' Arena',
                'city' => 'City ' . ($index + 1),
                'founded_year' => rand(1920, 2020),
                'formation' => '4-3-3'
            ]);
            $teamModels[] = $team;

            User::create([
                'name' => $t[0] . ' Manager',
                'email' => strtolower(str_replace(' ', '', $t[0])) . '@league.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'team_id' => $team->id,
            ]);

            // Add 15 players for each team
            $positions = ['GK', 'GK', 'DEF', 'DEF', 'DEF', 'DEF', 'DEF', 'MID', 'MID', 'MID', 'MID', 'MID', 'FWD', 'FWD', 'FWD'];
            $starting_xi = [];
            foreach ($positions as $pIdx => $pos) {
                $player = Player::create([
                    'team_id' => $team->id,
                    'name' => $t[0] . ' Player ' . ($pIdx + 1),
                    'position' => $pos,
                    'goals' => 0,
                    'assists' => 0,
                    'rating' => rand(65, 95),
                    'nationality' => '🇬🇭',
                    'appearances' => rand(1, 10),
                    'number' => rand(1, 99),
                    'yellow_cards' => rand(0, 4),
                    'red_cards' => (rand(1, 10) <= 2) ? 1 : 0,
                    'clean_sheets' => ($pos === 'GK' || $pos === 'DEF') ? rand(0, 5) : 0,
                    'motm_awards' => rand(0, 3),
                ]);
                if (count($starting_xi) < 11) {
                    $starting_xi[] = $player->id;
                }
            }
            $team->update(['starting_xi' => $starting_xi]);
        }

        // Create 50 finished games across multiple matchweeks
        for ($mw = 1; $mw <= 5; $mw++) {
            $shuffledTeams = $teamModels;
            shuffle($shuffledTeams);

            for ($i = 0; $i < 10; $i++) {
                $h = $shuffledTeams[$i*2];
                $a = $shuffledTeams[$i*2 + 1];

                $hScore = rand(0, 4);
                $aScore = rand(0, 4);

                $game = Game::create([
                    'home_team_id' => $h->id,
                    'away_team_id' => $a->id,
                    'home_score' => $hScore,
                    'away_score' => $aScore,
                    'kickoff' => now()->subDays((6 - $mw) * 7 + rand(0, 6)),
                    'matchweek' => $mw,
                    'status' => 'finished',
                    'venue' => $h->home_stadium,
                ]);

                // Create events and update player stats
                $this->generateEvents($game, $hScore, $aScore);
            }
        }

        // Create upcoming games
        for ($mw = 6; $mw <= 7; $mw++) {
            $shuffledTeams = $teamModels;
            shuffle($shuffledTeams);
            for ($i = 0; $i < 5; $i++) {
                 Game::create([
                    'home_team_id' => $shuffledTeams[$i*2]->id,
                    'away_team_id' => $shuffledTeams[$i*2 + 1]->id,
                    'kickoff' => now()->addDays(($mw - 5) * 7 + rand(0, 6)),
                    'matchweek' => $mw,
                    'status' => 'upcoming',
                    'venue' => $shuffledTeams[$i*2]->home_stadium,
                ]);
            }
        }

        Article::create([
            'title' => 'League Season 2024/25 Officially Kicks Off!',
            'content' => 'The highly anticipated football season has started with some thrilling matches across the country. 20 teams are competing for the ultimate glory.',
            'tag' => 'Announcement',
            'is_published' => true,
        ]);

        // Create dummy predictions from fans
        $userNames = ['Abel', 'Kwame', 'John', 'Kofi', 'Ama', 'Yao', 'Araba', 'Kweku'];
        $finishedGames = \App\Models\Game::where('status', 'finished')->get();
        foreach ($finishedGames as $game) {
            // Each game has 2-4 random predictions from our users
            $predictors = array_rand(array_flip($userNames), rand(2, 4));
            if (!is_array($predictors)) {
                $predictors = [$predictors];
            }
            foreach ($predictors as $predictor) {
                // Generate predictions: some correct, some close, some random
                if (rand(1, 10) <= 4) {
                    // Exact score prediction
                    $predHome = $game->home_score;
                    $predAway = $game->away_score;
                } elseif (rand(1, 10) <= 7) {
                    // Correct outcome prediction, but different score
                    $actualDiff = $game->home_score - $game->away_score;
                    if ($actualDiff > 0) {
                        $predHome = rand(1, 4);
                        $predAway = rand(0, $predHome - 1);
                    } elseif ($actualDiff < 0) {
                        $predAway = rand(1, 4);
                        $predHome = rand(0, $predAway - 1);
                    } else {
                        $score = rand(0, 3);
                        $predHome = $score;
                        $predAway = $score;
                    }
                    // Ensure we don't accidentally match the exact score
                    if ($predHome === $game->home_score && $predAway === $game->away_score) {
                        $predHome += 1;
                    }
                } else {
                    // Random prediction
                    $predHome = rand(0, 4);
                    $predAway = rand(0, 4);
                }

                \App\Models\Prediction::create([
                    'game_id' => $game->id,
                    'user_name' => $predictor,
                    'home_score_prediction' => $predHome,
                    'away_score_prediction' => $predAway,
                ]);
            }
        }
    }

    private function generateEvents($game, $hScore, $aScore)
    {
        $hPlayers = $game->homeTeam->players;
        $aPlayers = $game->awayTeam->players;

        // Give appearances
        foreach($hPlayers->take(11) as $p) $p->increment('appearances');
        foreach($aPlayers->take(11) as $p) $p->increment('appearances');

        // Home goals
        for ($i = 0; $i < $hScore; $i++) {
            $scorer = $hPlayers->random();
            $scorer->increment('goals');
            MatchEvent::create([
                'game_id' => $game->id,
                'team_id' => $game->home_team_id,
                'player_id' => $scorer->id,
                'type' => 'goal',
                'minute' => rand(1, 90)
            ]);
            if (rand(0, 1)) {
                $assistant = $hPlayers->where('id', '!=', $scorer->id)->random();
                $assistant->increment('assists');
            }
        }

        // Away goals
        for ($i = 0; $i < $aScore; $i++) {
            $scorer = $aPlayers->random();
            $scorer->increment('goals');
            MatchEvent::create([
                'game_id' => $game->id,
                'team_id' => $game->away_team_id,
                'player_id' => $scorer->id,
                'type' => 'goal',
                'minute' => rand(1, 90)
            ]);
            if (rand(0, 1)) {
                $assistant = $aPlayers->where('id', '!=', $scorer->id)->random();
                $assistant->increment('assists');
            }
        }
    }
}
