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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller
{
    private static function getFilters(Request $request): array
    {
        return [
            'q' => $request->query('q'),
            'status' => $request->query('status'),
            'from' => $request->query('from'),
            'to' => $request->query('to'),
        ];
    }

    private static function getKunjunganPerUnit(array $filters)
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $status = trim((string) ($filters['status'] ?? ''));
        $from = trim((string) ($filters['from'] ?? ''));
        $to = trim((string) ($filters['to'] ?? ''));

        // Agregasi per unit relatif berat; cache singkat agar refresh UI cepat.
        $kunjunganPerUnitCacheKey = 'kunjungan_per_unit:' . md5(json_encode([$q, $status, $from, $to]));

        return Cache::remember($kunjunganPerUnitCacheKey, now()->addSeconds(60), function () use ($q, $status, $from, $to) {
            // Filter KUNJUNGAN lebih dulu (memanfaatkan index), baru join ke MENEMUI/PEGAWAI/UNIT.
            $filteredKunjungan = DB::table('KUNJUNGAN')->select('KUNJUNGAN.ID_KUNJUNGAN');

            if ($q !== '') {
                $filteredKunjungan
                    ->join('TAMU', 'TAMU.ID_TAMU', '=', 'KUNJUNGAN.ID_TAMU')
                    ->where('TAMU.NAMA_TAMU', 'like', '%' . $q . '%');
            }

            if ($status !== '') {
                $filteredKunjungan->where('STATUS_KUNJUNGAN', $status);
            }
            if ($from !== '') {
                $filteredKunjungan->where('TANGGAL_KUNJUNGAN', '>=', $from);
            }
            if ($to !== '') {
                $filteredKunjungan->where('TANGGAL_KUNJUNGAN', '<=', $to);
            }

            return DB::table('MENEMUI')
                ->joinSub($filteredKunjungan, 'K', function ($join) {
                    $join->on('K.ID_KUNJUNGAN', '=', 'MENEMUI.ID_KUNJUNGAN');
                })
                ->join('PEGAWAI', 'PEGAWAI.NIK', '=', 'MENEMUI.NIK')
                ->join('UNIT', 'UNIT.ID_UNIT', '=', 'PEGAWAI.ID_UNIT')
                ->select(
                    'UNIT.ID_UNIT as ID_UNIT',
                    'UNIT.NAMA_UNIT as NAMA_UNIT',
                    DB::raw('COUNT(DISTINCT MENEMUI.ID_KUNJUNGAN) as total')
                )
                ->groupBy('UNIT.ID_UNIT', 'UNIT.NAMA_UNIT')
                ->orderByDesc('total')
                ->orderBy('UNIT.NAMA_UNIT')
                ->get();
        });
    }

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

    private static function normalizeTimeInput(?string $hi): ?string
    {
        // Kolom DB bertipe TIME: simpan sebagai string tanpa parsing Carbon / timezone.
        // Input dari form divalidasi H:i, dan kita normalisasi menjadi H:i:s.
        return self::hiToTime($hi);
    }

    public function index(Request $request)
    {
        $filters = self::getFilters($request);

        $perPage = (int) $request->query('per_page', 5);
        $perPage = max(1, min(100, $perPage));

        // Index/table hanya butuh data tamu + beberapa kolom kunjungan.
        // Hindari eager-load relasi many-to-many (pegawai/keperluan) untuk mempercepat.
        $kunjungans = Kunjungan::query()
            ->forIndex()
            ->filter($filters)
            ->orderByDesc('TANGGAL_KUNJUNGAN')
            ->orderByDesc('ID_KUNJUNGAN')
            ->paginate($perPage)
            ->appends($request->query());

        $latestKunjungan = Kunjungan::query()
            ->with(['tamu:ID_TAMU,NAMA_TAMU,INSTANSI'])
            ->select([
                'ID_KUNJUNGAN',
                'ID_TAMU',
                'TANGGAL_KUNJUNGAN',
                'JAM_MASUK',
                'JAM_KELUAR',
                'STATUS_KUNJUNGAN',
            ])
            ->orderByDesc('TANGGAL_KUNJUNGAN')
            ->orderByDesc('ID_KUNJUNGAN')
            ->first();

        if ($request->ajax()) {
            return view('bukutamu.kunjungan.table', [
                'kunjungans' => $kunjungans,
            ])->render();
        }

        $today = now()->toDateString();

        // Gabungkan statistik menjadi 1 query.
        $stats = Kunjungan::query()
            ->selectRaw('SUM(CASE WHEN TANGGAL_KUNJUNGAN = ? THEN 1 ELSE 0 END) as totalHariIni', [$today])
            ->selectRaw("SUM(CASE WHEN STATUS_KUNJUNGAN = 'Aktif' THEN 1 ELSE 0 END) as totalAktif")
            ->selectRaw("SUM(CASE WHEN TANGGAL_KUNJUNGAN = ? AND STATUS_KUNJUNGAN = 'Selesai' THEN 1 ELSE 0 END) as totalSelesaiHariIni", [$today])
            ->first();

        $totalHariIni = (int) ($stats->totalHariIni ?? 0);
        $totalAktif = (int) ($stats->totalAktif ?? 0);
        $totalSelesaiHariIni = (int) ($stats->totalSelesaiHariIni ?? 0);

        // Untuk render HTML: per-unit di-load async agar halaman lebih cepat.
        // Untuk API/JSON: tetap kirim datanya.
        $kunjunganPerUnit = $request->wantsJson() ? self::getKunjunganPerUnit($filters) : [];

        if ($request->wantsJson()) {
            return response()->json([
                'latest' => $latestKunjungan,
                'data' => $kunjungans,
                'kunjunganPerUnit' => $kunjunganPerUnit,
                'stats' => [
                    'today' => $today,
                    'totalHariIni' => $totalHariIni,
                    'totalAktif' => $totalAktif,
                    'totalSelesaiHariIni' => $totalSelesaiHariIni,
                ],
            ]);
        }

        return view('bukutamu.kunjungan.index', [
            'kunjungans' => $kunjungans,
            'latestKunjungan' => $latestKunjungan,
            'today' => $today,
            'totalHariIni' => $totalHariIni,
            'totalAktif' => $totalAktif,
            'totalSelesaiHariIni' => $totalSelesaiHariIni,
            'kunjunganPerUnit' => $kunjunganPerUnit,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $filters = self::getFilters($request);

        // Jangan `get()` semua data untuk export: stream per chunk agar cepat & hemat memori.
        $query = Kunjungan::query()
            ->filter($filters)
            ->with(['tamu:ID_TAMU,NAMA_TAMU'])
            ->select([
                'ID_KUNJUNGAN',
                'ID_TAMU',
                'TANGGAL_KUNJUNGAN',
                'JAM_MASUK',
                'JAM_KELUAR',
                'STATUS_KUNJUNGAN',
            ])
            ->orderByDesc('TANGGAL_KUNJUNGAN')
            ->orderByDesc('ID_KUNJUNGAN');

        $fileName = 'kunjungan-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM agar Excel Windows lebih aman
            fprintf($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID_KUNJUNGAN',
                'NAMA_TAMU',
                'TANGGAL_KUNJUNGAN',
                'JAM_MASUK',
                'JAM_KELUAR',
                'STATUS_KUNJUNGAN',
            ]);

            $query->chunk(1000, function ($chunk) use ($out) {
                foreach ($chunk as $k) {
                    fputcsv($out, [
                        $k->ID_KUNJUNGAN,
                        optional($k->tamu)->NAMA_TAMU,
                        $k->TANGGAL_KUNJUNGAN,
                        $k->JAM_MASUK,
                        $k->JAM_KELUAR,
                        $k->STATUS_KUNJUNGAN,
                    ]);
                }
            });

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $filters = self::getFilters($request);

        $rows = Kunjungan::query()
            ->filter($filters)
            ->with(['tamu:ID_TAMU,NAMA_TAMU'])
            ->select([
                'ID_KUNJUNGAN',
                'ID_TAMU',
                'TANGGAL_KUNJUNGAN',
                'JAM_MASUK',
                'JAM_KELUAR',
                'STATUS_KUNJUNGAN',
            ])
            ->orderByDesc('TANGGAL_KUNJUNGAN')
            ->orderByDesc('ID_KUNJUNGAN')
            ->get();

        $data = [
            'rows' => $rows,
            'filters' => [
                'status' => $request->query('status'),
                'from' => $request->query('from'),
                'to' => $request->query('to'),
            ],
        ];

        // Jika dompdf tersedia, hasilkan PDF. Jika belum, fallback ke halaman HTML siap-print.
        if (class_exists(\Dompdf\Dompdf::class)) {
            $html = view('bukutamu.kunjungan.export-pdf', $data)->render();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $fileName = 'kunjungan-' . now()->format('Ymd-His') . '.pdf';

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        }

        return view('bukutamu.kunjungan.export-pdf', $data);
    }

    public function perUnit(Request $request)
    {
        $filters = self::getFilters($request);

        return response()->json([
            'kunjunganPerUnit' => self::getKunjunganPerUnit($filters),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request)
    {
        $tamus = Tamu::query()
            ->select(['ID_TAMU', 'NAMA_TAMU', 'INSTANSI'])
            ->orderBy('NAMA_TAMU')
            ->get();
        $keperluans = Keperluan::query()
            ->select(['ID_KEPERLUAN', 'NAMA_KEPERLUAN', 'KETERANGAN'])
            ->orderBy('NAMA_KEPERLUAN')
            ->get();
        $pegawais = Pegawai::query()
            ->select(['NIK', 'ID_UNIT', 'NAMA_PEGAWAI'])
            ->with(['unit:ID_UNIT,NAMA_UNIT'])
            ->orderBy('NAMA_PEGAWAI')
            ->get();

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

    public function print(string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->with(['tamu', 'pegawai.unit', 'keperluan'])
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        return view('bukutamu.kunjungan.print', [
            'kunjungan' => $kunjungan,
            'printedAt' => now(),
        ]);
    }

    public function end(Request $request, string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        DB::transaction(function () use ($kunjungan) {
            $updates = ['STATUS_KUNJUNGAN' => 'Selesai'];

            // Isi jam keluar hanya jika masih kosong
            if (empty($kunjungan->JAM_KELUAR)) {
                // Gunakan timezone aplikasi (config/app.php) dan simpan sebagai TIME string.
                $updates['JAM_KELUAR'] = now()->format('H:i:s');
            }

            $kunjungan->update($updates);
        });

        if ($request->wantsJson()) {
            return response()->json($kunjungan->fresh());
        }

        return redirect()
            ->route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN)
            ->with('status', 'Kunjungan telah diakhiri.');
    }

    public function edit(Request $request, string $ID_KUNJUNGAN)
    {
        $kunjungan = Kunjungan::query()
            ->with(['pegawai', 'keperluan'])
            ->where('ID_KUNJUNGAN', $ID_KUNJUNGAN)
            ->firstOrFail();

        $tamus = Tamu::query()
            ->select(['ID_TAMU', 'NAMA_TAMU', 'INSTANSI'])
            ->orderBy('NAMA_TAMU')
            ->get();
        $keperluans = Keperluan::query()
            ->select(['ID_KEPERLUAN', 'NAMA_KEPERLUAN', 'KETERANGAN'])
            ->orderBy('NAMA_KEPERLUAN')
            ->get();
        $pegawais = Pegawai::query()
            ->select(['NIK', 'ID_UNIT', 'NAMA_PEGAWAI'])
            ->with(['unit:ID_UNIT,NAMA_UNIT'])
            ->orderBy('NAMA_PEGAWAI')
            ->get();

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

            $jamMasuk = self::normalizeTimeInput($validated['JAM_MASUK'] ?? null);

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
            $jamMasuk = self::normalizeTimeInput($validated['JAM_MASUK'] ?? null);
            $jamKeluar = self::normalizeTimeInput($validated['JAM_KELUAR'] ?? null);
            $status = $validated['STATUS_KUNJUNGAN'];

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
