@extends('layouts.app')

@section('title', __('Users & Permissions'))
@section('page_title', __('Users & Permissions Management'))

@section('content')
<div class="space-y-6">

    <!-- Header with Stats -->
    <div class="card-shell p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-lg font-bold text-white tracking-tight">{{ __('System Users') }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Admin, manager, cashier, and storekeeper staff accounts and permissions</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Add New User') }}
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-slate-400">{{ __('Total Users') }}</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Active Accounts</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-indigo-400">Admin</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $stats['admins'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Full control</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-cyan-400">Manager</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $stats['managers'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Sales & Ops</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-emerald-400">Cashier</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $stats['cashiers'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">POS Billing</div>
        </div>
        <div class="card-shell p-4">
            <div class="text-xs uppercase tracking-wider font-semibold text-amber-400">Storekeeper</div>
            <div class="text-2xl font-bold text-white font-mono tnum mt-1">{{ $stats['storekeepers'] ?? 0 }}</div>
            <div class="text-[11px] text-slate-500 mt-1">Warehouse Stock</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('users.index') }}" class="card-shell p-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('Role') }}</label>
            <select name="role" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
                <option value="">-- {{ __('Select Role') }} --</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="cashier" {{ request('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="storekeeper" {{ request('role') === 'storekeeper' ? 'selected' : '' }}>Storekeeper</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 font-semibold mb-1">{{ __('Search...') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search...') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3 py-2 text-slate-200 focus:outline-none focus:border-indigo-500">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center py-2 text-xs">{{ __('Filter') }}</button>
            <a href="{{ route('users.index') }}" class="btn-secondary py-2 text-xs">{{ __('Reset') }}</a>
        </div>
    </form>

    <!-- Users Table -->
    <div class="card-shell overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 font-semibold border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">{{ __('User') }}</th>
                        <th class="p-3.5">{{ __('Role') }}</th>
                        <th class="p-3.5">{{ __('Branch Access') }}</th>
                        <th class="p-3.5">{{ __('Phone Number') }}</th>
                        <th class="p-3.5 text-center">{{ __('Status') }}</th>
                        <th class="p-3.5 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="p-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center font-bold text-indigo-400">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-white font-sans block">{{ $user->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $user->roleBadgeClass() }}">
                                {{ $user->roleName() }}
                            </span>
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($user->warehouse)
                                <span class="text-slate-300 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $user->warehouse->name }}
                                </span>
                            @else
                                <span class="text-slate-500">All Branches</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-slate-300 font-mono">
                            {{ $user->phone ?? '-' }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-secondary py-1 px-2.5 text-[11px] inline-flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('Edit') }}
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition" title="{{ __('Delete') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 font-sans">{{ __('No users found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-slate-800 bg-slate-900/60">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection