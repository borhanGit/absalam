@extends('layouts.app')

@section('title', 'নতুন পারচেজ চালান এন্ট্রি')
@section('page_title', '🏭 ফ্যাক্টরি / মিল থেকে পণ্য ক্রয় এন্ট্রি (Purchase)')

@section('content')
<div class="max-w-5xl mx-auto">
    <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
        @csrf

        <!-- Supplier & Mill Details -->
        <div class="rounded-2xl border border-slate-800 glass-panel p-6 space-y-4">
            <h3 class="text-sm font-bold text-white flex items-center justify-between border-b border-slate-800 pb-3">
                <span>🏢 মিল / সাপ্লায়ার ও ডেলিভারি গোডাউন</span>
                <a href="{{ route('purchases.index') }}" class="text-xs text-slate-400 hover:text-white">← ফিরে যান</a>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">সাপ্লায়ার / মিল নির্বাচন <span class="text-rose-400">*</span></label>
                    <select name="supplier_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} (বর্তমান দেনা: ৳{{ number_format($s->current_balance, 0) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">আনলোডিং গোডাউন <span class="text-rose-400">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">ক্রয় তারিখ <span class="text-rose-400">*</span></label>
                    <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">মিলের চালান / ইনভয়েস নং</label>
                    <input type="text" name="supplier_invoice_no" placeholder="যেমন: KAI-98124" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:border-cyan-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Purchase Line Items -->
        <div class="rounded-2xl border border-slate-800 glass-panel p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-sm font-bold text-white">ক্রয়কৃত পণ্যের তালিকা (Purchase Items)</h3>
                <button type="button" onclick="addPurchaseRow()" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 font-bold text-xs hover:bg-cyan-500/20 transition">
                    ➕ আরেকটি পণ্য যোগ করুন
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-900 text-slate-400">
                        <tr>
                            <th class="p-2.5">পণ্য নির্বাচন</th>
                            <th class="p-2.5 text-center">পরিমাণ (বার/SFT)</th>
                            <th class="p-2.5 text-center">মোট ওজন (Kg)</th>
                            <th class="p-2.5 text-right">দর / ক্রয়মূল্য (৳)</th>
                            <th class="p-2.5 text-right">মোট টাকা (৳)</th>
                            <th class="p-2.5 text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseItemsBody" class="divide-y divide-slate-800 text-slate-300">
                        <tr class="purchase-row">
                            <td class="p-2.5">
                                <select name="items[0][product_id]" required class="w-72 px-3 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-xs">
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->purchase_price }}">{{ $p->name }} ({{ $p->code }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2.5">
                                <input type="number" name="items[0][quantity]" value="50" step="0.5" oninput="recalcPurRow(this)" required class="w-24 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-center font-mono">
                            </td>
                            <td class="p-2.5">
                                <input type="number" name="items[0][total_weight_kg]" value="212.5" step="0.1" class="w-24 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-center font-mono text-slate-400">
                            </td>
                            <td class="p-2.5 text-right">
                                <input type="number" name="items[0][unit_price]" value="1950" step="10" oninput="recalcPurRow(this)" required class="w-28 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono">
                            </td>
                            <td class="p-2.5 text-right font-bold text-emerald-400 font-mono-numbers pur-row-total">
                                ৳97,500.00
                            </td>
                            <td class="p-2.5 text-center">
                                <button type="button" onclick="removePurRow(this)" class="text-rose-400 hover:text-rose-300 font-bold">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Transport & Bill Totals -->
            <div class="border-t border-slate-800 pt-4 flex flex-col sm:flex-row justify-between gap-6 text-xs">
                <div class="sm:w-1/2 space-y-2">
                    <label class="block font-semibold text-slate-300">নোট ও বিশেষ বিবরণ</label>
                    <textarea name="notes" rows="3" placeholder="ট্রাক ড্রাইভার নম্বর বা চালানের বিশেষ তথ্য..." class="w-full p-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs"></textarea>
                </div>

                <div class="sm:w-80 space-y-2 font-mono text-slate-300">
                    <div class="flex justify-between">
                        <span>আইটেম মোট:</span>
                        <span id="purSubtotal" class="font-bold text-white">৳97,500.00</span>
                        <input type="hidden" name="subtotal" id="purSubtotalInput" value="97500">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span>ট্রাক / পিকআপ ভাড়া (+):</span>
                        <input type="number" name="carrying_cost" id="purCarrying" value="2500" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span>কুলি / আনলোডিং খরচ (+):</span>
                        <input type="number" name="labor_cost" id="purLabor" value="800" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span>মিল ডিসকাউন্ট (-):</span>
                        <input type="number" name="discount_amount" id="purDiscount" value="0" step="50" oninput="recalcPurGrand()" class="w-28 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono text-rose-400">
                    </div>
                    <div class="flex justify-between font-bold text-sm text-white border-t border-slate-800 pt-2">
                        <span>সর্বমোট খরচ চালান:</span>
                        <span id="purGrandTotal" class="text-emerald-400 text-lg">৳100,800.00</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 pt-2">
                        <span class="text-slate-200 font-semibold">নগদ প্রদান (Paid):</span>
                        <input type="number" name="paid_amount" id="purPaid" value="50000" step="100" class="w-32 px-3 py-1.5 rounded-xl bg-slate-900 border border-emerald-500/50 text-emerald-400 font-bold text-right font-mono text-sm">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-slate-800 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 text-xs text-slate-400 hover:text-white">বাতিল</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20 transition">
                    ✓ পারচেজ চালান সম্পন্ন করুন
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
        tr.className = 'purchase-row hover:bg-slate-800/30 transition';
        tr.innerHTML = `
            <td class="p-2.5">
                <select name="items[${pRowIndex}][product_id]" required class="w-72 px-3 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-xs">
                    ${firstSelect}
                </select>
            </td>
            <td class="p-2.5">
                <input type="number" name="items[${pRowIndex}][quantity]" value="10" step="0.5" oninput="recalcPurRow(this)" required class="w-24 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-center font-mono">
            </td>
            <td class="p-2.5">
                <input type="number" name="items[${pRowIndex}][total_weight_kg]" value="0" step="0.1" class="w-24 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-center font-mono text-slate-400">
            </td>
            <td class="p-2.5 text-right">
                <input type="number" name="items[${pRowIndex}][unit_price]" value="1950" step="10" oninput="recalcPurRow(this)" required class="w-28 px-2 py-1.5 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono">
            </td>
            <td class="p-2.5 text-right font-bold text-emerald-400 font-mono-numbers pur-row-total">
                ৳19,500.00
            </td>
            <td class="p-2.5 text-center">
                <button type="button" onclick="removePurRow(this)" class="text-rose-400 hover:text-rose-300 font-bold">✕</button>
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
