@extends('layouts.app')

@section('page_title', 'নতুন খরচ এন্ট্রি (Add Daily Expense)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>📉</span> নতুন দোকান খরচ এন্ট্রি
            </h1>
            <p class="text-xs text-slate-400 mt-1">দৈনিক দোকান পরিচালনা ও অন্যান্য খরচের হিসাব লিপিবদ্ধ করুন</p>
        </div>
        <a href="{{ route('expenses.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← খরচের তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('expenses.store') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">ভাউচার নং <span class="text-rose-500">*</span></label>
                    <input type="text" name="expense_no" value="{{ old('expense_no', $expenseNo) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs font-mono text-rose-400 font-bold focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">তারিখ <span class="text-rose-500">*</span></label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">খরচের খাত (Expense Category) <span class="text-rose-500">*</span></label>
                <input list="category_list" name="category_name" required placeholder="খাতের নাম লিখুন বা সিলেক্ট করুন..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-rose-500">
                <datalist id="category_list">
                    @foreach($defaultCategories as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">টাকার পরিমাণ (৳) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-sm text-rose-400 font-bold focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পেমেন্ট মেথড <span class="text-rose-500">*</span></label>
                    <select name="payment_method" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-rose-500">
                        <option value="cash">নগদ ক্যাশ (Cash)</option>
                        <option value="bank">ব্যাংক একাউন্ট</option>
                        <option value="bkash">বিকাশ / নগদ</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">বিবরণ / নোট</label>
                <textarea name="description" rows="3" placeholder="খরচের কারণ বা রিসিট নং..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-rose-500"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">বাতিল</a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white font-bold text-xs shadow-lg shadow-rose-500/20 transition">
                💾 খরচ সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
