@extends('layouts.main')

@section('title', 'Data Guru')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chalkboard-teacher mr-2 text-primary"></i>Data Guru</h1>
        <a href="{{ route('guru.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Guru
        </a>
    </div>

    @include('partials.search-info', ['count' => $gurus->count(), 'resetUrl' => route('guru.index')])

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Guru / Tenaga Pendidik</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $index => $guru)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge badge-secondary">{{ $guru->nip }}</span></td>
                                <td class="font-weight-bold">{{ $guru->nama }}</td>
                                <td>
                                    <span class="badge badge-{{ $guru->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                        {{ $guru->gender_label }}
                                    </span>
                                </td>
                                <td>{{ $guru->email ?? '-' }}</td>
                                <td>{{ $guru->no_hp ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('guru.edit', $guru->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
