@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Transfer Market</h2>
        <div class="text-sm text-gray-400">Transfer Window: <span class="text-green-400 font-bold uppercase">Open</span></div>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-900/30 border border-red-800 text-red-400 text-sm rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Incoming Offers</h3>
                <div class="space-y-4">
                    @forelse($incoming_offers as $offer)
                        <div class="glass-card p-4 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-lg">{{ $offer->player->name }}</div>
                                <div class="text-xs text-gray-400">Offer from: <span class="text-white font-bold">{{ $offer->buyingTeam->team_name }}</span></div>
                                <div class="text-accent-gold font-black mt-1">GH₵ {{ number_format($offer->offer_amount, 2) }}</div>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">Accept</button>
                                </form>
                                <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">Reject</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm italic">No pending offers received.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Available Players</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($listings as $listing)
                    <div class="glass-card p-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="font-bold text-lg">{{ $listing->player->name }}</div>
                                <div class="text-xs text-gray-400 uppercase font-bold">{{ $listing->player->position }} · {{ $listing->team->team_name }}</div>
                            </div>
                            <div class="bg-accent-gold text-bg-dark px-2 py-1 rounded text-xs font-black">
                                GH₵ {{ number_format($listing->asking_price, 2) }}
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">{{ $listing->reason }}</p>

                        @if($listing->team_id !== auth()->user()->team_id)
                            <button onclick="openOfferModal({{ $listing->player_id }}, '{{ $listing->player->name }}')" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg text-sm transition-colors hover:bg-blue-500">Make Offer</button>
                        @else
                            <div class="text-center text-xs text-gray-500 font-bold uppercase italic">Your Listing</div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 col-span-2 text-center py-8">No players currently listed.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold mb-4">Manage My Transfers</h3>
                <button onclick="toggleModal('list-player-modal')" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mb-4">List a Player</button>
                <div class="text-[10px] text-gray-500 text-center uppercase">List your players to receive offers</div>
            </div>
        </div>
    </div>
</div>

<!-- Make Offer Modal -->
<div id="offer-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">Make Transfer Offer</h3>
        <form action="{{ route('manager.transfers.offer') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="player_id" id="offer_player_id">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player</label>
                <input type="text" id="offer_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-gray-400">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Offer Amount (GH₵)</label>
                <input type="number" name="offer_amount" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-lg">Send Offer</button>
                <button type="button" onclick="toggleModal('offer-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- List Player Modal -->
<div id="list-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">List Player for Transfer</h3>
        <form action="{{ route('manager.transfers.list') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Select Player</label>
                <select name="player_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    @foreach(auth()->user()->team->players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }} ({{ $player->position }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Listing Type</label>
                    <select name="type" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="permanent">Permanent</option>
                        <option value="loan">Loan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Asking Price</label>
                    <input type="number" name="asking_price" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Reason for Listing</label>
                <textarea name="reason" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">List Player</button>
                <button type="button" onclick="toggleModal('list-player-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOfferModal(id, name) {
    document.getElementById('offer_player_id').value = id;
    document.getElementById('offer_player_name').value = name;
    toggleModal('offer-modal');
}
</script>
@endsection
