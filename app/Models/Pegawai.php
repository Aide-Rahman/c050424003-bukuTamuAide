<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pegawai extends Model
{
    protected $table = 'PEGAWAI';
    protected $primaryKey = 'NIK';

    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'NIK',
        'ID_UNIT',
        'NAMA_PEGAWAI',
        'JABATAN',
        'EMAIL',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'ID_UNIT', 'ID_UNIT');
    }

    public function kunjungan(): BelongsToMany
    {
        return $this->belongsToMany(Kunjungan::class, 'MENEMUI', 'NIK', 'ID_KUNJUNGAN');
    }
}
