<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $table = 'UNIT';
    protected $primaryKey = 'ID_UNIT';

    public $timestamps = false;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ID_UNIT',
        'NAMA_UNIT',
        'LOKASI_UNIT',
    ];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'ID_UNIT', 'ID_UNIT');
    }
}
