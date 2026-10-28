@extends('layouts.main')

@section('title', 'Data Kelas')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-door-open mr-2 text-primary"></i>Data Kelas</h1>
        <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kelas
        </a>
    </div>

    @include('partials.search-info', ['count' => $kelas->count(), 'resetUrl' => route('kelas.index')])

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kelas SISFO Akademik</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Tahun Ajaran</th>
                            <th>Jumlah Siswa</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold text-primary">{{ $item->nama_kelas }}</td>
                                <td><span class="badge badge-info">{{ $item->tingkat }}</span></td>
                                <td>{{ $item->jurusan }}</td>
                                <td>{{ $item->tahun_ajaran }}</td>
                                <td>
                                    <span class="badge badge-secondary p-2"><i class="fas fa-user-graduate mr-1"></i>
                                        {{ $item->siswas_count }} Siswa</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kelas.destroy', $item->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
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
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
