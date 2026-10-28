@php $g = $guru ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="nip" class="font-weight-bold">NIP <span class="text-danger">*</span></label>
        <input type="text" id="nip" name="nip" value="{{ old('nip', $g?->nip) }}" maxlength="30"
            class="form-control @error('nip') is-invalid @enderror" placeholder="Nomor Induk Pegawai" required>
        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="nama" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $g?->nama) }}"
            class="form-control @error('nama') is-invalid @enderror" placeholder="Nama lengkap beserta gelar" required>
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="jenis_kelamin" class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $g?->jenis_kelamin) == 'L')>Laki-Laki</option>
            <option value="P" @selected(old('jenis_kelamin', $g?->jenis_kelamin) == 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="email" class="font-weight-bold">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $g?->email) }}"
            class="form-control @error('email') is-invalid @enderror" placeholder="nama@sekolah.sch.id">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="no_hp" class="font-weight-bold">No HP</label>
        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $g?->no_hp) }}" maxlength="20"
            class="form-control @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx">
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-group">
    <label for="alamat" class="font-weight-bold">Alamat</label>
    <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
        placeholder="Alamat lengkap">{{ old('alamat', $g?->alamat) }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
