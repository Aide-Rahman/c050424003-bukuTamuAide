<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $data = Unit::query()
            ->orderBy('NAMA_UNIT')
            ->paginate($perPage);

        return response()->json($data);
    }

    public function show(string $ID_UNIT)
    {
        $unit = Unit::query()
            ->with('pegawai')
            ->where('ID_UNIT', $ID_UNIT)
            ->firstOrFail();

        return response()->json($unit);
    }
}
