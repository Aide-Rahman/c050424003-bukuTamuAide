<?php

namespace App\Support;

use App\Models\Kunjungan;
use App\Models\Tamu;
use Illuminate\Support\Facades\DB;

class IdGenerator
{
    /**
     * Generate ID_KUNJUNGAN format: V001, V002, ...
     * Panggil di dalam DB::transaction() agar lockForUpdate efektif.
     */
    public static function nextKunjunganId(): string
    {
        $table = (new Kunjungan())->getTable();

        $row = DB::table($table)
            ->select('ID_KUNJUNGAN')
            ->where('ID_KUNJUNGAN', 'like', 'V%')
            ->orderByRaw('CAST(SUBSTRING(ID_KUNJUNGAN, 2) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        $last = 0;
        if ($row && isset($row->ID_KUNJUNGAN)) {
            $digits = preg_replace('/\D+/', '', (string) $row->ID_KUNJUNGAN);
            $last = (int) ($digits === '' ? 0 : $digits);
        }

        $next = $last + 1;

        return 'V' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate ID_TAMU format: T01, T02, ...
     * Catatan: jika ID_TAMU CHAR(3), kapasitas praktis maksimal T99.
     * Panggil di dalam DB::transaction() agar lockForUpdate efektif.
     */
    public static function nextTamuId(): string
    {
        $table = (new Tamu())->getTable();

        $row = DB::table($table)
            ->select('ID_TAMU')
            ->where('ID_TAMU', 'like', 'T%')
            ->orderByRaw('CAST(SUBSTRING(ID_TAMU, 2) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        $last = 0;
        if ($row && isset($row->ID_TAMU)) {
            $digits = preg_replace('/\D+/', '', (string) $row->ID_TAMU);
            $last = (int) ($digits === '' ? 0 : $digits);
        }

        $next = $last + 1;

        if ($next > 99) {
            throw new \RuntimeException('ID_TAMU melebihi kapasitas format T01..T99.');
        }

        return 'T' . str_pad((string) $next, 2, '0', STR_PAD_LEFT);
    }
}
