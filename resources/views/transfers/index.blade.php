@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading uppercase tracking-wider">Transfer & Loan Negotiation Hub</h2>
            <p class="text-xs text-gray-400">Negotiate permanent player signings, half-season loans, and full-season loans across the league.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400">Transfer Window: <span class="text-green-400 font-bold uppercase">Open</span></span>
            <button onclick="toggleModal('list-player-modal')" class="bg-accent-gold text-bg-dark font-bold px-4 py-2 rounded-lg text-sm uppercase font-heading">List Player on Market</button>
        </div>
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

    <!-- Deal Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-white/10 pb-4 overflow-x-auto">
        <a href="{{ route('manager.transfers.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ !$dealTypeFilter ? 'bg-accent-gold text-bg-dark' : 'bg-white/5 text-gray-400 hover:text-white' }}">
            All Listed Deals
        </a>
        <a href="{{ route('manager.transfers.index', ['deal_type' => 'permanent']) }}" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $dealTypeFilter === 'permanent' ? 'bg-accent-gold text-bg-dark' : 'bg-white/5 text-gray-400 hover:text-white' }}">
            Full-Time Purchases
        </a>
        <a href="{{ route('manager.transfers.index', ['deal_type' => 'loan_half']) }}" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $dealTypeFilter === 'loan_half' ? 'bg-accent-gold text-bg-dark' : 'bg-white/5 text-gray-400 hover:text-white' }}">
            Loans (Half Season / 6 Mos)
        </a>
        <a href="{{ route('manager.transfers.index', ['deal_type' => 'loan_full']) }}" class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all {{ $dealTypeFilter === 'loan_full' ? 'bg-accent-gold text-bg-dark' : 'bg-white/5 text-gray-400 hover:text-white' }}">
            Loans (Full Season / 1 Year)
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Incoming Bids & Counter-Offers -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4 flex items-center gap-2 text-cyan-400">
                    <span>📩</span> Incoming Bids & Negotiations ({{ $incoming_offers->count() }})
                </h3>
                <div class="space-y-4">
                    @forelse($incoming_offers as $offer)
                        <div class="glass-card p-4 border border-cyan-500/20 bg-cyan-500/5">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-3">
                                <div>
                                    <div class="font-bold text-lg text-white flex items-center gap-2">
                                        {{ $offer->player->name }}
                                        <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded {{ str_contains($offer->deal_type, 'loan') ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400' }}">
                                            {{ $offer->deal_type === 'permanent' ? 'Full-Time Purchase' : ($offer->deal_type === 'loan_half' ? 'Half Season Loan' : 'Full Season Loan') }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400">Offer from: <span class="text-white font-bold">{{ $offer->buyingTeam->team_name }}</span></div>
                                    <div class="text-accent-gold font-black mt-1">
                                        Offered Amount: GH₵ {{ number_format($offer->offer_amount, 2) }}
                                    </div>
                                    @if($offer->status === 'countered')
                                        <div class="text-xs text-amber-400 font-bold mt-1">
                                            Your Counter-Offer: GH₵ {{ number_format($offer->counter_amount, 2) }}
                                        </div>
                                    @endif
                                    @if($offer->notes)
                                        <div class="text-xs text-gray-400 italic mt-2">"{{ $offer->notes }}"</div>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2 items-center">
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase hover:bg-green-500">Accept Bid</button>
                                    </form>
                                    <button onclick="openCounterModal({{ $offer->id }}, '{{ $offer->player->name }}', {{ $offer->offer_amount }})" class="bg-amber-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase hover:bg-amber-500">Counter Bid</button>
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase hover:bg-red-500">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm italic glass-card p-4 text-center">No active incoming bids received.</p>
                    @endforelse
                </div>
            </div>

            <!-- Outgoing Bids Thread -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4 flex items-center gap-2 text-amber-400">
                    <span>📤</span> Outgoing Bids History ({{ $outgoing_offers->count() }})
                </h3>
                <div class="space-y-4">
                    @forelse($outgoing_offers as $offer)
                        <div class="glass-card p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div>
                                <div class="font-bold text-base text-white flex items-center gap-2">
                                    {{ $offer->player->name }}
                                    <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded {{ $offer->status === 'accepted' ? 'bg-green-500/20 text-green-400' : ($offer->status === 'rejected' ? 'bg-red-500/20 text-red-400' : 'bg-amber-500/20 text-amber-400') }}">
                                        {{ ucfirst($offer->status) }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-400">Selling Club: <span class="text-white font-bold">{{ $offer->sellingTeam->team_name }}</span></div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Deal Type: <span class="text-accent-gold font-bold">{{ $offer->deal_type === 'permanent' ? 'Full Purchase' : ($offer->deal_type === 'loan_half' ? 'Half Season Loan' : 'Full Season Loan') }}</span> |
                                    Amount: <span class="text-white font-bold">GH₵ {{ number_format($offer->offer_amount, 2) }}</span>
                                </div>
                                @if($offer->status === 'countered')
                                    <div class="mt-2 p-2 bg-amber-500/10 border border-amber-500/20 rounded-lg text-xs">
                                        <span class="text-amber-400 font-bold uppercase">Seller Counter-Offer:</span> GH₵ {{ number_format($offer->counter_amount, 2) }}
                                        @if($offer->counter_notes)<p class="text-gray-400 italic mt-1">"{{ $offer->counter_notes }}"</p>@endif
                                    </div>
                                @endif
                            </div>
                            @if($offer->status === 'countered')
                                <div class="flex gap-2">
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs font-bold uppercase">Accept Counter</button>
                                    </form>
                                    <form action="{{ route('manager.transfers.handle', $offer->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded text-xs font-bold uppercase">Decline</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm italic glass-card p-4 text-center">No outgoing bids submitted yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Listed Market Players -->
            <div>
                <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-4">Available Market Players</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($listings as $listing)
                        <div class="glass-card p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-bold text-lg text-white">{{ $listing->player->name }}</div>
                                        <div class="text-xs text-gray-400 uppercase font-bold">{{ $listing->player->position }} · {{ $listing->team->team_name }} · OVR {{ $listing->player->rating }}</div>
                                    </div>
                                    <div class="bg-accent-gold text-bg-dark px-2.5 py-1 rounded text-xs font-black">
                                        GH₵ {{ number_format($listing->asking_price, 2) }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 my-2">
                                    <span class="text-[9px] uppercase font-extrabold px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                        {{ $listing->deal_type === 'permanent' ? 'Full Purchase' : ($listing->deal_type === 'loan_half' ? 'Half Season Loan (6M)' : 'Full Season Loan (1Y)') }}
                                    </span>
                                    @if($listing->scoutAgent)
                                        <span class="text-[9px] uppercase font-extrabold px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                            Scout: {{ $listing->scoutAgent->name }}
                                        </span>
                                    @endif
                                </div>
                                @if($listing->reason)<p class="text-xs text-gray-400 mb-4 italic">"{{ $listing->reason }}"</p>@endif
                            </div>

                            @if($listing->team_id !== auth()->user()->team_id)
                                <button onclick="openOfferModal({{ $listing->player_id }}, '{{ $listing->player->name }}', '{{ $listing->deal_type }}', {{ $listing->asking_price }})" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg text-sm transition-colors hover:bg-blue-500 uppercase font-heading tracking-wider">
                                    Make Bid / Offer
                                </button>
                            @else
                                <div class="text-center text-xs text-gray-500 font-bold uppercase italic border-t border-white/5 pt-2">Your Team Listing</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-2 text-center py-8 glass-card">No players currently listed for this deal type.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading uppercase mb-4">Transfer Operations</h3>
                <button onclick="toggleModal('list-player-modal')" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mb-4 uppercase font-heading">List Player on Market</button>
                <a href="{{ route('manager.scouts.index') }}" class="block text-center w-full bg-cyan-600 text-white font-bold py-3 rounded-lg mb-4 uppercase font-heading hover:bg-cyan-500 transition-colors">Manage Scouting Agents</a>
                <div class="text-[10px] text-gray-500 text-center uppercase">List players for full-time sale or seasonal loans</div>
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
                            <p class="text-xs font-semibold leading-normal">"{{ $rumour['title'] }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Make Offer / Bid Modal -->
<div id="offer-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-6">Submit Transfer or Loan Bid</h3>
        <form action="{{ route('manager.transfers.offer') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="player_id" id="offer_player_id">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player Target</label>
                <input type="text" id="offer_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-gray-400 font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Select Deal Type</label>
                <select name="deal_type" id="offer_deal_type" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    <option value="permanent" class="bg-gray-900">Full-Time Purchase (Permanent Transfer)</option>
                    <option value="loan_half" class="bg-gray-900">Loan (Half Season / 6 Months)</option>
                    <option value="loan_full" class="bg-gray-900">Loan (Full Season / 1 Year)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Bid / Offer Amount (GH₵)</label>
                <input type="number" name="offer_amount" id="offer_amount_input" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Proposed Contract / Loan Years</label>
                <input type="number" name="proposed_contract_years" value="1" min="1" max="5" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Negotiation Notes / Offer Terms</label>
                <textarea name="notes" rows="3" placeholder="Add custom terms or wage structure details..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-lg font-heading uppercase">Submit Bid</button>
                <button type="button" onclick="toggleModal('offer-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10 font-heading uppercase">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Counter Offer Modal -->
<div id="counter-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-6">Submit Counter-Offer</h3>
        <form id="counter-form" action="" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="action" value="counter">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player Target</label>
                <input type="text" id="counter_player_name" disabled class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-gray-400 font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Your Counter Asking Price (GH₵)</label>
                <input type="number" name="counter_amount" id="counter_amount_input" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Counter Notes</label>
                <textarea name="counter_notes" rows="3" placeholder="Explain your required terms or valuation..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-amber-600 text-white font-bold py-3 rounded-lg font-heading uppercase">Send Counter-Bid</button>
                <button type="button" onclick="toggleModal('counter-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10 font-heading uppercase">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- List Player Modal -->
<div id="list-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold font-heading uppercase tracking-wider mb-6">List Player for Transfer or Loan</h3>
        <form action="{{ route('manager.transfers.list') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Select Player</label>
                <select name="player_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    @foreach(auth()->user()->team->players as $player)
                        <option value="{{ $player->id }}" class="bg-gray-900 text-white">{{ $player->name }} ({{ $player->position }}, OVR {{ $player->rating }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Deal Type</label>
                    <select name="deal_type" id="list_deal_type" onchange="syncListType()" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="permanent" class="bg-gray-900">Full-Time Purchase</option>
                        <option value="loan_half" class="bg-gray-900">Loan (Half Season)</option>
                        <option value="loan_full" class="bg-gray-900">Loan (Full Season)</option>
                    </select>
                    <input type="hidden" name="type" id="list_type_hidden" value="permanent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Asking Price / Fee</label>
                    <input type="number" name="asking_price" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Reason for Listing</label>
                <textarea name="reason" rows="3" placeholder="e.g. Seeking regular playing time or squad rebalancing..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg font-heading uppercase">List Player</button>
                <button type="button" onclick="toggleModal('list-player-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10 font-heading uppercase">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOfferModal(id, name, dealType, askingPrice) {
    document.getElementById('offer_player_id').value = id;
    document.getElementById('offer_player_name').value = name;
    if (dealType) {
        document.getElementById('offer_deal_type').value = dealType;
    }
    if (askingPrice) {
        document.getElementById('offer_amount_input').value = askingPrice;
    }
    toggleModal('offer-modal');
}

function openCounterModal(offerId, playerName, currentAmount) {
    const form = document.getElementById('counter-form');
    form.action = `/manager/transfers/handle/${offerId}`;
    document.getElementById('counter_player_name').value = playerName;
    document.getElementById('counter_amount_input').value = currentAmount;
    toggleModal('counter-modal');
}

function syncListType() {
    const dealType = document.getElementById('list_deal_type').value;
    const typeHidden = document.getElementById('list_type_hidden');
    if (typeHidden) {
        typeHidden.value = dealType.includes('loan') ? 'loan' : 'permanent';
    }
}
</script>
@endsection
