<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menemui extends Model
{
    protected $table = 'MENEMUI';

    /**
     * Pivot table (M:N) tanpa primary key.
     */
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'ID_KUNJUNGAN',
        'NIK',
    ];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'ID_KUNJUNGAN', 'ID_KUNJUNGAN');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'NIK', 'NIK');
    }
}
