<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }} - Buku Tamu Digital</title>

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
            max-width: 1000px;
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
            padding: 0 24px; max-width: 1000px; margin: 0 auto;
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
            position: relative;
            z-index: 1;
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
                0 18px 40px rgba(255,255,255,0.12),
                0 18px 34px rgba(0,0,0,0.55);
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

        .ios-input {
            height: 48px;
            width: 100%;
            padding: 0 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-dim);
            background-color: var(--bg-input) !important;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s var(--ease-apple);
        }
        textarea.ios-input { height: auto; padding: 12px 16px; }
        .ios-input:focus {
            outline: none;
            background-color: #000 !important;
            border-color: var(--text-primary);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.08);
        }
        input[type="date"].ios-input::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2);
            opacity: 0.9;
            cursor: pointer;
        }
        input[type="time"].ios-input::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2);
            opacity: 0.9;
            cursor: pointer;
        }
        select.ios-input {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.2em;
        }

        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        @media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
        .field { display: flex; flex-direction: column; }

        .choice-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        @media (max-width: 900px) { .choice-grid { grid-template-columns: 1fr; } }
        .choice-card {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 14px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.02);
            transition: transform 0.2s var(--ease-apple), border-color 0.2s, background 0.2s;
            cursor: pointer;
        }
        .choice-card:hover { transform: translateY(-1px); border-color: rgba(255,255,255,0.14); background: rgba(255,255,255,0.03); }
        .custom-checkbox { width: 18px; height: 18px; margin-top: 2px; accent-color: #ffffff; }
        .mono { font-family: "JetBrains Mono", monospace; color: var(--text-tertiary); }
        .muted { color: var(--text-secondary); }

        .alert {
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.02);
            padding: 14px 16px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }
        .alert strong { color: var(--text-primary); }
        .alert ul { margin: 10px 0 0 18px; }

        .ld-modal {
            position: fixed;
            inset: 0;
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }
        .ld-modal.is-open { display: flex; }
        .ld-modal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.62);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .ld-modal__dialog {
            position: relative;
            width: min(520px, calc(100vw - 36px));
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(10,10,10,0.72);
            backdrop-filter: blur(28px) saturate(170%);
            -webkit-backdrop-filter: blur(28px) saturate(170%);
            box-shadow: 0 50px 90px rgba(0,0,0,0.75);
            overflow: hidden;
            transform: translateY(6px);
            opacity: 0;
            transition: transform 180ms var(--ease-apple), opacity 180ms var(--ease-apple);
        }
        .ld-modal.is-open .ld-modal__dialog { transform: translateY(0); opacity: 1; }
        .ld-modal__header {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .ld-modal__title {
            font-size: 0.85rem;
            font-weight: 750;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.9);
            margin: 0;
        }
        .ld-modal__body { padding: 18px; color: rgba(255,255,255,0.82); }
        .ld-modal__body p { margin: 0; line-height: 1.6; }
        .ld-modal__footer {
            padding: 16px 18px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            background: rgba(255,255,255,0.02);
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
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Kunjungan</h1>
                <p class="page-subtitle">Mengubah entri <span class="mono">#{{ $kunjungan->ID_KUNJUNGAN }}</span></p>
            </div>
            <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}" class="btn btn-secondary">Batal</a>
        </div>

        @if ($errors->any())
            <div class="alert">
                <strong>Validasi gagal:</strong>
                <ul>
                    @foreach ($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bukutamu.kunjungan.update', $kunjungan->ID_KUNJUNGAN) }}" method="post" style="display:flex; flex-direction:column; gap: 16px;">
            @csrf
            @method('PUT')

            <section class="glass-panel">
                <div class="panel-header">
                    <span class="label" style="margin:0">Identitas Tamu</span>
                </div>
                <div class="panel-body">
                    <div class="field">
                        <label class="label">Pilih Tamu Terdaftar</label>
                        <select name="ID_TAMU" class="ios-input">
                            <option value="">-- Silakan Pilih Tamu --</option>
                            @foreach ($tamus as $t)
                                <option value="{{ $t->ID_TAMU }}" @selected(old('ID_TAMU', $kunjungan->ID_TAMU) == $t->ID_TAMU)>
                                    {{ $t->ID_TAMU }} - {{ $t->NAMA_TAMU }} ({{ $t->INSTANSI }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            <section class="glass-panel">
                <div class="panel-header">
                    <span class="label" style="margin:0">Detail Waktu & Status</span>
                </div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="field">
                            <label class="label">Tanggal Kunjungan</label>
                            <input type="date" name="TANGGAL_KUNJUNGAN" value="{{ old('TANGGAL_KUNJUNGAN', $kunjungan->TANGGAL_KUNJUNGAN) }}" class="ios-input" />
                        </div>

                        <div class="field">
                            <label class="label">Status Kunjungan</label>
                            @php($statusOld = old('STATUS_KUNJUNGAN', $kunjungan->STATUS_KUNJUNGAN))
                            <select name="STATUS_KUNJUNGAN" class="ios-input">
                                <option value="Aktif" @selected($statusOld === 'Aktif')>Aktif</option>
                                <option value="Selesai" @selected($statusOld === 'Selesai')>Selesai</option>
                            </select>
                        </div>

                        <div class="field">
                            <label class="label">Jam Masuk</label>
                            <input type="time" name="JAM_MASUK" value="{{ old('JAM_MASUK', $JAM_MASUK_HI ?? null) }}" step="60" class="ios-input" />
                        </div>

                        <div class="field">
                            <label class="label">Jam Keluar</label>
                            <input type="time" name="JAM_KELUAR" value="{{ old('JAM_KELUAR', $JAM_KELUAR_HI ?? null) }}" step="60" class="ios-input" />
                        </div>

                        <div class="field" style="grid-column: 1 / -1;">
                            <label class="label">Catatan Tambahan</label>
                            <textarea name="CATATAN" rows="3" class="ios-input" placeholder="Tuliskan alasan atau keterangan tambahan jika ada...">{{ old('CATATAN', $kunjungan->CATATAN) }}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            <section class="glass-panel">
                <div class="panel-header">
                    <span class="label" style="margin:0">Rincian Keperluan (MEMILIKI)</span>
                </div>
                <div class="panel-body">
                    @php($selectedKeperluan = old('ID_KEPERLUAN', $kunjungan->keperluan->pluck('ID_KEPERLUAN')->all()))
                    <div class="choice-grid">
                        @foreach ($keperluans as $kp)
                            <label class="choice-card">
                                <input type="checkbox" name="ID_KEPERLUAN[]" value="{{ $kp->ID_KEPERLUAN }}" class="custom-checkbox" @checked(in_array($kp->ID_KEPERLUAN, $selectedKeperluan, true))>
                                <div style="min-width:0; display:flex; flex-direction:column; gap: 4px;">
                                    <div style="display:flex; align-items:center; gap: 10px; min-width:0;">
                                        <span style="font-weight:700; color: var(--text-primary); min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $kp->NAMA_KEPERLUAN }}</span>
                                        <span class="mono">{{ $kp->ID_KEPERLUAN }}</span>
                                    </div>
                                    @if($kp->KETERANGAN)
                                        <span class="muted" style="font-style: italic; line-height: 1.5;">"{{ $kp->KETERANGAN }}"</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="glass-panel">
                <div class="panel-header">
                    <span class="label" style="margin:0">Pegawai yang Ditemui (MENEMUI)</span>
                </div>
                <div class="panel-body">
                    @php($selectedNik = old('NIK', $kunjungan->pegawai->pluck('NIK')->all()))
                    <div class="choice-grid">
                        @foreach ($pegawais as $p)
                            <label class="choice-card">
                                <input type="checkbox" name="NIK[]" value="{{ $p->NIK }}" class="custom-checkbox" @checked(in_array($p->NIK, $selectedNik, true))>
                                <div style="min-width:0; display:flex; flex-direction:column; gap: 6px;">
                                    <span style="font-weight:700; color: var(--text-primary);">{{ $p->NAMA_PEGAWAI }}</span>
                                    <span class="muted" style="font-size: 0.9rem;">NIK: <span class="mono">{{ $p->NIK }}</span> • Unit: {{ optional($p->unit)->NAMA_UNIT ?? '-' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </section>

            <div style="display:flex; gap: 12px; justify-content:flex-end; margin-top: 8px;">
                <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </main>

    <div id="status-confirm-modal" class="ld-modal" aria-hidden="true">
        <div class="ld-modal__backdrop" data-close="1"></div>
        <div class="ld-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="status-confirm-title">
            <div class="ld-modal__header">
                <h2 id="status-confirm-title" class="ld-modal__title">Konfirmasi</h2>
                <button type="button" class="btn btn-secondary" style="height:36px; padding:0 14px; border-radius: 999px;" data-close="1">Tutup</button>
            </div>
            <div class="ld-modal__body">
                <p>Anda memilih status <strong style="color: rgba(255,255,255,0.95)">Selesai</strong>. Lanjutkan mengakhiri kunjungan?</p>
            </div>
            <div class="ld-modal__footer">
                <button type="button" class="btn btn-secondary" data-close="1">Batal</button>
                <button type="button" class="btn btn-primary" id="status-confirm-yes">Konfirmasi</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.querySelector('form');
            const statusSelect = document.querySelector('select[name="STATUS_KUNJUNGAN"]');
            if (!form || !statusSelect) return;

            const modal = document.getElementById('status-confirm-modal');
            const yesBtn = document.getElementById('status-confirm-yes');
            let confirmed = false;

            function openModal() {
                if (!modal) return;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            if (modal) {
                modal.addEventListener('click', function (e) {
                    const t = e.target;
                    if (t && t.getAttribute && t.getAttribute('data-close') === '1') {
                        closeModal();
                    }
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                        closeModal();
                    }
                });
            }

            if (yesBtn) {
                yesBtn.addEventListener('click', function () {
                    confirmed = true;
                    closeModal();
                    // requestSubmit menjaga validasi HTML (kalau ada)
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                if (confirmed) return;
                const val = (statusSelect.value || '').toLowerCase();
                if (val === 'selesai') {
                    e.preventDefault();
                    openModal();
                }
            });
        })();
    </script>

    @include('partials.toast')
</body>
</html>