@php
    $toastType = null;
    $toastMessage = null;

    if (session()->has('error')) {
        $toastType = 'error';
        $toastMessage = session('error');
    } elseif (session()->has('warning')) {
        $toastType = 'warning';
        $toastMessage = session('warning');
    } elseif (session()->has('success')) {
        $toastType = 'success';
        $toastMessage = session('success');
    } elseif (session()->has('status')) {
        // Default Laravel habit: status biasanya sukses.
        $toastType = 'success';
        $toastMessage = session('status');
    } elseif (session()->has('info')) {
        $toastType = 'info';
        $toastMessage = session('info');
    }
@endphp

@if ($toastMessage)
    <style>
        .ld-toast {
            position: fixed;
            right: 16px;
            top: 16px;
            z-index: 9999;
            max-width: 420px;
            width: calc(100vw - 32px);
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(10,10,10,0.62);
            backdrop-filter: blur(22px) saturate(160%);
            -webkit-backdrop-filter: blur(22px) saturate(160%);
            box-shadow:
                0 30px 70px rgba(0,0,0,0.65),
                inset 0 1px 0 rgba(255,255,255,0.05);
            overflow: hidden;
            animation: ldToastIn 240ms cubic-bezier(0.25, 1, 0.5, 1);
        }
        @keyframes ldToastIn {
            from { transform: translateY(-8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .ld-toast__inner {
            display: flex;
            gap: 12px;
            padding: 12px 14px;
            align-items: flex-start;
        }
        .ld-toast__icon {
            width: 20px;
            height: 20px;
            margin-top: 1px;
            flex: 0 0 auto;
            color: rgba(255,255,255,0.92);
            opacity: 0.9;
        }
        .ld-toast__content { min-width: 0; flex: 1 1 auto; }
        .ld-toast__title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 750;
            color: rgba(255,255,255,0.85);
            margin: 0 0 4px 0;
        }
        .ld-toast__message {
            font-size: 0.95rem;
            font-weight: 650;
            color: rgba(255,255,255,0.86);
            line-height: 1.35;
            margin: 0;
            word-break: break-word;
        }
        .ld-toast__close {
            margin-left: 6px;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.03);
            color: rgba(255,255,255,0.70);
            cursor: pointer;
            flex: 0 0 auto;
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease;
        }
        .ld-toast__close:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.16);
            color: rgba(255,255,255,0.92);
        }
        .ld-toast__close:active { transform: scale(0.98); opacity: 0.92; }
        .ld-toast__bar {
            height: 2px;
            background: rgba(255,255,255,0.18);
            transform-origin: left;
            animation: ldToastBar linear forwards;
        }
        @keyframes ldToastBar {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

        /* Variasi halus per tipe (tetap grayscale) */
        .ld-toast--error { border-color: rgba(255,255,255,0.16); }
        .ld-toast--warning { border-color: rgba(255,255,255,0.14); }
        .ld-toast--success { border-color: rgba(255,255,255,0.12); }
        .ld-toast--info { border-color: rgba(255,255,255,0.12); }
    </style>

    <div id="ld-toast" class="ld-toast ld-toast--{{ $toastType }}" role="status" aria-live="polite">
        <div class="ld-toast__inner">
            <svg class="ld-toast__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                @if ($toastType === 'success')
                    <path d="M20 6L9 17l-5-5" />
                @elseif ($toastType === 'error')
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                @elseif ($toastType === 'warning')
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" />
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                @else
                    <path d="M12 16v-4" />
                    <path d="M12 8h.01" />
                    <circle cx="12" cy="12" r="10" />
                @endif
            </svg>

            <div class="ld-toast__content">
                <div class="ld-toast__title">
                    @if ($toastType === 'success')
                        Berhasil
                    @elseif ($toastType === 'error')
                        Gagal
                    @elseif ($toastType === 'warning')
                        Peringatan
                    @else
                        Info
                    @endif
                </div>
                <p class="ld-toast__message">{{ $toastMessage }}</p>
            </div>

            <button class="ld-toast__close" type="button" aria-label="Tutup" onclick="document.getElementById('ld-toast')?.remove()">×</button>
        </div>
        <div class="ld-toast__bar" id="ld-toast-bar"></div>
    </div>

    <script>
        (function () {
            const toast = document.getElementById('ld-toast');
            const bar = document.getElementById('ld-toast-bar');
            if (!toast) return;

            const durationMs = 3200;
            if (bar) bar.style.animationDuration = durationMs + 'ms';

            const remove = () => {
                if (!toast.isConnected) return;
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-6px)';
                toast.style.transition = 'opacity 220ms ease, transform 220ms ease';
                setTimeout(() => toast.remove(), 240);
            };

            setTimeout(remove, durationMs);
        })();
    </script>
@endif
