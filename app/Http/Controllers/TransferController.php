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
        $incoming_offers = TransferOffer::with(['player', 'buyingTeam'])
            ->where('selling_team_id', auth()->user()->team_id)
            ->where('status', 'pending')
            ->get();

        return view('transfers.index', compact('listings', 'incoming_offers'));
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
            'type' => 'required|in:permanent,loan',
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

        return redirect()->back()->with('success', 'Player listed for transfer.');
    }

    public function makeOffer(Request $request)
    {
        if (!$this->isWindowOpen()) {
            return redirect()->back()->with('error', 'The transfer window is currently closed.');
        }

        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'offer_amount' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $player = Player::findOrFail($validated['player_id']);

        if ($player->team_id === auth()->user()->team_id) {
            return redirect()->back()->with('error', 'You cannot buy your own player.');
        }

        TransferOffer::create([
            'player_id' => $player->id,
            'buying_team_id' => auth()->user()->team_id,
            'selling_team_id' => $player->team_id,
            'offer_amount' => $validated['offer_amount'],
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('success', 'Transfer offer sent.');
    }

    public function handleOffer(Request $request, $id)
    {
        $offer = TransferOffer::findOrFail($id);

        if ($offer->selling_team_id !== auth()->user()->team_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $action = $request->input('action'); // accept or reject

        if ($action === 'accept') {
            $offer->update(['status' => 'accepted']);

            // Execute transfer
            $player = $offer->player;
            $player->update(['team_id' => $offer->buying_team_id]);

            // Close listings
            TransferListing::where('player_id', $player->id)->update(['status' => 'sold']);

            return redirect()->back()->with('success', 'Transfer completed!');
        } else {
            $offer->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Offer rejected.');
        }
    }
}
