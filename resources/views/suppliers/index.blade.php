@extends('layouts.app')

@section('title', __('Mills & Suppliers'))
@section('page_title', __('Aluminium Mills & Glass Factories'))

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="card-shell p-5 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">{{ __('Total Suppliers / Mills') }}</span>
                <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $suppliers->total() }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <div class="card-shell p-5 flex items-center justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold">{{ __('Total Payable') }}</span>
                <div class="text-2xl font-bold text-rose-400 font-mono tnum mt-1">৳{{ number_format($totalPayable, 2) }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Actions & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('suppliers.index') }}" class="flex items-center gap-2">
            <div class="relative w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search...') }}" class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700/80 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-indigo-500">
                <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="btn-secondary text-xs">
                {{ __('Filter') }}
            </button>
            @if(request('search'))
                <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-400 hover:text-white px-2">{{ __('Reset') }}</a>
            @endif
        </form>

        <a href="{{ route('suppliers.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Add New Mill / Supplier') }}
        </a>
    </div>

    <!-- Suppliers Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3">{{ __('Mill / Supplier Name') }}</th>
                        <th class="p-3">{{ __('Contact Person') }}</th>
                        <th class="p-3">{{ __('Phone Number') }}</th>
                        <th class="p-3">{{ __('Address & Area') }}</th>
                        <th class="p-3 text-right">{{ __('Current Balance (Payable)') }} (৳)</th>
                        <th class="p-3 text-center">{{ __('Supplier Ledger') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($suppliers as $s)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-semibold text-white">
                                <a href="{{ route('suppliers.show', $s->id) }}" class="hover:underline hover:text-indigo-400">{{ $s->name }}</a>
                            </td>
                            <td class="p-3 text-slate-400">{{ $s->company_name ?? '-' }}</td>
                            <td class="p-3 font-mono text-slate-300">{{ $s->phone }}</td>
                            <td class="p-3 text-slate-400">{{ $s->address ?? '-' }}</td>
                            <td class="p-3 text-right font-mono tnum font-bold text-rose-400">
                                ৳{{ number_format($s->current_balance, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('suppliers.show', $s->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 font-medium text-[11px] transition">
                                    {{ __('View') }}
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                {{ __('No suppliers found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/40">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection