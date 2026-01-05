<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // Kolom JAM_MASUK & JAM_KELUAR bertipe TIME (bukan DATETIME) => simpan & baca sebagai string tanpa konversi timezone.
    protected $casts = [
        'TANGGAL_KUNJUNGAN' => 'string',
        'JAM_MASUK' => 'string',
        'JAM_KELUAR' => 'string',
        'STATUS_KUNJUNGAN' => 'string',
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

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $q = trim((string) ($filters['q'] ?? ''));
        if ($q !== '') {
            $query->whereHas('tamu', function (Builder $tamuQuery) use ($q) {
                $tamuQuery->where('NAMA_TAMU', 'like', '%' . $q . '%');
            });
        }

        $status = trim((string) ($filters['status'] ?? ''));
        if ($status !== '') {
            $query->where('STATUS_KUNJUNGAN', $status);
        }

        $from = trim((string) ($filters['from'] ?? ''));
        if ($from !== '') {
            $query->where('TANGGAL_KUNJUNGAN', '>=', $from);
        }

        $to = trim((string) ($filters['to'] ?? ''));
        if ($to !== '') {
            $query->where('TANGGAL_KUNJUNGAN', '<=', $to);
        }

        return $query;
    }

    public function scopeForIndex(Builder $query): Builder
    {
        return $query
            ->with(['tamu:ID_TAMU,NAMA_TAMU,INSTANSI'])
            ->select([
                'ID_KUNJUNGAN',
                'ID_TAMU',
                'TANGGAL_KUNJUNGAN',
                'JAM_MASUK',
                'JAM_KELUAR',
                'STATUS_KUNJUNGAN',
            ]);
    }
}
