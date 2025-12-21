<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu Digital - Kunjungan</title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Make Laravel pagination acceptable without extra packages */
        .pagination { display:flex; gap:.25rem; flex-wrap:wrap; }
        .pagination > * { border:1px solid rgb(229 231 235); padding:.35rem .6rem; border-radius:.5rem; background:white; }
        .pagination .active { background: rgb(37 99 235); color:white; border-color: rgb(37 99 235); }
        .pagination .disabled { opacity:.5; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <!-- Header (Dashboard) -->
    <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-blue-600"></div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Sistem Informasi Buku Tamu Digital</div>
                        <div class="text-xs text-slate-500">Daftar Kunjungan</div>
                    </div>
                </div>

                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('bukutamu.kunjungan.index') }}"
                       class="rounded-lg px-3 py-2 font-medium text-blue-700 bg-blue-50 border border-blue-100">
                        Kunjungan
                    </a>
                    <a href="{{ route('bukutamu.kunjungan.create') }}"
                       class="rounded-lg px-3 py-2 font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Tambah
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-base font-semibold text-slate-900">Daftar Kunjungan</h1>
                    <p class="text-sm text-slate-500">
                        Data kunjungan beserta relasi tamu. Total:
                        <span class="font-medium text-slate-700">
                            {{ method_exists($kunjungans, 'total') ? $kunjungans->total() : count($kunjungans) }}
                        </span>
                    </p>
                </div>

                <div class="text-sm text-slate-500">
                    <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                        Route: <span class="font-mono text-slate-700">/bukutamu/kunjungan</span>
                    </span>
                </div>
            </div>

            <!-- Table (responsive) -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                ID Kunjungan
                            </th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Nama Tamu
                            </th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Tanggal Kunjungan
                            </th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Status
                            </th>
                            <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($kunjungans as $k)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 text-sm">
                                    <div class="font-mono text-slate-900">{{ $k->ID_KUNJUNGAN }}</div>
                                </td>

                                <td class="px-5 py-3 text-sm">
                                    <div class="font-medium text-slate-900">
                                        {{ optional($k->tamu)->NAMA_TAMU ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-5 py-3 text-sm text-slate-700">
                                    {{ $k->TANGGAL_KUNJUNGAN }}
                                </td>

                                <td class="px-5 py-3 text-sm">
                                    @php($status = $k->STATUS_KUNJUNGAN ?? '-')
                                    @php($isSelesai = strtolower($status) === 'selesai')

                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $isSelesai ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-1 ring-slate-200' }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-5 py-3 text-right text-sm">
                                    <a href="{{ route('bukutamu.kunjungan.show', $k->ID_KUNJUNGAN) }}"
                                       class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 font-medium text-slate-700 hover:bg-slate-50">
                                        Detail
                                        <span aria-hidden="true" class="text-slate-400">→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">
                                    Belum ada data kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500">
                    @if (method_exists($kunjungans, 'firstItem'))
                        Menampilkan
                        <span class="font-medium text-slate-700">{{ $kunjungans->firstItem() ?? 0 }}</span>
                        –
                        <span class="font-medium text-slate-700">{{ $kunjungans->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium text-slate-700">{{ $kunjungans->total() }}</span>
                        data
                    @else
                        Total: <span class="font-medium text-slate-700">{{ count($kunjungans) }}</span> data
                    @endif
                </div>

                <div class="text-sm">
                    @if (method_exists($kunjungans, 'links'))
                        {{ $kunjungans->links() }}
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
