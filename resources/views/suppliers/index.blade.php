@extends('layouts.app')

@section('title', 'সাপ্লায়ার ও মিল তালিকা')
@section('page_title', '🏢 অ্যালুমিনিয়াম মিল ও কাঁচ সাপ্লায়ার্স (Suppliers & Mills)')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মোট নিবন্ধিত মিল ও সাপ্লায়ার</span>
            <div class="text-xl font-black text-white font-mono-numbers mt-1">{{ $suppliers->total() }} টি প্রতিষ্ঠান</div>
        </div>
        <div class="p-4 rounded-xl glass-panel border border-slate-800">
            <span class="text-xs text-slate-400">মিল ও সাপ্লায়ার মোট দেনা (Accounts Payable)</span>
            <div class="text-xl font-black text-rose-400 font-mono-numbers mt-1">৳{{ number_format($totalPayable, 2) }}</div>
        </div>
    </div>

    <!-- Actions & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('suppliers.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="মিলের নাম বা কোম্পানি খুঁজুন..." class="px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs placeholder:text-slate-500 focus:outline-none focus:border-cyan-500 w-72">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                ফিল্টার
            </button>
        </form>

        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
            <span>➕</span> নতুন মিল / সাপ্লায়ার যুক্ত করুন
        </a>
    </div>

    <!-- Suppliers Table -->
    <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-900/80 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="p-3">প্রতিষ্ঠানের নাম</th>
                        <th class="p-3">যোগাযোগের ব্যক্তি</th>
                        <th class="p-3">মোবাইল নম্বর</th>
                        <th class="p-3">ঠিকানা / ফ্যাক্টরি এলাকা</th>
                        <th class="p-3 text-right">বর্তমান পাওনা দেনা (৳)</th>
                        <th class="p-3 text-center">লেজার খাতা</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($suppliers as $s)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="p-3 font-bold text-white">
                                <a href="{{ route('suppliers.show', $s->id) }}" class="hover:underline hover:text-cyan-400">{{ $s->name }}</a>
                            </td>
                            <td class="p-3 text-slate-400">{{ $s->company_name ?? '-' }}</td>
                            <td class="p-3 font-mono">{{ $s->phone }}</td>
                            <td class="p-3 text-slate-400">{{ $s->address ?? '-' }}</td>
                            <td class="p-3 text-right font-mono-numbers font-bold text-rose-400">
                                ৳{{ number_format($s->current_balance, 2) }}
                            </td>
                            <td class="p-3 text-center">
                                <a href="{{ route('suppliers.show', $s->id) }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-cyan-400 font-semibold text-[11px] transition">
                                    লেজার দেখুন ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                কোনো সাপ্লায়ার পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/60">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
