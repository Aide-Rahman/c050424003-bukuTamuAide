<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }}</title>

    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-void: #050505;
            --bg-card: rgba(10, 10, 10, 0.7);
            --bg-input: #0a0a0a;

            --border-dim: rgba(255, 255, 255, 0.08);
            --border-highlight: rgba(255, 255, 255, 0.2);

            --text-primary: #ffffff;
            --text-secondary: #888888;
            --text-tertiary: #52525b;

            --radius-xl: 28px;
            --radius-lg: 20px;
            --radius-md: 14px;

            --ease-apple: cubic-bezier(0.25, 1, 0.5, 1);
        }

        body {
            margin: 0;
            min-height: 100vh;
            background-color: var(--bg-void);
            color: var(--text-primary);
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            font-size: 14px;
        }
        a { text-decoration: none; color: inherit; transition: all 0.2s ease; }
        * { box-sizing: border-box; outline: none; }

        .bg-fixed-layer { position: fixed; inset: 0; pointer-events: none; }
        .bg-liquid {
            z-index: -3;
            position: fixed;
            inset: 0;
            background:
                radial-gradient(at 0% 0%, hsla(0,0%,15%,1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(0,0%,5%,1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(0,0%,15%,1) 0, transparent 50%);
            background-size: 200% 200%;
            animation: liquidFlow 15s ease infinite alternate;
            opacity: 0.6;
        }
        @keyframes liquidFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .bg-smoke {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 150vw; height: 150vh;
            z-index: -2;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 60%);
            filter: blur(80px);
            animation: breathe 10s infinite ease-in-out;
        }
        @keyframes breathe {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }
        .bg-grid {
            z-index: -1;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
            opacity: 0.4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px 100px 24px;
            width: 100%;
        }
        @media (max-width: 768px) {
            .container { padding: 0 16px 80px 16px; }
            .nav-inner { padding: 0 16px; }
        }

        .navbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(10, 10, 10, 0.35);
            backdrop-filter: blur(34px) saturate(160%);
            -webkit-backdrop-filter: blur(34px) saturate(160%);
            border-bottom: 1px solid var(--border-dim);
            height: 72px;
            display: flex; align-items: center;
            margin-bottom: 32px;
            isolation: isolate;
        }
        .navbar::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 0%, rgba(255,255,255,0.06), transparent 45%),
                radial-gradient(circle at 80% 0%, rgba(255,255,255,0.04), transparent 50%);
            opacity: 0.9;
            pointer-events: none;
            z-index: -1;
        }
        .navbar::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 1px;
            background: linear-gradient(90deg,
                rgba(255,255,255,0),
                rgba(255,255,255,0.16),
                rgba(255,255,255,0)
            );
            pointer-events: none;
            z-index: -1;
        }
        .nav-inner {
            display: flex; justify-content: space-between; align-items: center; width: 100%;
            padding: 0 24px; max-width: 1200px; margin: 0 auto;
        }
        .logo-group { display: flex; align-items: center; gap: 14px; }
        .logo-box {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #1f1f22, #070708);
            border: 1px solid var(--border-dim);
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            display: grid; place-items: center;
        }
        .nav-title { font-weight: 700; font-size: 1rem; letter-spacing: -0.01em; line-height: 1.1; }
        .nav-subtitle { font-size: 0.75rem; color: var(--text-tertiary); font-weight: 500; line-height: 1.1; margin-top: 2px; }
        .nav-menu { display: flex; align-items: center; gap: 14px; }
        .nav-link {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid transparent;
        }
        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.06);
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--text-secondary);
            font-weight: 700;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 8px 14px;
            border-radius: 10px;
            transition: transform 0.2s var(--ease-apple), opacity 0.2s, background 0.2s, border-color 0.2s;
            height: 36px;
        }
        .btn-logout:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.16); color: var(--text-primary); }
        .btn-logout:active { transform: scale(0.98); opacity: 0.9; }
        @media (max-width: 640px) {
            .navbar { height: auto; }
            .nav-inner { flex-direction: column; align-items: stretch; gap: 12px; padding: 12px 16px; }
            .nav-menu { width: 100%; justify-content: space-between; }
        }

        .glass-panel {
            background: var(--bg-card);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border-radius: var(--radius-xl);
            border: 1px solid transparent;
            background-image:
                linear-gradient(var(--bg-card), var(--bg-card)),
                linear-gradient(180deg, rgba(255,255,255,0.15), rgba(255,255,255,0.02));
            background-origin: border-box;
            background-clip: padding-box, border-box;
            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.85);
            overflow: hidden;
        }
        .panel-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-dim);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.015);
        }
        .panel-body { padding: 24px; }

        .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 0.6rem;
            display: block;
        }
        .page-title {
            font-size: 2rem;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(90deg, #fff, #999);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .page-subtitle { color: var(--text-secondary); margin: 8px 0 0 0; font-size: 1rem; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 24px; }
        @media (max-width: 768px) { .page-header { flex-direction: column; align-items: stretch; } }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 650;
            cursor: pointer;
            text-decoration: none;
            border-radius: var(--radius-md);
            height: 48px;
            padding: 0 24px;
            font-size: 0.95rem;
            border: 1px solid transparent;
            transition:
                transform 0.2s var(--ease-apple),
                opacity 0.2s,
                background 0.2s,
                border-color 0.2s,
                box-shadow 0.2s;
            user-select: none;
        }
        .btn:active { transform: scale(0.99); opacity: 0.94; }
        .btn:focus-visible { outline: none; box-shadow: 0 0 0 4px rgba(255,255,255,0.08); }
        .btn-primary {
            background: #ffffff;
            color: #000000;
            border-color: rgba(255,255,255,0.22);
            box-shadow:
                0 18px 40px rgba(255,255,255,0.10),
                0 12px 26px rgba(0,0,0,0.35);
        }
        .btn-primary:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-secondary {
            background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            color: var(--text-primary);
            border-color: rgba(255,255,255,0.10);
            box-shadow:
                0 12px 26px rgba(0,0,0,0.28),
                inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .btn-secondary:hover { transform: translateY(-1px); border-color: rgba(255,255,255,0.16); }

        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; }
        @media (max-width: 1000px) { .grid-2 { grid-template-columns: 1fr; } }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th {
            text-align: left;
            padding: 14px 18px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-dim);
            white-space: nowrap;
        }
        td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.02);
            font-size: 0.95rem;
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .status-aktif {
            background: rgba(255, 255, 255, 0.06); color: var(--text-primary);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }
        .status-selesai {
            background: rgba(255, 255, 255, 0.05); color: var(--text-secondary);
            border: 1px solid var(--border-dim);
        }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .mono { font-family: "JetBrains Mono", monospace; color: var(--text-tertiary); }
        .muted { color: var(--text-secondary); }
        .card {
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.02);
            padding: 14px;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.02);
            padding: 8px 12px;
        }
    </style>
</head>

<body>
    <div class="bg-fixed-layer bg-liquid"></div>
    <div class="bg-smoke"></div>
    <div class="bg-fixed-layer bg-grid"></div>

    <header class="navbar">
        <div class="nav-inner">
            <div class="logo-group">
                <div class="logo-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                </div>
                <div>
                    <div class="nav-title">Buku Tamu</div>
                    <div class="nav-subtitle">Portal Admin</div>
                </div>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('bukutamu.kunjungan.index') }}" class="nav-link">Monitoring</a>
                <form method="post" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        @php($status = $kunjungan->STATUS_KUNJUNGAN ?? 'Pending')
        @php($statusLower = strtolower((string) $status))
        @php($isAktif = $statusLower === 'aktif')
        @php($isSelesai = $statusLower === 'selesai')

        <div class="page-header">
            <div>
                <h1 class="page-title">Kunjungan <span class="mono">#{{ $kunjungan->ID_KUNJUNGAN }}</span></h1>
                <p class="page-subtitle">Detail record kunjungan tamu.</p>
            </div>
            <div style="display:flex; gap: 12px; justify-content:flex-end; flex-wrap: wrap;">
                <a href="{{ route('bukutamu.kunjungan.edit', $kunjungan->ID_KUNJUNGAN) }}" class="btn btn-secondary">Edit Data</a>
                <a href="{{ route('bukutamu.kunjungan.print', $kunjungan->ID_KUNJUNGAN) }}" class="btn btn-secondary" target="_blank" rel="noopener">Cetak Bukti</a>
                @if (!$isSelesai)
                    <form method="post" action="{{ route('bukutamu.kunjungan.end', $kunjungan->ID_KUNJUNGAN) }}" style="margin:0" onsubmit="return confirm('Akhiri kunjungan ini? Status akan menjadi Selesai.');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary">Akhiri Kunjungan</button>
                    </form>
                @endif
                <a href="{{ route('bukutamu.kunjungan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="grid-2">
            <div style="display:flex; flex-direction:column; gap: 16px;">
                <section class="glass-panel">
                    <div class="panel-header">
                        <span class="label" style="margin:0">Informasi Utama</span>
                        <span class="status-badge {{ $isAktif ? 'status-aktif' : 'status-selesai' }}">
                            <span class="status-dot"></span>
                            {{ $status }}
                        </span>
                    </div>
                    <div class="panel-body">
                        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">
                            <div class="card" style="grid-column: 1 / -1;">
                                <span class="label" style="margin:0 0 6px 0">Nama Tamu</span>
                                <div style="font-size: 1.15rem; font-weight: 800;">{{ optional($kunjungan->tamu)->NAMA_TAMU ?? 'Tanpa Nama' }}</div>
                            </div>

                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Instansi</span>
                                <div style="font-weight: 650;">{{ optional($kunjungan->tamu)->INSTANSI ?? '-' }}</div>
                            </div>
                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Nomor HP</span>
                                <div style="font-weight: 650;">{{ optional($kunjungan->tamu)->NO_HP ?? '-' }}</div>
                            </div>
                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Alamat Email</span>
                                <div style="font-weight: 650; word-break: break-word;">{{ optional($kunjungan->tamu)->EMAIL ?? '-' }}</div>
                            </div>
                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Nomor KTP</span>
                                <div style="font-weight: 650;">{{ optional($kunjungan->tamu)->NO_KTP ?? '-' }}</div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 12px;">
                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Tanggal</span>
                                <div style="font-weight: 700;">{{ $kunjungan->TANGGAL_KUNJUNGAN }}</div>
                            </div>
                            <div class="card">
                                <span class="label" style="margin:0 0 6px 0">Jam Masuk / Keluar</span>
                                <div style="font-weight: 700;">
                                    {{ $kunjungan->JAM_MASUK ?? '--:--' }} <span class="muted">→</span> {{ $kunjungan->JAM_KELUAR ?? '--:--' }}
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 12px;" class="card">
                            <span class="label" style="margin:0 0 6px 0">Catatan Tambahan</span>
                            <div class="muted" style="line-height: 1.6; font-style: italic;">{{ $kunjungan->CATATAN ?: 'Tidak ada catatan khusus untuk kunjungan ini.' }}</div>
                        </div>
                    </div>
                </section>

                <section class="glass-panel">
                    <div class="panel-header">
                        <span class="label" style="margin:0">Pegawai yang Ditemui</span>
                    </div>
                    <div class="panel-body">
                        @if ($kunjungan->pegawai->isEmpty())
                            <div class="muted" style="font-style: italic;">Belum ada pegawai yang didata untuk kunjungan ini.</div>
                        @else
                            <div style="overflow-x:auto;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama Pegawai</th>
                                            <th>Unit Kerja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kunjungan->pegawai as $p)
                                            <tr>
                                                <td style="font-weight: 650;">{{ $p->NIK }}</td>
                                                <td style="font-weight: 650;">{{ $p->NAMA_PEGAWAI }}</td>
                                                <td><span class="pill">{{ optional($p->unit)->NAMA_UNIT ?? 'N/A' }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <div style="display:flex; flex-direction:column; gap: 16px;">
                <section class="glass-panel">
                    <div class="panel-header">
                        <span class="label" style="margin:0">Keperluan Kunjungan</span>
                    </div>
                    <div class="panel-body">
                        @if ($kunjungan->keperluan->isEmpty())
                            <div class="muted" style="font-style: italic;">Tidak ada rincian keperluan.</div>
                        @else
                            <div style="display:flex; flex-wrap: wrap; gap: 10px;">
                                @foreach ($kunjungan->keperluan as $kp)
                                    <span class="pill">{{ $kp->NAMA_KEPERLUAN }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="glass-panel">
                    <div class="panel-header">
                        <span class="label" style="margin:0">Tindakan</span>
                    </div>
                    <div class="panel-body">
                        <div class="muted" style="line-height: 1.6; margin-bottom: 12px;">
                            Menghapus kunjungan akan menghilangkan data terkait secara permanen.
                        </div>
                        <form action="{{ route('bukutamu.kunjungan.destroy', $kunjungan->ID_KUNJUNGAN) }}" method="post" style="margin:0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')" class="btn btn-secondary" style="width:100%">
                                Hapus Record
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @include('partials.toast')
</body>
</html>