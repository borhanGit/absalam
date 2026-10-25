<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_all_modules_render_successfully(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $this->actingAs($admin);

        $routes = [
            '/',
            '/dashboard',
            '/pos',
            '/sales',
            '/quotations',
            '/purchases',
            '/products',
            '/stocks',
            '/stocks/transfer',
            '/offcuts',
            '/customers',
            '/suppliers',
            '/cheques',
            '/cheques/create',
            '/receipts',
            '/receipts/create',
            '/payments',
            '/payments/create',
            '/expenses',
            '/expenses/create',
            '/accounts',
            '/accounts/cashbook',
            '/accounts/profit-loss',
            '/users',
            '/users/create',
            '/profile',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
