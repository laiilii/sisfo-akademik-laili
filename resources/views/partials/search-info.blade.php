@if (!empty($q))
    <div class="alert alert-info py-2 small d-flex justify-content-between align-items-center shadow-sm">
        <span><i class="fas fa-search mr-1"></i> Hasil pencarian untuk <strong>"{{ $q }}"</strong>
            ({{ $count }} data ditemukan)</span>
        <a href="{{ $resetUrl }}" class="font-weight-bold">Reset pencarian</a>
    </div>
@endif
