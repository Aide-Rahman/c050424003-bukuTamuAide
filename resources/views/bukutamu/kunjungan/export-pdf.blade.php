<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export Kunjungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 16px; margin: 0 0 8px 0; }
        .muted { color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: top; }
        th { background: #f1f5f9; text-align: left; }
    </style>
</head>
<body>
    <h1>Data Kunjungan</h1>
    <div class="muted">
        Filter:
        Status={{ $filters['status'] ?: '-' }},
        From={{ $filters['from'] ?: '-' }},
        To={{ $filters['to'] ?: '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">ID</th>
                <th style="width: 26%">Tamu</th>
                <th style="width: 14%">Tanggal</th>
                <th style="width: 14%">Jam Masuk</th>
                <th style="width: 14%">Jam Keluar</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $k)
                <tr>
                    <td>{{ $k->ID_KUNJUNGAN }}</td>
                    <td>{{ optional($k->tamu)->NAMA_TAMU ?? '-' }}</td>
                    <td>{{ $k->TANGGAL_KUNJUNGAN }}</td>
                    <td>{{ $k->JAM_MASUK }}</td>
                    <td>{{ $k->JAM_KELUAR }}</td>
                    <td>{{ $k->STATUS_KUNJUNGAN }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
