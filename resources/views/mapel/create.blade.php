@extends('layouts.main')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-book mr-2 text-primary"></i>Tambah Mata Pelajaran</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Tambah Mata Pelajaran</h6></div>
        <div class="card-body">
            <form action="{{ route('mapel.store') }}" method="POST">
                @csrf
                @include('mapel._form')
                @include('partials.form-actions', ['cancel' => route('mapel.index')])
            </form>
        </div>
    </div>
@endsection
