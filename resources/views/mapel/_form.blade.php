@php $m = $mapel ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="kode_mapel" class="font-weight-bold">Kode Mapel <span class="text-danger">*</span></label>
        <input type="text" id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel', $m?->kode_mapel) }}"
            class="form-control @error('kode_mapel') is-invalid @enderror" placeholder="Contoh: MP-PWPB" maxlength="50"
            required>
        @error('kode_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-5">
        <label for="nama_mapel" class="font-weight-bold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
        <input type="text" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel', $m?->nama_mapel) }}"
            class="form-control @error('nama_mapel') is-invalid @enderror" placeholder="Nama mata pelajaran" required>
        @error('nama_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-3">
        <label for="kkm" class="font-weight-bold">KKM Minimum <span class="text-danger">*</span></label>
        <input type="number" id="kkm" name="kkm" value="{{ old('kkm', $m?->kkm ?? 75) }}" min="0" max="100"
            class="form-control @error('kkm') is-invalid @enderror" required>
        @error('kkm') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
