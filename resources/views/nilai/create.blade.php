@extends('layouts.main')

@section('title', 'Input Nilai Siswa')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-star mr-2 text-primary"></i>Input Nilai Siswa</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Input Nilai</h6></div>
        <div class="card-body">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                @include('nilai._form')
                @include('partials.form-actions', ['cancel' => route('nilai.index')])
            </form>
        </div>
    </div>
@endsection
