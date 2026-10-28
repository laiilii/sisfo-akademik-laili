@extends('layouts.main')

@section('title', 'Data Siswa')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate mr-2 text-primary"></i>Data Siswa</h1>
        <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Siswa
        </a>
    </div>

    @include('partials.search-info', ['count' => $siswas->count(), 'resetUrl' => route('siswa.index')])

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa Terdaftar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas</th>
                            <th>No HP</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $index => $siswa)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="badge badge-secondary">{{ $siswa->nis }}</span></td>
                                <td class="font-weight-bold">{{ $siswa->nama }}</td>
                                <td>
                                    <span class="badge badge-{{ $siswa->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                        {{ $siswa->gender_label }}
                                    </span>
                                </td>
                                <td><span class="badge badge-info">{{ $siswa->kelas->nama_kelas ?? '-' }}</span></td>
                                <td>{{ $siswa->no_hp ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('siswa.show', $siswa->id) }}" class="btn btn-sm btn-info"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Nilai milik siswa ini juga akan terhapus.')">
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
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
