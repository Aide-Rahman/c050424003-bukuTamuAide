<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kunjungan extends Model
{
    protected $table = 'KUNJUNGAN';
    protected $primaryKey = 'ID_KUNJUNGAN';

    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_KUNJUNGAN',
        'ID_TAMU',
        'TANGGAL_KUNJUNGAN',
        'JAM_MASUK',
        'JAM_KELUAR',
        'STATUS_KUNJUNGAN',
        'CATATAN',
    ];

    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'ID_TAMU', 'ID_TAMU');
    }

    public function pegawai(): BelongsToMany
    {
        return $this->belongsToMany(Pegawai::class, 'MENEMUI', 'ID_KUNJUNGAN', 'NIK');
    }

    public function keperluan(): BelongsToMany
    {
        return $this->belongsToMany(Keperluan::class, 'MEMILIKI', 'ID_KUNJUNGAN', 'ID_KEPERLUAN');
    }
}
