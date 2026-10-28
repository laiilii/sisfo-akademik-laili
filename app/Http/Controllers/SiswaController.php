<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $siswas = Siswa::with('kelas')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nis', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhereHas('kelas', fn ($k) => $k->where('nama_kelas', 'like', "%{$q}%"));
                });
            })
            ->orderBy('id')
            ->get();

        return view('siswa.index', compact('siswas', 'q'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        Siswa::create($this->validated($request));

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'nilais.mapel']);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($this->validated($request, $siswa));

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        // Nilai milik siswa ikut terhapus (cascade pada foreign key).
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    private function validated(Request $request, ?Siswa $siswa = null): array
    {
        return $request->validate([
            'nis' => ['required', 'string', 'max:20', Rule::unique('siswas', 'nis')->ignore($siswa?->id)],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ], [
            'no_hp.regex' => 'No HP hanya boleh berisi angka, spasi, tanda + atau -.',
        ]);
    }
}
