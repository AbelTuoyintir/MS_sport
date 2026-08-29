<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Player;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    private function isWindowOpen()
    {
        $month = now()->month;
        return !in_array($month, [5, 6, 11, 12]);
    }

    public function index()
    {
        $userTeamId = auth()->user()->team_id;

        $listings = TransferListing::with(['player', 'team'])->where('status', 'active')->get();

        $incoming_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam'])
            ->where('selling_team_id', $userTeamId)
            ->whereIn('status', ['pending', 'countered'])
            ->get();

        $outgoing_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam'])
            ->where('buying_team_id', $userTeamId)
            ->whereIn('status', ['pending', 'countered'])
            ->get();

        $rumours = $this->generateTransferRumours();

        return view('transfers.index', compact('listings', 'incoming_offers', 'outgoing_offers', 'rumours'));
    }

    private function generateTransferRumours()
    {
        $players = Player::with('team')->orderBy('rating', 'desc')->take(30)->get();
        $teams = \App\Models\Team::all();

        $rumours = [];
        if ($players->count() > 3 && $teams->count() > 1) {
            $templates = [
                "REVEALED: {team} are preparing an ambitious bid for {player}!",
                "SCOUT REPORT: {team} scouts were spotted monitoring {player}'s performance.",
                "TRANSFER TALK: {player} has emerged as a top target for {team}.",
                "EXCL: {team} have opened initial talks to sign {player} next season.",
                "EXCLUSIVE: {player} is reportedly considering options with {team} interested.",
                "RUMOUR: {team} are ready to meet the valuation for {player}!"
            ];

            for ($i = 0; $i < 5; $i++) {
                $player = $players->get(($i * 4) % $players->count());
                $possibleTeams = $teams->filter(fn($t) => $t->id !== $player->team_id);
                $team = $possibleTeams->isNotEmpty() ? $possibleTeams->values()->get($i % $possibleTeams->count()) : $teams->first();

                $template = $templates[$i % count($templates)];
                $title = str_replace(['{player}', '{team}'], [$player->name, $team->team_name], $template);

                $rumours[] = [
                    'title' => $title,
                    'probability' => rand(40, 95) . '% probability',
                    'urgency' => ['Hot', 'Developing', 'High Interest', 'Rumour', 'Breaking'][$i % 5]
                ];
            }
        } else {
            $rumours = [
                ['title' => "TRANSFER TALK: Cape Coast Stars are monitoring top forward targets.", 'probability' => '65% probability', 'urgency' => 'Hot'],
                ['title' => "REVEALED: Accra Lions prepare high-value offer for a premium playmaker.", 'probability' => '72% probability', 'urgency' => 'Developing'],
                ['title' => "EXCL: Kumasi Warriors manager reveals plans for winter reinforcements.", 'probability' => '85% probability', 'urgency' => 'Breaking'],
            ];
        }
        return $rumours;
    }

    public function listPlayer(Request $request)
    {
        if (!$this->isWindowOpen()) {
            return redirect()->back()->with('error', 'The transfer window is currently closed.');
        }

        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'asking_price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string',
            'type' => 'required|in:permanent,loan_half,loan_full',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id !== auth()->user()->team_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        TransferListing::create([
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'asking_price' => $validated['asking_price'],
            'reason' => $validated['reason'],
            'type' => $validated['type'],
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Player listed for transfer/loan.');
    }

    public function makeOffer(Request $request)
    {
        if (!$this->isWindowOpen()) {
            return redirect()->back()->with('error', 'The transfer window is currently closed.');
        }

        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'offer_type' => 'required|in:permanent,loan_half,loan_full',
            'offer_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id === auth()->user()->team_id) {
            return redirect()->back()->with('error', 'You cannot bid on your own player.');
        }

        $listing = TransferListing::where('player_id', $player->id)->where('status', 'active')->first();

        TransferOffer::create([
            'listing_id' => $listing?->id,
            'player_id' => $player->id,
            'buying_team_id' => auth()->user()->team_id,
            'selling_team_id' => $player->team_id,
            'offer_type' => $validated['offer_type'],
            'offer_amount' => $validated['offer_amount'],
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        $typeLabel = match ($validated['offer_type']) {
            'loan_half' => 'Half-Season Loan',
            'loan_full' => 'Full-Season Loan',
            default => 'Permanent Transfer',
        };

        return redirect()->back()->with('success', "{$typeLabel} offer sent successfully.");
    }

    public function handleOffer(Request $request, $id)
    {
        $offer = TransferOffer::findOrFail($id);
        $userTeamId = auth()->user()->team_id;

        $action = $request->input('action'); // accept, reject, counter

        if ($action === 'counter') {
            if ($offer->selling_team_id !== $userTeamId) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }

            $validated = $request->validate([
                'counter_amount' => 'required|numeric|min:0',
                'counter_notes' => 'nullable|string',
            ]);

            $offer->update([
                'status' => 'countered',
                'counter_amount' => $validated['counter_amount'],
                'counter_notes' => $validated['counter_notes'],
            ]);

            return redirect()->back()->with('success', 'Counter-offer sent to the buying club.');
        }

        if ($action === 'accept') {
            // Buyer accepting counter OR Seller accepting original offer
            if ($offer->status === 'countered') {
                if ($offer->buying_team_id !== $userTeamId) {
                    return redirect()->back()->with('error', 'Unauthorized.');
                }
                $finalAmount = $offer->counter_amount ?? $offer->offer_amount;
            } else {
                if ($offer->selling_team_id !== $userTeamId) {
                    return redirect()->back()->with('error', 'Unauthorized.');
                }
                $finalAmount = $offer->offer_amount;
            }

            $offer->update(['status' => 'accepted']);

            $player = $offer->player;
            $oldTeamName = $offer->sellingTeam->team_name;

            // Execute transfer
            $player->update(['team_id' => $offer->buying_team_id]);
            $player->refresh();
            $newTeamName = $player->buyingTeam?->team_name ?? $offer->buyingTeam->team_name;

            // Close active listings
            TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

            $moveType = match ($offer->offer_type) {
                'loan_half' => 'Half-Season Loan',
                'loan_full' => 'Full-Season Loan',
                default => 'Permanent Deal',
            };

            // Automatically generate news article
            Article::create([
                'title' => "DEAL DONE: {$player->name} Joins {$newTeamName} ({$moveType})!",
                'slug' => Str::slug("transfer-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                'content' => "{$player->name} has officially completed a {$moveType} move from {$oldTeamName} to {$newTeamName} for a fee of GH₵ " . number_format($finalAmount, 2) . ". The manager of {$newTeamName} expressed great delight in securing the deal.",
                'tag' => 'Transfer',
                'is_published' => true,
            ]);

            return redirect()->back()->with('success', 'Deal accepted and completed! News article generated.');
        } else { // reject
            if ($offer->selling_team_id !== $userTeamId && $offer->buying_team_id !== $userTeamId) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }

            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Offer rejected.');
        }
    }
}
