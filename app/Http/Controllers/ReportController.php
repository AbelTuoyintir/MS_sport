<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        return view('manager.reports.index');
    }

    public function exportPlayers()
    {
        $players = Player::where('team_id', auth()->user()->team_id)->get();

        $response = new StreamedResponse(function() use ($players) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Position', 'Number', 'Age', 'Goals', 'Assists', 'Rating', 'Status']);

            foreach ($players as $player) {
                fputcsv($handle, [
                    $player->name,
                    $player->position,
                    $player->number,
                    $player->age,
                    $player->goals,
                    $player->assists,
                    $player->rating,
                    $player->status,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="squad_report.csv"');

        return $response;
    }
}
