<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $kelas = Kelas::withCount('siswas')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama_kelas', 'like', "%{$q}%")
                        ->orWhere('tingkat', 'like', "%{$q}%")
                        ->orWhere('jurusan', 'like', "%{$q}%")
                        ->orWhere('tahun_ajaran', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->get();

        return view('kelas.index', compact('kelas', 'q'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        Kelas::create($this->validated($request));

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        return view('kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $kela->update($this->validated($request, $kela));

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->siswas()->exists()) {
            return redirect()->route('kelas.index')
                ->with('error', "Kelas {$kela->nama_kelas} tidak dapat dihapus karena masih memiliki siswa.");
        }

        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }

    private function validated(Request $request, ?Kelas $kelas = null): array
    {
        return $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas', 'nama_kelas')->ignore($kelas?->id)],
            'tingkat' => ['required', Rule::in(['X', 'XI', 'XII'])],
            'jurusan' => ['required', 'string', 'max:255'],
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
        ], [
            'tahun_ajaran.regex' => 'Format tahun ajaran harus seperti 2025/2026.',
        ]);
    }
}
