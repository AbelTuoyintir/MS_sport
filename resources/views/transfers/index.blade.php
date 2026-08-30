@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-card p-6 border-l-4 border-l-gold">
        <div>
            <h2 class="text-2xl font-black font-heading tracking-wider text-white uppercase flex items-center gap-2">
                <i data-lucide="arrow-left-right" class="w-7 h-7 text-accent-gold"></i>
                Transfer & Negotiation Hub
            </h2>
            <p class="text-xs text-gray-400 mt-1">Negotiate permanent transfers & season loans, manage incoming bids, counter-offer, and submit transfer offers.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Transfer Window Open
            </span>
            <button onclick="toggleModal('list-player-modal')" class="px-4 py-2.5 bg-accent-gold text-black font-heading font-black uppercase text-xs tracking-wider rounded-xl shadow-lg shadow-gold/20 hover:scale-105 transition-all flex items-center gap-2">
                <i data-lucide="tag" class="w-4 h-4"></i> List Player
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Incoming Offers & Active Negotiations -->
            <div>
                <h3 class="text-lg font-heading font-extrabold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="inbox" class="w-5 h-5 text-accent-gold"></i> Incoming Bids & Counter-Offers
                </h3>
                <div class="space-y-4">
                    @forelse($incoming_offers as $offer)
                        <div class="glass-card p-5 border border-white/10 relative overflow-hidden">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-heading font-extrabold text-lg text-white">{{ $offer->player->name }}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $offer->offer_type === 'permanent' ? 'bg-amber-500/20 text-amber-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                                            {{ $offer->offer_type === 'loan_half' ? 'Half-Season Loan' : ($offer->offer_type === 'loan_full' ? 'Full-Season Loan' : 'Permanent Buy') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Offer from: <span class="text-white font-bold">{{ $offer->buyingTeam->team_name }}</span>
                                    </div>
                                    <div class="text-emerald-400 font-mono font-extrabold text-base mt-1">
                                        Offered Fee: GH₵ {{ number_format($offer->offer_amount, 2) }}
                                    </div>
                                    @if($offer->notes)
                                        <p class="text-xs text-gray-400 italic mt-1 font-mono">"{{ $offer->notes }}"</p>
                                    @endif
                                    @if($offer->status === 'countered')
                                        <div class="mt-2 p-2 bg-amber-500/10 border border-amber-500/30 rounded text-xs text-amber-300">
                                            <strong>Counter-Offer Sent:</strong> GH₵ {{ number_format($offer->counter_amount, 2) }} - {{ $offer->counter_notes }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase flex items-center gap-1">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i> Accept
                                        </button>
                                    </form>
                                    <button onclick="openCounterModal({{ $offer->id }}, '{{ $offer->player->name }}', {{ $offer->offer_amount }})" class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase flex items-center gap-1">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Counter
                                    </button>
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg font-bold text-xs uppercase flex items-center gap-1">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card p-6 text-center text-gray-400 text-xs">
                            <i data-lucide="inbox-stop" class="w-8 h-8 mx-auto mb-2 text-gray-600"></i>
                            No active incoming bids or negotiation requests.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Market Listed Players -->
            <div>
                <h3 class="text-lg font-heading font-extrabold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-cyan-400"></i> Market Listed Players
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($listings as $listing)
                        <div class="glass-card p-4 border border-white/10 hover:border-gold/30 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-heading font-black text-lg text-white uppercase">{{ $listing->player->name }}</div>
                                        <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">
                                            {{ $listing->player->position }} (OVR: {{ $listing->player->rating }}) · {{ $listing->team->team_name }}
                                        </div>
                                    </div>
                                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-accent-gold text-black">
                                        GH₵ {{ number_format($listing->asking_price, 2) }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300">
                                        {{ $listing->type === 'loan_half' ? 'Half-Season Loan' : ($listing->type === 'loan_full' ? 'Full-Season Loan' : 'Permanent Purchase') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 italic mb-4">"{{ $listing->reason ?? 'Listed on open market' }}"</p>
                            </div>

                            @if($listing->team_id !== auth()->user()->team_id)
                                <button onclick="openOfferModal({{ $listing->player_id }}, '{{ $listing->player->name }}', {{ $listing->id }})" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-heading font-bold py-2 rounded-xl text-xs uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5">
                                    <i data-lucide="send" class="w-3.5 h-3.5"></i> Submit Bid / Loan Offer
                                </button>
                            @else
                                <div class="text-center text-xs text-gray-500 font-bold uppercase tracking-wider py-2 bg-white/5 rounded-xl border border-white/5">
                                    Your Active Listing
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full glass-card p-8 text-center text-gray-400 text-xs">
                            No players currently listed on the transfer market.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Direct League Player Bidding -->
            <div>
                <h3 class="text-lg font-heading font-extrabold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-emerald-400"></i> Direct League Player Bidding
                </h3>
                <div class="glass-card p-5">
                    <p class="text-xs text-gray-400 mb-4">Make an unsolicited transfer or loan bid for any player in the league.</p>
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-2">
                        @foreach($all_players as $p)
                            <div class="flex items-center justify-between p-3 bg-white/5 border border-white/5 rounded-xl text-xs hover:border-white/20 transition-all">
                                <div>
                                    <span class="font-bold text-white font-heading text-sm uppercase">{{ $p->name }}</span>
                                    <span class="text-gray-400 ml-2">({{ $p->position }} - Rating {{ $p->rating }})</span>
                                    <div class="text-[10px] text-accent-gold">{{ $p->team ? $p->team->team_name : 'Free Agent' }}</div>
                                </div>
                                <button onclick="openOfferModal({{ $p->id }}, '{{ $p->name }}')" class="px-3 py-1.5 bg-accent-gold text-black rounded-lg text-xs font-heading font-bold uppercase tracking-wider hover:bg-amber-400">
                                    Bid / Negotiate
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Sent Offers & Rumours -->
        <div class="space-y-6">
            <!-- Sent Offers & Negotiations -->
            <div class="glass-card p-6">
                <h3 class="text-base font-heading font-extrabold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4 text-cyan-400"></i> Your Sent Bids & Negotiation Status
                </h3>
                <div class="space-y-3">
                    @forelse($my_sent_offers as $sent)
                        <div class="p-3 bg-white/5 border border-white/10 rounded-xl text-xs space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-white">{{ $sent->player->name }}</span>
                                <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded {{ $sent->status === 'accepted' ? 'bg-emerald-500/20 text-emerald-400' : ($sent->status === 'rejected' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-300') }}">
                                    {{ ucfirst($sent->status) }}
                                </span>
                            </div>
                            <div class="text-gray-400 text-[11px]">To: {{ $sent->sellingTeam->team_name }}</div>
                            <div class="text-emerald-400 font-mono font-bold">Offer: GH₵ {{ number_format($sent->offer_amount, 2) }}</div>
                            @if($sent->status === 'countered')
                                <div class="mt-1 p-2 bg-amber-500/20 rounded border border-amber-500/40 text-amber-200">
                                    <strong>Seller Counter:</strong> GH₵ {{ number_format($sent->counter_amount, 2) }}
                                    <p class="text-[10px] italic mt-0.5">"{{ $sent->counter_notes }}"</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-xs italic">You have not submitted any active bids.</p>
                    @endforelse
                </div>
            </div>

            <!-- Transfer Rumour Mill -->
            <div class="glass-card p-6 bg-gradient-to-b from-cyan-500/5 to-transparent border border-cyan-500/20">
                <h3 class="text-base font-heading font-extrabold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span>🔮</span> Transfer Rumour Mill
                </h3>
                <div class="space-y-3">
                    @foreach($rumours as $rumour)
                        <div class="p-3 bg-white/5 border border-white/5 rounded-xl space-y-1.5">
                            <div class="flex items-center justify-between text-[8px] uppercase">
                                <span class="font-extrabold px-1.5 py-0.5 rounded {{ $rumour['urgency'] === 'Breaking' ? 'bg-rose-500/10 text-rose-400' : 'bg-blue-500/10 text-blue-400' }}">{{ $rumour['urgency'] }}</span>
                                <span class="font-semibold text-emerald-400">{{ $rumour['probability'] }}</span>
                            </div>
                            <p class="text-xs font-semibold leading-normal text-gray-200 font-heading">"{{ $rumour['title'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Make Offer / Bid -->
<div id="offer-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 border border-white/20">
        <h3 class="text-xl font-heading font-black text-accent-gold uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="coins" class="w-5 h-5"></i> Submit Bid / Offer
        </h3>
        <form action="{{ route('manager.transfers.offer') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="player_id" id="offer_player_id">
            <input type="hidden" name="listing_id" id="offer_listing_id">

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Target Player</label>
                <input type="text" id="offer_player_name" readonly class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Offer Type</label>
                    <select name="offer_type" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="permanent">Permanent Purchase</option>
                        <option value="loan_half">Half-Season Loan</option>
                        <option value="loan_full">Full-Season Loan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Offer Fee (GH₵)</label>
                    <input type="number" step="0.01" name="offer_amount" required placeholder="e.g. 500000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Negotiation Terms / Notes</label>
                <textarea name="notes" rows="3" placeholder="Add custom terms or payment structure details..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>

            <div class="flex gap-3 pt-3 border-t border-white/10">
                <button type="button" onclick="toggleModal('offer-modal')" class="flex-1 bg-white/5 hover:bg-white/10 text-gray-400 font-bold py-2.5 rounded-xl border border-white/10">Cancel</button>
                <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-500 text-white font-heading font-black py-2.5 rounded-xl uppercase tracking-wider shadow">Submit Bid</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Counter-Offer -->
<div id="counter-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 border border-amber-500/30">
        <h3 class="text-xl font-heading font-black text-amber-400 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="refresh-cw" class="w-5 h-5"></i> Submit Counter-Offer
        </h3>
        <form id="counter-form" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="action" value="counter">

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Player</label>
                <input type="text" id="counter_player_name" readonly class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white font-bold">
            </div>

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Counter Price (GH₵)</label>
                <input type="number" step="0.01" name="counter_amount" id="counter_amount" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-amber-400 outline-none">
            </div>

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Counter Notes / Reason</label>
                <textarea name="counter_notes" rows="3" placeholder="Explain why you are demanding this revised fee..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-amber-400 outline-none"></textarea>
            </div>

            <div class="flex gap-3 pt-3 border-t border-white/10">
                <button type="button" onclick="toggleModal('counter-modal')" class="flex-1 bg-white/5 text-gray-400 font-bold py-2.5 rounded-xl border border-white/10">Cancel</button>
                <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-500 text-white font-heading font-black py-2.5 rounded-xl uppercase tracking-wider shadow">Send Counter-Offer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: List Player -->
<div id="list-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 border border-white/20">
        <h3 class="text-xl font-heading font-black text-accent-gold uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="tag" class="w-5 h-5"></i> List Player to Market
        </h3>
        <form action="{{ route('manager.transfers.list') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Select Squad Player</label>
                <select name="player_id" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                    @foreach(auth()->user()->team->players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }} ({{ $player->position }} - OVR: {{ $player->rating }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Listing Type</label>
                    <select name="type" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="permanent">Permanent Sale</option>
                        <option value="loan_half">Loan (Half Season)</option>
                        <option value="loan_full">Loan (Full Season)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Asking Price (GH₵)</label>
                    <input type="number" step="0.01" name="asking_price" required placeholder="e.g. 1000000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Reason for Listing</label>
                <textarea name="reason" rows="3" placeholder="e.g. Seeking game time on loan / Surplus to requirements" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>

            <div class="flex gap-3 pt-3 border-t border-white/10">
                <button type="button" onclick="toggleModal('list-player-modal')" class="flex-1 bg-white/5 text-gray-400 font-bold py-2.5 rounded-xl border border-white/10">Cancel</button>
                <button type="submit" class="flex-1 bg-accent-gold text-black font-heading font-black py-2.5 rounded-xl uppercase tracking-wider shadow">List Player</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOfferModal(id, name, listingId = null) {
    document.getElementById('offer_player_id').value = id;
    document.getElementById('offer_player_name').value = name;
    document.getElementById('offer_listing_id').value = listingId || '';
    toggleModal('offer-modal');
}

function openCounterModal(offerId, playerName, currentFee) {
    document.getElementById('counter-form').action = '/manager/transfers/handle/' + offerId;
    document.getElementById('counter_player_name').value = playerName;
    document.getElementById('counter_amount').value = currentFee;
    toggleModal('counter-modal');
}
</script>
@endsection
