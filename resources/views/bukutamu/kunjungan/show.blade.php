<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50/50 text-slate-800">

<header class="sticky top-0 z-10 border-b border-slate-200 bg-white/80 backdrop-blur-md">
    <div class="mx-auto max-w-5xl px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-900 leading-tight">Buku Tamu Digital</div>
                <div class="text-[11px] font-medium uppercase tracking-wider text-slate-500">Detail Record</div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('bukutamu.kunjungan.index') }}" class="hidden sm:block text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors">Kembali</a>
            <div class="h-6 w-[1px] bg-slate-200 hidden sm:block"></div>
            <form method="post" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Logout</button>
            </form>
        </div>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }}</h1>
            <p class="text-sm text-slate-500 italic">Terdaftar pada sistem informasi buku tamu</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('bukutamu.kunjungan.edit', $kunjungan->ID_KUNJUNGAN) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                   <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
               </svg>
               Edit Data
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition-hover hover:shadow-md">
                <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600">Informasi Utama</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-8">
                        <div>
                            <span class="text-xs font-semibold uppercase text-slate-400">Nama Tamu</span>
                            <h3 class="text-xl font-bold text-slate-900">{{ optional($kunjungan->tamu)->NAMA_TAMU ?? 'Tanpa Nama' }}</h3>
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-xs font-semibold uppercase text-slate-400 block mb-1">Status</span>
                            @php($status = $kunjungan->STATUS_KUNJUNGAN ?? 'Pending')
                            @php($isSelesai = strtolower($status) === 'selesai')
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider
                                {{ $isSelesai ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-300' : 'bg-amber-100 text-amber-700 ring-1 ring-amber-300' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isSelesai ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="group flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-colors hover:bg-slate-50">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 group-hover:bg-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold uppercase text-slate-400">Tanggal</div>
                                <div class="text-sm font-semibold text-slate-900">{{ $kunjungan->TANGGAL_KUNJUNGAN }}</div>
                            </div>
                        </div>

                        <div class="group flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-colors hover:bg-slate-50">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 group-hover:bg-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold uppercase text-slate-400">Jam Masuk / Keluar</div>
                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $kunjungan->JAM_MASUK ?? '--:--' }} <span class="mx-1 text-slate-300">→</span> {{ $kunjungan->JAM_KELUAR ?? '--:--' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="text-[11px] font-bold uppercase text-slate-400 mb-2">Catatan Tambahan</div>
                        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 border border-slate-100 italic">
                            {{ $kunjungan->CATATAN ?: 'Tidak ada catatan khusus untuk kunjungan ini.' }}
                        </div>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600">Pegawai yang Ditemui</h2>
                </div>
                @if ($kunjungan->pegawai->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-sm text-slate-500 italic">Belum ada pegawai yang didata untuk kunjungan ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-3 text-[11px] font-bold uppercase text-slate-500">NIK / Identitas</th>
                                    <th class="px-6 py-3 text-[11px] font-bold uppercase text-slate-500">Nama Pegawai</th>
                                    <th class="px-6 py-3 text-[11px] font-bold uppercase text-slate-500">Unit Kerja</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($kunjungan->pegawai as $p)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $p->NIK }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $p->NAMA_PEGAWAI }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                                {{ optional($p->unit)->NAMA_UNIT ?? 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Keperluan Kunjungan</h2>
                @if ($kunjungan->keperluan->isEmpty())
                    <p class="text-sm text-slate-400 italic">Tidak ada rincian keperluan.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach ($kunjungan->keperluan as $kp)
                            <div class="flex items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 shadow-sm shadow-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $kp->NAMA_KEPERLUAN }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <div class="rounded-2xl border border-rose-100 bg-rose-50/30 p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-rose-500 mb-3">Tindakan Berbahaya</h2>
                <p class="text-xs text-rose-600/70 mb-4 leading-relaxed">Menghapus kunjungan ini akan menghilangkan seluruh riwayat data terkait secara permanen.</p>
                
                <form action="{{ route('bukutamu.kunjungan.destroy', $kunjungan->ID_KUNJUNGAN) }}" method="post" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus kunjungan ini?')"
                            class="w-full rounded-xl bg-white border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-95">
                        Hapus Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

</body>
</html>