<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guests_are_redirected_to_login_page(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    public function test_login_screen_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Dhaka Thai');
        $response->assertSee('admin@thaiglass.com');
    }

    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@thaiglass.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@thaiglass.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $inactiveUser = User::create([
            'name' => 'Inactive Staff',
            'email' => 'inactive@thaiglass.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@thaiglass.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_cashier_can_access_pos_but_forbidden_from_accounts_and_users(): void
    {
        $cashier = User::where('email', 'cashier@thaiglass.com')->first();
        $this->actingAs($cashier);

        // POS should be accessible
        $response = $this->get('/pos');
        $response->assertStatus(200);

        // Accounts should be 403 Forbidden
        $response = $this->get('/accounts');
        $response->assertStatus(403);

        // User management should be 403 Forbidden
        $response = $this->get('/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_user_management_and_accounts(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $this->actingAs($admin);

        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertSee('ব্যবহারকারী ও ভূমিকা ব্যবস্থাপনা');

        $response = $this->get('/accounts');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_new_staff_user(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $this->actingAs($admin);

        $response = $this->post('/users', [
            'name' => 'New Counter Cashier',
            'email' => 'newcashier@thaiglass.com',
            'phone' => '01700-112233',
            'role' => 'cashier',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => 1,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newcashier@thaiglass.com',
            'role' => 'cashier',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $this->actingAs($admin);

        $response = $this->delete("/users/{$admin->id}");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_user_can_update_profile(): void
    {
        $cashier = User::where('email', 'cashier@thaiglass.com')->first();
        $this->actingAs($cashier);

        $response = $this->put('/profile', [
            'name' => 'Updated Cashier Name',
            'phone' => '01999-887766',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $cashier->id,
            'name' => 'Updated Cashier Name',
            'phone' => '01999-887766',
        ]);
    }

    public function test_user_can_logout(): void
    {
        $admin = User::where('email', 'admin@thaiglass.com')->first();
        $this->actingAs($admin);

        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
