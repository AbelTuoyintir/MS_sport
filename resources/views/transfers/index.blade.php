@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i data-lucide="repeat" class="w-6 h-6 text-accent-gold"></i>
                Transfer & Negotiation Hub
            </h2>
            <p class="text-xs text-gray-400">Buy permanent, sign loans (half/full season), negotiate counter-offers, and list players.</p>
        </div>
        <div class="text-xs text-gray-400">Transfer Window: <span class="text-green-400 font-bold uppercase">Open</span></div>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-900/30 border border-red-800 text-red-400 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">

            <!-- Incoming Offers Section -->
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2 text-white">
                    <span>📩</span> Incoming Bids & Negotiated Offers
                </h3>
                <div class="space-y-4">
                    @forelse($incoming_offers as $offer)
                        <div class="glass-card p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 border border-white/10">
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="font-bold text-lg text-white">{{ $offer->player->name }}</div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $offer->offer_type === 'permanent' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30' }}">
                                        {{ $offer->offer_type === 'permanent' ? 'Permanent Purchase' : ($offer->offer_type === 'loan_half' ? 'Half-Season Loan' : 'Full-Season Loan') }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">Buying Club: <span class="text-white font-bold">{{ $offer->buyingTeam->team_name }}</span></div>

                                @if($offer->status === 'countered')
                                    <div class="mt-2 text-xs p-2 bg-yellow-500/10 border border-yellow-500/20 rounded">
                                        <div class="text-yellow-400 font-bold">You Counter-Offered: GH₵ {{ number_format($offer->counter_amount, 2) }}</div>
                                        @if($offer->counter_notes) <p class="text-[11px] text-gray-300 italic">"{{ $offer->counter_notes }}"</p> @endif
                                        <span class="text-[10px] text-gray-400">Awaiting response from {{ $offer->buyingTeam->team_name }}</span>
                                    </div>
                                @else
                                    <div class="text-accent-gold font-black mt-1 text-base">GH₵ {{ number_format($offer->offer_amount, 2) }}</div>
                                    @if($offer->notes) <p class="text-xs text-gray-400 italic">"{{ $offer->notes }}"</p> @endif
                                @endif
                            </div>

                            @if($offer->status !== 'countered')
                                <div class="flex flex-wrap gap-2 self-start sm:self-center">
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase tracking-wider">Accept</button>
                                    </form>
                                    <button onclick="openCounterModal({{ $offer->id }}, '{{ $offer->player->name }}', {{ $offer->offer_amount }})" class="bg-yellow-600 hover:bg-yellow-500 text-bg-dark font-bold px-3 py-1.5 rounded-lg text-xs uppercase tracking-wider">Counter</button>
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase tracking-wider">Reject</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-xs italic glass-card p-4">No pending incoming bids received.</p>
                    @endforelse
                </div>
            </div>

            <!-- Outgoing Offers Section -->
            @if(isset($outgoing_offers) && $outgoing_offers->isNotEmpty())
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2 text-white">
                    <span>📤</span> My Active Bids & Counter-Offers
                </h3>
                <div class="space-y-4">
                    @foreach($outgoing_offers as $offer)
                        <div class="glass-card p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 border border-blue-500/20">
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="font-bold text-lg text-white">{{ $offer->player->name }}</div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $offer->offer_type === 'permanent' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                                        {{ $offer->offer_type === 'permanent' ? 'Permanent' : ($offer->offer_type === 'loan_half' ? 'Half-Season Loan' : 'Full-Season Loan') }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">Owner Club: <span class="text-white font-bold">{{ $offer->sellingTeam->team_name }}</span></div>
                                <div class="text-xs text-gray-300 mt-1">Your Bid: <span class="font-bold text-accent-gold">GH₵ {{ number_format($offer->offer_amount, 2) }}</span></div>

                                @if($offer->status === 'countered')
                                    <div class="mt-2 text-xs p-2 bg-accent-gold/10 border border-accent-gold/30 rounded">
                                        <div class="text-accent-gold font-bold">Counter Offer Received: GH₵ {{ number_format($offer->counter_amount, 2) }}</div>
                                        @if($offer->counter_notes) <p class="text-[11px] text-gray-300 italic">"{{ $offer->counter_notes }}"</p> @endif
                                    </div>
                                @endif
                            </div>

                            @if($offer->status === 'countered')
                                <div class="flex gap-2 self-start sm:self-center">
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase tracking-wider">Accept Counter</button>
                                    </form>
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase tracking-wider">Reject Counter</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-yellow-400 font-bold italic">Pending Review</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Available Players on Market -->
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2 text-white">
                    <span>🛒</span> Available Transfer & Loan Market
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($listings as $listing)
                        <div class="glass-card p-4 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="font-bold text-lg text-white">{{ $listing->player->name }}</div>
                                    <div class="text-xs text-gray-400 uppercase font-bold">{{ $listing->player->position }} · {{ $listing->team->team_name }}</div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $listing->type === 'permanent' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-purple-500/20 text-purple-400 border border-purple-500/30' }}">
                                        {{ $listing->type === 'permanent' ? 'Permanent' : ($listing->type === 'loan_half' ? 'Half-Season Loan' : 'Full-Season Loan') }}
                                    </span>
                                    <div class="bg-accent-gold text-bg-dark px-2 py-0.5 rounded text-xs font-black">
                                        GH₵ {{ number_format($listing->asking_price, 2) }}
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mb-4 italic">{{ $listing->reason ?: 'Listed for transfer/loan' }}</p>

                            @if($listing->team_id !== auth()->user()->team_id)
                                <button onclick="openOfferModal({{ $listing->player_id }}, '{{ $listing->player->name }}', '{{ $listing->type }}')" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded-lg text-xs uppercase tracking-wider transition-colors">
                                    Place Bid / Submit Offer
                                </button>
                            @else
                                <div class="text-center text-xs text-gray-500 font-bold uppercase italic py-2 bg-white/5 rounded-lg">Your Listed Player</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-2 text-center py-8 glass-card text-xs">No players currently listed on the transfer market.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold mb-4 text-white">Manage My Squad Transfers</h3>
                <button onclick="toggleModal('list-player-modal')" class="w-full bg-accent-gold hover:bg-yellow-500 text-bg-dark font-bold py-3 rounded-lg mb-4 text-xs uppercase tracking-wider transition-all">
                    List Squad Player
                </button>
                <div class="text-[10px] text-gray-400 text-center uppercase">List players for permanent sale or loans</div>
            </div>

            <div class="glass-card p-6 bg-gradient-to-b from-[#00e5ff]/5 to-transparent border border-[#00e5ff]/20">
                <h3 class="text-lg font-bold mb-4 text-[#00e5ff] flex items-center gap-2"><span>🔮</span> Transfer Rumour Mill</h3>
                <div class="space-y-4">
                    @foreach($rumours as $rumour)
                        <div class="p-3 bg-white/5 border border-white/5 rounded-lg space-y-2">
                            <div class="flex items-center justify-between text-[8px] uppercase">
                                <span class="font-extrabold px-1.5 py-0.5 rounded {{ $rumour['urgency'] === 'Breaking' ? 'bg-rose-500/10 text-rose-400' : 'bg-blue-500/10 text-blue-400' }}">{{ $rumour['urgency'] }}</span>
                                <span class="font-semibold text-emerald-400">{{ $rumour['probability'] }}</span>
                            </div>
                            <p class="text-xs font-semibold leading-normal text-white">"{{ $rumour['title'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Make Offer Modal -->
<div id="offer-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 bg-bg-dark border border-white/10">
        <h3 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
            <i data-lucide="banknote" class="w-5 h-5 text-accent-gold"></i> Make Bidding Offer
        </h3>
        <form action="{{ route('manager.transfers.offer') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="player_id" id="offer_player_id">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Target Player</label>
                <input type="text" id="offer_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-gray-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Offer Deal Type</label>
                <select name="offer_type" id="offer_type_select" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                    <option value="permanent">Permanent Purchase</option>
                    <option value="loan_half">Half-Season Loan</option>
                    <option value="loan_full">Full-Season Loan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Offer Amount (GH₵)</label>
                <input type="number" name="offer_amount" min="0" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Notes / Terms</label>
                <textarea name="notes" rows="3" placeholder="Add specific negotiation notes..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase tracking-wider">Send Bid Offer</button>
                <button type="button" onclick="toggleModal('offer-modal')" class="flex-1 bg-white/5 text-gray-300 font-bold py-3 rounded-lg border border-white/10 text-xs uppercase tracking-wider">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Counter Offer Modal -->
<div id="counter-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 bg-bg-dark border border-white/10">
        <h3 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
            <i data-lucide="repeat" class="w-5 h-5 text-yellow-400"></i> Counter Offer
        </h3>
        <form id="counter-form" action="" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="action" value="counter">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Player</label>
                <input type="text" id="counter_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-gray-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Original Bid Amount</label>
                <input type="text" id="counter_original_amount" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-gray-400 font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Counter Price (GH₵)</label>
                <input type="number" name="counter_amount" min="0" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Counter Notes</label>
                <textarea name="counter_notes" rows="3" placeholder="Explain your counter demands..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-bg-dark font-bold py-3 rounded-lg text-xs uppercase tracking-wider">Submit Counter</button>
                <button type="button" onclick="toggleModal('counter-modal')" class="flex-1 bg-white/5 text-gray-300 font-bold py-3 rounded-lg border border-white/10 text-xs uppercase tracking-wider">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- List Player Modal -->
<div id="list-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 bg-bg-dark border border-white/10">
        <h3 class="text-xl font-bold mb-6 text-white flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-accent-gold"></i> List Player for Transfer / Loan
        </h3>
        <form action="{{ route('manager.transfers.list') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Select Player</label>
                <select name="player_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                    @if(auth()->user()->team)
                        @foreach(auth()->user()->team->players as $player)
                            <option value="{{ $player->id }}">{{ $player->name }} ({{ $player->position }} - Rating: {{ $player->rating }})</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Listing Type</label>
                    <select name="type" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                        <option value="permanent">Permanent Sale</option>
                        <option value="loan_half">Half-Season Loan</option>
                        <option value="loan_full">Full-Season Loan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Asking Price (GH₵)</label>
                    <input type="number" name="asking_price" min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Reason for Listing</label>
                <textarea name="reason" rows="3" placeholder="Reason for listing..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg text-xs uppercase tracking-wider">List Player</button>
                <button type="button" onclick="toggleModal('list-player-modal')" class="flex-1 bg-white/5 text-gray-300 font-bold py-3 rounded-lg border border-white/10 text-xs uppercase tracking-wider">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOfferModal(id, name, defaultType) {
    document.getElementById('offer_player_id').value = id;
    document.getElementById('offer_player_name').value = name;
    if (defaultType && ['permanent', 'loan_half', 'loan_full'].includes(defaultType)) {
        document.getElementById('offer_type_select').value = defaultType;
    }
    toggleModal('offer-modal');
}

function openCounterModal(offerId, name, originalAmount) {
    document.getElementById('counter-form').action = '/manager/transfers/handle/' + offerId;
    document.getElementById('counter_player_name').value = name;
    document.getElementById('counter_original_amount').value = 'GH₵ ' + Number(originalAmount).toLocaleString();
    toggleModal('counter-modal');
}
</script>
@endsection
