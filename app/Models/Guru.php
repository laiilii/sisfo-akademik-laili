<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';

    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'email',
        'no_hp',
        'alamat',
    ];

    public function getGenderLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan';
    }
}
