<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu Digital - Monitoring Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ===============================
           THEME: LIQUID DARK PRO (MATCH LOGIN)
        ================================ */
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
            font-size: 14px; /* Base size lebih rapi */
        }

        a { text-decoration: none; color: inherit; transition: all 0.2s ease; }
        * { box-sizing: border-box; outline: none; }

        /* --- ANIMATIONS --- */

        /* --- BACKGROUNDS --- */
        .bg-fixed-layer { position: fixed; inset: 0; pointer-events: none; }

        /* Layer 1: Fluid Mesh (same as login) */
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

        /* Layer 2: Smoke (same as login) */
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

        /* --- LAYOUT --- */
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

        /* --- NAVBAR --- */
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

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0;
            border-radius: 0;
            background: transparent;
            border: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }
        .nav-link {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            position: relative;
            border: 1px solid transparent;
        }
        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.06);
        }
        .nav-link.active {
            color: var(--text-primary);
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.12);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
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

        /* --- GLASS CARDS (Panel) --- */
        /* Glass panel border technique (same spirit as login-card) */
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
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
        }

        .panel-body { padding: 24px; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .panel-header { 
            padding: 18px 24px; border-bottom: 1px solid var(--border-dim); 
            display: flex; justify-content: space-between; align-items: center;
            background: rgba(255,255,255,0.015);
        }
        .panel-footer {
            padding: 14px 24px; border-top: 1px solid var(--border-dim);
            background: rgba(0,0,0,0.2);
            display: flex; justify-content: space-between; align-items: center;
        }

        /* --- KUNJUNGAN TERAKHIR (Symmetric content) --- */
        .latest-header {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: start;
            gap: 16px;
            margin-bottom: 20px;
        }
        .latest-left { min-width: 0; text-align: center; grid-column: 2; }
        .latest-left .label { margin-left: 0; }
        .latest-id {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 4px;
            letter-spacing: -0.01em;
            font-family: "SF Pro Display", sans-serif;
        }
        .latest-spacer { grid-column: 1; justify-self: start; visibility: hidden; }
        .latest-detail { grid-column: 3; justify-self: end; align-self: start; }

        /* Detail button (Latest Kunjungan) */
        .latest-detail-btn {
            height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
            border: 1px solid rgba(255,255,255,0.12);
            color: var(--text-primary);
            box-shadow:
                0 10px 24px rgba(0,0,0,0.35),
                inset 0 1px 0 rgba(255,255,255,0.06);
            transition: transform 0.2s var(--ease-apple), opacity 0.2s, background 0.2s, border-color 0.2s;
            gap: 8px;
        }
        .latest-detail-btn:hover {
            background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
            border-color: rgba(255,255,255,0.18);
            transform: translateY(-1px);
        }
        .latest-detail-btn:active {
            transform: translateY(0) scale(0.99);
            opacity: 0.92;
        }
        .latest-detail-btn:focus-visible {
            outline: none;
            box-shadow:
                0 10px 24px rgba(0,0,0,0.35),
                inset 0 1px 0 rgba(255,255,255,0.06),
                0 0 0 4px rgba(255,255,255,0.08);
        }
        .latest-detail-btn svg { opacity: 0.9; }
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .panel-body--top { justify-content: flex-start; }

        @media (max-width: 600px) {
            .latest-header { grid-template-columns: 1fr; align-items: start; }
            .latest-left { grid-column: auto; text-align: left; }
            .latest-detail { grid-column: auto; justify-self: start; }
            .latest-spacer { display: none; }
        }

        /* --- GRIDS --- */
        .grid-split { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; align-items: stretch; }
        .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 24px; }
        @media (max-width: 900px) { .grid-split, .grid-stats { grid-template-columns: 1fr; } }

        /* --- PAGE HEADER (Symmetric + Responsive) --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 32px;
        }
        .page-header__text { min-width: 0; }
        .page-title {
            font-size: 2rem;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(90deg, #fff, #999);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .page-subtitle {
            color: var(--text-secondary);
            margin: 8px 0 0 0;
            font-size: 1rem;
        }
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .page-title { font-size: 1.75rem; }
            .page-header .btn { width: 100%; justify-content: center; }
        }

        /* --- INLINE GRID HELPERS (Kunjungan Terakhir) --- */
        .latest-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
            align-items: start;
        }
        .latest-grid .label { margin-left: 0; margin-bottom: 6px; }
        .latest-item {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .latest-value { font-weight: 600; font-size: 1rem; line-height: 1.4; color: var(--text-primary); max-width: 100%; }

        @media (min-width: 901px) {
            .latest-item { position: relative; padding: 0 12px; }
            .latest-item:nth-child(2)::before,
            .latest-item:nth-child(3)::before {
                content: '';
                position: absolute;
                left: -12px;
                top: 0;
                bottom: 0;
                width: 1px;
                background: rgba(255,255,255,0.06);
            }
        }
        @media (max-width: 900px) {
            .latest-grid { grid-template-columns: 1fr; gap: 14px; }
            .latest-item { align-items: flex-start; text-align: left; }
            .latest-item:nth-child(2),
            .latest-item:nth-child(3) {
                border-left: none;
                padding-left: 0;
            }
            .latest-item { padding: 0; }
        }

        /* --- UNIT SUMMARY (Replace Status Sistem) --- */
        .unit-list {
            list-style: none;
            padding: 0;
            margin: 4px 0 0 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .unit-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .unit-name {
            min-width: 0;
            color: var(--text-secondary);
            font-weight: 600;
        }
        .unit-count {
            font-family: "JetBrains Mono", monospace;
            font-weight: 700;
            color: var(--text-primary);
        }
        .unit-empty {
            color: var(--text-tertiary);
            font-style: italic;
            margin-top: 6px;
        }

        /* --- TYPOGRAPHY & LABELS --- */
        .label { 
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--text-secondary); font-weight: 600; margin-bottom: 0.6rem; display: block;
            margin-left: 4px;
        }
        .value-lg { 
            font-size: 2.2rem; font-weight: 700; letter-spacing: -0.03em; line-height: 1;
            font-family: "SF Pro Display", sans-serif;
            background: linear-gradient(180deg, #fff, #ccc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 15px rgba(255,255,255,0.1));
        }
        .text-glow-green { text-shadow: 0 0 18px rgba(255, 255, 255, 0.12); -webkit-text-fill-color: var(--text-primary); }
        .text-glow-primary { text-shadow: 0 0 18px rgba(255, 255, 255, 0.12); -webkit-text-fill-color: var(--text-primary); }

        /* --- FORMS --- */
        .filter-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; align-items: end; }
        .input-group { display: flex; flex-direction: column; }
        .filter-actions { display:flex; gap: 12px; align-items: end; align-self: stretch; }
        .filter-actions .btn { height: 48px; }
        .filter-actions .btn { flex: 1; }
        @media (max-width: 1024px) { .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 900px) { .filter-grid { grid-template-columns: 1fr; } .filter-actions { flex-direction: column; } .filter-actions .btn { width: 100%; } }
        .ios-input {
            height: 48px; width: 100%; padding: 0 16px;
            border-radius: var(--radius-md); border: 1px solid var(--border-dim);
            background-color: var(--bg-input) !important;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s var(--ease-apple);
        }
        .ios-input:focus { 
            outline: none;
            background-color: #000 !important;
            border-color: var(--text-primary);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.08);
        }

        /* Date input calendar icon: force white (Chrome/Edge/WebKit) */
        input[type="date"].ios-input::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(1.2);
            opacity: 0.9;
            cursor: pointer;
        }
        input[type="date"].ios-input::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
        input[type="date"].ios-input::-webkit-calendar-picker-indicator:active {
            opacity: 0.8;
        }
        /* Select icon hack */
        select.ios-input { -webkit-appearance: none; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em; }

        /* --- BUTTONS --- */
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
            -webkit-tap-highlight-color: transparent;
        }
        .btn:active { transform: scale(0.99); opacity: 0.94; }
        .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.08);
        }
        
        .btn-primary { 
            background: #ffffff;
            color: #000000;
            border-color: rgba(255,255,255,0.22);
            box-shadow:
                0 18px 40px rgba(255,255,255,0.10),
                0 12px 26px rgba(0,0,0,0.35);
        }
        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow:
                0 22px 50px rgba(255,255,255,0.12),
                0 16px 34px rgba(0,0,0,0.38);
        }
        .btn-primary:active { transform: translateY(0) scale(0.99); opacity: 0.9; }

        .btn-secondary { 
            background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            color: var(--text-primary);
            border-color: rgba(255,255,255,0.10);
            box-shadow:
                0 12px 26px rgba(0,0,0,0.28),
                inset 0 1px 0 rgba(255,255,255,0.05);
        }
        .btn-secondary:hover {
            background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03));
            border-color: rgba(255,255,255,0.16);
            transform: translateY(-1px);
        }
        .btn-secondary:active { transform: translateY(0) scale(0.99); }
        
        .btn-sm { height: 34px; padding: 0 14px; font-size: 0.8rem; border-radius: 999px; }

        /* --- TABLE STYLES (Dipakai oleh table.blade.php) --- */
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { 
            text-align: left; padding: 18px 24px; 
            font-size: 0.75rem; text-transform: uppercase; color: var(--text-secondary); 
            font-weight: 600; letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-dim); white-space: nowrap;
        }
        td { 
            padding: 18px 24px; border-bottom: 1px solid rgba(255,255,255,0.02); 
            font-size: 0.95rem; vertical-align: middle; 
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        
        .id-cell { font-family: "JetBrains Mono", monospace; color: var(--text-tertiary); font-size: 0.85rem; }

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

        /* Pagination */
        .pagination-simple { display: flex; gap: 6px; }
        .page-link { 
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 0.85rem; 
            background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            border: 1px solid rgba(255,255,255,0.10);
            color: var(--text-secondary);
            transition: transform 0.2s var(--ease-apple), background 0.2s, border-color 0.2s, color 0.2s;
        }
        .page-link:hover { border-color: rgba(255,255,255,0.18); color: var(--text-primary); transform: translateY(-1px); }
        .page-link.current { background: white; color: black; border-color: rgba(255,255,255,0.9); font-weight: 800; }
        
        /* Avatar */
        .avatar-circle {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #27272a, #000); 
            border: 1px solid var(--border-dim);
            display: grid; place-items: center; font-size: 0.8rem; font-weight: 700; color: #d4d4d8;
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
                <a href="{{ route('bukutamu.kunjungan.index') }}" class="nav-link active">Monitoring</a>
                <form method="post" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        
        <div class="page-header">
            <div class="page-header__text">
                <h1 class="page-title">Data Kunjungan</h1>
                <p class="page-subtitle">Pantau aktivitas tamu secara real-time.</p>
            </div>
            <a href="{{ route('bukutamu.kunjungan.create') }}" class="btn btn-primary" aria-label="Tambah kunjungan baru">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                Kunjungan Baru
            </a>
        </div>

        <div class="grid-split">
            <div class="glass-panel">
                <div class="panel-body panel-body--top">
                    <div class="latest-header">
                        @if(!empty($latestKunjungan))
                            <span class="btn btn-secondary btn-sm latest-spacer latest-detail-btn" aria-hidden="true">
                                Detail
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                            </span>
                        @endif
                        <div class="latest-left">
                            <span class="label">Kunjungan Terakhir</span>
                            <div class="latest-id truncate">
                                @if(!empty($latestKunjungan))
                                    #{{ $latestKunjungan->ID_KUNJUNGAN }}
                                @else
                                    <span style="opacity:0.3">--</span>
                                @endif
                            </div>
                        </div>
                        @if(!empty($latestKunjungan))
                            <a href="{{ route('bukutamu.kunjungan.show', $latestKunjungan->ID_KUNJUNGAN) }}" class="btn btn-secondary btn-sm latest-detail latest-detail-btn">
                                Detail
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                            </a>
                        @endif
                    </div>

                    @if(!empty($latestKunjungan))
                    <div class="latest-grid">
                        <div class="latest-item">
                            <span class="label">Tamu</span>
                            <div class="latest-value truncate">{{ optional($latestKunjungan->tamu)->NAMA_TAMU ?? '-' }}</div>
                        </div>
                        <div class="latest-item">
                            <span class="label">Waktu</span>
                            <div class="latest-value truncate">{{ $latestKunjungan->TANGGAL_KUNJUNGAN ?? '-' }}</div>
                        </div>
                        <div class="latest-item">
                            <span class="label">Status</span>
                            <div class="latest-value truncate">{{ $latestKunjungan->STATUS_KUNJUNGAN ?? '-' }}</div>
                        </div>
                    </div>
                    @else
                        <div style="color: var(--text-tertiary); font-style: italic;">Belum ada data masuk hari ini.</div>
                    @endif
                </div>
            </div>

            <div class="glass-panel">
                <div class="panel-body panel-body--top">
                    <span class="label">Total Kunjungan per Unit</span>

                    <ul class="unit-list" id="unit-list" style="display:none"></ul>
                    <div class="unit-empty" id="unit-empty">Memuat data...</div>
                </div>
            </div>
        </div>

        <div class="grid-stats">
            <div class="glass-panel">
                <div class="panel-body" style="align-items: center; text-align: center;">
                    <span class="label">Total Hari Ini</span>
                    <div class="value-lg">{{ $totalHariIni }}</div>
                </div>
            </div>
            <div class="glass-panel">
                <div class="panel-body" style="align-items: center; text-align: center;">
                    <span class="label">Sedang Aktif</span>
                    <div class="value-lg text-glow-green">{{ $totalAktif }}</div>
                </div>
            </div>
            <div class="glass-panel">
                <div class="panel-body" style="align-items: center; text-align: center;">
                    <span class="label">Selesai Hari Ini</span>
                    <div class="value-lg">{{ $totalSelesaiHariIni }}</div>
                </div>
            </div>
        </div>

        @php($activeStatus = request('status'))
        @php($activeFrom = request('from'))
        @php($activeTo = request('to'))
        @php($activeQ = request('q'))

        <div class="glass-panel" style="margin-bottom: 32px;">
            <div class="panel-body">
                <form method="get" action="{{ route('bukutamu.kunjungan.index') }}" class="filter-grid">
                    <div class="input-group">
                        <label class="label">Cari Nama</label>
                        <input type="text" name="q" value="{{ $activeQ }}" class="ios-input" placeholder="Masukkan nama tamu..." />
                    </div>
                    <div class="input-group">
                        <label class="label">Status</label>
                        <select name="status" class="ios-input">
                            <option value="">Semua Status</option>
                            <option value="Aktif" @selected($activeStatus === 'Aktif')>Aktif</option>
                            <option value="Selesai" @selected($activeStatus === 'Selesai')>Selesai</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label class="label">Dari</label>
                        <input type="date" name="from" value="{{ $activeFrom }}" class="ios-input" />
                    </div>
                    <div class="input-group">
                        <label class="label">Sampai</label>
                        <input type="date" name="to" value="{{ $activeTo }}" class="ios-input" />
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Filter Data</button>
                        <a href="{{ route('bukutamu.kunjungan.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <span class="label" style="margin:0">Export Laporan</span>
                <div style="display:flex; gap:10px;">
                    <a href="{{ route('bukutamu.kunjungan.export.csv', request()->query()) }}" class="btn btn-secondary btn-sm" style="display:flex; align-items:center;">Download CSV</a>
                    <a href="{{ route('bukutamu.kunjungan.export.pdf', request()->query()) }}" class="btn btn-secondary btn-sm" style="display:flex; align-items:center;">Download PDF</a>
                </div>
            </div>
        </div>

        <div id="table-container">
            @include('bukutamu.kunjungan.table')
        </div>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitList = document.getElementById('unit-list');
        const unitEmpty = document.getElementById('unit-empty');

        if (unitList && unitEmpty) {
            const perUnitUrl = @json(route('bukutamu.kunjungan.perUnit', request()->query()));

            fetch(perUnitUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                if (!r.ok) throw new Error('Failed to load per-unit');
                return r.json();
            })
            .then(payload => {
                const rows = payload?.kunjunganPerUnit || [];

                unitList.innerHTML = '';

                if (!rows.length) {
                    unitList.style.display = 'none';
                    unitEmpty.textContent = 'Belum ada data untuk filter saat ini.';
                    return;
                }

                rows.forEach(row => {
                    const li = document.createElement('li');
                    li.className = 'unit-row';

                    const name = document.createElement('span');
                    name.className = 'unit-name truncate';
                    name.textContent = row.NAMA_UNIT ?? '-';

                    const count = document.createElement('span');
                    count.className = 'unit-count';
                    count.textContent = row.total ?? 0;

                    li.appendChild(name);
                    li.appendChild(count);
                    unitList.appendChild(li);
                });

                unitEmpty.style.display = 'none';
                unitList.style.display = '';
            })
            .catch(() => {
                unitList.style.display = 'none';
                unitEmpty.textContent = 'Gagal memuat data unit. Silakan refresh.';
            });
        }

        const tableContainer = document.getElementById('table-container');

        if(tableContainer) {
            tableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('#table-container .pagination-simple a') || e.target.closest('#table-container .pagination a');
                
                if (link && link.href) {
                    e.preventDefault(); 
                    loadTable(link.href);
                }
            });
        }

        function loadTable(url) {
            const wrapper = document.getElementById('kunjungan-table-wrapper'); 
            if(wrapper) {
                wrapper.style.transition = 'opacity 0.2s';
                wrapper.style.opacity = '0.5';
                wrapper.style.pointerEvents = 'none'; // Cegah klik ganda
            }

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                document.getElementById('table-container').innerHTML = html;
                window.history.pushState(null, '', url);
                
                // Scroll sedikit ke atas tabel jika tabelnya panjang (opsional)
                // document.getElementById('table-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = url; // Fallback refresh
            });
        }

        window.onpopstate = function() {
            loadTable(window.location.href);
        };
    });
    </script>

    @include('partials.toast')
</body>
</html>