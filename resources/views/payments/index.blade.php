@extends('layouts.app')

@section('title', __('Supplier Payments'))
@section('page_title', __('Supplier Payments Register'))

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">{{ __('Supplier Payments Register') }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('Supplier Payments') }}</p>
        </div>
        <a href="{{ route('payments.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('New Supplier Payment') }}
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">{{ __('Total Paid to Suppliers') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['total_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">All-time</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">{{ __('Today\'s Supplier Payment') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['today_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ date('d M, Y') }}</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">{{ __('Cash Collection') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['cash_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Cash Register</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-sky-400">{{ __('Bank Cheque') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['cheque_paid'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Bank Cheque / TT</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('payments.index') }}" class="card-shell p-4 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('Supplier') }}</label>
            <select name="supplier_id" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">-- {{ __('Select Supplier') }} --</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('Payment Method') }}</label>
            <select name="payment_method" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">{{ __('All Methods') }}</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>{{ __('Bank Cheque') }}</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                <option value="bkash" {{ request('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('From Date') }}</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center py-2 text-xs">{{ __('Filter') }}</button>
            <a href="{{ route('payments.index') }}" class="btn-secondary py-2 text-xs">{{ __('Reset') }}</a>
        </div>
    </form>

    <!-- Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">{{ __('Payment #') }}</th>
                        <th class="p-3.5">{{ __('Supplier') }}</th>
                        <th class="p-3.5">{{ __('Date') }}</th>
                        <th class="p-3.5">{{ __('Payment Method') }}</th>
                        <th class="p-3.5">{{ __('Cheque #') }} / Ref</th>
                        <th class="p-3.5 text-right">{{ __('Paid Amount') }}</th>
                        <th class="p-3.5 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($payments as $p)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5 font-mono font-semibold text-indigo-400">
                            <a href="{{ route('payments.show', $p->id) }}" class="hover:underline">{{ $p->payment_no }}</a>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="font-semibold text-white block">{{ $p->supplier->name ?? '-' }}</span>
                            <span class="text-[11px] text-slate-500 font-mono">{{ $p->supplier->phone ?? '' }}</span>
                        </td>
                        <td class="p-3.5 text-slate-400">
                            {{ \Carbon\Carbon::parse($p->payment_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($p->payment_method === 'cash')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">{{ __('Cash') }}</span>
                            @elseif($p->payment_method === 'cheque')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ __('Bank Cheque') }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">{{ strtoupper($p->payment_method) }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans text-slate-400">
                            @if($p->cheque)
                                <span class="text-white font-mono block">#{{ $p->cheque->cheque_no }}</span>
                                <span class="text-[10px] text-slate-500">{{ $p->cheque->bank_name }}</span>
                            @elseif($p->transaction_reference)
                                <span class="font-mono text-slate-300">{{ $p->transaction_reference }}</span>
                            @else
                                <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-rose-400 text-sm">
                            ৳ {{ number_format($p->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <a href="{{ route('payments.show', $p->id) }}" class="btn-secondary py-1 px-2.5 text-[11px] inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                {{ __('View') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">{{ __('No payments found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection