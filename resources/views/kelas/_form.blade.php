@php $k = $kelas ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="nama_kelas" class="font-weight-bold">Nama Kelas <span class="text-danger">*</span></label>
        <input type="text" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $k?->nama_kelas) }}"
            class="form-control @error('nama_kelas') is-invalid @enderror" placeholder="Contoh: XI RPL 1" required>
        @error('nama_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tingkat" class="font-weight-bold">Tingkat <span class="text-danger">*</span></label>
        <select id="tingkat" name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
            <option value="">-- Pilih Tingkat --</option>
            @foreach (['X', 'XI', 'XII'] as $t)
                <option value="{{ $t }}" @selected(old('tingkat', $k?->tingkat) == $t)>{{ $t }}</option>
            @endforeach
        </select>
        @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="jurusan" class="font-weight-bold">Jurusan <span class="text-danger">*</span></label>
        <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $k?->jurusan) }}"
            class="form-control @error('jurusan') is-invalid @enderror" placeholder="Contoh: Rekayasa Perangkat Lunak"
            required>
        @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="tahun_ajaran" class="font-weight-bold">Tahun Ajaran <span class="text-danger">*</span></label>
        <input type="text" id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $k?->tahun_ajaran) }}"
            class="form-control @error('tahun_ajaran') is-invalid @enderror" placeholder="Contoh: 2025/2026"
            maxlength="9" required>
        @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
