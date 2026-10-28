<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $mapels = Mapel::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('kode_mapel', 'like', "%{$q}%")
                        ->orWhere('nama_mapel', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->get();

        return view('mapel.index', compact('mapels', 'q'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        Mapel::create($this->validated($request));

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $mapel->update($this->validated($request, $mapel));

        // Jika KKM berubah, status kelulusan nilai yang sudah ada ikut disesuaikan.
        if ($mapel->wasChanged('kkm')) {
            $mapel->nilais()->where('nilai_akhir', '>=', $mapel->kkm)->update(['keterangan' => 'Lulus']);
            $mapel->nilais()->where('nilai_akhir', '<', $mapel->kkm)->update(['keterangan' => 'Tidak Lulus']);
        }

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        if ($mapel->nilais()->exists()) {
            return redirect()->route('mapel.index')
                ->with('error', "Mata pelajaran {$mapel->nama_mapel} tidak dapat dihapus karena sudah memiliki data nilai.");
        }

        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
    }

    private function validated(Request $request, ?Mapel $mapel = null): array
    {
        return $request->validate([
            'kode_mapel' => ['required', 'string', 'max:50', Rule::unique('mapels', 'kode_mapel')->ignore($mapel?->id)],
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);
    }
}
