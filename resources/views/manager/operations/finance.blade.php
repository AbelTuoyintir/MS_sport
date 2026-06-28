@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold">Financial Records</h2>
            <p class="text-gray-400 text-sm">Track income, salaries, and expenses</p>
        </div>
        <div class="text-right">
            <div class="text-xs text-gray-500 font-bold uppercase">Current Balance</div>
            <div class="text-3xl font-black {{ $balance >= 0 ? 'text-green-400' : 'text-red-400' }}">GH₵ {{ number_format($balance, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/5">
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($finances as $record)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold uppercase px-2 py-1 rounded bg-white/10">{{ $record->category }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ $record->description }}</td>
                                <td class="px-6 py-4 text-right font-bold {{ $record->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $record->type === 'income' ? '+' : '-' }} GH₵ {{ number_format($record->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No financial records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold mb-6">Add Transaction</h3>
                <form action="{{ route('manager.finance.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                        <select name="type" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
                        <select name="category" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                            <option value="Salary">Salary</option>
                            <option value="Transfer Fee">Transfer Fee</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Sponsorship">Sponsorship</option>
                            <option value="Travel">Travel</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Amount (GH₵)</label>
                        <input type="number" name="amount" step="0.01" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                        <textarea name="description" rows="2" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mt-4">Save Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
