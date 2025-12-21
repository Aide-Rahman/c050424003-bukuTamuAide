<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Buku Tamu')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header { margin-bottom: 16px; }
        nav a { margin-right: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; vertical-align: top; }
        th { background: #f5f5f5; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 12px; margin-top: 12px; }
        .row { margin-bottom: 10px; }
        label { display: inline-block; margin-bottom: 4px; font-weight: bold; }
        input[type="text"], input[type="date"], input[type="time"], select, textarea { width: 100%; padding: 6px; }
        textarea { min-height: 90px; }
        .error { color: #b00020; margin-top: 4px; }
        .actions a, .actions button { margin-right: 6px; }
        .muted { color: #666; }
        .alert { padding: 10px; border: 1px solid #cce5ff; background: #eaf4ff; margin-top: 10px; }
        .checkbox-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 16px; }
        @media (max-width: 700px) { .checkbox-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h2 style="margin: 0 0 8px 0;">Buku Tamu</h2>
        <nav>
            <a href="{{ route('bukutamu.kunjungan.index') }}">Kunjungan</a>
            <a href="{{ route('bukutamu.tamu.index') }}">Tamu (JSON)</a>
            <a href="{{ route('bukutamu.pegawai.index') }}">Pegawai (JSON)</a>
            <a href="{{ route('bukutamu.unit.index') }}">Unit (JSON)</a>
        </nav>

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
    </header>

    <main>
        @yield('content')
    </main>
</div>
</body>
</html>
