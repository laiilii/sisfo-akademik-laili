@extends('layouts.main')

@section('title', 'Detail Data Siswa')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-graduate mr-2 text-primary"></i>Detail Data Siswa</h1>
        <div>
            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm mr-1"></i> Edit
            </a>
            <a href="{{ route('siswa.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Profil Siswa</h6></div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th width="35%">NIS</th><td><span class="badge badge-secondary">{{ $siswa->nis }}</span></td></tr>
                        <tr><th>Nama</th><td class="font-weight-bold">{{ $siswa->nama }}</td></tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><span class="badge badge-{{ $siswa->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">{{ $siswa->gender_label }}</span></td>
                        </tr>
                        <tr><th>Kelas</th><td><span class="badge badge-info">{{ $siswa->kelas->nama_kelas ?? '-' }}</span></td></tr>
                        <tr><th>Jurusan</th><td>{{ $siswa->kelas->jurusan ?? '-' }}</td></tr>
                        <tr><th>Tahun Ajaran</th><td>{{ $siswa->kelas->tahun_ajaran ?? '-' }}</td></tr>
                        <tr><th>No HP</th><td>{{ $siswa->no_hp ?? '-' }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $siswa->alamat ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Riwayat Nilai Mata Pelajaran</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" width="100%" cellspacing="0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Tugas</th>
                                    <th class="text-center">UTS</th>
                                    <th class="text-center">UAS</th>
                                    <th class="text-center">Nilai Akhir</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswa->nilais as $nilai)
                                    <tr>
                                        <td>{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                                        <td class="text-center">{{ number_format($nilai->nilai_tugas, 2) }}</td>
                                        <td class="text-center">{{ number_format($nilai->nilai_uts, 2) }}</td>
                                        <td class="text-center">{{ number_format($nilai->nilai_uas, 2) }}</td>
                                        <td class="text-center"><span class="badge badge-info p-2">{{ number_format($nilai->nilai_akhir, 2) }}</span></td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $nilai->keterangan == 'Lulus' ? 'success' : 'danger' }} p-2">{{ $nilai->keterangan }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada nilai untuk siswa ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
