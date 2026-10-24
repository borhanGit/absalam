<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ChartOfAccount;
use App\Models\Color;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Thickness;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with Bangladesh Thai & Aluminium market data.
     */
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@thaipos.com'],
            [
                'name' => 'Aluminium Shop Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Company / Shop Settings
        CompanySetting::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Dhaka Thai & Aluminium Glass House',
                'proprietor_name' => 'Md. Borhan Uddin',
                'phone' => '01712-345678',
                'phone_alternate' => '01812-345678',
                'email' => 'contact@dhakathai.com',
                'address' => 'Plot #12, Road #3, Sector #10, Mirpur, Dhaka-1216, Bangladesh',
                'bin_vat_number' => '123456789-0101',
                'trade_license' => 'TRAD/DNCC/01234/2026',
                'currency_symbol' => '৳',
                'default_vat_percentage' => 0.00,
                'invoice_footer_note' => 'ধন্যবাদ! কাঁচ ও অ্যালুমিনিয়াম মাল ডেলিভারির সময় ভালো করে দেখে বুঝে নিন। কাটার পর মাল ফেরত গ্রহণযোগ্য নয়।',
            ]
        );

        // 3. Warehouses / Locations
        $whMain = Warehouse::firstOrCreate(
            ['code' => 'WH-MAIN'],
            ['name' => 'Main Showroom & Sales Counter', 'phone' => '01712-345678', 'is_primary' => true, 'address' => 'Mirpur-10, Dhaka']
        );
        $whGodown = Warehouse::firstOrCreate(
            ['code' => 'WH-GDN1'],
            ['name' => 'Central Godown (Stock Yard)', 'phone' => '01712-345679', 'is_primary' => false, 'address' => 'Gabtoli, Dhaka']
        );
        $whWorkshop = Warehouse::firstOrCreate(
            ['code' => 'WH-WKS'],
            ['name' => 'Cutting & Fabrication Workshop', 'phone' => '01712-345680', 'is_primary' => false, 'address' => 'Mirpur-1, Dhaka']
        );

        // 4. Units
        $uBar = Unit::firstOrCreate(['code' => 'bar'], ['name' => 'Piece / Bar (১৬/১৮ ফুট)', 'allow_decimal' => false]);
        $uSqft = Unit::firstOrCreate(['code' => 'sqft'], ['name' => 'Square Foot (Sq.ft)', 'allow_decimal' => true]);
        $uRft = Unit::firstOrCreate(['code' => 'rft'], ['name' => 'Running Foot (RFT)', 'allow_decimal' => true]);
        $uKg = Unit::firstOrCreate(['code' => 'kg'], ['name' => 'Kilogram (Kg)', 'allow_decimal' => true]);
        $uPcs = Unit::firstOrCreate(['code' => 'pcs'], ['name' => 'Piece (Pcs)', 'allow_decimal' => false]);
        $uBox = Unit::firstOrCreate(['code' => 'box'], ['name' => 'Box', 'allow_decimal' => false]);
        $uTube = Unit::firstOrCreate(['code' => 'tube'], ['name' => 'Tube / Can', 'allow_decimal' => false]);
        $uRoll = Unit::firstOrCreate(['code' => 'roll'], ['name' => 'Roll', 'allow_decimal' => true]);

        // 5. Categories
        $catProfile = Category::firstOrCreate(['slug' => 'aluminium-profiles'], ['name' => 'Aluminium Profiles (সেকশন)', 'type' => 'profile']);
        $catGlass = Category::firstOrCreate(['slug' => 'glass-sheets'], ['name' => 'Glass & Mirrors (কাঁচ ও গ্লাস)', 'type' => 'glass']);
        $catHardware = Category::firstOrCreate(['slug' => 'hardware-accessories'], ['name' => 'Hardware & Accessories (ফিটিংস ও লক)', 'type' => 'accessory']);
        $catBoard = Category::firstOrCreate(['slug' => 'board-ceiling'], ['name' => 'Alucobond, Board & Ceiling', 'type' => 'board_ceiling']);
        $catScrap = Category::firstOrCreate(['slug' => 'aluminium-scrap'], ['name' => 'Aluminium Scrap (ভাঙারি ও অফ-কাট)', 'type' => 'scrap']);

        // 6. Brands / Mills
        $bKai = Brand::firstOrCreate(['name' => 'Kai Aluminium'], ['code' => 'KAI', 'origin_country' => 'Bangladesh']);
        $bDhakaThai = Brand::firstOrCreate(['name' => 'Dhaka Thai'], ['code' => 'DTHAI', 'origin_country' => 'Bangladesh']);
        $bChungHua = Brand::firstOrCreate(['name' => 'Chung Hua'], ['code' => 'CHUNG', 'origin_country' => 'Bangladesh']);
        $bAlAqsa = Brand::firstOrCreate(['name' => 'Al-Aqsa Aluminium'], ['code' => 'ALAQSA', 'origin_country' => 'Bangladesh']);
        $bPhp = Brand::firstOrCreate(['name' => 'PHP Float Glass'], ['code' => 'PHP', 'origin_country' => 'Bangladesh']);
        $bNasir = Brand::firstOrCreate(['name' => 'Nasir Glass'], ['code' => 'NASIR', 'origin_country' => 'Bangladesh']);

        // 7. Colors & Finishes
        $colSilver = Color::firstOrCreate(['name' => 'Silver (Mill Finish)'], ['color_code' => '#C0C0C0', 'applicable_for' => 'profile']);
        $colBronze = Color::firstOrCreate(['name' => 'Bronze / Champagne'], ['color_code' => '#8C7853', 'applicable_for' => 'profile']);
        $colSS = Color::firstOrCreate(['name' => 'Stainless Steel (SS Finish)'], ['color_code' => '#DCDCDC', 'applicable_for' => 'profile']);
        $colWhite = Color::firstOrCreate(['name' => 'Powder Coated White'], ['color_code' => '#FFFFFF', 'applicable_for' => 'profile']);
        $colBlack = Color::firstOrCreate(['name' => 'Powder Coated Black'], ['color_code' => '#1A1A1A', 'applicable_for' => 'profile']);
        $colWood = Color::firstOrCreate(['name' => 'Wood Grain Finish'], ['color_code' => '#8B5A2B', 'applicable_for' => 'profile']);
        $colClearGlass = Color::firstOrCreate(['name' => 'Clear (স্বচ্ছ)'], ['color_code' => '#E0F7FA', 'applicable_for' => 'glass']);
        $colTintedBlack = Color::firstOrCreate(['name' => 'Tinted Dark Black'], ['color_code' => '#212121', 'applicable_for' => 'glass']);
        $colReflectiveBlue = Color::firstOrCreate(['name' => 'Reflective Dark Blue'], ['color_code' => '#0D47A1', 'applicable_for' => 'glass']);
        $colFrosted = Color::firstOrCreate(['name' => 'Frosted / Mercury Glass'], ['color_code' => '#ECEFF1', 'applicable_for' => 'glass']);

        // 8. Thicknesses
        $th10 = Thickness::firstOrCreate(['value' => '1.0mm'], ['numeric_value_mm' => 1.00, 'applicable_for' => 'profile']);
        $th12 = Thickness::firstOrCreate(['value' => '1.2mm'], ['numeric_value_mm' => 1.20, 'applicable_for' => 'profile']);
        $th15 = Thickness::firstOrCreate(['value' => '1.5mm'], ['numeric_value_mm' => 1.50, 'applicable_for' => 'profile']);
        $th20 = Thickness::firstOrCreate(['value' => '2.0mm'], ['numeric_value_mm' => 2.00, 'applicable_for' => 'profile']);
        $thG5 = Thickness::firstOrCreate(['value' => '5mm Glass'], ['numeric_value_mm' => 5.00, 'applicable_for' => 'glass']);
        $thG6 = Thickness::firstOrCreate(['value' => '6mm Glass'], ['numeric_value_mm' => 6.00, 'applicable_for' => 'glass']);
        $thG8 = Thickness::firstOrCreate(['value' => '8mm Glass'], ['numeric_value_mm' => 8.00, 'applicable_for' => 'glass']);
        $thG10 = Thickness::firstOrCreate(['value' => '10mm Glass'], ['numeric_value_mm' => 10.00, 'applicable_for' => 'glass']);

        // 9. Core Aluminium & Glass Products
        $p1 = Product::firstOrCreate(
            ['code' => 'SEC-4IN-BOT-SS-12'],
            [
                'name' => '4" Sliding Bottom (1.2mm SS Finish)',
                'product_type' => 'profile',
                'category_id' => $catProfile->id,
                'brand_id' => $bKai->id,
                'unit_id' => $uBar->id,
                'secondary_unit_id' => $uKg->id,
                'conversion_ratio' => 4.25, // 1 bar = 4.25 kg
                'color_id' => $colSS->id,
                'thickness_id' => $th12->id,
                'length_feet' => 16.00,
                'weight_per_unit_kg' => 4.25,
                'purchase_price' => 1950.00,
                'selling_price_retail' => 2300.00,
                'selling_price_wholesale' => 2150.00,
                'selling_price_contractor' => 2200.00,
                'min_stock_alert' => 20,
            ]
        );

        $p2 = Product::firstOrCreate(
            ['code' => 'SEC-4IN-TOP-SS-12'],
            [
                'name' => '4" Sliding Top (1.2mm SS Finish)',
                'product_type' => 'profile',
                'category_id' => $catProfile->id,
                'brand_id' => $bKai->id,
                'unit_id' => $uBar->id,
                'secondary_unit_id' => $uKg->id,
                'conversion_ratio' => 3.80,
                'color_id' => $colSS->id,
                'thickness_id' => $th12->id,
                'length_feet' => 16.00,
                'weight_per_unit_kg' => 3.80,
                'purchase_price' => 1750.00,
                'selling_price_retail' => 2100.00,
                'selling_price_wholesale' => 1950.00,
                'selling_price_contractor' => 2000.00,
                'min_stock_alert' => 20,
            ]
        );

        $p3 = Product::firstOrCreate(
            ['code' => 'SEC-4IN-INT-SS-12'],
            [
                'name' => '4" Sliding Interlock (1.2mm SS Finish)',
                'product_type' => 'profile',
                'category_id' => $catProfile->id,
                'brand_id' => $bKai->id,
                'unit_id' => $uBar->id,
                'secondary_unit_id' => $uKg->id,
                'conversion_ratio' => 3.20,
                'color_id' => $colSS->id,
                'thickness_id' => $th12->id,
                'length_feet' => 16.00,
                'weight_per_unit_kg' => 3.20,
                'purchase_price' => 1500.00,
                'selling_price_retail' => 1800.00,
                'selling_price_wholesale' => 1650.00,
                'selling_price_contractor' => 1700.00,
                'min_stock_alert' => 20,
            ]
        );

        $p4 = Product::firstOrCreate(
            ['code' => 'GLS-5MM-CLEAR'],
            [
                'name' => '5mm Clear Sheet Glass (PHP)',
                'product_type' => 'glass',
                'category_id' => $catGlass->id,
                'brand_id' => $bPhp->id,
                'unit_id' => $uSqft->id,
                'color_id' => $colClearGlass->id,
                'thickness_id' => $thG5->id,
                'purchase_price' => 52.00,
                'selling_price_retail' => 70.00,
                'selling_price_wholesale' => 62.00,
                'selling_price_contractor' => 65.00,
                'min_stock_alert' => 200,
            ]
        );

        $p5 = Product::firstOrCreate(
            ['code' => 'GLS-5MM-TINT-BLK'],
            [
                'name' => '5mm Tinted Dark Black Glass (Nasir)',
                'product_type' => 'glass',
                'category_id' => $catGlass->id,
                'brand_id' => $bNasir->id,
                'unit_id' => $uSqft->id,
                'color_id' => $colTintedBlack->id,
                'thickness_id' => $thG5->id,
                'purchase_price' => 62.00,
                'selling_price_retail' => 85.00,
                'selling_price_wholesale' => 75.00,
                'selling_price_contractor' => 78.00,
                'min_stock_alert' => 150,
            ]
        );

        $p6 = Product::firstOrCreate(
            ['code' => 'ACC-SLD-WHEEL-DBL'],
            [
                'name' => 'Sliding Heavy Duty Double Bearing Roller',
                'product_type' => 'accessory',
                'category_id' => $catHardware->id,
                'unit_id' => $uPcs->id,
                'purchase_price' => 85.00,
                'selling_price_retail' => 130.00,
                'selling_price_wholesale' => 105.00,
                'selling_price_contractor' => 110.00,
                'min_stock_alert' => 100,
            ]
        );

        $p7 = Product::firstOrCreate(
            ['code' => 'ACC-LOCK-CRESCENT'],
            [
                'name' => 'Crescent Sliding Window Lock (SS Finish)',
                'product_type' => 'accessory',
                'category_id' => $catHardware->id,
                'unit_id' => $uPcs->id,
                'purchase_price' => 140.00,
                'selling_price_retail' => 220.00,
                'selling_price_wholesale' => 180.00,
                'selling_price_contractor' => 190.00,
                'min_stock_alert' => 50,
            ]
        );

        $p8 = Product::firstOrCreate(
            ['code' => 'ACC-SILICONE-CLEAR'],
            [
                'name' => 'Silicone Sealant Clear (GP 300ml)',
                'product_type' => 'accessory',
                'category_id' => $catHardware->id,
                'unit_id' => $uTube->id,
                'purchase_price' => 210.00,
                'selling_price_retail' => 290.00,
                'selling_price_wholesale' => 250.00,
                'selling_price_contractor' => 260.00,
                'min_stock_alert' => 60,
            ]
        );

        $p9 = Product::firstOrCreate(
            ['code' => 'SCRAP-ALUM-BHANGARI'],
            [
                'name' => 'Aluminium Scrap / Bhangari (ভাঙারি)',
                'product_type' => 'scrap',
                'category_id' => $catScrap->id,
                'unit_id' => $uKg->id,
                'purchase_price' => 220.00,
                'selling_price_retail' => 270.00,
                'selling_price_wholesale' => 270.00,
                'selling_price_contractor' => 270.00,
                'min_stock_alert' => 10,
            ]
        );

        // 10. Initial Stock seeding
        Stock::firstOrCreate(['product_id' => $p1->id, 'warehouse_id' => $whMain->id], ['quantity' => 120, 'quantity_secondary' => 510.0]);
        Stock::firstOrCreate(['product_id' => $p2->id, 'warehouse_id' => $whMain->id], ['quantity' => 100, 'quantity_secondary' => 380.0]);
        Stock::firstOrCreate(['product_id' => $p3->id, 'warehouse_id' => $whMain->id], ['quantity' => 95, 'quantity_secondary' => 304.0]);
        Stock::firstOrCreate(['product_id' => $p4->id, 'warehouse_id' => $whMain->id], ['quantity' => 1200, 'quantity_secondary' => 0]);
        Stock::firstOrCreate(['product_id' => $p5->id, 'warehouse_id' => $whMain->id], ['quantity' => 850, 'quantity_secondary' => 0]);
        Stock::firstOrCreate(['product_id' => $p6->id, 'warehouse_id' => $whMain->id], ['quantity' => 400, 'quantity_secondary' => 0]);
        Stock::firstOrCreate(['product_id' => $p7->id, 'warehouse_id' => $whMain->id], ['quantity' => 150, 'quantity_secondary' => 0]);
        Stock::firstOrCreate(['product_id' => $p8->id, 'warehouse_id' => $whMain->id], ['quantity' => 120, 'quantity_secondary' => 0]);

        // 11. Sample Suppliers
        Supplier::firstOrCreate(
            ['name' => 'Kai Aluminium Industries Ltd'],
            [
                'company_name' => 'Kai Aluminium Ltd',
                'phone' => '01711-000001',
                'address' => 'Tongi Industrial Area, Gazipur',
                'current_balance' => 85000.00,
                'notes' => 'Primary aluminium section mill'
            ]
        );
        Supplier::firstOrCreate(
            ['name' => 'PHP Float Glass Industries'],
            [
                'company_name' => 'PHP Group',
                'phone' => '01711-000002',
                'address' => 'Sitakunda, Chittagong',
                'current_balance' => 45000.00,
                'notes' => 'Float glass and tinted glass supplier'
            ]
        );
        Supplier::firstOrCreate(
            ['name' => 'Bhai Bhai Hardware & Import House'],
            [
                'company_name' => 'Bhai Bhai Hardware',
                'phone' => '01711-000003',
                'address' => 'Nawabpur Road, Old Dhaka',
                'current_balance' => 12500.00,
                'notes' => 'Lock, roller, wool pile, silicon importer'
            ]
        );

        // 12. Sample Customers (Retail, Mistri, Builder)
        Customer::firstOrCreate(
            ['phone' => '01819-112233'],
            [
                'name' => 'Kawsar Ahmed (Thai Mistri)',
                'customer_type' => 'contractor_mistri',
                'address' => 'Mirpur-11, Block-C, Dhaka',
                'current_balance' => 18500.00,
                'credit_limit' => 50000.00,
                'default_commission_rate' => 5.00, // 5 Tk per sqft or %
                'notes' => 'Experienced fabricator. Usually buys on running chalan.'
            ]
        );
        Customer::firstOrCreate(
            ['phone' => '01911-445566'],
            [
                'name' => 'Rahim Construction & Real Estate Ltd',
                'customer_type' => 'builder',
                'address' => 'Banani DOHS, Dhaka',
                'current_balance' => 125000.00,
                'credit_limit' => 300000.00,
                'notes' => 'Bulk developer builder. Payments by bank cheque.'
            ]
        );
        Customer::firstOrCreate(
            ['phone' => '01715-778899'],
            [
                'name' => 'Engr. Tanvir Hasan (House Owner)',
                'customer_type' => 'retail',
                'address' => 'Uttara Sector 11, Dhaka',
                'current_balance' => 0.00,
                'notes' => 'Retail residential client.'
            ]
        );

        // 13. Chart of Accounts (Standard double-entry baseline)
        $coaCash = ChartOfAccount::firstOrCreate(['code' => '1001'], ['name' => 'Cash in Hand (ক্যাশ খাতা)', 'account_type' => 'asset', 'is_system_locked' => true]);
        $coaBank = ChartOfAccount::firstOrCreate(['code' => '1002'], ['name' => 'Bank Accounts (ব্যাংক খাতা)', 'account_type' => 'asset', 'is_system_locked' => true]);
        $coaAR = ChartOfAccount::firstOrCreate(['code' => '1003'], ['name' => 'Accounts Receivable (কাস্টমার বাকি)', 'account_type' => 'asset', 'is_system_locked' => true]);
        $coaStock = ChartOfAccount::firstOrCreate(['code' => '1004'], ['name' => 'Inventory Asset (স্টক ভ্যালু)', 'account_type' => 'asset', 'is_system_locked' => true]);
        $coaAP = ChartOfAccount::firstOrCreate(['code' => '2001'], ['name' => 'Accounts Payable (সাপ্লায়ার বাকি)', 'account_type' => 'liability', 'is_system_locked' => true]);
        $coaCapital = ChartOfAccount::firstOrCreate(['code' => '3001'], ['name' => 'Owner Equity / Capital (মূলধন)', 'account_type' => 'equity', 'is_system_locked' => true]);
        $coaSales = ChartOfAccount::firstOrCreate(['code' => '4001'], ['name' => 'Sales Revenue (বিক্রয় আয়)', 'account_type' => 'revenue', 'is_system_locked' => true]);
        $coaCOGS = ChartOfAccount::firstOrCreate(['code' => '5001'], ['name' => 'Cost of Goods Sold / Purchases (ক্রয় খরচ)', 'account_type' => 'expense', 'is_system_locked' => true]);
        $coaRent = ChartOfAccount::firstOrCreate(['code' => '5002'], ['name' => 'Shop / Godown Rent (ভাড়া খরচ)', 'account_type' => 'expense']);
        $coaSalary = ChartOfAccount::firstOrCreate(['code' => '5003'], ['name' => 'Staff Salary & Wages (বেতন খরচ)', 'account_type' => 'expense']);
        $coaTransport = ChartOfAccount::firstOrCreate(['code' => '5004'], ['name' => 'Transport & Carrying (পরিবহন ও ভ্যান খরচ)', 'account_type' => 'expense']);
        $coaMisc = ChartOfAccount::firstOrCreate(['code' => '5005'], ['name' => 'General / Office Expenses (আপ্যায়ন ও বিবিধ)', 'account_type' => 'expense']);
    }
}
