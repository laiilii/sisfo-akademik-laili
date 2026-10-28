@php $s = $siswa ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nis" class="font-weight-bold">NIS <span class="text-danger">*</span></label>
        <input type="text" id="nis" name="nis" value="{{ old('nis', $s?->nis) }}" maxlength="20"
            class="form-control @error('nis') is-invalid @enderror" placeholder="Nomor Induk Siswa" required>
        @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-8">
        <label for="nama" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $s?->nama) }}"
            class="form-control @error('nama') is-invalid @enderror" placeholder="Nama lengkap siswa" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="kelas_id" class="font-weight-bold">Kelas <span class="text-danger">*</span></label>
        <select id="kelas_id" name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id }}" @selected(old('kelas_id', $s?->kelas_id) == $k->id)>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
        @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="jenis_kelamin" class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $s?->jenis_kelamin) == 'L')>Laki-Laki</option>
            <option value="P" @selected(old('jenis_kelamin', $s?->jenis_kelamin) == 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="no_hp" class="font-weight-bold">No HP</label>
        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $s?->no_hp) }}" maxlength="20"
            class="form-control @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-group">
    <label for="alamat" class="font-weight-bold">Alamat</label>
    <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
        placeholder="Alamat lengkap">{{ old('alamat', $s?->alamat) }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
