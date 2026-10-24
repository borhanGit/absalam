@extends('layouts.app')

@section('title', 'পিওএস সেলস কাউন্টার')
@section('page_title', '⚡ থাই ও গ্লাস পিওএস কাউন্টার (Quick POS Billing)')

@section('content')
<div class="h-full">
    <form id="posForm" method="POST" action="{{ route('pos.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        @csrf

        <!-- Left Column: Item Entry & Cart Table (8 Cols) -->
        <div class="lg:col-span-8 space-y-4">
            <!-- Add Item Bar -->
            <div class="rounded-2xl border border-slate-800 glass-panel p-4 space-y-3">
                <div class="flex items-center justify-between text-xs font-bold text-cyan-400">
                    <span class="flex items-center gap-2"><span>📦</span> আইটেম নির্বাচন ও সাইজ ইনপুট</span>
                    <span class="text-slate-400 font-normal">কাঁচের মাপ ইঞ্চিতে দিলে অটো স্কয়ার ফিট হিসাব হবে</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-6">
                        <select id="productSelect" onchange="onProductSelect()" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                            <option value="">পণ্য নির্বাচন করুন...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" 
                                    data-type="{{ $p->product_type }}"
                                    data-price="{{ $p->selling_price_retail }}"
                                    data-contractor-price="{{ $p->selling_price_contractor }}"
                                    data-unit="{{ $p->unit->code }}"
                                    data-name="{{ $p->name }}">
                                    [{{ strtoupper($p->product_type) }}] {{ $p->name }} (৳{{ $p->selling_price_retail }}/{{ $p->unit->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <input type="number" id="itemPrice" placeholder="দর / রেট" step="0.5" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs font-mono focus:border-cyan-500 focus:outline-none">
                    </div>

                    <div class="sm:col-span-3">
                        <button type="button" onclick="addItemToCart()" class="w-full py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-md shadow-cyan-500/20 transition flex items-center justify-center gap-1.5">
                            <span>➕</span> কার্টে যোগ করুন
                        </button>
                    </div>
                </div>

                <!-- Dimensional Input Expandable Helper (For Glass / Custom Cuts) -->
                <div id="dimensionalInputs" class="hidden p-3 rounded-xl bg-slate-950/80 border border-slate-800 grid grid-cols-2 sm:grid-cols-5 gap-2 text-xs">
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">উচ্চতা (ইঞ্চি)</label>
                        <input type="number" id="dimHeight" value="54" step="0.25" oninput="recalcModal()" class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">প্রস্থ (ইঞ্চি)</label>
                        <input type="number" id="dimWidth" value="36" step="0.25" oninput="recalcModal()" class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">সংখ্যা (Pcs)</label>
                        <input type="number" id="dimQty" value="1" min="1" oninput="recalcModal()" class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">মোট স্কয়ার ফিট</label>
                        <input type="text" id="dimSqft" readonly class="w-full px-2.5 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-cyan-400 text-xs font-bold font-mono">
                    </div>
                    <div id="cutLengthDiv" class="hidden">
                        <label class="block text-[11px] text-slate-400 mb-1">কাটিং দৈর্ঘ্য (ফুট)</label>
                        <input type="number" id="cutFeet" value="16" step="0.5" class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-mono">
                    </div>
                </div>
            </div>

            <!-- Cart Table -->
            <div class="rounded-2xl border border-slate-800 glass-panel overflow-hidden">
                <div class="p-3.5 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between text-xs">
                    <span class="font-bold text-white flex items-center gap-1.5">
                        <span>🛒</span> বিক্রয়ের পণ্য তালিকা (<span id="cartCount">0</span> টি আইটেম)
                    </span>
                    <button type="button" onclick="clearCart()" class="text-rose-400 hover:underline">কার্ট খালি করুন</button>
                </div>

                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900 text-slate-400">
                            <tr>
                                <th class="p-3">পণ্যের নাম</th>
                                <th class="p-3">সাইজ / পরিমাপ</th>
                                <th class="p-3 text-center">পরিমাণ</th>
                                <th class="p-3 text-right">দর (৳)</th>
                                <th class="p-3 text-right">মোট (৳)</th>
                                <th class="p-3 text-center">রিমুভ</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody" class="divide-y divide-slate-800 text-slate-300">
                            <tr id="emptyCartRow">
                                <td colspan="6" class="p-12 text-center text-slate-500">
                                    উপরে আইটেম সিলেক্ট করে "কার্টে যোগ করুন"-এ চাপুন।
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer, Summary & Payment (4 Cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="rounded-2xl border border-slate-800 glass-panel p-5 space-y-4">
                <h3 class="text-sm font-bold text-white border-b border-slate-800 pb-3 flex items-center justify-between">
                    <span>🧾 চালান ও পেমেন্ট বিবরণ</span>
                    <span class="text-cyan-400 font-mono text-xs">INV-AUTO</span>
                </h3>

                <!-- Warehouse & Date -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">বিক্রয় শাখা</label>
                        <select name="warehouse_id" required class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs">
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ $wh->id == $defaultWarehouse->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1">তারিখ</label>
                        <input type="date" name="sale_date" value="{{ date('Y-m-d') }}" required class="w-full px-2.5 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs">
                    </div>
                </div>

                <!-- Customer Selection -->
                <div>
                    <label class="block text-[11px] text-slate-300 font-semibold mb-1">কাস্টমার নির্বাচন করুন <span class="text-rose-400">*</span></label>
                    <select name="customer_id" id="customerSelect" onchange="onCustomerChange()" required class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-type="{{ $c->customer_type }}" data-balance="{{ $c->current_balance }}">
                                {{ $c->name }} ({{ ucfirst($c->customer_type) }}) — বাকি: ৳{{ number_format($c->current_balance, 0) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Thai Mistri / Contractor Tagging -->
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">থাই মিস্ত্রি / রেফারেন্স (কমিশনের জন্য)</label>
                    <select name="contractor_id" id="mistriSelect" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyan-500 focus:outline-none">
                        <option value="">কোনো মিস্ত্রি নেই</option>
                        @foreach($mistris as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bill Totals Breakdown -->
                <div class="space-y-2 pt-3 border-t border-slate-800 text-xs">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>আইটেম সাবটোটাল:</span>
                        <span id="subtotalDisplay" class="font-bold text-white font-mono-numbers">৳0.00</span>
                        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-slate-400">ভ্যান / পরিবহন ভাড়া (+):</span>
                        <input type="number" name="carrying_cost" id="carryingCost" value="0" step="10" oninput="recalcBill()" class="w-24 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono text-xs">
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-slate-400">কাটিং / ফিটিং মজুরি (+):</span>
                        <input type="number" name="labor_fitting_cost" id="laborCost" value="0" step="10" oninput="recalcBill()" class="w-24 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono text-xs">
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-slate-400">ডিসকাউন্ট (-):</span>
                        <input type="number" name="discount_amount" id="discountAmount" value="0" step="10" oninput="recalcBill()" class="w-24 px-2 py-1 rounded bg-slate-900 border border-slate-700 text-white text-right font-mono text-xs text-rose-400">
                    </div>

                    <!-- Grand Total Banner -->
                    <div class="p-3 rounded-xl bg-cyan-950/40 border border-cyan-500/30 flex items-center justify-between text-sm">
                        <span class="font-bold text-cyan-300">সর্বমোট প্রদেয় বিল:</span>
                        <span id="grandTotalDisplay" class="font-black text-xl text-white font-mono-numbers">৳0.00</span>
                        <input type="hidden" name="grand_total" id="grandTotalInput" value="0">
                    </div>

                    <!-- Payment Inputs -->
                    <div class="pt-2 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-300 font-semibold">ক্যাশ গ্রহণ / পরিশোধ (৳):</span>
                            <input type="number" name="paid_amount" id="paidAmount" value="0" step="10" oninput="recalcBill()" class="w-32 px-3 py-1.5 rounded-xl bg-slate-900 border border-emerald-500/50 text-emerald-400 font-bold text-right font-mono text-sm focus:outline-none">
                        </div>

                        <div class="flex items-center justify-between text-xs p-2 rounded-lg bg-slate-950">
                            <span class="text-slate-400">বাকি টাকা (Due):</span>
                            <span id="dueDisplay" class="font-bold text-amber-400 font-mono-numbers">৳0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Sale Button -->
                <button type="submit" id="submitBtn" disabled class="w-full py-3 rounded-xl bg-cyan-500 hover:bg-cyan-400 disabled:opacity-40 disabled:cursor-not-allowed text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/20 transition flex items-center justify-center gap-2">
                    <span>✓</span> চালান নিশ্চিত করুন ও প্রিন্ট করুন
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let cart = [];

    function onProductSelect() {
        const select = document.getElementById('productSelect');
        const opt = select.options[select.selectedIndex];
        if (!opt.value) return;

        const type = opt.dataset.type;
        const price = parseFloat(opt.dataset.price) || 0;
        document.getElementById('itemPrice').value = price;

        const dimBox = document.getElementById('dimensionalInputs');
        const cutDiv = document.getElementById('cutLengthDiv');

        if (type === 'glass') {
            dimBox.classList.remove('hidden');
            cutDiv.classList.add('hidden');
            recalcModal();
        } else if (type === 'profile') {
            dimBox.classList.remove('hidden');
            cutDiv.classList.remove('hidden');
            recalcModal();
        } else {
            dimBox.classList.add('hidden');
        }
    }

    function recalcModal() {
        const h = parseFloat(document.getElementById('dimHeight').value) || 0;
        const w = parseFloat(document.getElementById('dimWidth').value) || 0;
        const qty = parseInt(document.getElementById('dimQty').value) || 1;
        const sqft = ((h * w) / 144) * qty;
        document.getElementById('dimSqft').value = sqft.toFixed(2) + ' SFT';
    }

    function addItemToCart() {
        const select = document.getElementById('productSelect');
        const opt = select.options[select.selectedIndex];
        if (!opt.value) {
            alert('অনুগ্রহ করে পণ্য নির্বাচন করুন!');
            return;
        }

        const id = opt.value;
        const name = opt.dataset.name;
        const type = opt.dataset.type;
        const unit = opt.dataset.unit;
        const price = parseFloat(document.getElementById('itemPrice').value) || 0;

        let qty = 1;
        let dimText = '-';
        let h = null;
        let w = null;
        let sqft = null;
        let cutFeet = null;

        if (type === 'glass') {
            h = parseFloat(document.getElementById('dimHeight').value) || 0;
            w = parseFloat(document.getElementById('dimWidth').value) || 0;
            const count = parseInt(document.getElementById('dimQty').value) || 1;
            sqft = ((h * w) / 144) * count;
            qty = sqft; // For glass, billed quantity is SFT!
            dimText = h + '" × ' + w + '" (' + count + ' পিস = ' + sqft.toFixed(2) + ' SFT)';
        } else if (type === 'profile') {
            cutFeet = parseFloat(document.getElementById('cutFeet').value) || 16;
            qty = parseInt(document.getElementById('dimQty').value) || 1;
            dimText = cutFeet < 16 ? cutFeet + ' ফুট কাট (অফ-কাট ' + (16 - cutFeet) + "')" : '১৬ ফুট ফুল বার';
        }

        cart.push({
            product_id: id,
            name: name,
            type: type,
            unit: unit,
            dimText: dimText,
            quantity: qty,
            unit_price: price,
            height_inch: h,
            width_inch: w,
            total_sqft: sqft,
            cut_length_feet: cutFeet,
            save_off_cut: cutFeet < 16 ? 1 : 0
        });

        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        tbody.innerHTML = '';

        if (cart.length === 0) {
            tbody.innerHTML = '<tr id="emptyCartRow"><td colspan="6" class="p-12 text-center text-slate-500">উপরে আইটেম সিলেক্ট করে "কার্টে যোগ করুন"-এ চাপুন।</td></tr>';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('cartCount').innerText = '0';
            recalcBill();
            return;
        }

        document.getElementById('submitBtn').disabled = false;
        document.getElementById('cartCount').innerText = cart.length;

        cart.forEach((item, index) => {
            const lineTotal = item.quantity * item.unit_price;
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-800/40 transition';
            tr.innerHTML = `
                <td class="p-3 font-medium text-white">
                    ${item.name}
                    <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item.unit_price}">
                    <input type="hidden" name="items[${index}][height_inch]" value="${item.height_inch || ''}">
                    <input type="hidden" name="items[${index}][width_inch]" value="${item.width_inch || ''}">
                    <input type="hidden" name="items[${index}][total_sqft]" value="${item.total_sqft || ''}">
                    <input type="hidden" name="items[${index}][cut_length_feet]" value="${item.cut_length_feet || ''}">
                    <input type="hidden" name="items[${index}][save_off_cut]" value="${item.save_off_cut || 0}">
                </td>
                <td class="p-3 text-cyan-400 text-[11px] font-mono">${item.dimText}</td>
                <td class="p-3 text-center font-bold font-mono">${item.quantity.toFixed(2)}</td>
                <td class="p-3 text-right font-mono-numbers">৳${item.unit_price.toFixed(2)}</td>
                <td class="p-3 text-right font-bold text-emerald-400 font-mono-numbers">৳${lineTotal.toFixed(2)}</td>
                <td class="p-3 text-center">
                    <button type="button" onclick="removeItem(${index})" class="text-rose-400 hover:text-rose-300 font-bold">✕</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        recalcBill();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function recalcBill() {
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += item.quantity * item.unit_price;
        });

        const carrying = parseFloat(document.getElementById('carryingCost').value) || 0;
        const labor = parseFloat(document.getElementById('laborCost').value) || 0;
        const discount = parseFloat(document.getElementById('discountAmount').value) || 0;

        const grandTotal = Math.max(0, subtotal + carrying + labor - discount);
        const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
        const due = Math.max(0, grandTotal - paid);

        document.getElementById('subtotalDisplay').innerText = '৳' + subtotal.toFixed(2);
        document.getElementById('subtotalInput').value = subtotal.toFixed(2);

        document.getElementById('grandTotalDisplay').innerText = '৳' + grandTotal.toFixed(2);
        document.getElementById('grandTotalInput').value = grandTotal.toFixed(2);

        document.getElementById('dueDisplay').innerText = '৳' + due.toFixed(2);
    }

    function onCustomerChange() {
        const select = document.getElementById('customerSelect');
        const opt = select.options[select.selectedIndex];
        const isMistri = opt.dataset.type === 'contractor_mistri';
        if (isMistri) {
            document.getElementById('mistriSelect').value = opt.value;
        }
    }
</script>
@endpush
