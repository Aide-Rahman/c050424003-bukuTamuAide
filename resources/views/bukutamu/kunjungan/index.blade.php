<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu Digital - Daftar Kunjungan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Pagination Styling */
        .pagination { display: flex; gap: 0.4rem; flex-wrap: wrap; }
        .pagination > * { 
            border: 1px solid #e2e8f0; padding: 0.5rem 0.8rem; border-radius: 0.75rem; 
            background: white; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;
        }
        .pagination .active { background: #2563eb; color: white; border-color: #2563eb; shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }
        .pagination a:hover:not(.active) { background: #f8fafc; border-color: #cbd5e1; }
        .pagination .disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] text-slate-800">

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 shadow-md shadow-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 leading-none">Buku Tamu Digital</h1>
                        <p class="mt-1 text-[11px] font-medium uppercase tracking-wider text-slate-500">Dashboard Panel</p>
                    </div>
                </div>

                <nav class="flex items-center gap-3">
                    <a href="{{ route('bukutamu.kunjungan.index') }}" class="hidden md:block rounded-lg px-3 py-2 text-sm font-semibold text-blue-600 bg-blue-50">
                        Monitoring
                    </a>
                    <div class="h-6 w-px bg-slate-200 mx-1 hidden md:block"></div>
                    <form method="post" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-rose-500 hover:text-rose-600 transition-colors">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Kunjungan</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau seluruh tamu yang hadir di instansi Anda.</p>
            </div>
            <a href="{{ route('bukutamu.kunjungan.create') }}" 
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Kunjungan
            </a>
        </div>

        @if (session('status'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 animate-in fade-in duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/60">
            
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Database Tamu</span>
                </div>
                <div class="rounded-lg bg-white px-3 py-1 text-[11px] font-bold text-slate-400 border border-slate-200 shadow-sm">
                    {{ method_exists($kunjungans, 'total') ? $kunjungans->total() : count($kunjungans) }} TOTAL RECORD
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">ID</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tamu / Pengunjung</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Waktu Kunjungan</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 text-right">Opsi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50">
                        @forelse ($kunjungans as $k)
                            <tr class="group hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-blue-500 transition-colors">#{{ $k->ID_KUNJUNGAN }}</span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-500 text-xs border border-white shadow-sm uppercase">
                                            {{ substr(optional($k->tamu)->NAMA_TAMU ?? '?', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-900 leading-tight">
                                                {{ optional($k->tamu)->NAMA_TAMU ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $k->TANGGAL_KUNJUNGAN }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php($status = $k->STATUS_KUNJUNGAN ?? '-')
                                    @php($isSelesai = strtolower($status) === 'selesai')
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                                        {{ $isSelesai ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-600 ring-1 ring-amber-200' }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('bukutamu.kunjungan.show', $k->ID_KUNJUNGAN) }}"
                                       class="inline-flex items-center gap-2 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-slate-600 border border-slate-200 hover:border-blue-400 hover:text-blue-600 hover:shadow-sm transition-all active:bg-slate-50">
                                        Detail
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 opacity-50" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-slate-50 p-4 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900">Belum ada data</h3>
                                        <p class="text-xs text-slate-500 mt-1">Klik tombol 'Tambah Kunjungan' untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 border-t border-slate-100 bg-slate-50/30 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-[11px] font-bold uppercase tracking-widest text-slate-400">
                    @if (method_exists($kunjungans, 'firstItem'))
                        Menampilkan <span class="text-slate-700">{{ $kunjungans->firstItem() ?? 0 }}-{{ $kunjungans->lastItem() ?? 0 }}</span> dari <span class="text-slate-700">{{ $kunjungans->total() }}</span> entri
                    @else
                        Total: <span class="text-slate-700">{{ count($kunjungans) }}</span> entri
                    @endif
                </div>

                <div class="flex items-center">
                    @if (method_exists($kunjungans, 'links'))
                        {{ $kunjungans->links() }}
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>