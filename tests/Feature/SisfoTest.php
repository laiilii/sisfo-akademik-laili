<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SisfoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['email' => 'admin@test.com', 'password' => 'rahasia123']);
    }

    private function kelas(string $nama = 'XI RPL 1'): Kelas
    {
        return Kelas::create(['nama_kelas' => $nama, 'tingkat' => 'XI', 'jurusan' => 'RPL', 'tahun_ajaran' => '2025/2026']);
    }

    private function siswa(?Kelas $k = null, string $nis = '1001'): Siswa
    {
        return Siswa::create(['nis' => $nis, 'nama' => 'Budi', 'jenis_kelamin' => 'L', 'kelas_id' => ($k ?? $this->kelas())->id]);
    }

    private function mapel(int $kkm = 75, string $kode = 'MP-A'): Mapel
    {
        return Mapel::create(['kode_mapel' => $kode, 'nama_mapel' => 'Basis Data', 'kkm' => $kkm]);
    }

    // ---------- AUTH
    public function test_guest_is_redirected_to_login_on_every_admin_page(): void
    {
        foreach (['/', '/home', '/kelas', '/kelas/create', '/siswa', '/siswa/create', '/guru', '/guru/create',
            '/mapel', '/mapel/create', '/nilai', '/nilai/create'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->post('/siswa', [])->assertRedirect('/login');
        $this->delete('/siswa/1')->assertRedirect('/login');
    }

    public function test_login_page_opens_and_has_no_prefilled_credentials(): void
    {
        $this->get('/login')->assertOk()->assertSee('Kata Sandi')->assertDontSee('admin@admin.com');
    }

    public function test_login_success_goes_to_dashboard(): void
    {
        $this->admin();
        $this->post('/login', ['email' => 'admin@test.com', 'password' => 'rahasia123'])->assertRedirect('/');
        $this->assertAuthenticated();
        $this->get('/')->assertOk()->assertSee('Dashboard Akademik');
    }

    public function test_login_failure_shows_clear_message(): void
    {
        $this->admin();
        $this->from('/login')->followingRedirects()
            ->post('/login', ['email' => 'admin@test.com', 'password' => 'salah'])
            ->assertOk()->assertSee('Email atau kata sandi salah.')->assertSee('is-invalid', false);
        $this->assertGuest();
    }

    public function test_password_is_stored_hashed(): void
    {
        $u = $this->admin();
        $this->assertNotSame('rahasia123', $u->fresh()->password);
        $this->assertTrue(password_verify('rahasia123', $u->fresh()->password));
    }

    public function test_logout_ends_session_and_blocks_back_button_cache(): void
    {
        $this->actingAs($this->admin());
        $res = $this->get('/siswa');
        $this->assertStringContainsString('no-store', $res->headers->get('Cache-Control'));
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/siswa')->assertRedirect('/login');
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_create_command(): void
    {
        $this->artisan('admin:create', ['--name' => 'Ani', '--email' => 'ani@x.com', '--password' => 'abcd1234'])->assertExitCode(0);
        $this->assertTrue(password_verify('abcd1234', User::where('email', 'ani@x.com')->first()->password));
        $this->artisan('admin:create', ['--name' => 'Ani', '--email' => 'ani@x.com', '--password' => 'short'])->assertExitCode(1);
    }

    // ---------- DASHBOARD & PAGES
    public function test_all_pages_open_when_logged_in(): void
    {
        $this->actingAs($this->admin());
        $s = $this->siswa();
        $m = $this->mapel();
        $g = Guru::create(['nip' => '1', 'nama' => 'Dewi', 'jenis_kelamin' => 'P']);
        $n = Nilai::create(['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 80, 'nilai_uts' => 80, 'nilai_uas' => 80, 'nilai_akhir' => 80, 'keterangan' => 'Lulus']);
        foreach (['/', '/kelas', '/kelas/create', "/kelas/{$s->kelas_id}/edit", '/siswa', '/siswa/create', "/siswa/{$s->id}", "/siswa/{$s->id}/edit",
            '/guru', '/guru/create', "/guru/{$g->id}/edit", '/mapel', '/mapel/create', "/mapel/{$m->id}/edit",
            '/nilai', '/nilai/create', "/nilai/{$n->id}/edit"] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_dashboard_counts_come_from_database(): void
    {
        $this->actingAs($this->admin());
        $this->siswa();
        $this->mapel();
        $html = $this->get('/')->getContent();
        $this->assertMatchesRegularExpression('/Total Siswa.*?>\s*1\s*</s', $html);
        $this->assertMatchesRegularExpression('/Mata Pelajaran.*?>\s*1\s*</s', $html);
    }

    // ---------- CRUD
    public function test_kelas_crud(): void
    {
        $this->actingAs($this->admin());
        $this->post('/kelas', ['nama_kelas' => 'XII TKJ 1', 'tingkat' => 'XII', 'jurusan' => 'TKJ', 'tahun_ajaran' => '2025/2026'])->assertRedirect('/kelas');
        $k = Kelas::where('nama_kelas', 'XII TKJ 1')->firstOrFail();
        $this->put("/kelas/{$k->id}", ['nama_kelas' => 'XII TKJ 2', 'tingkat' => 'XII', 'jurusan' => 'TKJ', 'tahun_ajaran' => '2025/2026'])->assertRedirect('/kelas');
        $this->assertDatabaseHas('kelas', ['id' => $k->id, 'nama_kelas' => 'XII TKJ 2']);
        $this->delete("/kelas/{$k->id}")->assertRedirect('/kelas');
        $this->assertDatabaseMissing('kelas', ['id' => $k->id]);
    }

    public function test_kelas_validation_and_delete_protection(): void
    {
        $this->actingAs($this->admin());
        $this->post('/kelas', ['nama_kelas' => '', 'tingkat' => 'IX', 'jurusan' => '', 'tahun_ajaran' => '2025'])
            ->assertSessionHasErrors(['nama_kelas', 'tingkat', 'jurusan', 'tahun_ajaran']);
        $s = $this->siswa();
        $this->delete("/kelas/{$s->kelas_id}")->assertSessionHas('error');
        $this->assertDatabaseHas('kelas', ['id' => $s->kelas_id]);
    }

    public function test_siswa_crud_and_validation(): void
    {
        $this->actingAs($this->admin());
        $k = $this->kelas();
        $data = ['nis' => '555', 'nama' => 'Ani', 'jenis_kelamin' => 'P', 'alamat' => 'Jl X', 'no_hp' => '0812', 'kelas_id' => $k->id];
        $this->post('/siswa', $data)->assertRedirect('/siswa');
        $s = Siswa::where('nis', '555')->firstOrFail();
        $this->post('/siswa', $data)->assertSessionHasErrors('nis'); // NIS unik
        $this->post('/siswa', ['nis' => '', 'jenis_kelamin' => 'X', 'kelas_id' => 999, 'no_hp' => 'abc'])
            ->assertSessionHasErrors(['nis', 'nama', 'jenis_kelamin', 'kelas_id', 'no_hp']);
        $this->put("/siswa/{$s->id}", array_merge($data, ['nama' => 'Ani Baru']))->assertRedirect('/siswa'); // NIS sendiri boleh
        $this->assertDatabaseHas('siswas', ['id' => $s->id, 'nama' => 'Ani Baru']);
        $this->get("/siswa/{$s->id}")->assertOk()->assertSee('Ani Baru')->assertSee('Belum ada nilai');
        $this->delete("/siswa/{$s->id}")->assertRedirect('/siswa');
        $this->assertDatabaseMissing('siswas', ['id' => $s->id]);
    }

    public function test_guru_crud(): void
    {
        $this->actingAs($this->admin());
        $d = ['nip' => '1985', 'nama' => 'Drs. A', 'jenis_kelamin' => 'L', 'email' => 'a@s.id', 'no_hp' => '0813', 'alamat' => 'x'];
        $this->post('/guru', $d)->assertRedirect('/guru');
        $g = Guru::firstOrFail();
        $this->post('/guru', $d)->assertSessionHasErrors('nip');
        $this->post('/guru', array_merge($d, ['nip' => '2', 'email' => 'bukan-email']))->assertSessionHasErrors('email');
        $this->put("/guru/{$g->id}", array_merge($d, ['nama' => 'Drs. B']))->assertRedirect('/guru');
        $this->assertDatabaseHas('gurus', ['id' => $g->id, 'nama' => 'Drs. B']);
        $this->delete("/guru/{$g->id}")->assertRedirect('/guru');
        $this->assertDatabaseCount('gurus', 0);
    }

    public function test_mapel_crud_and_delete_protection(): void
    {
        $this->actingAs($this->admin());
        $this->post('/mapel', ['kode_mapel' => 'MP-X', 'nama_mapel' => 'X', 'kkm' => 70])->assertRedirect('/mapel');
        $m = Mapel::where('kode_mapel', 'MP-X')->firstOrFail();
        $this->post('/mapel', ['kode_mapel' => 'MP-X', 'nama_mapel' => 'X', 'kkm' => 101])->assertSessionHasErrors(['kode_mapel', 'kkm']);
        $this->put("/mapel/{$m->id}", ['kode_mapel' => 'MP-X', 'nama_mapel' => 'Y', 'kkm' => 80])->assertRedirect('/mapel');
        $this->assertDatabaseHas('mapels', ['id' => $m->id, 'nama_mapel' => 'Y', 'kkm' => 80]);
        $s = $this->siswa();
        Nilai::create(['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 1, 'nilai_uts' => 1, 'nilai_uas' => 1, 'nilai_akhir' => 1, 'keterangan' => 'Tidak Lulus']);
        $this->delete("/mapel/{$m->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('mapels', ['id' => $m->id]);
    }

    // ---------- NILAI
    public function test_nilai_formula_and_status_from_kkm(): void
    {
        $this->actingAs($this->admin());
        $s = $this->siswa();
        $m = $this->mapel(75);
        // Sesuai referensi: 80/76/100 => 86.80 Lulus
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 80, 'nilai_uts' => 76, 'nilai_uas' => 100])->assertRedirect('/nilai');
        $n = Nilai::firstOrFail();
        $this->assertEquals(86.80, (float) $n->nilai_akhir);
        $this->assertSame('Lulus', $n->keterangan);

        // 70/65/70 => 68.50 Tidak Lulus
        $this->put("/nilai/{$n->id}", ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 70, 'nilai_uts' => 65, 'nilai_uas' => 70])->assertRedirect('/nilai');
        $n->refresh();
        $this->assertEquals(68.50, (float) $n->nilai_akhir);
        $this->assertSame('Tidak Lulus', $n->keterangan);

        // Tepat di KKM = Lulus
        $m2 = $this->mapel(75, 'MP-B');
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m2->id, 'nilai_tugas' => 75, 'nilai_uts' => 75, 'nilai_uas' => 75]);
        $this->assertSame('Lulus', Nilai::where('mapel_id', $m2->id)->first()->keterangan);
    }

    public function test_nilai_cannot_be_forged_from_client_and_is_validated(): void
    {
        $this->actingAs($this->admin());
        $s = $this->siswa();
        $m = $this->mapel(75);
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 10, 'nilai_uts' => 10, 'nilai_uas' => 10, 'nilai_akhir' => 100, 'keterangan' => 'Lulus']);
        $n = Nilai::firstOrFail();
        $this->assertEquals(10, (float) $n->nilai_akhir);
        $this->assertSame('Tidak Lulus', $n->keterangan);
        // duplikat siswa+mapel ditolak
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 1, 'nilai_uts' => 1, 'nilai_uas' => 1])->assertSessionHasErrors('mapel_id');
        // rentang
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 101, 'nilai_uts' => -1, 'nilai_uas' => 'a'])
            ->assertSessionHasErrors(['nilai_tugas', 'nilai_uts', 'nilai_uas']);
    }

    public function test_changing_kkm_updates_status_and_nilai_delete(): void
    {
        $this->actingAs($this->admin());
        $s = $this->siswa();
        $m = $this->mapel(75);
        $this->post('/nilai', ['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 80, 'nilai_uts' => 80, 'nilai_uas' => 80]);
        $this->assertSame('Lulus', Nilai::first()->keterangan);
        $this->put("/mapel/{$m->id}", ['kode_mapel' => 'MP-A', 'nama_mapel' => 'Basis Data', 'kkm' => 90]);
        $this->assertSame('Tidak Lulus', Nilai::first()->keterangan);
        $this->delete('/nilai/' . Nilai::first()->id)->assertRedirect('/nilai');
        $this->assertDatabaseCount('nilais', 0);
    }

    public function test_deleting_siswa_removes_their_nilai(): void
    {
        $this->actingAs($this->admin());
        $s = $this->siswa();
        $m = $this->mapel();
        Nilai::create(['siswa_id' => $s->id, 'mapel_id' => $m->id, 'nilai_tugas' => 1, 'nilai_uts' => 1, 'nilai_uas' => 1, 'nilai_akhir' => 1, 'keterangan' => 'Tidak Lulus']);
        $this->delete("/siswa/{$s->id}");
        $this->assertDatabaseCount('nilais', 0);
    }

    // ---------- SEARCH
    public function test_search(): void
    {
        $this->actingAs($this->admin());
        $this->siswa(null, '1');
        Siswa::create(['nis' => '2', 'nama' => 'Citra Lestari', 'jenis_kelamin' => 'P', 'kelas_id' => Kelas::first()->id]);
        $this->get('/siswa?q=Citra')->assertOk()->assertSee('Citra Lestari')->assertDontSee('Budi');
        $this->get('/kelas?q=RPL')->assertOk()->assertSee('XI RPL 1');
        $this->get('/kelas?q=zzzz')->assertOk()->assertSee('Belum ada data kelas');
        // form pencarian navbar mengarah ke modul yang sedang dibuka
        $this->get('/guru')->assertSee('action="' . route('guru.index') . '"', false);
    }

    public function test_seeder_creates_admin_with_hashed_password_and_reference_data(): void
    {
        $this->seed();
        $u = User::where('email', 'admin@admin.com')->firstOrFail();
        $this->assertNotSame('password', $u->password);
        $this->assertTrue(password_verify('password', $u->password));
        $this->assertSame(3, Siswa::count());
        $this->assertEquals(86.80, (float) Nilai::where('siswa_id', Siswa::where('nis', '2026002')->first()->id)->first()->nilai_akhir);
        $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'password'])->assertRedirect('/');
    }
}
