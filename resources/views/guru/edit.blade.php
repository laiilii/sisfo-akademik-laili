@extends('layouts.main')

@section('title', 'Edit Data Guru')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chalkboard-teacher mr-2 text-primary"></i>Edit Data Guru</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Edit Guru</h6></div>
        <div class="card-body">
            <form action="{{ route('guru.update', $guru->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('guru._form')
                @include('partials.form-actions', ['cancel' => route('guru.index'), 'label' => 'Simpan Perubahan'])
            </form>
        </div>
    </div>
@endsection
