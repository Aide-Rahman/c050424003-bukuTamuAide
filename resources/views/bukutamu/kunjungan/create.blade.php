<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kunjungan - Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-5xl px-4 py-4 flex items-center justify-between">
        <div>
            <div class="text-sm font-semibold text-slate-900">Sistem Informasi Buku Tamu Digital</div>
            <div class="text-xs text-slate-500">Tambah Kunjungan</div>
        </div>
        <a href="{{ route('bukutamu.kunjungan.index') }}" class="text-sm text-blue-700 hover:underline">Kembali</a>
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

    <form action="{{ route('bukutamu.kunjungan.store') }}" method="post" class="space-y-4">
        @csrf

        <!-- DATA TAMU -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Data Tamu</h2>
            <p class="mt-1 text-sm text-slate-500">Pilih tamu existing atau input tamu baru.</p>

            <div class="mt-4">
                <div class="flex flex-wrap gap-4 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="TAMU_MODE" value="existing"
                               {{ old('TAMU_MODE', 'existing') === 'existing' ? 'checked' : '' }}
                               onchange="toggleTamuMode()">
                        <span>Pilih Tamu Existing</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="TAMU_MODE" value="new"
                               {{ old('TAMU_MODE') === 'new' ? 'checked' : '' }}
                               onchange="toggleTamuMode()">
                        <span>Input Tamu Baru</span>
                    </label>
                </div>
                @error('TAMU_MODE')
                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <!-- Existing tamu -->
            <div id="tamu-existing" class="mt-4">
                <label class="block text-sm font-medium text-slate-700">Tamu</label>
                <select name="ID_TAMU"
                        class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                    <option value="">-- Pilih Tamu --</option>
                    @foreach ($tamus as $t)
                        <option value="{{ $t->ID_TAMU }}" @selected(old('ID_TAMU') === $t->ID_TAMU)>
                            {{ $t->ID_TAMU }} - {{ $t->NAMA_TAMU }} ({{ $t->INSTANSI }})
                        </option>
                    @endforeach
                </select>
                @error('ID_TAMU')
                    <p class="mt-2 text-sm text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <!-- New tamu -->
            <div id="tamu-new" class="mt-4 hidden">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">NAMA_TAMU</label>
                        <input type="text" name="NAMA_TAMU" value="{{ old('NAMA_TAMU') }}"
                               class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @error('NAMA_TAMU') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">INSTANSI</label>
                        <input type="text" name="INSTANSI" value="{{ old('INSTANSI') }}"
                               class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @error('INSTANSI') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">NO_HP</label>
                        <input type="text" name="NO_HP" value="{{ old('NO_HP') }}"
                               class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @error('NO_HP') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">EMAIL</label>
                        <input type="text" name="EMAIL" value="{{ old('EMAIL') }}"
                               class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @error('EMAIL') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">NO_KTP</label>
                        <input type="text" name="NO_KTP" value="{{ old('NO_KTP') }}"
                               class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @error('NO_KTP') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <p class="mt-3 text-xs text-slate-500">ID_TAMU digenerate otomatis (T01, T02, ...).</p>
            </div>
        </section>

        <!-- DATA KUNJUNGAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Data Kunjungan</h2>
            <p class="mt-1 text-sm text-slate-500">ID_KUNJUNGAN dan status tidak ditampilkan. Status otomatis <span class="font-medium">Aktif</span>.</p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">TANGGAL_KUNJUNGAN</label>
                    <input type="date" name="TANGGAL_KUNJUNGAN" value="{{ old('TANGGAL_KUNJUNGAN') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    @error('TANGGAL_KUNJUNGAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">JAM_MASUK</label>
                    <input type="time" name="JAM_MASUK" value="{{ old('JAM_MASUK') }}"
                           class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    @error('JAM_MASUK') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">CATATAN</label>
                    <textarea name="CATATAN" rows="3"
                              class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">{{ old('CATATAN') }}</textarea>
                    @error('CATATAN') <p class="mt-2 text-sm text-rose-700">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <!-- KEPERLUAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-slate-900">Keperluan (MEMILIKI)</h2>

            @php($oldKeperluan = old('ID_KEPERLUAN', []))
            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($keperluans as $kp)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="ID_KEPERLUAN[]" value="{{ $kp->ID_KEPERLUAN }}" class="mt-1"
                               @checked(in_array($kp->ID_KEPERLUAN, $oldKeperluan, true))>
                        <span>
                            <span class="font-medium">{{ $kp->NAMA_KEPERLUAN }}</span>
                            <span class="text-slate-500">({{ $kp->ID_KEPERLUAN }})</span>
                            @if($kp->KETERANGAN)
                                <div class="text-xs text-slate-500">{{ $kp->KETERANGAN }}</div>
                            @endif
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

            @php($oldNik = old('NIK', []))
            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($pegawais as $p)
                    <label class="flex items-start gap-2 text-sm">
                        <input type="checkbox" name="NIK[]" value="{{ $p->NIK }}" class="mt-1"
                               @checked(in_array($p->NIK, $oldNik, true))>
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
            <a href="{{ route('bukutamu.kunjungan.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Simpan Kunjungan
            </button>
        </div>
    </form>
</main>

<script>
    function toggleTamuMode() {
        const mode = document.querySelector('input[name="TAMU_MODE"]:checked')?.value || 'existing';
        const existing = document.getElementById('tamu-existing');
        const newBox = document.getElementById('tamu-new');

        if (mode === 'new') {
            existing.classList.add('hidden');
            newBox.classList.remove('hidden');
        } else {
            newBox.classList.add('hidden');
            existing.classList.remove('hidden');
        }
    }
    toggleTamuMode();
</script>
</body>
</html>
