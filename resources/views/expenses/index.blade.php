@extends('layouts.app')
@section('title', 'Budget Trip')

@section('header')
<div class="flex items-center gap-3 w-full">
    <a href="{{ route('trips.show', $trip) }}" class="w-10 h-10 bg-white border-[3px] border-[#1A1A2E] rounded-full flex items-center justify-center font-bold shadow-[2px_2px_0px_#1A1A2E] shrink-0 hover:translate-y-[-2px] transition-transform">
        &larr;
    </a>
    <div class="flex-1 overflow-hidden">
        <h1 class="text-xl font-heading font-bold truncate">Budget Tracker 💰</h1>
        <p class="text-xs font-medium opacity-80 truncate">{{ $trip->title }}</p>
    </div>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="mb-4 p-4 bg-[#DDF6FF] border-[3px] border-[#4361EE] rounded-xl text-[#1A1A2E] font-bold">
        {{ session('success') }}
    </div>
@endif
<!-- Budget Summary Card -->
<x-card class="bg-[#1A1A2E] text-white mb-6">
    <div class="flex justify-between items-end mb-4">
        <div>
            <div class="text-xs font-medium opacity-80 mb-1">Total Budget</div>
            <div class="font-heading font-bold text-xl">Rp {{ number_format($trip->total_budget, 0, ',', '.') }}</div>
        </div>
        <div class="text-right">
            <div class="text-xs font-medium opacity-80 mb-1">Terpakai</div>
            <div class="font-heading font-bold text-xl text-[#FF6B9D]">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
        </div>
    </div>
    
    @php
        $percentage = $trip->total_budget > 0 ? min(100, ($totalSpent / $trip->total_budget) * 100) : 0;
        $progressColor = $percentage > 90 ? 'bg-[#FF6B9D]' : ($percentage > 75 ? 'bg-[#FF8C42]' : 'bg-[#00D4AA]');
    @endphp
    
    <div class="w-full h-4 bg-white/20 rounded-full overflow-hidden mb-2 border-2 border-white/10">
        <div class="h-full {{ $progressColor }} transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
    
    <div class="flex justify-between text-xs font-bold">
        <span class="text-[#00D4AA]">Sisa: Rp {{ number_format($remainingBudget, 0, ',', '.') }}</span>
        <span>{{ number_format($percentage, 1) }}%</span>
    </div>
    @if($splitBudgetExpenseExists)
    <div class="mt-4 px-4 py-3 bg-white border-[3px] border-[#1A1A2E] rounded-xl text-sm font-bold text-[#1A1A2E]">
        💡 Budget ini berasal dari split bill. Semua anggota telah dibagi secara rata berdasarkan total trip.
    </div>
    @endif
</x-card>

<!-- Settlement / Utang Piutang -->
<h3 class="font-heading font-bold text-lg mb-3">⚖️ Settlement (Siapa bayar siapa)</h3>
<div class="flex flex-col gap-3 mb-8">
    @php
        $debtors = [];
        $creditors = [];
        foreach($balances as $id => $data) {
            if ($data['balance'] < -0.01) $debtors[] = $data;
            elseif ($data['balance'] > 0.01) $creditors[] = $data;
        }
    @endphp
    
    @if(empty($debtors))
        <x-card class="bg-[#00D4AA] text-[#1A1A2E]">
            <div class="flex items-center gap-3">
                <div class="text-2xl">🎉</div>
                <div class="font-bold">Semua patungan sudah lunas!</div>
            </div>
        </x-card>
    @else
        @foreach($debtors as $debtor)
            @foreach($creditors as $creditor)
                @if($debtor['balance'] < -0.01 && $creditor['balance'] > 0.01)
                    @php
                        $amount = min(abs($debtor['balance']), $creditor['balance']);
                        $debtor['balance'] += $amount;
                        $creditor['balance'] -= $amount;
                    @endphp
                    <x-card class="bg-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-avatar :user="$debtor['user']" size="sm" />
                                <span class="font-bold text-sm">{{ $debtor['user']->id === Auth::id() ? 'Kamu' : $debtor['user']->name }}</span>
                            </div>
                            <div class="flex flex-col items-center px-2 opacity-50">
                                <span class="text-xs font-bold">bayar ke</span>
                                <span>&rarr;</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm">{{ $creditor['user']->id === Auth::id() ? 'Kamu' : $creditor['user']->name }}</span>
                                <x-avatar :user="$creditor['user']" size="sm" />
                            </div>
                        </div>
                        <div class="text-center mt-3 pt-3 border-t-2 border-dashed border-gray-200 font-heading font-bold text-[#FF6B9D]">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </div>
                    </x-card>
                @endif
            @endforeach
        @endforeach
    @endif
</div>

<div class="flex justify-between items-center mb-4">
    <h3 class="font-heading font-bold text-lg">📝 Riwayat Pengeluaran</h3>
    <a href="{{ route('expenses.create', $trip) }}" class="nb-btn nb-btn-primary nb-btn-sm">+ Catat</a>
</div>

<div class="flex flex-col gap-3">
    @forelse($trip->expenses()->orderByDesc('expense_date')->orderByDesc('created_at')->get() as $expense)
        <x-card class="bg-white flex items-center gap-3">
            <div class="w-12 h-12 rounded-full border-2 border-[#1A1A2E] flex items-center justify-center text-xl bg-[#FFFBEB] shrink-0">
                @switch($expense->category)
                    @case('akomodasi') 🏨 @break
                    @case('transportasi') 🚗 @break
                    @case('kuliner') 🍜 @break
                    @case('tiket') 🎫 @break
                    @case('belanja') 🛍️ @break
                    @default ✨
                @endswitch
            </div>
            
            <div class="flex-1 overflow-hidden">
                <div class="flex justify-between items-start">
                    <h4 class="font-bold font-heading truncate">{{ $expense->title }}</h4>
                    <span class="font-bold shrink-0 ml-2">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center mt-1">
                    <div class="text-xs font-medium opacity-70">
                        Dibayar oleh: {{ $expense->payer->id === Auth::id() ? 'Kamu' : $expense->payer->name }}
                    </div>
                    
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-sm bg-red-500 text-white border-2 border-[#1A1A2E] font-bold shadow-[2px_2px_0px_#1A1A2E] hover:translate-y-[-1px] transition-transform">&times;</button>
                    </form>
                </div>
            </div>
        </x-card>
    @empty
        <div class="text-center py-8 opacity-60">
            <div class="text-4xl mb-2">💸</div>
            <p class="font-bold">Belum ada pengeluaran dicatat.</p>
        </div>
    @endforelse
</div>
@endsection
