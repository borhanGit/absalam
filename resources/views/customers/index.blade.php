@extends('layouts.app')

@section('title', __('Customers & Contractors'))
@section('page_title', __('Customers & Contractors CRM'))

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Total Customers') }}</span>
            <div class="text-xl font-bold text-white tnum mt-1">{{ $customers->total() }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Registered Thai Mistri / Contractors') }}</span>
            <div class="text-xl font-bold text-[#533afd] tnum mt-1">{{ $mistriCount }}</div>
        </div>
        <div class="p-4 rounded-xl card-shell">
            <span class="text-xs text-[#64748d] font-medium">{{ __('Total Customer Due') }}</span>
            <div class="text-xl font-bold text-amber-400 tnum mt-1">৳{{ number_format($totalDue, 2) }}</div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search name or phone...') }}" class="px-3.5 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs placeholder-[#64748d] focus:outline-none focus:border-[#533afd] w-64">
            
            <select name="customer_type" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:outline-none focus:border-[#533afd]">
                <option value="">{{ __('All Types') }}</option>
                <option value="contractor_mistri" {{ request('customer_type') === 'contractor_mistri' ? 'selected' : '' }}>{{ __('Thai Mistri') }}</option>
                <option value="builder" {{ request('customer_type') === 'builder' ? 'selected' : '' }}>{{ __('Real Estate / Builder') }}</option>
                <option value="retail" {{ request('customer_type') === 'retail' ? 'selected' : '' }}>{{ __('Retail Customer') }}</option>
                <option value="wholesale" {{ request('customer_type') === 'wholesale' ? 'selected' : '' }}>{{ __('Wholesale Customer') }}</option>
            </select>

            <button type="submit" class="btn-secondary text-xs py-2 px-4">
                {{ __('Filter') }}
            </button>
            @if(request()->hasAny(['search', 'customer_type']))
                <a href="{{ route('customers.index') }}" class="px-3 py-2 text-xs text-rose-400 hover:underline">{{ __('Reset') }}</a>
            @endif
        </form>

        <a href="{{ route('customers.create') }}" class="btn-primary">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>
            </svg>
            <span>{{ __('Add Customer / Mistri') }}</span>
        </a>
    </div>

    <!-- Customers Table -->
    <div class="rounded-xl card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                    <tr>
                        <th class="p-3 font-semibold">{{ __('Name') }}</th>
                        <th class="p-3 font-semibold">{{ __('Type') }}</th>
                        <th class="p-3 font-semibold">{{ __('Phone Number') }}</th>
                        <th class="p-3 font-semibold">{{ __('Address & Area') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Credit Limit') }}</th>
                        <th class="p-3 font-semibold text-right">{{ __('Current Balance') }}</th>
                        <th class="p-3 font-semibold text-center">{{ __('Customer Ledger') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                    @forelse($customers as $c)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-3 font-semibold text-white">
                                <a href="{{ route('customers.show', $c->id) }}" class="hover:underline text-[#533afd]">{{ $c->name }}</a>
                            </td>
                            <td class="p-3">
                                @if($c->customer_type === 'contractor_mistri')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#533afd]/15 text-[#533afd] border border-[#533afd]/30">{{ __('Thai Mistri') }}</span>
                                @elseif($c->customer_type === 'builder')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/20">{{ __('Real Estate / Builder') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] bg-white/5 border border-white/10 text-slate-300">{{ ucfirst($c->customer_type) }}</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono text-[#64748d] tnum">{{ $c->phone }}</td>
                            <td class="p-3 text-[#64748d]">{{ $c->address ?? '-' }}</td>
                            <td class="p-3 text-right tnum text-[#64748d]">
                                ৳{{ number_format($c->credit_limit, 2) }}
                            </td>
                            <td class="p-3 text-right tnum font-semibold {{ $c->current_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                ৳{{ number_format($c->current_balance, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('customers.show', $c->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/5 hover:bg-white/10 text-slate-200 border border-[#e3e8ee]/10 transition font-medium text-[11px]">
                                    <span>{{ __('View Ledger') }}</span>
                                    <span>↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-[#64748d]">
                                {{ __('No customers found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-[#e3e8ee]/10 bg-[#070d18]/60">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection