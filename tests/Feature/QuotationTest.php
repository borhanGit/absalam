<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\JobQuotation;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_quotation_details_page_renders_and_converts_to_sale_successfully(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $customer = Customer::first();
        $warehouse = Warehouse::first();

        // Create a quotation
        $quotation = JobQuotation::create([
            'quotation_no' => 'QUO-2026-00001',
            'customer_id' => $customer->id,
            'warehouse_id' => $warehouse->id,
            'quotation_date' => now()->toDateString(),
            'valid_until' => now()->addDays(15)->toDateString(),
            'subtotal' => 15000,
            'discount_amount' => 500,
            'vat_tax_amount' => 0,
            'grand_total' => 14500,
            'status' => 'draft',
            'notes' => 'Test quotation for 4 sliding windows',
        ]);

        // 1. Test Viewing the Quotation Show Page (Where route [quotations.convert] is used)
        $response = $this->actingAs($admin)->get(route('quotations.show', $quotation->id));
        $response->assertStatus(200);
        $response->assertSee('QUO-2026-00001');
        $response->assertSee('সেলস চালানে রূপান্তর করুন');

        // 2. Test Converting Quotation to Sale via route('quotations.convert')
        $convertResponse = $this->actingAs($admin)->post(route('quotations.convert', $quotation->id));
        $convertResponse->assertRedirect();

        // 3. Verify Database Updates
        $this->assertDatabaseHas('job_quotations', [
            'id' => $quotation->id,
            'status' => 'converted_to_sale',
        ]);

        $this->assertDatabaseHas('sales', [
            'customer_id' => $customer->id,
            'sale_type' => 'quotation_converted',
            'grand_total' => 14500,
        ]);
    }
}
