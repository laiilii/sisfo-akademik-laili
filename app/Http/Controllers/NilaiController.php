<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $nilais = Nilai::with(['siswa.kelas', 'mapel'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%{$q}%")->orWhere('nis', 'like', "%{$q}%"))
                        ->orWhereHas('mapel', fn ($m) => $m->where('nama_mapel', 'like', "%{$q}%"));
                });
            })
            ->orderBy('id')
            ->get();

        return view('nilai.index', compact('nilais', 'q'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.create', compact('siswas', 'mapels'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Nilai::create($this->withHasil($data));

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil disimpan.');
    }

    public function edit(Nilai $nilai)
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.edit', compact('nilai', 'siswas', 'mapels'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $data = $this->validated($request, $nilai);

        $nilai->update($this->withHasil($data));

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus.');
    }

    private function validated(Request $request, ?Nilai $nilai = null): array
    {
        $siswaId = $request->input('siswa_id');

        return $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'mapel_id' => [
                'required',
                'exists:mapels,id',
                // Satu siswa hanya memiliki satu nilai untuk setiap mata pelajaran.
                Rule::unique('nilais', 'mapel_id')
                    ->where(fn ($q) => $q->where('siswa_id', $siswaId))
                    ->ignore($nilai?->id),
            ],
            'nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ]);
    }

    /**
     * Tambahkan nilai akhir dan keterangan (Lulus / Tidak Lulus) berdasarkan KKM mapel.
     */
    private function withHasil(array $data): array
    {
        $kkm = (int) Mapel::findOrFail($data['mapel_id'])->kkm;

        $akhir = Nilai::hitungNilaiAkhir(
            (float) $data['nilai_tugas'],
            (float) $data['nilai_uts'],
            (float) $data['nilai_uas']
        );

        $data['nilai_akhir'] = $akhir;
        $data['keterangan'] = Nilai::tentukanKeterangan($akhir, $kkm);

        return $data;
    }
}
