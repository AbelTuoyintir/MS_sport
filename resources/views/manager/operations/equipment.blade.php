@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Equipment Inventory</h2>
        <button onclick="toggleModal('add-equipment-modal')" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">+ Add Item</button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($equipment as $item)
            <div class="glass-card p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center">
                        <i data-lucide="package" class="w-5 h-5 text-accent-gold"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-1 rounded bg-white/10">{{ $item->condition }}</span>
                </div>
                <h3 class="font-bold text-lg mb-1">{{ $item->name }}</h3>
                <div class="flex items-end gap-2">
                    <div class="text-2xl font-black text-accent-gold">{{ $item->available_quantity }}</div>
                    <div class="text-xs text-gray-500 mb-1">/ {{ $item->total_quantity }} Available</div>
                </div>
                <div class="mt-4 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-accent-gold h-full" style="width: {{ ($item->available_quantity / $item->total_quantity) * 100 }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-full text-center py-12">No equipment records found.</p>
        @endforelse
    </div>
</div>
<!-- Add Equipment Modal -->
<div id="add-equipment-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">Add Equipment</h3>
        <form action="{{ route('manager.equipment.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Name</label>
                <input type="text" name="name" required placeholder="e.g. Training Balls" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Total Quantity</label>
                    <input type="number" name="total_quantity" required min="1" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Available</label>
                    <input type="number" name="available_quantity" required min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Condition</label>
                <select name="condition" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    <option value="New">New</option>
                    <option value="Good">Good</option>
                    <option value="Worn">Worn</option>
                    <option value="Damaged">Damaged</option>
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Save Item</button>
                <button type="button" onclick="toggleModal('add-equipment-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
