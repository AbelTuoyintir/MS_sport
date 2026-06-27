<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Game;
use Illuminate\Support\Collection;

class StandingsService
{
    public function getStandings(): Collection
    {
        $teams = Team::where('registration_status', 'approved')->get();
        $games = Game::where('status', 'finished')->get();

        $standings = $teams->map(function ($team) use ($games) {
            $stats = [
                'id' => $team->id,
                'name' => $team->team_name,
                'logo' => $team->logo_path,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'gf' => 0,
                'ga' => 0,
                'gd' => 0,
                'points' => 0,
            ];

            foreach ($games as $game) {
                if ($game->home_team_id === $team->id) {
                    $stats['played']++;
                    $stats['gf'] += $game->home_score;
                    $stats['ga'] += $game->away_score;
                    if ($game->home_score > $game->away_score) {
                        $stats['won']++;
                        $stats['points'] += 3;
                    } elseif ($game->home_score === $game->away_score) {
                        $stats['drawn']++;
                        $stats['points'] += 1;
                    } else {
                        $stats['lost']++;
                    }
                } elseif ($game->away_team_id === $team->id) {
                    $stats['played']++;
                    $stats['gf'] += $game->away_score;
                    $stats['ga'] += $game->home_score;
                    if ($game->away_score > $game->home_score) {
                        $stats['won']++;
                        $stats['points'] += 3;
                    } elseif ($game->home_score === $game->away_score) {
                        $stats['drawn']++;
                        $stats['points'] += 1;
                    } else {
                        $stats['lost']++;
                    }
                }
            }

            $stats['gd'] = $stats['gf'] - $stats['ga'];
            return (object) $stats;
        });

        return $standings->sort(function ($a, $b) {
            if ($a->points !== $b->points) return $b->points <=> $a->points;
            if ($a->gd !== $b->gd) return $b->gd <=> $a->gd;
            return $b->gf <=> $a->gf;
        })->values();
    }
}
