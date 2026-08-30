<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\TransferListing;
use App\Models\TransferOffer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    private function isWindowOpen()
    {
        // For demonstration, let's say the window is always open unless we're in May/June
        $month = now()->month;
        return !in_array($month, [5, 6, 11, 12]);
    }

    public function index()
    {
        $listings = TransferListing::with(['player', 'team'])->where('status', 'active')->get();
        $userTeamId = auth()->user()->team_id;

        $incoming_offers = TransferOffer::with(['player', 'buyingTeam', 'counterOffers'])
            ->where('selling_team_id', $userTeamId)
            ->whereIn('status', ['pending', 'countered'])
            ->get();

        $my_sent_offers = TransferOffer::with(['player', 'sellingTeam', 'counterOffers'])
            ->where('buying_team_id', $userTeamId)
            ->get();

        $all_players = Player::with('team')->where('team_id', '!=', $userTeamId)->get();
        $rumours = $this->generateTransferRumours();

        return view('transfers.index', compact('listings', 'incoming_offers', 'my_sent_offers', 'all_players', 'rumours'));
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
            'asking_price' => 'nullable|numeric',
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

        return redirect()->back()->with('success', 'Player listed for transfer/loan successfully.');
    }

    public function makeOffer(Request $request)
    {
        if (!$this->isWindowOpen()) {
            return redirect()->back()->with('error', 'The transfer window is currently closed.');
        }

        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'offer_amount' => 'required|numeric|min:0',
            'offer_type' => 'required|in:permanent,loan_half,loan_full',
            'notes' => 'nullable|string',
            'listing_id' => 'nullable|exists:transfer_listings,id',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id === auth()->user()->team_id) {
            return redirect()->back()->with('error', 'You cannot place an offer on your own player.');
        }

        TransferOffer::create([
            'listing_id' => $validated['listing_id'] ?? null,
            'player_id' => $player->id,
            'buying_team_id' => auth()->user()->team_id,
            'selling_team_id' => $player->team_id,
            'offer_amount' => $validated['offer_amount'],
            'offer_type' => $validated['offer_type'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Transfer / Loan bid submitted to selling club.');
    }

    public function handleOffer(Request $request, $id)
    {
        $offer = TransferOffer::findOrFail($id);
        $userTeamId = auth()->user()->team_id;

        if ($offer->selling_team_id !== $userTeamId && $offer->buying_team_id !== $userTeamId) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $action = $request->input('action'); // accept, reject, counter

        if ($action === 'counter') {
            $validated = $request->validate([
                'counter_amount' => 'required|numeric|min:0',
                'counter_notes' => 'nullable|string',
            ]);

            $offer->update([
                'status' => 'countered',
                'counter_amount' => $validated['counter_amount'],
                'counter_notes' => $validated['counter_notes'],
            ]);

            return redirect()->back()->with('success', 'Counter-offer sent to buying manager.');
        }

        if ($action === 'accept') {
            $offer->update(['status' => 'accepted']);

            // Execute transfer or loan
            $player = $offer->player;
            $oldTeamName = $player->team->team_name;
            $player->update(['team_id' => $offer->buying_team_id]);
            $player->refresh();
            $newTeamName = $player->team->team_name;

            // Close listings
            TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

            $dealTypeTitle = match($offer->offer_type) {
                'loan_half' => 'Half-Season Loan',
                'loan_full' => 'Full-Season Loan',
                default => 'Permanent Move',
            };

            $finalAmount = ($offer->status === 'countered' && $offer->counter_amount) ? $offer->counter_amount : $offer->offer_amount;

            // Automatically generate a News Article
            \App\Models\Article::create([
                'title' => "DEAL DONE ({$dealTypeTitle}): {$player->name} Joins {$newTeamName}!",
                'slug' => \Illuminate\Support\Str::slug("transfer-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                'content' => "{$player->name} has officially completed a {$dealTypeTitle} move from {$oldTeamName} to {$newTeamName} for a fee of GH₵ " . number_format($finalAmount, 2) . ". The manager of {$newTeamName} expressed great delight in finalizing negotiations.",
                'tag' => 'Transfer',
                'is_published' => true,
            ]);

            return redirect()->back()->with('success', 'Deal accepted and completed successfully! News article generated.');
        } else {
            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Offer rejected.');
        }
    }
}
