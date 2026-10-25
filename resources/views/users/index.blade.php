@extends('layouts.app')

@section('page_title', 'ব্যবহারকারী ব্যবস্থাপনা (User & Role Management)')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-800">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>👥</span> ব্যবহারকারী ও ভূমিকা ব্যবস্থাপনা (User Management)
            </h1>
            <p class="text-xs text-slate-400 mt-1">দোকানের অ্যাডমিন, ম্যানেজার, ক্যাশিয়ার ও গোডাউন ইন-চার্জদের একাউন্ট ও অনুমতি নিয়ন্ত্রণ</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-purple-500/20 transition flex items-center gap-2">
            <span>➕</span> নতুন ব্যবহারকারী যোগ করুন
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-700">
            <div class="text-xs font-medium text-slate-400">সর্বমোট ব্যবহারকারী</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['total_users'] ?? 0 }} জন</div>
            <div class="text-[11px] text-slate-500 mt-0.5">মোট নিবন্ধিত স্টাফ</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-purple-500/20">
            <div class="text-xs font-medium text-purple-400">অ্যাডমিন (Admin)</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['admins'] ?? 0 }} জন</div>
            <div class="text-[11px] text-slate-500 mt-0.5">মালিক / পূর্ণ নিয়ন্ত্রণ</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-cyan-500/20">
            <div class="text-xs font-medium text-cyan-400">ম্যানেজার (Manager)</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['managers'] ?? 0 }} জন</div>
            <div class="text-[11px] text-slate-500 mt-0.5">সেলস ও গোডাউন তদারকি</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-emerald-500/20">
            <div class="text-xs font-medium text-emerald-400">ক্যাশিয়ার (Cashier)</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['cashiers'] ?? 0 }} জন</div>
            <div class="text-[11px] text-slate-500 mt-0.5">পিওএস কাউন্টার ও বিলিং</div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/80 border border-amber-500/20">
            <div class="text-xs font-medium text-amber-400">স্টোরকিপার (Store)</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $stats['storekeepers'] ?? 0 }} জন</div>
            <div class="text-[11px] text-slate-500 mt-0.5">গোডাউন ও অফ-কাট স্টক</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('users.index') }}" class="p-4 rounded-xl bg-slate-900/50 border border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
        <div>
            <label class="block text-slate-400 mb-1">রোল দিয়ে ফিল্টার</label>
            <select name="role" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
                <option value="">-- সকল ভূমিকা (All Roles) --</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>অ্যাডমিন (Admin)</option>
                <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>ম্যানেজার (Manager)</option>
                <option value="cashier" {{ request('role') === 'cashier' ? 'selected' : '' }}>ক্যাশিয়ার (Cashier)</option>
                <option value="storekeeper" {{ request('role') === 'storekeeper' ? 'selected' : '' }}>স্টোরকিপার (Storekeeper)</option>
            </select>
        </div>
        <div>
            <label class="block text-slate-400 mb-1">নাম, ইমেইল বা ফোন দিয়ে খুঁজুন</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="সার্চ করুন..." class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-slate-200">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-1.5 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-lg transition">ফিল্টার করুন</button>
            <a href="{{ route('users.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition">রিসেট</a>
        </div>
    </form>

    <!-- Users Table -->
    <div class="bg-slate-900/60 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 text-slate-400 font-semibold uppercase text-[11px] border-b border-slate-800">
                    <tr>
                        <th class="p-3.5">ব্যবহারকারী</th>
                        <th class="p-3.5">ভূমিকা (Role)</th>
                        <th class="p-3.5">শাখা / ওয়্যারহাউস</th>
                        <th class="p-3.5">মোবাইল নম্বর</th>
                        <th class="p-3.5 text-center">স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="p-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-white font-sans block">{{ $user->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5 font-sans">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $user->roleBadgeClass() }}">
                                {{ $user->roleNameBengali() }}
                            </span>
                        </td>
                        <td class="p-3.5 font-sans">
                            @if($user->warehouse)
                                <span class="text-slate-300 inline-flex items-center gap-1">
                                    <span>📍</span> {{ $user->warehouse->name }}
                                </span>
                            @else
                                <span class="text-slate-500">সব শাখা (সার্বজনীন)</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-slate-300 font-mono">
                            {{ $user->phone ?? '-' }}
                        </td>
                        <td class="p-3.5 text-center font-sans">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                    <span>●</span> সক্রিয়
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                    <span>○</span> নিষ্ক্রিয়
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right font-sans">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white border border-slate-700 text-[11px] transition">
                                    ✏️ এডিট
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ব্যবহারকারী অ্যাকাউন্টটি মুছে ফেলতে চান?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                        🗑️
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 font-sans">কোনো ব্যবহারকারীর তথ্য পাওয়া যায়নি।</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-3 border-t border-slate-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
