<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_siswa_cannot_access_admin_dashboard(): void
    {
        $siswa = User::where('role', 'siswa')->first();
        $response = $this->actingAs($siswa)->get('/admin/dashboard');

        $response->assertRedirect('/siswa/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('PAPAN KELOLA ADUAN');
    }

    public function test_admin_can_access_laporan_cetak(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/laporan/cetak');

        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI LAPORAN KERUSAKAN');
    }

    public function test_admin_can_download_laporan_pdf(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/laporan/cetak?download=pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_download_laporan_excel(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin/laporan/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_siswa_cannot_access_laporan_excel(): void
    {
        $siswa = User::where('role', 'siswa')->first();
        $response = $this->actingAs($siswa)->get('/admin/laporan/excel');

        $response->assertRedirect('/siswa/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_siswa_cannot_access_laporan_cetak(): void
    {
        $siswa = User::where('role', 'siswa')->first();
        $response = $this->actingAs($siswa)->get('/admin/laporan/cetak');

        $response->assertRedirect('/siswa/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_delete_aduan(): void
    {
        $admin = User::where('role', 'admin')->first();
        $aduan = \App\Models\Aduan::first();

        $response = $this->actingAs($admin)->delete('/admin/aduan/' . $aduan->id);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('aduan', ['id' => $aduan->id]);
    }

    public function test_siswa_cannot_delete_aduan(): void
    {
        $siswa = User::where('role', 'siswa')->first();
        $aduan = \App\Models\Aduan::first();

        $response = $this->actingAs($siswa)->delete('/admin/aduan/' . $aduan->id);

        $response->assertRedirect('/siswa/dashboard');
        $this->assertDatabaseHas('aduan', ['id' => $aduan->id]);
    }
}
