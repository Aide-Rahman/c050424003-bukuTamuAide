<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Kunjungan - Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-5xl px-4 py-4 flex items-center justify-between">
        <div>
            <div class="text-sm font-semibold text-slate-900">Sistem Informasi Buku Tamu Digital</div>
            <div class="text-xs text-slate-500">Detail Kunjungan</div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('bukutamu.kunjungan.index') }}" class="text-sm text-blue-700 hover:underline">Kembali</a>
            <a href="{{ route('bukutamu.kunjungan.edit', $kunjungan->ID_KUNJUNGAN) }}"
               class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Edit
            </a>
        </div>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-6">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            {{ session('status') }}
        </div>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-base font-semibold text-slate-900">Kunjungan {{ $kunjungan->ID_KUNJUNGAN }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Tamu: <span class="font-medium text-slate-700">{{ optional($kunjungan->tamu)->NAMA_TAMU ?? '-' }}</span>
                </p>
            </div>

            @php($status = $kunjungan->STATUS_KUNJUNGAN ?? '-')
            @php($isSelesai = strtolower($status) === 'selesai')
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                {{ $isSelesai ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-1 ring-slate-200' }}">
                {{ $status }}
            </span>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-500">Tanggal Kunjungan</div>
                <div class="mt-1 text-sm font-semibold text-slate-900">{{ $kunjungan->TANGGAL_KUNJUNGAN }}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-500">Jam Masuk / Keluar</div>
                <div class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $kunjungan->JAM_MASUK ?? '-' }} / {{ $kunjungan->JAM_KELUAR ?? '-' }}
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="text-xs text-slate-500">Catatan</div>
            <div class="mt-1 text-sm text-slate-900">{{ $kunjungan->CATATAN ?: '-' }}</div>
        </div>
    </section>

    <section class="mt-4 rounded-2xl border border-slate-200 bg-white p-5">
        <h2 class="text-sm font-semibold text-slate-900">Keperluan (MEMILIKI)</h2>
        @if ($kunjungan->keperluan->isEmpty())
            <p class="mt-2 text-sm text-slate-500">Tidak ada keperluan.</p>
        @else
            <ul class="mt-3 list-disc pl-5 text-sm text-slate-700">
                @foreach ($kunjungan->keperluan as $kp)
                    <li>{{ $kp->NAMA_KEPERLUAN }} <span class="text-slate-500">({{ $kp->ID_KEPERLUAN }})</span></li>
                @endforeach
            </ul>
        @endif
    </section>

    <section class="mt-4 rounded-2xl border border-slate-200 bg-white p-5">
        <h2 class="text-sm font-semibold text-slate-900">Pegawai yang Ditemui (MENEMUI)</h2>
        @if ($kunjungan->pegawai->isEmpty())
            <p class="mt-2 text-sm text-slate-500">Tidak ada pegawai.</p>
        @else
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">NIK</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Unit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($kunjungan->pegawai as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 text-sm font-mono text-slate-900">{{ $p->NIK }}</td>
                                <td class="px-4 py-2 text-sm text-slate-900">{{ $p->NAMA_PEGAWAI }}</td>
                                <td class="px-4 py-2 text-sm text-slate-700">{{ optional($p->unit)->NAMA_UNIT ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <form action="{{ route('bukutamu.kunjungan.destroy', $kunjungan->ID_KUNJUNGAN) }}" method="post" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit"
                onclick="return confirm('Hapus kunjungan {{ $kunjungan->ID_KUNJUNGAN }}?')"
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">
            Hapus Kunjungan
        </button>
    </form>
</main>
</body>
</html>
