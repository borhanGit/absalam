@extends('layouts.app')

@section('page_title', 'নতুন সাপ্লায়ার পেমেন্ট (Supplier Payment Entry)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>🏭</span> মিল / সাপ্লায়ার পেমেন্ট এন্ট্রি
            </h1>
            <p class="text-xs text-slate-400 mt-1">অ্যালুমিনিয়াম ফ্যাক্টরি বা গ্লাস কোম্পানির বিল পরিশোধের ভাউচার তৈরি করুন</p>
        </div>
        <a href="{{ route('payments.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white text-xs border border-slate-700 transition">
            ← পেমেন্ট তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('payments.store') }}" method="POST" class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-5">
        @csrf

        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">ভাউচার নং <span class="text-rose-500">*</span></label>
                    <input type="text" name="payment_no" value="{{ old('payment_no', $paymentNo) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs font-mono text-amber-400 font-bold focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পরিশোধের তারিখ <span class="text-rose-500">*</span></label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">সাপ্লায়ার / মিল নির্বাচন করুন <span class="text-rose-500">*</span></label>
                <select name="supplier_id" id="supplier_select" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                    <option value="">-- সাপ্লায়ার নির্বাচন করুন --</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" data-balance="{{ $s->current_balance }}" {{ (old('supplier_id') == $s->id || ($selectedSupplier && $selectedSupplier->id == $s->id)) ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->phone ?? 'ফোন নেই' }}) — মোট পাওনা: ৳{{ number_format($s->current_balance, 2) }}
                        </option>
                    @endforeach
                </select>
                <div id="supplier_payable_banner" class="hidden mt-2 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300 flex items-center justify-between">
                    <span>সাপ্লায়ারের মোট পাওনা (Payable):</span>
                    <span id="supplier_payable_val" class="font-bold font-mono">৳ 0.00</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পরিশোধের পরিমাণ (৳) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-sm text-amber-400 font-bold focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">পেমেন্ট মেথড <span class="text-rose-500">*</span></label>
                    <select name="payment_method" id="payment_method_select" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
                        <option value="cash">নগদ ক্যাশ (Cash)</option>
                        <option value="cheque">ব্যাংক চেক (Cheque)</option>
                        <option value="bank_transfer">ব্যাংক ট্রান্সফার / TT / PO / DD</option>
                        <option value="bkash">বিকাশ (bKash)</option>
                    </select>
                </div>
            </div>

            <!-- Conditional Cheque Inputs -->
            <div id="cheque_fields" class="hidden p-4 rounded-xl bg-slate-950 border border-amber-500/30 space-y-3">
                <div class="text-xs font-bold text-amber-400 flex items-center gap-1.5">
                    <span>🏦</span> ইস্যুকৃত চেকের বিবরণ
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">চেক নং</label>
                        <input type="text" name="cheque_no" placeholder="CHQ-..." class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">আমাদের ব্যাংকের নাম</label>
                        <input type="text" name="bank_name" placeholder="উদাঃ Islami Bank" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">চেকের ক্লিয়ারিং তারিখ</label>
                        <input type="date" name="cheque_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-slate-200">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">ট্রানজেকশন রেফারেন্স / ডিপোজিট স্লিপ নং</label>
                <input type="text" name="transaction_reference" placeholder="ব্যাংক ডিপোজিট স্লিপ বা টিটি রেফারেন্স..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">নোট / বিবরণ</label>
                <textarea name="notes" rows="2" placeholder="কোন ইনভয়েসের মাল বাবদ পরিশোধ..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-amber-500"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
            <a href="{{ route('payments.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition">বাতিল</a>
            <button type="submit" class="px-6 py-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold text-xs shadow-lg shadow-amber-500/20 transition">
                💳 পেমেন্ট কনফার্ম করুন
            </button>
        </div>
    </form>
</div>

<script>
    const supplierSelect = document.getElementById('supplier_select');
    const payableBanner = document.getElementById('supplier_payable_banner');
    const payableVal = document.getElementById('supplier_payable_val');
    const methodSelect = document.getElementById('payment_method_select');
    const chequeFields = document.getElementById('cheque_fields');

    function updatePayable() {
        const opt = supplierSelect.options[supplierSelect.selectedIndex];
        if (opt && opt.dataset.balance !== undefined) {
            payableVal.innerText = '৳ ' + parseFloat(opt.dataset.balance).toLocaleString('en-US', {minimumFractionDigits: 2});
            payableBanner.classList.remove('hidden');
        } else {
            payableBanner.classList.add('hidden');
        }
    }

    supplierSelect.addEventListener('change', updatePayable);
    if (supplierSelect.value) {
        updatePayable();
    }

    methodSelect.addEventListener('change', function() {
        if (this.value === 'cheque') {
            chequeFields.classList.remove('hidden');
        } else {
            chequeFields.classList.add('hidden');
        }
    });
</script>
@endsection
