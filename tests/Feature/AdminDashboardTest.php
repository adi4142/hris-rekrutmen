<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_and_see_total_applicant()
    {
        // buat user admin
        $admin = User::factory()->create([
            'roles_id' => 1 // sesuaikan ID role admin kamu
        ]);

        // buat data pelamar
        Applicant::factory()->count(5)->create();

        // login sebagai admin
        $response = $this->actingAs($admin)
                         ->get('/admin/dashboard');

        // cek status halaman
        $response->assertStatus(200);

        // cek teks tampil
        $response->assertSee('Total Pelamar');

        // cek angka 5 muncul
        $response->assertSee('5');
    }
}
