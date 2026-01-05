<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #0a0a0a;
            --muted: #52525b;
            --line: #e5e7eb;
            --paper: #ffffff;
            --chip: #f4f4f5;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.45;
        }

        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 24px;
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 16px;
        }

        .title {
            margin: 0;
            font-size: 18px;
            letter-spacing: -0.02em;
            font-weight: 800;
        }

        .sub {
            margin: 6px 0 0 0;
            color: var(--muted);
            font-weight: 500;
        }

        .mono { font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            appearance: none;
            border: 1px solid #d4d4d8;
            background: #ffffff;
            color: var(--ink);
            padding: 8px 10px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn.secondary {
            background: #fafafa;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 14px;
        }

        @media (max-width: 760px) {
            .grid { grid-template-columns: 1fr; }
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #ffffff;
            overflow: hidden;
        }

        .card-h {
            padding: 10px 12px;
            font-weight: 800;
            border-bottom: 1px solid var(--line);
            background: #fafafa;
        }

        .card-b { padding: 12px; }

        .row {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 10px;
            padding: 7px 0;
            border-bottom: 1px dashed #efefef;
        }
        .row:last-child { border-bottom: none; }

        .k { color: var(--muted); font-weight: 700; }
        .v { font-weight: 700; word-break: break-word; }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--chip);
            border: 1px solid #e4e4e7;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 10px 10px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }
        th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
        }

        .footer {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
            color: var(--muted);
        }

        @media print {
            @page { margin: 12mm; }
            .page { padding: 0; }
            .actions { display: none !important; }
            a[href]:after { content: ""; }
        }
    </style>
</head>
<body>
    @php($tamu = $kunjungan->tamu)

    <div class="page">
        <div class="topbar">
            <div>
                <h1 class="title">Bukti Kunjungan <span class="mono">#{{ $kunjungan->ID_KUNJUNGAN }}</span></h1>
                <p class="sub">Dicetak: {{ $printedAt->format('d-m-Y H:i') }}</p>
            </div>
            <div class="actions">
                <button type="button" class="btn" onclick="window.print()">Cetak</button>
                <button type="button" class="btn secondary" onclick="window.close()">Tutup</button>
            </div>
        </div>

        <div class="grid">
            <section class="card">
                <div class="card-h">Informasi Kunjungan</div>
                <div class="card-b">
                    <div class="row">
                        <div class="k">Tanggal</div>
                        <div class="v">{{ $kunjungan->TANGGAL_KUNJUNGAN }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Jam Masuk</div>
                        <div class="v">{{ $kunjungan->JAM_MASUK ?? '--:--' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Jam Keluar</div>
                        <div class="v">{{ $kunjungan->JAM_KELUAR ?? '--:--' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Status</div>
                        <div class="v">{{ $kunjungan->STATUS_KUNJUNGAN ?? 'Pending' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Catatan</div>
                        <div class="v" style="font-weight:600; color: var(--muted);">{{ $kunjungan->CATATAN ?: '—' }}</div>
                    </div>
                </div>
            </section>

            <section class="card">
                <div class="card-h">Data Tamu</div>
                <div class="card-b">
                    <div class="row">
                        <div class="k">Nama</div>
                        <div class="v">{{ $tamu?->NAMA_TAMU ?? 'Tanpa Nama' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Instansi</div>
                        <div class="v">{{ $tamu?->INSTANSI ?? '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">No. HP</div>
                        <div class="v">{{ $tamu?->NO_HP ?? '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">Email</div>
                        <div class="v">{{ $tamu?->EMAIL ?? '—' }}</div>
                    </div>
                    <div class="row">
                        <div class="k">No. KTP</div>
                        <div class="v">{{ $tamu?->NO_KTP ?? '—' }}</div>
                    </div>
                </div>
            </section>
        </div>

        <div style="height: 14px"></div>

        <section class="card">
            <div class="card-h">Pegawai yang Ditemui</div>
            <div class="card-b">
                @if ($kunjungan->pegawai->isEmpty())
                    <div class="sub">Belum ada pegawai yang didata.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 140px">NIK</th>
                                <th>Nama Pegawai</th>
                                <th style="width: 220px">Unit Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kunjungan->pegawai as $p)
                                <tr>
                                    <td class="mono">{{ $p->NIK }}</td>
                                    <td style="font-weight:700">{{ $p->NAMA_PEGAWAI }}</td>
                                    <td>{{ $p->unit?->NAMA_UNIT ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        <div style="height: 14px"></div>

        <section class="card">
            <div class="card-h">Keperluan</div>
            <div class="card-b">
                @if ($kunjungan->keperluan->isEmpty())
                    <div class="sub">Tidak ada rincian keperluan.</div>
                @else
                    <div class="chips">
                        @foreach ($kunjungan->keperluan as $kp)
                            <span class="chip">{{ $kp->NAMA_KEPERLUAN }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <div class="footer">
            Bukti ini dihasilkan oleh sistem Buku Tamu Digital.
        </div>
    </div>

    <script>
        // Auto-open print dialog on first load (optional, safe fallback).
        window.addEventListener('load', function () {
            try { window.print(); } catch (e) {}
        });
    </script>
</body>
</html>
