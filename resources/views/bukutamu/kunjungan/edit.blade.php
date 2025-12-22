<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kunjungan #{{ $kunjungan->ID_KUNJUNGAN }} - Buku Tamu Digital</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Checkbox Style */
        .custom-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] text-slate-800">

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md">
    <div class="mx-auto max-w-5xl px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-md shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-900 leading-none">Buku Tamu Digital</div>
                <div class="text-[11px] font-medium uppercase tracking-widest text-slate-500 mt-1">Panel Penyuntingan</div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">Batal</a>
            <div class="h-5 w-px bg-slate-200"></div>
            <form method="post" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="text-sm font-bold text-rose-500 hover:text-rose-600 transition-colors">Logout</button>
            </form>
        </div>
    </div>
</header>

<main class="mx-auto max-w-4xl px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Data Kunjungan</h1>
        <p class="text-sm text-slate-500 mt-1">
            Mengubah entri kunjungan <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">#{{ $kunjungan->ID_KUNJUNGAN }}</span>
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm animate-in fade-in slide-in-from-top-2">
            <div class="flex items-center gap-2 font-bold mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Ups! Ada beberapa kesalahan input:
            </div>
            <ul class="list-disc pl-5 space-y-1 font-medium italic">
                @foreach ($errors->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bukutamu.kunjungan.update', $kunjungan->ID_KUNJUNGAN) }}" method="post" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 mb-5 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                Identitas Tamu
            </h2>
            
            <div class="space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Pilih Tamu Terdaftar</label>
                <div class="relative group">
                    <select name="ID_TAMU" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none appearance-none cursor-pointer">
                        <option value="">-- Silakan Pilih Tamu --</option>
                        @foreach ($tamus as $t)
                            <option value="{{ $t->ID_TAMU }}" @selected(old('ID_TAMU', $kunjungan->ID_TAMU) == $t->ID_TAMU)>
                                {{ $t->ID_TAMU }} - {{ $t->NAMA_TAMU }} ({{ $t->INSTANSI }})
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 group-hover:text-blue-500 transition-colors">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 mb-5 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Detail Waktu & Status
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Tanggal Kunjungan</label>
                    <input type="date" name="TANGGAL_KUNJUNGAN" 
                           value="{{ old('TANGGAL_KUNJUNGAN', $kunjungan->TANGGAL_KUNJUNGAN) }}" 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Status Kunjungan</label>
                    @php($statusOld = old('STATUS_KUNJUNGAN', $kunjungan->STATUS_KUNJUNGAN))
                    <select name="STATUS_KUNJUNGAN" 
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none cursor-pointer">
                        <option value="Aktif" @selected($statusOld === 'Aktif')>Aktif</option>
                        <option value="Selesai" @selected($statusOld === 'Selesai')>Selesai</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Jam Masuk</label>
                    <input type="time" name="JAM_MASUK" 
                           value="{{ old('JAM_MASUK', $JAM_MASUK_HI ?? null) }}" 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Jam Keluar</label>
                    <input type="time" name="JAM_KELUAR" 
                           value="{{ old('JAM_KELUAR', $JAM_KELUAR_HI ?? null) }}" 
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 ml-1">Catatan Tambahan</label>
                    <textarea name="CATATAN" rows="3" placeholder="Tuliskan alasan atau keterangan tambahan jika ada..."
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 outline-none">{{ old('CATATAN', $kunjungan->CATATAN) }}</textarea>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 mb-5 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                Rincian Keperluan (MEMILIKI)
            </h2>
            
            @php($selectedKeperluan = old('ID_KEPERLUAN', $kunjungan->keperluan->pluck('ID_KEPERLUAN')->all()))
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($keperluans as $kp)
                    <label class="flex items-start gap-3 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 cursor-pointer hover:bg-white hover:border-blue-200 hover:shadow-md hover:shadow-blue-50 transition-all group">
                        <input type="checkbox" name="ID_KEPERLUAN[]" value="{{ $kp->ID_KEPERLUAN }}" 
                               class="custom-checkbox mt-1 h-5 w-5 appearance-none rounded-md border border-slate-300 transition-all checked:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                               @checked(in_array($kp->ID_KEPERLUAN, $selectedKeperluan, true))>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $kp->NAMA_KEPERLUAN }}</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-200/50 px-1.5 py-0.5 rounded leading-none uppercase">{{ $kp->ID_KEPERLUAN }}</span>
                            </div>
                            
                            @if($kp->KETERANGAN)
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed italic group-hover:text-slate-600">
                                    "{{ $kp->KETERANGAN }}"
                                </p>
                            @else
                                <p class="mt-1 text-[10px] text-slate-400 italic">Tidak ada deskripsi tambahan.</p>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 mb-5 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                Pegawai yang Ditemui (MENEMUI)
            </h2>

            @php($selectedNik = old('NIK', $kunjungan->pegawai->pluck('NIK')->all()))
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($pegawais as $p)
                    <label class="flex items-start gap-3 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 cursor-pointer hover:bg-white hover:border-blue-200 hover:shadow-md hover:shadow-blue-50 transition-all group">
                        <input type="checkbox" name="NIK[]" value="{{ $p->NIK }}" 
                               class="custom-checkbox mt-1 h-5 w-5 appearance-none rounded-md border border-slate-300 transition-all checked:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                               @checked(in_array($p->NIK, $selectedNik, true))>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $p->NAMA_PEGAWAI }}</span>
                            <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1">
                                <span class="text-[11px] font-medium text-slate-400">NIK: {{ $p->NIK }}</span>
                                <span class="text-[11px] font-bold text-blue-500 uppercase">{{ optional($p->unit)->NAMA_UNIT ?? 'TANPA UNIT' }}</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </section>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
            <div class="hidden sm:block">
                <p class="text-[11px] text-slate-400 font-medium">Pastikan data yang diubah sudah sesuai dengan dokumen fisik tamu.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}"
                   class="flex-1 sm:flex-none text-center rounded-xl border border-slate-200 bg-white px-8 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 sm:flex-none rounded-xl bg-blue-600 px-10 py-3 text-sm font-bold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                    Update Data
                </button>
            </div>
        </div>
    </form>
</main>

<footer class="mt-12 mb-8 text-center text-[11px] font-medium text-slate-400 uppercase tracking-widest">
    &copy; {{ date('Y') }} Sistem Informasi Buku Tamu Digital
</footer>

</body>
</html>