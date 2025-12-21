<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keperluan extends Model
{
    protected $table = 'KEPERLUAN';
    protected $primaryKey = 'ID_KEPERLUAN';

    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_KEPERLUAN',
        'NAMA_KEPERLUAN',
        'KETERANGAN',
    ];

    public function kunjungan(): BelongsToMany
    {
        return $this->belongsToMany(Kunjungan::class, 'MEMILIKI', 'ID_KEPERLUAN', 'ID_KUNJUNGAN');
    }
}
