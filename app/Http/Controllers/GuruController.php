<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $gurus = Guru::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nip', 'like', "%{$q}%")
                        ->orWhere('nama', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->get();

        return view('guru.index', compact('gurus', 'q'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        Guru::create($this->validated($request));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $guru->update($this->validated($request, $guru));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    private function validated(Request $request, ?Guru $guru = null): array
    {
        return $request->validate([
            'nip' => ['required', 'string', 'max:30', Rule::unique('gurus', 'nip')->ignore($guru?->id)],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ], [
            'no_hp.regex' => 'No HP hanya boleh berisi angka, spasi, tanda + atau -.',
        ]);
    }
}
