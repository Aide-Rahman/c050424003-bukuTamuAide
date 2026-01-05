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

    public function up(): void
    {
        // Guarded indexes: project may be connected to an existing DB.
        // Only add indexes when the table/columns exist.

        if (Schema::hasTable('KUNJUNGAN')) {
            Schema::table('KUNJUNGAN', function (Blueprint $table) {
                if (Schema::hasColumn('KUNJUNGAN', 'TANGGAL_KUNJUNGAN')) {
                    if (!$this->indexExists('KUNJUNGAN', 'idx_kunjungan_tanggal')) {
                        $table->index('TANGGAL_KUNJUNGAN', 'idx_kunjungan_tanggal');
                    }
                }
                if (Schema::hasColumn('KUNJUNGAN', 'STATUS_KUNJUNGAN')) {
                    if (!$this->indexExists('KUNJUNGAN', 'idx_kunjungan_status')) {
                        $table->index('STATUS_KUNJUNGAN', 'idx_kunjungan_status');
                    }
                }
                if (Schema::hasColumn('KUNJUNGAN', 'TANGGAL_KUNJUNGAN') && Schema::hasColumn('KUNJUNGAN', 'ID_KUNJUNGAN')) {
                    if (!$this->indexExists('KUNJUNGAN', 'idx_kunjungan_tanggal_id')) {
                        $table->index(['TANGGAL_KUNJUNGAN', 'ID_KUNJUNGAN'], 'idx_kunjungan_tanggal_id');
                    }
                }
                if (Schema::hasColumn('KUNJUNGAN', 'STATUS_KUNJUNGAN') && Schema::hasColumn('KUNJUNGAN', 'TANGGAL_KUNJUNGAN')) {
                    if (!$this->indexExists('KUNJUNGAN', 'idx_kunjungan_status_tanggal')) {
                        $table->index(['STATUS_KUNJUNGAN', 'TANGGAL_KUNJUNGAN'], 'idx_kunjungan_status_tanggal');
                    }
                }
            });
        }

        if (Schema::hasTable('MENEMUI')) {
            Schema::table('MENEMUI', function (Blueprint $table) {
                if (Schema::hasColumn('MENEMUI', 'ID_KUNJUNGAN')) {
                    if (!$this->indexExists('MENEMUI', 'idx_menemui_kunjungan')) {
                        $table->index('ID_KUNJUNGAN', 'idx_menemui_kunjungan');
                    }
                }
                if (Schema::hasColumn('MENEMUI', 'NIK')) {
                    if (!$this->indexExists('MENEMUI', 'idx_menemui_nik')) {
                        $table->index('NIK', 'idx_menemui_nik');
                    }
                }
                if (Schema::hasColumn('MENEMUI', 'ID_KUNJUNGAN') && Schema::hasColumn('MENEMUI', 'NIK')) {
                    if (!$this->indexExists('MENEMUI', 'idx_menemui_kunjungan_nik')) {
                        $table->index(['ID_KUNJUNGAN', 'NIK'], 'idx_menemui_kunjungan_nik');
                    }
                }
            });
        }

        if (Schema::hasTable('MEMILIKI')) {
            Schema::table('MEMILIKI', function (Blueprint $table) {
                if (Schema::hasColumn('MEMILIKI', 'ID_KUNJUNGAN')) {
                    if (!$this->indexExists('MEMILIKI', 'idx_memiliki_kunjungan')) {
                        $table->index('ID_KUNJUNGAN', 'idx_memiliki_kunjungan');
                    }
                }
                if (Schema::hasColumn('MEMILIKI', 'ID_KEPERLUAN')) {
                    if (!$this->indexExists('MEMILIKI', 'idx_memiliki_keperluan')) {
                        $table->index('ID_KEPERLUAN', 'idx_memiliki_keperluan');
                    }
                }
                if (Schema::hasColumn('MEMILIKI', 'ID_KUNJUNGAN') && Schema::hasColumn('MEMILIKI', 'ID_KEPERLUAN')) {
                    if (!$this->indexExists('MEMILIKI', 'idx_memiliki_kunjungan_keperluan')) {
                        $table->index(['ID_KUNJUNGAN', 'ID_KEPERLUAN'], 'idx_memiliki_kunjungan_keperluan');
                    }
                }
            });
        }

        if (Schema::hasTable('PEGAWAI')) {
            Schema::table('PEGAWAI', function (Blueprint $table) {
                if (Schema::hasColumn('PEGAWAI', 'ID_UNIT')) {
                    if (!$this->indexExists('PEGAWAI', 'idx_pegawai_unit')) {
                        $table->index('ID_UNIT', 'idx_pegawai_unit');
                    }
                }
                if (Schema::hasColumn('PEGAWAI', 'NAMA_PEGAWAI')) {
                    if (!$this->indexExists('PEGAWAI', 'idx_pegawai_nama')) {
                        $table->index('NAMA_PEGAWAI', 'idx_pegawai_nama');
                    }
                }
            });
        }

        if (Schema::hasTable('TAMU')) {
            Schema::table('TAMU', function (Blueprint $table) {
                if (Schema::hasColumn('TAMU', 'NAMA_TAMU')) {
                    if (!$this->indexExists('TAMU', 'idx_tamu_nama')) {
                        $table->index('NAMA_TAMU', 'idx_tamu_nama');
                    }
                }
            });
        }

        if (Schema::hasTable('UNIT')) {
            Schema::table('UNIT', function (Blueprint $table) {
                if (Schema::hasColumn('UNIT', 'NAMA_UNIT')) {
                    if (!$this->indexExists('UNIT', 'idx_unit_nama')) {
                        $table->index('NAMA_UNIT', 'idx_unit_nama');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('KUNJUNGAN')) {
            Schema::table('KUNJUNGAN', function (Blueprint $table) {
                if ($this->indexExists('KUNJUNGAN', 'idx_kunjungan_tanggal')) {
                    $table->dropIndex('idx_kunjungan_tanggal');
                }
                if ($this->indexExists('KUNJUNGAN', 'idx_kunjungan_status')) {
                    $table->dropIndex('idx_kunjungan_status');
                }
                if ($this->indexExists('KUNJUNGAN', 'idx_kunjungan_tanggal_id')) {
                    $table->dropIndex('idx_kunjungan_tanggal_id');
                }
                if ($this->indexExists('KUNJUNGAN', 'idx_kunjungan_status_tanggal')) {
                    $table->dropIndex('idx_kunjungan_status_tanggal');
                }
            });
        }

        if (Schema::hasTable('MENEMUI')) {
            Schema::table('MENEMUI', function (Blueprint $table) {
                if ($this->indexExists('MENEMUI', 'idx_menemui_kunjungan')) {
                    $table->dropIndex('idx_menemui_kunjungan');
                }
                if ($this->indexExists('MENEMUI', 'idx_menemui_nik')) {
                    $table->dropIndex('idx_menemui_nik');
                }
                if ($this->indexExists('MENEMUI', 'idx_menemui_kunjungan_nik')) {
                    $table->dropIndex('idx_menemui_kunjungan_nik');
                }
            });
        }

        if (Schema::hasTable('MEMILIKI')) {
            Schema::table('MEMILIKI', function (Blueprint $table) {
                if ($this->indexExists('MEMILIKI', 'idx_memiliki_kunjungan')) {
                    $table->dropIndex('idx_memiliki_kunjungan');
                }
                if ($this->indexExists('MEMILIKI', 'idx_memiliki_keperluan')) {
                    $table->dropIndex('idx_memiliki_keperluan');
                }
                if ($this->indexExists('MEMILIKI', 'idx_memiliki_kunjungan_keperluan')) {
                    $table->dropIndex('idx_memiliki_kunjungan_keperluan');
                }
            });
        }

        if (Schema::hasTable('PEGAWAI')) {
            Schema::table('PEGAWAI', function (Blueprint $table) {
                if ($this->indexExists('PEGAWAI', 'idx_pegawai_unit')) {
                    $table->dropIndex('idx_pegawai_unit');
                }
                if ($this->indexExists('PEGAWAI', 'idx_pegawai_nama')) {
                    $table->dropIndex('idx_pegawai_nama');
                }
            });
        }

        if (Schema::hasTable('TAMU')) {
            Schema::table('TAMU', function (Blueprint $table) {
                if ($this->indexExists('TAMU', 'idx_tamu_nama')) {
                    $table->dropIndex('idx_tamu_nama');
                }
            });
        }

        if (Schema::hasTable('UNIT')) {
            Schema::table('UNIT', function (Blueprint $table) {
                if ($this->indexExists('UNIT', 'idx_unit_nama')) {
                    $table->dropIndex('idx_unit_nama');
                }
            });
        }
    }
};
