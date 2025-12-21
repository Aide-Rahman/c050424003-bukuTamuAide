<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $data = Tamu::query()
            ->orderBy('ID_TAMU')
            ->paginate($perPage);

        return response()->json($data);
    }

    public function show(string $ID_TAMU)
    {
        $tamu = Tamu::query()
            ->with('kunjungan')
            ->where('ID_TAMU', $ID_TAMU)
            ->firstOrFail();

        return response()->json($tamu);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ID_TAMU' => ['required', 'string', 'max:3'],
            'NAMA_TAMU' => ['required', 'string', 'max:50'],
            'INSTANSI' => ['required', 'string', 'max:60'],
            'NO_HP' => ['required', 'string', 'max:15'],
            'EMAIL' => ['required', 'string', 'max:15'],
            'NO_KTP' => ['required', 'string', 'max:16'],
        ]);

        $tamu = Tamu::query()->create($validated);

        return response()->json($tamu, 201);
    }

    public function update(Request $request, string $ID_TAMU)
    {
        $tamu = Tamu::query()
            ->where('ID_TAMU', $ID_TAMU)
            ->firstOrFail();

        $validated = $request->validate([
            'NAMA_TAMU' => ['required', 'string', 'max:50'],
            'INSTANSI' => ['required', 'string', 'max:60'],
            'NO_HP' => ['required', 'string', 'max:15'],
            'EMAIL' => ['required', 'string', 'max:15'],
            'NO_KTP' => ['required', 'string', 'max:16'],
        ]);

        $tamu->update($validated);

        return response()->json($tamu->fresh());
    }

    public function destroy(string $ID_TAMU)
    {
        $tamu = Tamu::query()
            ->where('ID_TAMU', $ID_TAMU)
            ->firstOrFail();

        $tamu->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
