@php $n = $nilai ?? null; @endphp
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="siswa_id" class="font-weight-bold">Siswa <span class="text-danger">*</span></label>
        <select id="siswa_id" name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach ($siswas as $s)
                <option value="{{ $s->id }}" @selected(old('siswa_id', $n?->siswa_id) == $s->id)>
                    {{ $s->nis }} - {{ $s->nama }} ({{ $s->kelas->nama_kelas ?? '-' }})
                </option>
            @endforeach
        </select>
        @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="mapel_id" class="font-weight-bold">Mata Pelajaran <span class="text-danger">*</span></label>
        <select id="mapel_id" name="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
            <option value="">-- Pilih Mata Pelajaran --</option>
            @foreach ($mapels as $m)
                <option value="{{ $m->id }}" data-kkm="{{ $m->kkm }}" @selected(old('mapel_id', $n?->mapel_id) == $m->id)>
                    {{ $m->nama_mapel }} (KKM {{ $m->kkm }})
                </option>
            @endforeach
        </select>
        @error('mapel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nilai_tugas" class="font-weight-bold">Nilai Tugas (30%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_tugas" name="nilai_tugas"
            value="{{ old('nilai_tugas', $n?->nilai_tugas) }}"
            class="form-control nilai-input @error('nilai_tugas') is-invalid @enderror" required>
        @error('nilai_tugas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="nilai_uts" class="font-weight-bold">Nilai UTS (30%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_uts" name="nilai_uts"
            value="{{ old('nilai_uts', $n?->nilai_uts) }}"
            class="form-control nilai-input @error('nilai_uts') is-invalid @enderror" required>
        @error('nilai_uts') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="nilai_uas" class="font-weight-bold">Nilai UAS (40%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" max="100" id="nilai_uas" name="nilai_uas"
            value="{{ old('nilai_uas', $n?->nilai_uas) }}"
            class="form-control nilai-input @error('nilai_uas') is-invalid @enderror" required>
        @error('nilai_uas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="alert alert-light border small mb-0" id="previewBox">
    <strong>Pratinjau:</strong> Nilai Akhir =
    <span class="badge badge-info p-2" id="previewAkhir">-</span>
    <span class="badge badge-secondary p-2" id="previewStatus">-</span>
    <span class="text-muted ml-2">Nilai Akhir = (Tugas &times; 30%) + (UTS &times; 30%) + (UAS &times; 40%). Nilai disimpan
        &amp; dihitung ulang oleh server saat disubmit.</span>
</div>

@push('scripts')
    <script>
        (function () {
            var inputs = document.querySelectorAll('.nilai-input');
            var mapel = document.getElementById('mapel_id');
            var akhir = document.getElementById('previewAkhir');
            var status = document.getElementById('previewStatus');

            function hitung() {
                var v = [].map.call(inputs, function (i) { return parseFloat(i.value); });
                if (v.some(isNaN)) { akhir.textContent = '-'; status.textContent = '-'; status.className = 'badge badge-secondary p-2'; return; }
                var nilai = Math.round((v[0] * 0.3 + v[1] * 0.3 + v[2] * 0.4) * 100) / 100;
                akhir.textContent = nilai.toFixed(2);
                var opt = mapel.options[mapel.selectedIndex];
                var kkm = opt && opt.dataset.kkm ? parseInt(opt.dataset.kkm, 10) : null;
                if (kkm === null) { status.textContent = 'Pilih mapel'; status.className = 'badge badge-secondary p-2'; return; }
                var lulus = nilai >= kkm;
                status.textContent = lulus ? 'Lulus' : 'Tidak Lulus';
                status.className = 'badge p-2 badge-' + (lulus ? 'success' : 'danger');
            }
            [].forEach.call(inputs, function (i) { i.addEventListener('input', hitung); });
            mapel.addEventListener('change', hitung);
            hitung();
        })();
    </script>
@endpush
