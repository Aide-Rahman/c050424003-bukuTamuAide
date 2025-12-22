<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Buku Tamu')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8fafc; color: #0f172a; }
        header { margin-bottom: 16px; }
        nav a { margin-right: 10px; text-decoration: none; color: #0f172a; }
        nav a:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
        th { background: #f5f5f5; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #e2e8f0; background: #ffffff; padding: 16px; margin-top: 12px; border-radius: 12px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06); }
        .row { margin-bottom: 10px; }
        label { display: inline-block; margin-bottom: 4px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
        textarea { min-height: 90px; }
        .error { color: #b00020; margin-top: 4px; }
        .actions a, .actions button { margin-right: 6px; }
        .muted { color: #666; }
        .alert { padding: 10px 12px; border: 1px solid #cce5ff; background: #eaf4ff; margin-top: 10px; border-radius: 10px; }
        .checkbox-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 16px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 12px; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-weight: 600; }
        .btn:hover { background: #f1f5f9; }
        .btn-primary { border-color: #2563eb; background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-link { border: none; background: transparent; padding: 0; font-weight: 600; color: #2563eb; cursor: pointer; }
        .btn-link:hover { text-decoration: underline; }
        .header-card { border: 1px solid #e2e8f0; background: #fff; padding: 12px 16px; border-radius: 12px; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06); }
        .header-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        @media (max-width: 700px) { .checkbox-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="header-card">
            <div class="header-top">
                <h2 style="margin: 0;">Buku Tamu</h2>
                <nav>
            @auth
                <a href="{{ route('bukutamu.kunjungan.index') }}">Kunjungan</a>
                <a href="{{ route('bukutamu.tamu.index') }}">Tamu (JSON)</a>
                <a href="{{ route('bukutamu.pegawai.index') }}">Pegawai (JSON)</a>
                <a href="{{ route('bukutamu.unit.index') }}">Unit (JSON)</a>

                <form method="post" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn">Logout</button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}">Login</a>
            @endguest
                </nav>
            </div>

        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert" style="border-color:#f5c2c7;background:#fff0f1;">
                <strong>Validasi gagal:</strong>
                <ul style="margin:8px 0 0 18px;">
                    @foreach ($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</div>
</body>
</html>
