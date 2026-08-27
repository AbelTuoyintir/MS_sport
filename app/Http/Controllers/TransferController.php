<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Player;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    private function isWindowOpen()
    {
        $month = now()->month;
        return !in_array($month, [5, 6, 11, 12]);
    }

    public function index(Request $request)
    {
        $dealTypeFilter = $request->query('deal_type');

        $listingsQuery = TransferListing::with(['player', 'team', 'scoutAgent'])->where('status', 'active');
        if ($dealTypeFilter && in_array($dealTypeFilter, ['permanent', 'loan_half', 'loan_full'])) {
            $listingsQuery->where('deal_type', $dealTypeFilter);
        }
        $listings = $listingsQuery->get();

        $incoming_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam', 'parentOffer'])
            ->where('selling_team_id', auth()->user()->team_id)
            ->whereIn('status', ['pending', 'countered'])
            ->get();

        $outgoing_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam', 'parentOffer'])
            ->where('buying_team_id', auth()->user()->team_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $rumours = $this->generateTransferRumours();

        return view('transfers.index', compact('listings', 'incoming_offers', 'outgoing_offers', 'rumours', 'dealTypeFilter'));
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
            'type' => 'required|in:permanent,loan',
            'deal_type' => 'nullable|in:permanent,loan_half,loan_full',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id !== auth()->user()->team_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $dealType = $validated['deal_type'] ?? ($validated['type'] === 'loan' ? 'loan_half' : 'permanent');

        TransferListing::create([
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'asking_price' => $validated['asking_price'],
            'reason' => $validated['reason'],
            'type' => $validated['type'],
            'deal_type' => $dealType,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Player listed on the market successfully.');
    }

    public function makeOffer(Request $request)
    {
        if (!$this->isWindowOpen()) {
            return redirect()->back()->with('error', 'The transfer window is currently closed.');
        }

        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'offer_amount' => 'required|numeric|min:0',
            'deal_type' => 'required|in:permanent,loan_half,loan_full',
            'proposed_contract_years' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id === auth()->user()->team_id) {
            return redirect()->back()->with('error', 'You cannot buy or bid for your own player.');
        }

        TransferOffer::create([
            'player_id' => $player->id,
            'buying_team_id' => auth()->user()->team_id,
            'selling_team_id' => $player->team_id,
            'offer_amount' => $validated['offer_amount'],
            'deal_type' => $validated['deal_type'],
            'proposed_contract_years' => $validated['proposed_contract_years'] ?? 1,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Transfer/Loan bid submitted successfully.');
    }

    public function handleOffer(Request $request, $id)
    {
        $offer = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam'])->findOrFail($id);

        $action = $request->input('action'); // accept, reject, counter

        if ($action === 'accept') {
            if ($offer->selling_team_id !== auth()->user()->team_id && $offer->buying_team_id !== auth()->user()->team_id) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }

            $offer->update(['status' => 'accepted']);

            $player = $offer->player;
            $oldTeamName = $offer->sellingTeam->team_name;
            $newTeamName = $offer->buyingTeam->team_name;

            if ($offer->deal_type === 'permanent') {
                $player->update(['team_id' => $offer->buying_team_id]);
                TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

                Article::create([
                    'title' => "TRANSFER DONE: {$player->name} Joins {$newTeamName}!",
                    'slug' => \Illuminate\Support\Str::slug("transfer-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                    'content' => "{$player->name} has officially completed a permanent transfer from {$oldTeamName} to {$newTeamName} for a agreed fee of GH₵ " . number_format($offer->offer_amount, 2) . ". The manager expressed great enthusiasm for the contract signing.",
                    'tag' => 'Transfer',
                    'is_published' => true,
                ]);
            } else {
                $termText = $offer->deal_type === 'loan_half' ? 'Half Season (6 Months)' : 'Full Season (1 Year)';
                // Maintain current team or transfer for duration
                $player->update(['team_id' => $offer->buying_team_id]);
                TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

                Article::create([
                    'title' => "LOAN SIGNING: {$player->name} Joins {$newTeamName} on Loan!",
                    'slug' => \Illuminate\Support\Str::slug("loan-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                    'content' => "{$player->name} has secured a loan move from {$oldTeamName} to {$newTeamName} for {$termText} with a loan fee of GH₵ " . number_format($offer->offer_amount, 2) . ".",
                    'tag' => 'Transfer',
                    'is_published' => true,
                ]);
            }

            return redirect()->back()->with('success', 'Deal accepted and agreement finalized!');
        } elseif ($action === 'counter') {
            if ($offer->selling_team_id !== auth()->user()->team_id) {
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

            return redirect()->back()->with('success', 'Counter-offer sent to buying team.');
        } else {
            if ($offer->selling_team_id !== auth()->user()->team_id && $offer->buying_team_id !== auth()->user()->team_id) {
                return redirect()->back()->with('error', 'Unauthorized.');
            }

            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Offer rejected.');
        }
    }
}
