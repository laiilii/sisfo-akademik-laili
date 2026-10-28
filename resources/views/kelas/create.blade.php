@extends('layouts.main')

@section('title', 'Tambah Data Kelas')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-door-open mr-2 text-primary"></i>Tambah Data Kelas</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Tambah Kelas</h6></div>
        <div class="card-body">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                @include('kelas._form')
                @include('partials.form-actions', ['cancel' => route('kelas.index')])
            </form>
        </div>
    </div>
@endsection
