<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $team = auth()->user()->team;

        if (!$team) {
            return view('manager.dashboard', [
                'upcoming_games' => collect(),
                'recent_results' => collect(),
                'staff' => collect(),
                'stats' => [
                    'total_goals' => 0,
                    'avg_rating' => 0,
                    'clean_sheets' => 0,
                    'total_players' => 0
                ],
                'recent_activities' => collect()
            ]);
        }

        $upcoming_games = Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'upcoming')
            ->orderBy('kickoff', 'asc')
            ->take(5)
            ->get();

        $recent_results = Game::with(['homeTeam', 'awayTeam'])
            ->where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'finished')
            ->orderBy('kickoff', 'desc')
            ->take(5)
            ->get();

        $staff = $team->staff;

        $stats = [
            'total_goals' => $team->players->sum('goals'),
            'avg_rating' => round($team->players->avg('rating') ?? 0, 1),
            'clean_sheets' => $team->players->sum('clean_sheets'),
            'total_players' => $team->players->count(),
        ];

        $recent_activities = collect();
        foreach($recent_results as $res) {
            $isHome = $res->home_team_id == $team->id;
            $myScore = $isHome ? $res->home_score : $res->away_score;
            $oppScore = $isHome ? $res->away_score : $res->home_score;
            $oppName = $isHome ? $res->awayTeam->team_name : $res->homeTeam->team_name;

            $color = '#f0c040';
            if($myScore > $oppScore) {
                $msg = "Victory! Defeated <strong>{$oppName}</strong> {$myScore}-{$oppScore}";
                $color = '#22c55e';
            } elseif($myScore < $oppScore) {
                $msg = "Defeat against <strong>{$oppName}</strong> {$myScore}-{$oppScore}";
                $color = '#ff3b3b';
            } else {
                $msg = "Draw against <strong>{$oppName}</strong> {$myScore}-{$oppScore}";
                $color = '#f0c040';
            }

            $recent_activities->push((object)[
                'message' => $msg,
                'time' => $res->kickoff->diffForHumans(),
                'color' => $color
            ]);
        }

        return view('manager.dashboard', compact('upcoming_games', 'recent_results', 'staff', 'stats', 'recent_activities'));
    }
}
