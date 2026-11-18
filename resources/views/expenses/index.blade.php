@extends('layouts.app')

@section('title', __('Shop Expenses'))
@section('page_title', __('Shop Expenses Register'))

@section('content')
<div class="space-y-6">

    <!-- Header with Actions -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">{{ __('Shop Expenses Register') }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('Daily showroom and workshop operational expenses') }}</p>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Add Expense') }}
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-rose-400">{{ __('Today\'s Expenses') }}</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['today'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ date('d M, Y') }}</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">{{ __('This Month\'s Expenses') }}</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['this_month'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">{{ date('F, Y') }}</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">{{ __('Total Expenses') }}</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">৳ {{ number_format($stats['total_expense'] ?? 0, 2) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">All recorded expenses</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('expenses.index') }}" class="card-shell p-4 grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('Expense Category') }}</label>
            <select name="category_name" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">{{ __('All Categories') }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category_name') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('From Date') }}</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('To Date') }}</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center py-2 text-xs">{{ __('Filter') }}</button>
            <a href="{{ route('expenses.index') }}" class="btn-secondary py-2 text-xs">{{ __('Reset') }}</a>
        </div>
    </form>

    <!-- Expenses Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">{{ __('Invoice #') }}</th>
                        <th class="p-3.5">{{ __('Date') }}</th>
                        <th class="p-3.5">{{ __('Expense Category') }}</th>
                        <th class="p-3.5">{{ __('Expense Description') }}</th>
                        <th class="p-3.5">{{ __('Payment Method') }}</th>
                        <th class="p-3.5 text-right">{{ __('Amount') }}</th>
                        <th class="p-3.5 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5 font-mono font-semibold text-rose-400">
                            {{ $e->expense_no }}
                        </td>
                        <td class="p-3.5 text-slate-400">
                            {{ \Carbon\Carbon::parse($e->expense_date)->format('d M, Y') }}
                        </td>
                        <td class="p-3.5 font-sans font-semibold text-white">
                            {{ $e->category_name }}
                        </td>
                        <td class="p-3.5 font-sans text-slate-400 max-w-xs truncate">
                            {{ $e->description ?? '-' }}
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-800 text-slate-300 border border-slate-700 uppercase">
                                {{ $e->payment_method }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right font-bold font-mono tnum text-rose-400 text-sm">
                            ৳ {{ number_format($e->amount, 2) }}
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this expense?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition" title="{{ __('Delete') }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500 font-sans">{{ __('No expenses recorded.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection