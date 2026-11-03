@extends('layouts.app')

@section('title', 'নতুন খরচ এন্ট্রি')
@section('page_title', 'নতুন দোকান খরচ এন্ট্রি (Add Daily Expense)')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('expenses.index') }}" class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        খরচের তালিকায় ফিরুন
    </a>

    <div class="card-shell p-6 sm:p-8">
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white tracking-tight">দোকান খরচ বিবরণ</h2>
                <p class="text-xs text-slate-400 mt-0.5">দৈনিক দোকান পরিচালনা ও অন্যান্য খরচের হিসাব লিপিবদ্ধ করুন</p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">ভাউচার নং <span class="text-rose-400">*</span></label>
                        <input type="text" name="expense_no" value="{{ old('expense_no', $expenseNo) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs font-mono tnum text-rose-400 font-bold focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">তারিখ <span class="text-rose-400">*</span></label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">খরচের খাত (Expense Category) <span class="text-rose-400">*</span></label>
                    <input list="category_list" name="category_name" required placeholder="খাতের নাম লিখুন বা সিলেক্ট করুন..." class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                    <datalist id="category_list">
                        @foreach($defaultCategories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">টাকার পরিমাণ (৳) <span class="text-rose-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-500 font-bold">৳</span>
                            <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full pl-8 pr-3.5 py-2.5 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-rose-400 font-mono tnum font-bold focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">পেমেন্ট মেথড <span class="text-rose-400">*</span></label>
                        <select name="payment_method" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-indigo-500">
                            <option value="cash">নগদ ক্যাশ (Cash)</option>
                            <option value="bank">ব্যাংক একাউন্ট</option>
                            <option value="bkash">বিকাশ / নগদ</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">বিবরণ / নোট</label>
                    <textarea name="description" rows="3" placeholder="খরচের কারণ বা রিসিট নং..." class="w-full p-3 bg-slate-900 border border-slate-700/80 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('expenses.index') }}" class="btn-secondary text-xs">বাতিল</a>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    খরচ সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
