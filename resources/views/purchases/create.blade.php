@extends('layouts.app')

@section('title', 'নতুন পারচেজ চালান এন্ট্রি')
@section('page_title', 'ফ্যাক্টরি / মিল থেকে পণ্য ক্রয় এন্ট্রি (Purchase)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
        @csrf

        <!-- Supplier & Mill Details -->
        <div class="rounded-xl card-shell p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-[#e3e8ee]/10 pb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/>
                    </svg>
                    <span class="text-sm font-semibold text-white">মিল / সাপ্লায়ার ও ডেলিভারি গোডাউন</span>
                </div>
                <a href="{{ route('purchases.index') }}" class="text-xs text-[#64748d] hover:text-white transition">← ফিরে যান</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">সাপ্লায়ার / মিল নির্বাচন <span class="text-rose-400">*</span></label>
                    <select name="supplier_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} (বর্তমান দেনা: ৳{{ number_format($s->current_balance, 0) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">আনলোডিং গোডাউন <span class="text-rose-400">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">ক্রয় তারিখ <span class="text-rose-400">*</span></label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs tnum focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">মিলের চালান / ইনভয়েস নং</label>
                    <input type="text" name="supplier_invoice_no" placeholder="যেমন: KAI-98124" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs font-mono focus:border-[#533afd] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Purchase Line Items -->
        <div class="rounded-xl card-shell p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-[#e3e8ee]/10 pb-3">
                <h2 class="text-sm font-semibold text-white">ক্রয়কৃত পণ্যের তালিকা (Purchase Items)</h2>
                <button type="button" onclick="addPurchaseRow()" class="btn-secondary text-xs py-1.5 px-3">
                    <svg class="w-3.5 h-3.5 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>আরেকটি পণ্য যোগ করুন</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                        <tr>
                            <th class="p-3 font-semibold">পণ্য নির্বাচন</th>
                            <th class="p-3 text-center font-semibold">পরিমাণ (বার/SFT)</th>
                            <th class="p-3 text-center font-semibold">মোট ওজন (Kg)</th>
                            <th class="p-3 text-right font-semibold">দর / ক্রয়মূল্য (৳)</th>
                            <th class="p-3 text-right font-semibold">মোট টাকা (৳)</th>
                            <th class="p-3 text-center font-semibold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseItemsBody" class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                        <tr class="purchase-row hover:bg-white/[0.02] transition">
                            <td class="p-3">
                                <select name="items[0][product_id]" required class="w-72 px-3 py-1.5 rounded-lg bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}">{{ $p->name }} ({{ $p->code }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3">
                                <input type="number" name="items[0][quantity]" value="50" step="0.5" oninput="recalcPurRow(this)" required class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3">
                                <input type="number" name="items[0][total_weight_kg]" value="212.5" step="0.1" class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono text-[#64748d] tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3 text-right">
                                <input type="number" name="items[0][unit_price]" value="1950" step="10" oninput="recalcPurRow(this)" required class="w-28 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3 text-right font-medium text-emerald-400 tnum pur-row-total">
                                ৳97,500.00
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" onclick="removePurRow(this)" class="w-6 h-6 rounded-full inline-flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Transport & Bill Totals -->
            <div class="border-t border-[#e3e8ee]/10 pt-4 flex flex-col sm:flex-row justify-between gap-6 text-xs">
                <div class="sm:w-1/2 space-y-2">
                    <label class="block font-semibold text-slate-300">নোট ও বিশেষ বিবরণ</label>
                    <textarea name="notes" rows="3" placeholder="ট্রাক ড্রাইভার নম্বর বা চালানের বিশেষ তথ্য..." class="w-full p-2.5 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none"></textarea>
                </div>

                <div class="sm:w-80 space-y-2 text-slate-300">
                    <div class="flex justify-between">
                        <span>আইটেম মোট:</span>
                        <span id="purSubtotal" class="font-semibold text-white tnum">৳97,500.00</span>
                        <input type="hidden" name="subtotal" id="purSubtotalInput" value="97500">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">ট্রাক / পিকআপ ভাড়া (+):</span>
                        <input type="number" name="carrying_cost" id="purCarrying" value="2500" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">কুলি / আনলোডিং খরচ (+):</span>
                        <input type="number" name="labor_cost" id="purLabor" value="800" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">মিল ডিসকাউন্ট (-):</span>
                        <input type="number" name="discount_amount" id="purDiscount" value="0" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-rose-400 text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex justify-between font-semibold text-sm text-white border-t border-[#e3e8ee]/10 pt-2.5">
                        <span class="text-[#b9b9f9]">সর্বমোট খরচ চালান:</span>
                        <span id="purGrandTotal" class="text-white font-bold text-lg tnum">৳100,800.00</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 pt-2">
                        <span class="text-slate-200 font-semibold">নগদ প্রদান (Paid):</span>
                        <input type="number" name="paid_amount" id="purPaid" value="50000" step="100" class="w-32 px-3 py-1.5 rounded-xl bg-[#070d18] border border-emerald-500/40 text-emerald-400 font-bold text-right font-mono text-sm tnum focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-[#e3e8ee]/10 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('purchases.index') }}" class="btn-secondary text-xs py-2 px-4">বাতিল</a>
                <button type="submit" class="btn-primary min-h-[42px] px-6">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>পারচেজ চালান সম্পন্ন করুন</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let pRowIndex = 1;

    function recalcPurRow(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
        const total = qty * price;

        row.querySelector('.pur-row-total').innerText = '৳' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        recalcPurGrand();
    }

    function recalcPurGrand() {
        let sum = 0;
        document.querySelectorAll('.purchase-row').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            sum += (qty * price);
        });

        const carrying = parseFloat(document.getElementById('purCarrying').value) || 0;
        const labor = parseFloat(document.getElementById('purLabor').value) || 0;
        const discount = parseFloat(document.getElementById('purDiscount').value) || 0;

        const grand = Math.max(0, sum + carrying + labor - discount);

        document.getElementById('purSubtotal').innerText = '৳' + sum.toFixed(2);
        document.getElementById('purSubtotalInput').value = sum.toFixed(2);
        document.getElementById('purGrandTotal').innerText = '৳' + grand.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function addPurchaseRow() {
        const tbody = document.getElementById('purchaseItemsBody');
        const firstSelect = document.querySelector('select[name*="[product_id]"]').innerHTML;
        const tr = document.createElement('tr');
        tr.className = 'purchase-row hover:bg-white/[0.02] transition';
        tr.innerHTML = `
            <td class="p-3">
                <select name="items[${pRowIndex}][product_id]" required class="w-72 px-3 py-1.5 rounded-lg bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                    ${firstSelect}
                </select>
            </td>
            <td class="p-3">
                <input type="number" name="items[${pRowIndex}][quantity]" value="10" step="0.5" oninput="recalcPurRow(this)" required class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3">
                <input type="number" name="items[${pRowIndex}][total_weight_kg]" value="0" step="0.1" class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono text-[#64748d] tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3 text-right">
                <input type="number" name="items[${pRowIndex}][unit_price]" value="1950" step="10" oninput="recalcPurRow(this)" required class="w-28 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3 text-right font-medium text-emerald-400 tnum pur-row-total">
                ৳19,500.00
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="removePurRow(this)" class="w-6 h-6 rounded-full inline-flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        pRowIndex++;
        recalcPurGrand();
    }

    function removePurRow(btn) {
        const rows = document.querySelectorAll('.purchase-row');
        if (rows.length <= 1) {
            alert('কমপক্ষে একটি পণ্য থাকতে হবে!');
            return;
        }
        btn.closest('tr').remove();
        recalcPurGrand();
    }
</script>
@endpush
