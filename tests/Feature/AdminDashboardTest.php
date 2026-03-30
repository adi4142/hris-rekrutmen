<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\JobApplicant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_and_see_total_applicant()
    {
        // buat user admin
        $admin = factory(User::class)->create([
            'roles_id' => 1 // sesuaikan ID role admin kamu
        ]);

        // buat data pelamar
        factory(JobApplicant::class, 5)->create();

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
