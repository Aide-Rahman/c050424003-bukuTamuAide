<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tamu extends Model
{
    protected $table = 'TAMU';
    protected $primaryKey = 'ID_TAMU';

    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_TAMU',
        'NAMA_TAMU',
        'INSTANSI',
        'NO_HP',
        'EMAIL',
        'NO_KTP',
    ];

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'ID_TAMU', 'ID_TAMU');
    }
}
