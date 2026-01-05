<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $tableName, string $indexName): bool
    {
        $driver = DB::getDriverName();
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return false;
        }

        $row = DB::selectOne(
            'SELECT 1 as x FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1',
            [$tableName, $indexName]
        );

        return $row !== null;
    }

    private function assertNoDuplicates(string $tableName, array $columns): void
    {
        $query = DB::table($tableName)
            ->select(array_merge($columns, [DB::raw('COUNT(*) as duplicate_count')]))
            ->groupBy($columns)
            ->havingRaw('COUNT(*) > 1')
            ->limit(5);

        $duplicates = $query->get();
        if ($duplicates->isEmpty()) {
            return;
        }

        $columnsStr = implode(', ', $columns);
        $example = $duplicates->map(fn ($row) => (array) $row)->all();

        throw new RuntimeException(
            "Cannot add UNIQUE constraint on {$tableName} ({$columnsStr}) because duplicate rows exist. "
            . 'Clean duplicates first. Example duplicates: ' . json_encode($example)
        );
    }

    public function up(): void
    {
        // Data integrity: prevent duplicate pivot rows.
        // Guarded so tests (SQLite) and fresh installs without these tables won't fail.

        if (Schema::hasTable('MENEMUI')
            && Schema::hasColumn('MENEMUI', 'ID_KUNJUNGAN')
            && Schema::hasColumn('MENEMUI', 'NIK')) {
            $indexName = 'uq_menemui_kunjungan_nik';

            if (!$this->indexExists('MENEMUI', $indexName)) {
                $this->assertNoDuplicates('MENEMUI', ['ID_KUNJUNGAN', 'NIK']);

                Schema::table('MENEMUI', function (Blueprint $table) use ($indexName) {
                    $table->unique(['ID_KUNJUNGAN', 'NIK'], $indexName);
                });
            }
        }

        if (Schema::hasTable('MEMILIKI')
            && Schema::hasColumn('MEMILIKI', 'ID_KUNJUNGAN')
            && Schema::hasColumn('MEMILIKI', 'ID_KEPERLUAN')) {
            $indexName = 'uq_memiliki_kunjungan_keperluan';

            if (!$this->indexExists('MEMILIKI', $indexName)) {
                $this->assertNoDuplicates('MEMILIKI', ['ID_KUNJUNGAN', 'ID_KEPERLUAN']);

                Schema::table('MEMILIKI', function (Blueprint $table) use ($indexName) {
                    $table->unique(['ID_KUNJUNGAN', 'ID_KEPERLUAN'], $indexName);
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('MENEMUI')) {
            $indexName = 'uq_menemui_kunjungan_nik';

            Schema::table('MENEMUI', function (Blueprint $table) use ($indexName) {
                if ($this->indexExists('MENEMUI', $indexName)) {
                    $table->dropUnique($indexName);
                }
            });
        }

        if (Schema::hasTable('MEMILIKI')) {
            $indexName = 'uq_memiliki_kunjungan_keperluan';

            Schema::table('MEMILIKI', function (Blueprint $table) use ($indexName) {
                if ($this->indexExists('MEMILIKI', $indexName)) {
                    $table->dropUnique($indexName);
                }
            });
        }
    }
};
