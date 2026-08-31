@extends('layouts.manager')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-heading tracking-wider uppercase">Transfer Market & Negotiation Hub</h2>
            <p class="text-xs text-gray-400">Buy full-time, loan for half or full season, and negotiate bids with opposing managers.</p>
        </div>
        <div class="text-xs font-mono font-bold bg-green-500/10 text-green-400 border border-green-500/20 px-3 py-1.5 rounded-lg flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            WINDOW OPEN
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-900/30 border border-green-800 text-green-400 text-xs font-semibold rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-900/30 border border-red-800 text-red-400 text-xs font-semibold rounded-lg flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">

            <!-- Section 1: Incoming Bids & Counter-Offers -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4 text-accent-gold flex items-center gap-2">
                    <span>📩</span> Incoming Bids on My Players
                </h3>
                <div class="space-y-4">
                    @forelse($incoming_offers as $offer)
                        <div class="glass-card p-5 border border-white/10 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-lg text-white font-heading">{{ $offer->player->name }}</div>
                                    <div class="text-xs text-gray-400">
                                        Bid from: <span class="text-accent-gold font-bold">{{ $offer->buyingTeam->team_name }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-accent-gold font-mono font-black text-sm">GH₵ {{ number_format($offer->offer_amount, 2) }}</span>
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                            {{ match($offer->offer_type) { 'loan_half' => 'Loan (Half Season)', 'loan_full' => 'Loan (Full Season)', default => 'Permanent Buy' } }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                        {{ ucfirst($offer->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($offer->notes)
                                <p class="text-xs text-gray-400 italic bg-white/5 p-2 rounded border border-white/5">
                                    "{{ $offer->notes }}"
                                </p>
                            @endif

                            <div class="flex items-center gap-2 pt-2 border-t border-white/10">
                                <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
                                        Accept
                                    </button>
                                </form>

                                <button type="button" onclick="openCounterModal({{ $offer->id }}, '{{ $offer->player->name }}', {{ $offer->offer_amount }}, '{{ $offer->offer_type }}')" class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
                                    Counter Offer
                                </button>

                                <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase tracking-wider transition-all">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-xs italic bg-white/5 p-4 rounded-lg">No pending bids received on your squad.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section 2: Outgoing Bids & Negotiation Threads -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4 text-cyan-400 flex items-center gap-2">
                    <span>📤</span> My Outgoing Bids & Counter Negotiations
                </h3>
                <div class="space-y-4">
                    @forelse($outgoing_offers as $offer)
                        <div class="glass-card p-5 border border-white/10 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-lg text-white font-heading">{{ $offer->player->name }}</div>
                                    <div class="text-xs text-gray-400">
                                        Owner Club: <span class="text-white font-bold">{{ $offer->sellingTeam->team_name }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-accent-gold font-mono font-bold text-sm">Offer: GH₵ {{ number_format($offer->offer_amount, 2) }}</span>
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                            {{ match($offer->offer_type) { 'loan_half' => 'Loan (Half Season)', 'loan_full' => 'Loan (Full Season)', default => 'Permanent Buy' } }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded {{ match($offer->status) { 'accepted' => 'bg-green-500/20 text-green-400 border border-green-500/30', 'countered' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30', 'rejected' => 'bg-red-500/20 text-red-400 border border-red-500/30', default => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' } }}">
                                        {{ $offer->status === 'countered' ? 'Counter-Offer Received' : ucfirst($offer->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($offer->status === 'countered')
                                <div class="bg-amber-500/10 border border-amber-500/30 p-4 rounded-lg space-y-2">
                                    <div class="text-xs font-bold text-amber-400 uppercase tracking-wider">
                                        Seller's Counter-Proposal:
                                    </div>
                                    <div class="text-sm font-mono font-black text-gold">
                                        GH₵ {{ number_format($offer->counter_amount, 2) }}
                                        <span class="text-xs font-normal text-gray-300">({{ match($offer->counter_type) { 'loan_half' => 'Half Season Loan', 'loan_full' => 'Full Season Loan', default => 'Permanent Buy' } }})</span>
                                    </div>
                                    @if($offer->counter_notes)
                                        <p class="text-xs text-gray-300 italic font-body">"{{ $offer->counter_notes }}"</p>
                                    @endif
                                    <div class="flex gap-2 pt-2">
                                        <form action="{{ route('manager.transfers.handle-counter', $offer->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-xs font-bold px-4 py-2 rounded-lg uppercase tracking-wider">
                                                Accept Counter Offer
                                            </button>
                                        </form>
                                        <form action="{{ route('manager.transfers.handle-counter', $offer->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-lg uppercase tracking-wider">
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-xs italic bg-white/5 p-4 rounded-lg">You have no active outgoing bids.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section 3: Marketplace Available Players -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span>⚽</span> Players Listed on Market
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($listings as $listing)
                        <div class="glass-card p-5 border border-white/10 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-bold text-lg font-heading text-white">{{ $listing->player->name }}</div>
                                        <div class="text-xs text-gray-400 uppercase font-bold">
                                            {{ $listing->player->position }} · OVR {{ $listing->player->rating }} · {{ $listing->team->team_name }}
                                        </div>
                                    </div>
                                    <div class="bg-accent-gold text-bg-dark px-2.5 py-1 rounded text-xs font-mono font-black">
                                        GH₵ {{ number_format($listing->asking_price, 2) }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                        {{ match($listing->type) { 'loan_half' => 'Loan (Half Season)', 'loan_full' => 'Loan (Full Season)', 'loan' => 'Loan', default => 'Permanent Buy' } }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mb-4">{{ $listing->reason ?: 'Listed for transfer/loan' }}</p>
                            </div>

                            @if($listing->team_id !== auth()->user()->team_id)
                                <button onclick="openOfferModal({{ $listing->player_id }}, '{{ $listing->player->name }}', '{{ $listing->type }}', {{ $listing->asking_price ?? 0 }})" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg text-xs uppercase tracking-wider transition-colors">
                                    Make Offer / Bid
                                </button>
                            @else
                                <div class="text-center text-xs text-gray-500 font-bold uppercase tracking-wider italic py-2 bg-white/5 rounded-lg">
                                    Your Club Listing
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-2 text-center py-8 glass-card">No players currently listed on the transfer market.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar Section -->
        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading uppercase mb-4 text-gold">Manage My Transfers</h3>
                <button onclick="toggleModal('list-player-modal')" class="w-full bg-accent-gold hover:bg-gold text-bg-dark font-extrabold font-heading tracking-wider uppercase py-3 rounded-lg mb-3">
                    List a Player
                </button>
                <div class="text-[11px] text-gray-400 text-center uppercase">Put squad members up for permanent sale or loan</div>
            </div>

            <div class="glass-card p-6 bg-gradient-to-b from-[#00e5ff]/5 to-transparent border border-[#00e5ff]/20">
                <h3 class="text-lg font-bold font-heading mb-4 text-[#00e5ff] flex items-center gap-2"><span>🔮</span> Transfer Rumour Mill</h3>
                <div class="space-y-4">
                    @foreach($rumours as $rumour)
                        <div class="p-3 bg-white/5 border border-white/5 rounded-lg space-y-2">
                            <div class="flex items-center justify-between text-[8px] uppercase">
                                <span class="font-extrabold px-1.5 py-0.5 rounded {{ $rumour['urgency'] === 'Breaking' ? 'bg-rose-500/10 text-rose-400' : 'bg-blue-500/10 text-blue-400' }}">{{ $rumour['urgency'] }}</span>
                                <span class="font-semibold text-emerald-400">{{ $rumour['probability'] }}</span>
                            </div>
                            <p class="text-xs font-semibold leading-normal">"{{ $rumour['title'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Make Offer / Bid -->
<div id="offer-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 border border-blue-500/40">
        <h3 class="text-xl font-black font-heading uppercase text-blue-400 mb-6">Make Transfer Bid</h3>
        <form action="{{ route('manager.transfers.offer') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="player_id" id="offer_player_id">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Target Player</label>
                <input type="text" id="offer_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-gray-300 font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Bid Type</label>
                <select name="offer_type" id="offer_type" required class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                    <option value="permanent">Full-Time Purchase (Permanent)</option>
                    <option value="loan_half">Loan Deal (Half Season - 6 Months)</option>
                    <option value="loan_full">Loan Deal (Full Season - 1 Year)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Bid Amount (GH₵)</label>
                <input type="number" name="offer_amount" id="offer_amount_input" required min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white font-mono font-bold focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Negotiation Notes / Clauses</label>
                <textarea name="notes" rows="3" placeholder="e.g. Willing to add performance bonuses or match clauses" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4 border-t border-white/10">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-lg text-xs uppercase font-heading tracking-wider">
                    Submit Bid
                </button>
                <button type="button" onclick="toggleModal('offer-modal')" class="px-5 bg-white/5 font-bold py-3 rounded-lg border border-white/10 text-xs">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Counter Offer -->
<div id="counter-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 border border-amber-500/40">
        <h3 class="text-xl font-black font-heading uppercase text-amber-400 mb-6">Send Counter-Offer</h3>
        <form id="counter_form" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="action" value="counter">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Player</label>
                <input type="text" id="counter_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-gray-300 font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Counter Deal Type</label>
                <select name="counter_type" id="counter_type" required class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                    <option value="permanent">Full-Time Purchase (Permanent)</option>
                    <option value="loan_half">Loan Deal (Half Season)</option>
                    <option value="loan_full">Loan Deal (Full Season)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Counter Amount (GH₵)</label>
                <input type="number" name="counter_amount" id="counter_amount_input" required min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white font-mono font-bold focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Counter Notes</label>
                <textarea name="counter_notes" rows="3" placeholder="e.g. We require a higher fee to sanction this move" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4 border-t border-white/10">
                <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 rounded-lg text-xs uppercase font-heading tracking-wider">
                    Send Counter Offer
                </button>
                <button type="button" onclick="toggleModal('counter-modal')" class="px-5 bg-white/5 font-bold py-3 rounded-lg border border-white/10 text-xs">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: List Player -->
<div id="list-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold font-heading uppercase tracking-wider text-gold mb-6">List Squad Player for Transfer</h3>
        <form action="{{ route('manager.transfers.list') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Select Player</label>
                <select name="player_id" required class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-accent-gold outline-none">
                    @foreach(auth()->user()->team->players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }} ({{ $player->position }} · OVR {{ $player->rating }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Listing Deal Type</label>
                    <select name="type" class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-accent-gold outline-none">
                        <option value="permanent">Permanent Sale</option>
                        <option value="loan_half">Half Season Loan</option>
                        <option value="loan_full">Full Season Loan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Asking Price (GH₵)</label>
                    <input type="number" name="asking_price" min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Listing Description / Reason</label>
                <textarea name="reason" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4 border-t border-white/10">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg text-xs uppercase font-heading tracking-wider">
                    List Player
                </button>
                <button type="button" onclick="toggleModal('list-player-modal')" class="px-5 bg-white/5 font-bold py-3 rounded-lg border border-white/10 text-xs">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openOfferModal(id, name, type, price) {
    document.getElementById('offer_player_id').value = id;
    document.getElementById('offer_player_name').value = name;
    document.getElementById('offer_amount_input').value = price || '';
    if (type && ['permanent', 'loan_half', 'loan_full'].includes(type)) {
        document.getElementById('offer_type').value = type;
    }
    toggleModal('offer-modal');
}

function openCounterModal(offerId, playerName, currentAmount, currentType) {
    document.getElementById('counter_form').action = "/manager/transfers/handle/" + offerId;
    document.getElementById('counter_player_name').value = playerName;
    document.getElementById('counter_amount_input').value = currentAmount || '';
    if (currentType && ['permanent', 'loan_half', 'loan_full'].includes(currentType)) {
        document.getElementById('counter_type').value = currentType;
    }
    toggleModal('counter-modal');
}
</script>
@endsection
