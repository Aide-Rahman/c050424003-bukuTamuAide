<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKunjunganRequest;
use App\Http\Requests\UpdateKunjunganRequest;
use App\Models\Keperluan;
use App\Models\Kunjungan;
use App\Models\Pegawai;
use App\Models\Tamu;
use App\Support\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller
{
    private static function timeToHi(?string $time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        // DB TIME biasanya "HH:MM:SS"; input type="time" butuh "HH:MM".
        return strlen($time) >= 5 ? substr($time, 0, 5) : $time;
    }

    private static function hiToTime(?string $hi): ?string
    {
        if ($hi === null || $hi === '') {
            return null;
        }

        // Sudah H:i:s
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $hi)) {
            return $hi;
        }

        // H:i -> H:i:s
        if (preg_match('/^\d{2}:\d{2}$/', $hi)) {
            return $hi . ':00';
        }

        return $hi;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $kunjungans = Kunjungan::query()
            ->with(['tamu', 'pegawai', 'keperluan'])
            ->orderByDesc('TANGGAL_KUNJUNGAN')
            ->orderByDesc('ID_KUNJUNGAN')
            ->paginate($perPage);

        if ($request->wantsJson()) {
            return response()->json($kunjungans);
        }

        return view('bukutamu.kunjungan.index', [
            'kunjungans' => $kunjungans,
        ]);
    }

    public function create(Request $request)
    {
        $tamus = Tamu::query()->orderBy('NAMA_TAMU')->get();
        $keperluans = Keperluan::query()->orderBy('NAMA_KEPERLUAN')->get();
        $pegawais = Pegawai::query()->with('unit')->orderBy('NAMA_PEGAWAI')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'tamus' => $tamus,
                'keperluans' => $keperluans,
                'pegawais' => $pegawais,
            ]);
        }

        return view('bukutamu.kunjungan.create', [
            'tamus' => $tamus,
            'keperluans' => $keperluans,
            'pegawais' => $pegawais,
        ]);
    }

    public function show(string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->with(['tamu', 'pegawai', 'keperluan'])
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        if (request()->wantsJson()) {
            return response()->json($kunjungan);
        }

        return view('bukutamu.kunjungan.show', [
            'kunjungan' => $kunjungan,
        ]);
    }

    public function edit(Request $request, string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->with(['pegawai', 'keperluan'])
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        $tamus = Tamu::query()->orderBy('NAMA_TAMU')->get();
        $keperluans = Keperluan::query()->orderBy('NAMA_KEPERLUAN')->get();
        $pegawais = Pegawai::query()->with('unit')->orderBy('NAMA_PEGAWAI')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'kunjungan' => $kunjungan,
                'tamus' => $tamus,
                'keperluans' => $keperluans,
                'pegawais' => $pegawais,
            ]);
        }

        return view('bukutamu.kunjungan.edit', [
            'kunjungan' => $kunjungan,
            'tamus' => $tamus,
            'keperluans' => $keperluans,
            'pegawais' => $pegawais,
            'JAM_MASUK_HI' => self::timeToHi($kunjungan->JAM_MASUK),
            'JAM_KELUAR_HI' => self::timeToHi($kunjungan->JAM_KELUAR),
        ]);
    }

    public function store(StoreKunjunganRequest $request)
    {
        $validated = $request->validated();

        $kunjungan = DB::transaction(function () use ($validated) {
            // Tentukan ID_TAMU: existing atau buat baru
            if (($validated['TAMU_MODE'] ?? 'existing') === 'new') {
                $newIdTamu = IdGenerator::nextTamuId();

                $tamu = Tamu::query()->create([
                    'ID_TAMU' => $newIdTamu,
                    'NAMA_TAMU' => $validated['NAMA_TAMU'],
                    'INSTANSI' => $validated['INSTANSI'],
                    'NO_HP' => $validated['NO_HP'],
                    'EMAIL' => $validated['EMAIL'],
                    'NO_KTP' => $validated['NO_KTP'],
                ]);

                $idTamu = $tamu->ID_TAMU;
            } else {
                $idTamu = $validated['ID_TAMU'];
            }

            $newIdKunjungan = IdGenerator::nextKunjunganId();

            $jamMasuk = self::hiToTime($validated['JAM_MASUK'] ?? null);

            $kunjungan = Kunjungan::query()->create([
                'ID_KUNJUNGAN' => $newIdKunjungan,
                'ID_TAMU' => $idTamu,
                'TANGGAL_KUNJUNGAN' => $validated['TANGGAL_KUNJUNGAN'],
                'JAM_MASUK' => $jamMasuk,
                'JAM_KELUAR' => null, // create: boleh kosong
                'STATUS_KUNJUNGAN' => 'Aktif', // create: default
                'CATATAN' => $validated['CATATAN'] ?? null,
            ]);

            $kunjungan->keperluan()->sync($validated['ID_KEPERLUAN']);
            $kunjungan->pegawai()->sync($validated['NIK']);

            return $kunjungan->load(['tamu', 'pegawai', 'keperluan']);
        });

        if ($request->wantsJson()) {
            return response()->json($kunjungan, 201);
        }

        return redirect()
            ->route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN)
            ->with('status', 'Kunjungan berhasil dibuat.');
    }

    public function update(UpdateKunjunganRequest $request, string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        $validated = $request->validated();

        $updated = DB::transaction(function () use ($kunjungan, $validated) {
            $jamMasuk = self::hiToTime($validated['JAM_MASUK'] ?? null);
            $jamKeluar = self::hiToTime($validated['JAM_KELUAR'] ?? null);
            $status = $jamKeluar ? 'Selesai' : $validated['STATUS_KUNJUNGAN'];

            $kunjungan->update([
                'ID_TAMU' => $validated['ID_TAMU'],
                'TANGGAL_KUNJUNGAN' => $validated['TANGGAL_KUNJUNGAN'],
                'JAM_MASUK' => $jamMasuk,
                'JAM_KELUAR' => $jamKeluar,
                'STATUS_KUNJUNGAN' => $status,
                'CATATAN' => $validated['CATATAN'] ?? null,
            ]);

            $kunjungan->keperluan()->sync($validated['ID_KEPERLUAN']);
            $kunjungan->pegawai()->sync($validated['NIK']);

            return $kunjungan->load(['tamu', 'pegawai', 'keperluan']);
        });

        if ($request->wantsJson()) {
            return response()->json($updated);
        }

        return redirect()
            ->route('bukutamu.kunjungan.show', $updated->ID_KUNJUNGAN)
            ->with('status', 'Kunjungan berhasil diupdate.');
    }

    public function destroy(string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        DB::transaction(function () use ($kunjungan) {
            $kunjungan->keperluan()->detach();
            $kunjungan->pegawai()->detach();
            $kunjungan->delete();
        });

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Deleted']);
        }

        return redirect()
            ->route('bukutamu.kunjungan.index')
            ->with('status', 'Kunjungan berhasil dihapus.');
    }
}
