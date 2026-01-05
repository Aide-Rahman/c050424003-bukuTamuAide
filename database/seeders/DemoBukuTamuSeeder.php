<?php

namespace Database\Seeders;

use App\Models\Keperluan;
use App\Models\Kunjungan;
use App\Models\Pegawai;
use App\Models\Tamu;
use App\Models\Unit;
use App\Support\IdGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoBukuTamuSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder ini dibuat untuk LOCAL DEMO.
        // Aman dijalankan berulang: memakai firstOrCreate/updateOrInsert dan sync.
        // Juga aman bila tabel domain belum ada (mis. pada sqlite :memory: untuk tests).

        if (!Schema::hasTable('UNIT')
            || !Schema::hasTable('PEGAWAI')
            || !Schema::hasTable('KEPERLUAN')
            || !Schema::hasTable('TAMU')
            || !Schema::hasTable('KUNJUNGAN')
            || !Schema::hasTable('MENEMUI')
            || !Schema::hasTable('MEMILIKI')) {
            $this->command?->warn('DemoBukuTamuSeeder: tabel domain tidak lengkap; seeding dilewati.');
            return;
        }

        DB::transaction(function () {
            // UNIT
            $unitTu = Unit::query()->firstOrCreate(
                ['ID_UNIT' => 'U01'],
                ['NAMA_UNIT' => 'Tata Usaha', 'LOKASI_UNIT' => 'Lantai 1']
            );
            $unitIt = Unit::query()->firstOrCreate(
                ['ID_UNIT' => 'U02'],
                ['NAMA_UNIT' => 'IT Support', 'LOKASI_UNIT' => 'Lantai 2']
            );

            // PEGAWAI
            $pegawai1 = Pegawai::query()->firstOrCreate(
                ['NIK' => '1987654321000001'],
                ['ID_UNIT' => $unitTu->ID_UNIT, 'NAMA_PEGAWAI' => 'Siti Nurhaliza', 'JABATAN' => 'Staf', 'EMAIL' => 'siti@example.local']
            );
            $pegawai2 = Pegawai::query()->firstOrCreate(
                ['NIK' => '1987654321000002'],
                ['ID_UNIT' => $unitIt->ID_UNIT, 'NAMA_PEGAWAI' => 'Andi Pratama', 'JABATAN' => 'Teknisi', 'EMAIL' => 'andi@example.local']
            );

            // KEPERLUAN
            $keperluan1 = Keperluan::query()->firstOrCreate(
                ['ID_KEPERLUAN' => 'K01'],
                ['NAMA_KEPERLUAN' => 'Administrasi', 'KETERANGAN' => 'Pengurusan administrasi']
            );
            $keperluan2 = Keperluan::query()->firstOrCreate(
                ['ID_KEPERLUAN' => 'K02'],
                ['NAMA_KEPERLUAN' => 'Konsultasi', 'KETERANGAN' => 'Konsultasi dengan pegawai']
            );

            // TAMU
            $tamuA = Tamu::query()->where('NAMA_TAMU', 'Budi Santoso')->first();
            if (!$tamuA) {
                $tamuA = Tamu::query()->create([
                    'ID_TAMU' => IdGenerator::nextTamuId(),
                    'NAMA_TAMU' => 'Budi Santoso',
                    'INSTANSI' => 'PT Maju Jaya',
                    'NO_HP' => '081234567890',
                    'EMAIL' => 'budi@example.local',
                    'NO_KTP' => '3201010101010101',
                ]);
            }

            $tamuB = Tamu::query()->where('NAMA_TAMU', 'Rina Wulandari')->first();
            if (!$tamuB) {
                $tamuB = Tamu::query()->create([
                    'ID_TAMU' => IdGenerator::nextTamuId(),
                    'NAMA_TAMU' => 'Rina Wulandari',
                    'INSTANSI' => 'CV Sejahtera',
                    'NO_HP' => '089876543210',
                    'EMAIL' => 'rina@example.local',
                    'NO_KTP' => '3273010101010101',
                ]);
            }

            // KUNJUNGAN
            $today = now()->toDateString();

            $kunjunganAktif = Kunjungan::query()
                ->where('ID_TAMU', $tamuA->ID_TAMU)
                ->where('TANGGAL_KUNJUNGAN', $today)
                ->where('STATUS_KUNJUNGAN', 'Aktif')
                ->first();

            if (!$kunjunganAktif) {
                $kunjunganAktif = Kunjungan::query()->create([
                    'ID_KUNJUNGAN' => IdGenerator::nextKunjunganId(),
                    'ID_TAMU' => $tamuA->ID_TAMU,
                    'TANGGAL_KUNJUNGAN' => $today,
                    'JAM_MASUK' => '09:00:00',
                    'JAM_KELUAR' => null,
                    'STATUS_KUNJUNGAN' => 'Aktif',
                    'CATATAN' => 'Data demo (aktif).',
                ]);

                $kunjunganAktif->keperluan()->sync([$keperluan1->ID_KEPERLUAN]);
                $kunjunganAktif->pegawai()->sync([$pegawai1->NIK]);
            }

            $kunjunganSelesai = Kunjungan::query()
                ->where('ID_TAMU', $tamuB->ID_TAMU)
                ->where('TANGGAL_KUNJUNGAN', $today)
                ->where('STATUS_KUNJUNGAN', 'Selesai')
                ->first();

            if (!$kunjunganSelesai) {
                $kunjunganSelesai = Kunjungan::query()->create([
                    'ID_KUNJUNGAN' => IdGenerator::nextKunjunganId(),
                    'ID_TAMU' => $tamuB->ID_TAMU,
                    'TANGGAL_KUNJUNGAN' => $today,
                    'JAM_MASUK' => '10:15:00',
                    'JAM_KELUAR' => '10:45:00',
                    'STATUS_KUNJUNGAN' => 'Selesai',
                    'CATATAN' => 'Data demo (selesai).',
                ]);

                $kunjunganSelesai->keperluan()->sync([$keperluan2->ID_KEPERLUAN]);
                $kunjunganSelesai->pegawai()->sync([$pegawai2->NIK]);
            }
        });

        $this->command?->info('DemoBukuTamuSeeder: data demo berhasil disiapkan.');
    }
}
