<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kunjungan - Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-5xl px-4 py-4 flex items-center justify-between">
        <div>
            <div class="text-sm font-semibold text-slate-900">Sistem Informasi Buku Tamu Digital</div>
            <div class="text-xs text-slate-500">Edit Kunjungan</div>
        </div>
        <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}" class="text-sm text-blue-700 hover:underline">Kembali</a>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-6">
    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
            <div class="font-semibold">Validasi gagal</div>
            <ul class="list-disc pl-5 mt-1">
                @foreach ($errors->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bukutamu.kunjungan.update', $kunjungan->ID_KUNJUNGAN) }}" method="post" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- RINGKASAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Ringkasan</h2>
            <p class="mt-1 text-sm text-slate-500">
                ID Kunjungan:
                <span class="font-mono font-semibold text-slate-900">{{ $kunjungan->ID_KUNJUNGAN }}</span>
            </p>
        </section>

        <!-- DATA TAMU (existing saja) -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Data Tamu</h2>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700">Tamu</label>
                <select name="ID_TAMU"
                        class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">-- Pilih Tamu --</option>
                    @foreach ($tamus as $t)
                        <option value="{{ $t->ID_TAMU }}" @selected(old('ID_TAMU', $kunjungan->ID_TAMU) === $t->ID_TAMU)>
                            {{ $t->ID_TAMU }} - {{ $t->NAMA_TAMU }} ({{ $t->INSTANSI }})
                        </option>
                    @endforeach
                </select>
                @error('ID_TAMU')
                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <!-- DATA KUNJUNGAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Data Kunjungan</h2>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">TANGGAL_KUNJUNGAN</label>
                    <input type="date" name="TANGGAL_KUNJUNGAN"
                           value="{{ old('TANGGAL_KUNJUNGAN', $kunjungan->TANGGAL_KUNJUNGAN) }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    @error('TANGGAL_KUNJUNGAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">STATUS_KUNJUNGAN</label>
                    @php($statusOld = old('STATUS_KUNJUNGAN', $kunjungan->STATUS_KUNJUNGAN))
                    <select name="STATUS_KUNJUNGAN"
                            class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="Aktif" @selected($statusOld === 'Aktif')>Aktif</option>
                        <option value="Selesai" @selected($statusOld === 'Selesai')>Selesai</option>
                    </select>
                    @error('STATUS_KUNJUNGAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">JAM_MASUK</label>
                    <input type="time" name="JAM_MASUK"
                           value="{{ old('JAM_MASUK', $JAM_MASUK_HI ?? null) }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    @error('JAM_MASUK') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">JAM_KELUAR</label>
                    <input type="time" name="JAM_KELUAR"
                           value="{{ old('JAM_KELUAR', $JAM_KELUAR_HI ?? null) }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    @error('JAM_KELUAR') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">CATATAN</label>
                    <textarea name="CATATAN" rows="3"
                              class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">{{ old('CATATAN', $kunjungan->CATATAN) }}</textarea>
                    @error('CATATAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <!-- KEPERLUAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Keperluan (MEMILIKI)</h2>

            @php($selectedKeperluan = old('ID_KEPERLUAN', $kunjungan->keperluan->pluck('ID_KEPERLUAN')->all()))
            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($keperluans as $kp)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="ID_KEPERLUAN[]" value="{{ $kp->ID_KEPERLUAN }}" class="mt-1"
                               @checked(in_array($kp->ID_KEPERLUAN, $selectedKeperluan, true))>
                        <span>
                            <span class="font-medium">{{ $kp->NAMA_KEPERLUAN }}</span>
                            <span class="text-slate-500">({{ $kp->ID_KEPERLUAN }})</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('ID_KEPERLUAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
            @error('ID_KEPERLUAN.*') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
        </section>

        <!-- PEGAWAI -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Pegawai yang Ditemui (MENEMUI)</h2>

            @php($selectedNik = old('NIK', $kunjungan->pegawai->pluck('NIK')->all()))
            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($pegawais as $p)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="NIK[]" value="{{ $p->NIK }}" class="mt-1"
                               @checked(in_array($p->NIK, $selectedNik, true))>
                        <span>
                            <span class="font-medium">{{ $p->NAMA_PEGAWAI }}</span>
                            <div class="text-xs text-slate-500">NIK: {{ $p->NIK }} • Unit: {{ optional($p->unit)->NAMA_UNIT ?? '-' }}</div>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('NIK') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
            @error('NIK.*') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
        </section>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('bukutamu.kunjungan.show', $kunjungan->ID_KUNJUNGAN) }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Update Kunjungan
            </button>
        </div>
    </form>
</main>
</body>
</html>
