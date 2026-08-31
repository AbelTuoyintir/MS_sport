<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Player;
use App\Models\Team;
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

        $listings = TransferListing::with(['player', 'team'])
            ->where('status', 'active')
            ->get();

        $incoming_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam'])
            ->where('selling_team_id', $userTeamId)
            ->whereIn('status', ['pending', 'countered'])
            ->orderBy('created_at', 'desc')
            ->get();

        $outgoing_offers = TransferOffer::with(['player', 'buyingTeam', 'sellingTeam'])
            ->where('buying_team_id', $userTeamId)
            ->orderBy('updated_at', 'desc')
            ->get();

        $rumours = $this->generateTransferRumours();

        return view('transfers.index', compact('listings', 'incoming_offers', 'outgoing_offers', 'rumours'));
    }

    private function generateTransferRumours()
    {
        $players = Player::with('team')->orderBy('rating', 'desc')->take(30)->get();
        $teams = Team::all();

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
            'type' => 'required|in:permanent,loan_half,loan_full,loan',
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
            'offer_amount' => 'required|numeric|min:0',
            'offer_type' => 'required|in:permanent,loan_half,loan_full',
            'notes' => 'nullable|string',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id === auth()->user()->team_id) {
            return redirect()->back()->with('error', 'You cannot buy your own player.');
        }

        $activeListing = TransferListing::where('player_id', $player->id)
            ->where('status', 'active')
            ->first();

        TransferOffer::create([
            'listing_id' => $activeListing?->id,
            'player_id' => $player->id,
            'buying_team_id' => auth()->user()->team_id,
            'selling_team_id' => $player->team_id,
            'offer_amount' => $validated['offer_amount'],
            'offer_type' => $validated['offer_type'],
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Transfer offer / bid submitted successfully.');
    }

    public function handleOffer(Request $request, $id)
    {
        $offer = TransferOffer::findOrFail($id);

        if ($offer->selling_team_id !== auth()->user()->team_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $action = $request->input('action'); // accept, reject, counter

        if ($action === 'accept') {
            $offer->update(['status' => 'accepted']);

            // Execute transfer or loan
            $player = $offer->player;
            $oldTeamName = $player->team->team_name;

            $player->update(['team_id' => $offer->buying_team_id]);
            $player->refresh();
            $newTeamName = $player->team->team_name;

            $dealType = $offer->offer_type ?? 'permanent';
            $isLoan = in_array($dealType, ['loan_half', 'loan_full']);

            // Close listings
            TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

            $dealTitle = $isLoan ? 'LOAN AGREED' : 'TRANSFER DONE';
            $dealTerm = match ($dealType) {
                'loan_half' => 'a half-season loan deal',
                'loan_full' => 'a full-season loan deal',
                default => 'a permanent transfer',
            };

            // Automatically generate a News Article
            Article::create([
                'title' => "{$dealTitle}: {$player->name} Joins {$newTeamName}!",
                'slug' => Str::slug("deal-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                'content' => "{$player->name} has officially completed {$dealTerm} from {$oldTeamName} to {$newTeamName} for a agreed fee/loan price of GH₵ " . number_format($offer->offer_amount, 2) . ". The manager of {$newTeamName} expressed delight in securing the deal.",
                'tag' => 'Transfer',
                'is_published' => true,
            ]);

            return redirect()->back()->with('success', 'Offer accepted! Deal completed and official news published.');
        } elseif ($action === 'counter') {
            $validated = $request->validate([
                'counter_amount' => 'required|numeric|min:0',
                'counter_type' => 'required|in:permanent,loan_half,loan_full',
                'counter_notes' => 'nullable|string',
            ]);

            $offer->update([
                'status' => 'countered',
                'counter_amount' => $validated['counter_amount'],
                'counter_type' => $validated['counter_type'],
                'counter_notes' => $validated['counter_notes'],
            ]);

            return redirect()->back()->with('success', 'Counter-offer sent to the bidding manager.');
        } else {
            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Offer rejected.');
        }
    }

    public function handleCounter(Request $request, $id)
    {
        $offer = TransferOffer::findOrFail($id);

        if ($offer->buying_team_id !== auth()->user()->team_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $action = $request->input('action'); // accept or reject

        if ($action === 'accept') {
            $finalAmount = $offer->counter_amount ?? $offer->offer_amount;
            $finalType = $offer->counter_type ?? $offer->offer_type ?? 'permanent';

            $offer->update([
                'status' => 'accepted',
                'offer_amount' => $finalAmount,
                'offer_type' => $finalType,
            ]);

            $player = $offer->player;
            $oldTeamName = $player->team->team_name;

            $player->update(['team_id' => $offer->buying_team_id]);
            $player->refresh();
            $newTeamName = $player->team->team_name;

            $isLoan = in_array($finalType, ['loan_half', 'loan_full']);
            TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

            $dealTitle = $isLoan ? 'LOAN AGREED' : 'TRANSFER DONE';
            $dealTerm = match ($finalType) {
                'loan_half' => 'a half-season loan deal',
                'loan_full' => 'a full-season loan deal',
                default => 'a permanent transfer',
            };

            Article::create([
                'title' => "{$dealTitle}: {$player->name} Joins {$newTeamName}!",
                'slug' => Str::slug("counter-deal-{$player->name}-joins-{$newTeamName}-" . uniqid()),
                'content' => "Following successful negotiations and counter-offer acceptance, {$player->name} has completed {$dealTerm} from {$oldTeamName} to {$newTeamName} for a fee of GH₵ " . number_format($finalAmount, 2) . ".",
                'tag' => 'Transfer',
                'is_published' => true,
            ]);

            return redirect()->back()->with('success', 'Counter-offer accepted! Transfer completed successfully.');
        } else {
            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Counter-offer declined.');
        }
    }
}
