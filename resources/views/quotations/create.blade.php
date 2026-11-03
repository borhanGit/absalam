@extends('layouts.app')

@section('title', 'নতুন থাই কোটেশন তৈরি')
@section('page_title', 'নতুন জানালা / দরজা প্রজেক্ট কোটেশন এস্টিমেটর')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <form method="POST" action="{{ route('quotations.store') }}" class="space-y-6">
        @csrf

        <!-- Project & Client Info -->
        <div class="rounded-xl card-shell p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-[#e3e8ee]/10 pb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="text-sm font-semibold text-white">ক্লায়েন্ট ও প্রজেক্ট তথ্য</span>
                </div>
                <a href="{{ route('quotations.index') }}" class="text-xs text-[#64748d] hover:text-white transition">← ফিরে যান</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">কাস্টমার / ক্লায়েন্ট <span class="text-rose-400">*</span></label>
                    <select name="customer_id" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">প্রজেক্ট / সাইটের নাম</label>
                    <input type="text" name="project_name" value="{{ old('project_name', '৫ তলা আবাসিক ভবন থাই জানালা প্রজেক্ট') }}" placeholder="যেমন: ধানমন্ডি ফ্ল্যাট ইন্টেরিয়র" class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                </div>

                <div>
                    <label class="block text-slate-300 font-semibold mb-1">কোটেশন তারিখ <span class="text-rose-400">*</span></label>
                    <input type="date" name="quotation_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs tnum focus:border-[#533afd] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Window / Door Specifications Grid -->
        <div class="rounded-xl card-shell p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-[#e3e8ee]/10 pb-3">
                <div>
                    <h2 class="text-sm font-semibold text-white">জানালা ও দরজার স্পেসিফিকেশন আইটেম</h2>
                    <p class="text-xs text-[#64748d]">মাপ ইঞ্চিতে ইনপুট দিন, স্বয়ংক্রিয়ভাবে মোট স্কয়ার ফিট বের হবে</p>
                </div>
                <button type="button" onclick="addQuoteRow()" class="btn-secondary text-xs py-1.5 px-3">
                    <svg class="w-3.5 h-3.5 text-[#533afd]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>আরেকটি মাপ যোগ করুন</span>
                </button>
            </div>

            <!-- Items Dynamic Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#070d18]/80 text-[#64748d] border-b border-[#e3e8ee]/5">
                        <tr>
                            <th class="p-3 font-semibold">কাঠামো টাইপ ও অবস্থান</th>
                            <th class="p-3 text-center font-semibold">চওড়া (ইঞ্চি)</th>
                            <th class="p-3 text-center font-semibold">উচ্চতা (ইঞ্চি)</th>
                            <th class="p-3 text-center font-semibold">সংখ্যা</th>
                            <th class="p-3 text-center font-semibold">মোট SFT</th>
                            <th class="p-3 font-semibold">সেকশন & কাঁচ</th>
                            <th class="p-3 text-right font-semibold">রেট/SFT (৳)</th>
                            <th class="p-3 text-right font-semibold">মোট (৳)</th>
                            <th class="p-3 text-center font-semibold">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody id="quoteItemsBody" class="divide-y divide-[#e3e8ee]/5 text-slate-300">
                        <!-- Initial Row -->
                        <tr class="quote-row hover:bg-white/[0.02] transition">
                            <td class="p-3">
                                <select name="items[0][structure_type]" class="w-full px-2 py-1.5 rounded-lg bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                                    <option value="sliding_window_2_track">২-ট্র্যাক স্লাইডিং জানালা</option>
                                    <option value="sliding_window_3_track">৩-ট্র্যাক স্লাইডিং জানালা (নেট সহ)</option>
                                    <option value="casement_window">ক্যাসমেন্ট ওপেনিং জানালা</option>
                                    <option value="fixed_partition">ফিক্সড গ্লাস পারটিশন</option>
                                    <option value="swing_door">সুইং থাই ডোর</option>
                                </select>
                                <input type="text" name="items[0][location_tag]" placeholder="যেমন: বেডরুম W-1" class="w-full mt-1.5 px-2 py-1 rounded bg-[#070d18]/60 border border-[#e3e8ee]/10 text-slate-300 text-[11px] focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3">
                                <input type="number" name="items[0][width_inch]" value="48" step="0.5" oninput="recalcRow(this)" class="w-20 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3">
                                <input type="number" name="items[0][height_inch]" value="48" step="0.5" oninput="recalcRow(this)" class="w-20 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3">
                                <input type="number" name="items[0][quantity]" value="4" min="1" oninput="recalcRow(this)" class="w-16 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3 text-center font-bold text-[#533afd] font-mono tnum row-sqft">
                                64.00
                            </td>
                            <td class="p-3">
                                <select name="items[0][profile_product_id]" class="w-36 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-[11px] block mb-1 focus:border-[#533afd] focus:outline-none">
                                    <option value="">সেকশন চয়ন...</option>
                                    @foreach($profileProducts as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <select name="items[0][glass_product_id]" class="w-36 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-[11px] block focus:border-[#533afd] focus:outline-none">
                                    <option value="">কাঁচ চয়ন...</option>
                                    @foreach($glassProducts as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3 text-right">
                                <input type="number" name="items[0][rate_per_sqft]" value="420" step="5" oninput="recalcRow(this)" class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                            </td>
                            <td class="p-3 text-right font-medium text-emerald-400 tnum row-total">
                                ৳26,880.00
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" onclick="removeQuoteRow(this)" class="w-6 h-6 rounded-full inline-flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Additional Charges & Grand Total -->
            <div class="border-t border-[#e3e8ee]/10 pt-4 flex flex-col sm:flex-row justify-between gap-6 text-xs">
                <div class="sm:w-1/2 space-y-2">
                    <label class="block font-semibold text-slate-300">শর্তাবলী ও ওয়ারেন্টি নোট</label>
                    <textarea name="terms_and_conditions" rows="3" class="w-full p-2.5 rounded-xl bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">১. ডেলিভারির সময়কাল: চুক্তি স্বাক্ষরের ৭-১০ কার্যদিবস।
২. কাজের শুরুতে ৫০% অগ্রিম, মালামাল সাইটে পৌঁছালে ৪০% এবং ফিটিং শেষে ১০% বিল পরিশোধযোগ্য।</textarea>
                </div>

                <div class="sm:w-80 space-y-2 text-slate-300">
                    <div class="flex justify-between">
                        <span>মোট স্কয়ার ফিট (SFT):</span>
                        <span id="qtnTotalSqft" class="font-bold text-[#533afd] tnum">64.00 SFT</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">ফিটিং / ইনস্টলেশন খরচ (+):</span>
                        <input type="number" name="fitting_installation_cost" id="qtnFitting" value="0" oninput="recalcGrandTotal()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">পরিবহন ভাড়া (+):</span>
                        <input type="number" name="transport_cost" id="qtnTransport" value="0" oninput="recalcGrandTotal()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[#64748d]">ডিসকাউন্ট (-):</span>
                        <input type="number" name="discount_amount" id="qtnDiscount" value="0" oninput="recalcGrandTotal()" class="w-28 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-rose-400 text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
                    </div>
                    <div class="flex justify-between font-semibold text-sm text-white border-t border-[#e3e8ee]/10 pt-2.5">
                        <span class="text-[#b9b9f9]">সর্বমোট বাজেট:</span>
                        <span id="qtnGrandTotal" class="text-white font-bold text-lg tnum">৳26,880.00</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-[#e3e8ee]/10 pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('quotations.index') }}" class="btn-secondary text-xs py-2 px-4">বাতিল</a>
                <button type="submit" class="btn-primary min-h-[42px] px-6">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>প্রজেক্ট কোটেশন সংরক্ষণ করুন</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let rowIndex = 1;

    function recalcRow(input) {
        const row = input.closest('tr');
        const w = parseFloat(row.querySelector('input[name*="[width_inch]"]').value) || 0;
        const h = parseFloat(row.querySelector('input[name*="[height_inch]"]').value) || 0;
        const qty = parseInt(row.querySelector('input[name*="[quantity]"]').value) || 1;
        const rate = parseFloat(row.querySelector('input[name*="[rate_per_sqft]"]').value) || 0;

        const unitSqft = (w * h) / 144;
        const totalSqft = unitSqft * qty;
        const totalAmount = totalSqft * rate;

        row.querySelector('.row-sqft').innerText = totalSqft.toFixed(2);
        row.querySelector('.row-total').innerText = '৳' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        recalcGrandTotal();
    }

    function recalcGrandTotal() {
        let grandSqft = 0;
        let itemsSum = 0;

        document.querySelectorAll('.quote-row').forEach(row => {
            const w = parseFloat(row.querySelector('input[name*="[width_inch]"]').value) || 0;
            const h = parseFloat(row.querySelector('input[name*="[height_inch]"]').value) || 0;
            const qty = parseInt(row.querySelector('input[name*="[quantity]"]').value) || 1;
            const rate = parseFloat(row.querySelector('input[name*="[rate_per_sqft]"]').value) || 0;

            const totalSqft = ((w * h) / 144) * qty;
            grandSqft += totalSqft;
            itemsSum += (totalSqft * rate);
        });

        const fitting = parseFloat(document.getElementById('qtnFitting').value) || 0;
        const transport = parseFloat(document.getElementById('qtnTransport').value) || 0;
        const discount = parseFloat(document.getElementById('qtnDiscount').value) || 0;

        const grand = Math.max(0, itemsSum + fitting + transport - discount);

        document.getElementById('qtnTotalSqft').innerText = grandSqft.toFixed(2) + ' SFT';
        document.getElementById('qtnGrandTotal').innerText = '৳' + grand.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function addQuoteRow() {
        const tbody = document.getElementById('quoteItemsBody');
        const tr = document.createElement('tr');
        tr.className = 'quote-row hover:bg-white/[0.02] transition';
        tr.innerHTML = `
            <td class="p-3">
                <select name="items[${rowIndex}][structure_type]" class="w-full px-2 py-1.5 rounded-lg bg-[#070d18] border border-[#e3e8ee]/15 text-white text-xs focus:border-[#533afd] focus:outline-none">
                    <option value="sliding_window_2_track">২-ট্র্যাক স্লাইডিং জানালা</option>
                    <option value="sliding_window_3_track">৩-ট্র্যাক স্লাইডিং জানালা (নেট সহ)</option>
                    <option value="casement_window">ক্যাসমেন্ট ওপেনিং জানালা</option>
                    <option value="fixed_partition">ফিক্সড গ্লাস পারটিশন</option>
                    <option value="swing_door">সুইং থাই ডোর</option>
                </select>
                <input type="text" name="items[${rowIndex}][location_tag]" placeholder="যেমন: ড্রয়িং রুম W-2" class="w-full mt-1.5 px-2 py-1 rounded bg-[#070d18]/60 border border-[#e3e8ee]/10 text-slate-300 text-[11px] focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowIndex}][width_inch]" value="48" step="0.5" oninput="recalcRow(this)" class="w-20 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowIndex}][height_inch]" value="48" step="0.5" oninput="recalcRow(this)" class="w-20 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3">
                <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" oninput="recalcRow(this)" class="w-16 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-center font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3 text-center font-bold text-[#533afd] font-mono tnum row-sqft">
                16.00
            </td>
            <td class="p-3">
                <select name="items[${rowIndex}][profile_product_id]" class="w-36 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-[11px] block mb-1 focus:border-[#533afd] focus:outline-none">
                    <option value="">সেকশন চয়ন...</option>
                    @foreach($profileProducts as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                <select name="items[${rowIndex}][glass_product_id]" class="w-36 px-2 py-1 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-[11px] block focus:border-[#533afd] focus:outline-none">
                    <option value="">কাঁচ চয়ন...</option>
                    @foreach($glassProducts as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="p-3 text-right">
                <input type="number" name="items[${rowIndex}][rate_per_sqft]" value="420" step="5" oninput="recalcRow(this)" class="w-24 px-2 py-1.5 rounded bg-[#070d18] border border-[#e3e8ee]/15 text-white text-right font-mono tnum focus:border-[#533afd] focus:outline-none">
            </td>
            <td class="p-3 text-right font-medium text-emerald-400 tnum row-total">
                ৳6,720.00
            </td>
            <td class="p-3 text-center">
                <button type="button" onclick="removeQuoteRow(this)" class="w-6 h-6 rounded-full inline-flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        recalcGrandTotal();
    }

    function removeQuoteRow(btn) {
        const rows = document.querySelectorAll('.quote-row');
        if (rows.length <= 1) {
            alert('কমপক্ষে একটি মাপ থাকতে হবে!');
            return;
        }
        btn.closest('tr').remove();
        recalcGrandTotal();
    }
</script>
@endpush
