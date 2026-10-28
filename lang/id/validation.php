<?php

return [
    'between' => [
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
    ],
    'email' => ':attribute harus berupa alamat email yang valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'max' => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'numeric' => ':attribute harus berupa angka.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah digunakan.',

    'custom' => [
        'mapel_id' => [
            'unique' => 'Nilai untuk siswa dan mata pelajaran ini sudah ada. Silakan edit data yang sudah ada.',
        ],
    ],

    'attributes' => [
        'email' => 'Email',
        'password' => 'Kata sandi',
        'nis' => 'NIS',
        'nip' => 'NIP',
        'nama' => 'Nama',
        'jenis_kelamin' => 'Jenis kelamin',
        'alamat' => 'Alamat',
        'no_hp' => 'No HP',
        'kelas_id' => 'Kelas',
        'nama_kelas' => 'Nama kelas',
        'tingkat' => 'Tingkat',
        'jurusan' => 'Jurusan',
        'tahun_ajaran' => 'Tahun ajaran',
        'kode_mapel' => 'Kode mapel',
        'nama_mapel' => 'Nama mata pelajaran',
        'kkm' => 'KKM',
        'siswa_id' => 'Siswa',
        'mapel_id' => 'Mata pelajaran',
        'nilai_tugas' => 'Nilai tugas',
        'nilai_uts' => 'Nilai UTS',
        'nilai_uas' => 'Nilai UAS',
    ],
];
