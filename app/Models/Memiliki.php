<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Memiliki extends Model
{
    protected $table = 'MEMILIKI';

    /**
     * Pivot table (M:N) tanpa primary key.
     */
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'ID_KEPERLUAN',
        'ID_KUNJUNGAN',
    ];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'ID_KUNJUNGAN', 'ID_KUNJUNGAN');
    }

    public function keperluan(): BelongsTo
    {
        return $this->belongsTo(Keperluan::class, 'ID_KEPERLUAN', 'ID_KEPERLUAN');
    }
}
