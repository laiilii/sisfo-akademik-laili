<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'keterangan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public const BOBOT_TUGAS = 0.3;
    public const BOBOT_UTS = 0.3;
    public const BOBOT_UAS = 0.4;

    /**
     * Nilai Akhir = (Tugas x 30%) + (UTS x 30%) + (UAS x 40%).
     */
    public static function hitungNilaiAkhir(float $tugas, float $uts, float $uas): float
    {
        return round(
            ($tugas * self::BOBOT_TUGAS) + ($uts * self::BOBOT_UTS) + ($uas * self::BOBOT_UAS),
            2
        );
    }

    /**
     * Status kelulusan berdasarkan KKM mata pelajaran.
     */
    public static function tentukanKeterangan(float $nilaiAkhir, int $kkm): string
    {
        return $nilaiAkhir >= $kkm ? 'Lulus' : 'Tidak Lulus';
    }
}
