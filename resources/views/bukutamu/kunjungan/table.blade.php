<div class="glass-panel" id="kunjungan-table-wrapper">
    <div class="panel-header">
        <div style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">Database Tamu</div>
        <div class="status-badge status-selesai" style="font-size: 0.7rem;">
            {{ method_exists($kunjungans, 'total') ? $kunjungans->total() : count($kunjungans) }} RECORD
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="35%">Tamu / Pengunjung</th>
                    <th width="25%">Waktu Masuk</th>
                    <th width="15%">Status</th>
                    <th width="15%" style="text-align:right">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kunjungans as $k)
                    <tr>
                        <td>
                            <span class="id-cell">#{{ $k->ID_KUNJUNGAN }}</span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:14px;">
                                <div class="avatar-circle">
                                    {{ substr(optional($k->tamu)->NAMA_TAMU ?? '?', 0, 2) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; color:white;">{{ optional($k->tamu)->NAMA_TAMU ?? '-' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top:2px;">
                                        {{ optional($k->tamu)->INSTANSI ?? 'Umum' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--text-secondary); font-weight:500;">
                            {{ $k->TANGGAL_KUNJUNGAN }}
                            <div style="font-size:0.75rem; color:var(--text-tertiary); margin-top:2px;">
                                {{ $k->JAM_MASUK ? substr($k->JAM_MASUK, 0, 5) : '-' }} WITA
                            </div>
                        </td>
                        <td>
                            @php($status = $k->STATUS_KUNJUNGAN ?? '-')
                            @php($isAktif = strtolower($status) === 'aktif')
                            <span class="status-badge {{ $isAktif ? 'status-aktif' : 'status-selesai' }}">
                                <span class="status-dot"></span> {{ $status }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('bukutamu.kunjungan.show', $k->ID_KUNJUNGAN) }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 80px 0;">
                            <div style="opacity: 0.3; margin-bottom: 1rem;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                            </div>
                            <div style="color: var(--text-secondary); font-size:1rem;">Belum ada data kunjungan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div style="font-size: 0.85rem; color: var(--text-secondary);">
            @if (method_exists($kunjungans, 'firstItem'))
                Menampilkan <span style="color:white; font-weight:600;">{{ $kunjungans->firstItem() ?? 0 }} - {{ $kunjungans->lastItem() ?? 0 }}</span> dari {{ $kunjungans->total() }}
            @else
                Total: {{ count($kunjungans) }}
            @endif
        </div>
        <div class="pagination-simple">
             @if (method_exists($kunjungans, 'links'))
                @if ($kunjungans->onFirstPage())
                    <span class="page-link" style="opacity:0.3; cursor:not-allowed;">&laquo;</span>
                @else
                    <a href="{{ $kunjungans->previousPageUrl() }}" class="page-link">&laquo;</a>
                @endif

                <span class="page-link current" style="border:none;">{{ $kunjungans->currentPage() }}</span>

                @if ($kunjungans->hasMorePages())
                    <a href="{{ $kunjungans->nextPageUrl() }}" class="page-link">&raquo;</a>
                @else
                    <span class="page-link" style="opacity:0.3; cursor:not-allowed;">&raquo;</span>
                @endif
            @endif
        </div>
    </div>
</div>