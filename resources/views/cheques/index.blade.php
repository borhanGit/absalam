@extends('layouts.app')

@section('title', __('Cheques Management'))
@section('page_title', __('Cheques Management'))

@section('content')
<div class="space-y-6">

    <!-- Header with Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">{{ __('Customer & Supplier Cheque Tracking') }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('Customer & Supplier Cheque Tracking') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('cheques.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Add Cheque') }}
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-amber-400">{{ __('Cheques in Hand') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['in_hand'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ $stats['in_hand_count'] ?? 0 }} cheques</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">{{ __('Deposited in Bank') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['deposited'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Pending clearance</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">{{ __('Cleared / Honored') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['honored'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Successfully cleared</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">{{ __('Dishonored / Bounced') }}</div>
            <div class="text-xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['bounced'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-rose-400 mt-1">{{ $stats['bounced_count'] ?? 0 }} cheques bounced</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card-shell p-4 flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('cheques.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') && !request('type') ? 'bg-indigo-600 text-white font-semibold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">{{ __('All') }}</a>
            <a href="{{ route('cheques.index', ['status' => 'in_hand']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'in_hand' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">{{ __('In Hand') }}</a>
            <a href="{{ route('cheques.index', ['status' => 'deposited']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'deposited' ? 'bg-indigo-500 text-white font-semibold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">{{ __('Deposited') }}</a>
            <a href="{{ route('cheques.index', ['status' => 'honored']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'honored' ? 'bg-emerald-500 text-slate-950 font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">{{ __('Cleared') }}</a>
            <a href="{{ route('cheques.index', ['status' => 'bounced']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'bounced' ? 'bg-rose-500 text-white font-bold' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">{{ __('Dishonored / Bounced') }}</a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cheques.index', ['type' => 'received_from_customer']) }}" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700/80 text-slate-300 hover:text-white transition {{ request('type') === 'received_from_customer' ? 'border-indigo-500 text-indigo-400' : '' }}">Customer Cheques</a>
            <a href="{{ route('cheques.index', ['type' => 'issued_to_supplier']) }}" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700/80 text-slate-300 hover:text-white transition {{ request('type') === 'issued_to_supplier' ? 'border-indigo-500 text-indigo-400' : '' }}">Supplier Cheques</a>
        </div>
    </div>

    <!-- Cheque List Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">{{ __('Cheque #') }} & {{ __('Bank & Branch') }}</th>
                        <th class="p-3.5">{{ __('Party / Customer') }}</th>
                        <th class="p-3.5">{{ __('Type') }}</th>
                        <th class="p-3.5">{{ __('Cheque Date') }}</th>
                        <th class="p-3.5 text-right">{{ __('Amount') }}</th>
                        <th class="p-3.5 text-center">{{ __('Cheque Status') }}</th>
                        <th class="p-3.5 text-right">{{ __('Update Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($cheques as $cheque)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5">
                            <span class="font-bold font-mono tnum text-white block">{{ $cheque->cheque_no }}</span>
                            <span class="text-[11px] text-slate-400 font-sans">{{ $cheque->bank_name }} {{ $cheque->branch_name ? "({$cheque->branch_name})" : '' }}</span>
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->customer)
                                <a href="{{ route('customers.show', $cheque->customer_id) }}" class="font-semibold text-indigo-400 hover:underline block">{{ $cheque->customer->name }}</a>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $cheque->customer->phone }}</span>
                            @elseif($cheque->supplier)
                                <span class="font-semibold text-amber-400 block">{{ $cheque->supplier->name }}</span>
                                <span class="text-[10px] text-slate-500">{{ __('Supplier / Mill') }}</span>
                            @else
                                <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($cheque->type === 'received_from_customer')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    Customer
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Supplier
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-slate-400">
                            {{ $cheque->cheque_date ? \Carbon\Carbon::parse($cheque->cheque_date)->format('d M, Y') : '-' }}
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-white text-sm">
                            ৳ {{ number_format($cheque->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($cheque->status === 'in_hand')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">{{ __('In Hand') }}</span>
                            @elseif($cheque->status === 'deposited')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/15 text-indigo-400 border border-indigo-500/30">{{ __('Deposited') }}</span>
                            @elseif($cheque->status === 'honored')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">{{ __('Cleared') }}</span>
                            @elseif($cheque->status === 'bounced')
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">{{ __('Dishonored / Bounced') }}</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-700 text-slate-300">{{ $cheque->status }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <!-- Quick Status Update Form -->
                            <form action="{{ route('cheques.update-status', $cheque->id) }}" method="POST" class="inline-flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700/80 text-[11px] rounded-lg px-2.5 py-1 text-slate-200 focus:outline-none focus:border-indigo-500">
                                    <option value="in_hand" {{ $cheque->status === 'in_hand' ? 'selected' : '' }}>{{ __('In Hand') }}</option>
                                    <option value="deposited" {{ $cheque->status === 'deposited' ? 'selected' : '' }}>{{ __('Deposited') }}</option>
                                    <option value="honored" {{ $cheque->status === 'honored' ? 'selected' : '' }}>{{ __('Cleared') }}</option>
                                    <option value="bounced" {{ $cheque->status === 'bounced' ? 'selected' : '' }}>{{ __('Dishonored / Bounced') }}</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">{{ __('No cheques found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cheques->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $cheques->links() }}
        </div>
        @endif
    </div>
</div>
@endsection