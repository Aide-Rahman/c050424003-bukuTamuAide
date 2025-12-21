<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $data = Pegawai::query()
            ->with('unit')
            ->orderBy('NAMA_PEGAWAI')
            ->paginate($perPage);

        return response()->json($data);
    }

    public function show(string $NIK)
    {
        $pegawai = Pegawai::query()
            ->with(['unit', 'kunjungan'])
            ->where('NIK', $NIK)
            ->firstOrFail();

        return response()->json($pegawai);
    }
}
