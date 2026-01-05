@extends('layout')

@section('content')

<style>
/* ===============================
   THEME: LIQUID DARK PRO
================================ */
:root {
    --bg-void: #050505;
    --bg-card: rgba(10, 10, 10, 0.7);
    --bg-input: #0a0a0a;
    
    --border-dim: rgba(255, 255, 255, 0.08);
    --border-highlight: rgba(255, 255, 255, 0.2);
    
    --text-primary: #ffffff;
    --text-secondary: #888888;
    
    --ease-apple: cubic-bezier(0.25, 1, 0.5, 1);
}

body {
    margin: 0;
    height: 100vh;
    background-color: var(--bg-void);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    -webkit-font-smoothing: antialiased;
}

/* ===============================
   BACKGROUND: LIQUID + GRID
================================ */

/* Layer 1: Fluid Mesh Animation */
.bg-liquid {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: -3;
    background: 
        radial-gradient(at 0% 0%, hsla(0,0%,15%,1) 0, transparent 50%), 
        radial-gradient(at 50% 0%, hsla(0,0%,5%,1) 0, transparent 50%), 
        radial-gradient(at 100% 0%, hsla(0,0%,15%,1) 0, transparent 50%);
    background-size: 200% 200%;
    animation: liquidFlow 15s ease infinite alternate;
    opacity: 0.6;
}

@keyframes liquidFlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Layer 2: Moving Orbs (Asap Gelap) */
.bg-smoke {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 150vw; height: 150vh;
    z-index: -2;
    background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 60%);
    filter: blur(80px);
    animation: breathe 10s infinite ease-in-out;
}

@keyframes breathe {
    0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
    50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
}

/* Layer 3: Tech Grid Pattern */
.bg-grid {
    position: fixed;
    inset: 0;
    z-index: -1;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 60px 60px; /* Ukuran kotak */
    mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
    -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
    opacity: 0.6;
}

/* ===============================
   CARD DESIGN (FROSTED GLASS)
================================ */
.login-card {
    width: 100%;
    max-width: 380px;
    padding: 3rem 2.5rem;
    
    /* Efek Kaca Lebih Gelap & Mewah */
    background: var(--bg-card);
    backdrop-filter: blur(40px) saturate(180%);
    -webkit-backdrop-filter: blur(40px) saturate(180%);
    
    border-radius: 28px;
    /* Border gradasi halus */
    border: 1px solid transparent;
    background-image: linear-gradient(var(--bg-card), var(--bg-card)), 
                      linear-gradient(180deg, rgba(255,255,255,0.15), rgba(255,255,255,0.02));
    background-origin: border-box;
    background-clip: padding-box, border-box;
    
    box-shadow: 0 50px 100px -20px rgba(0,0,0,0.9);
    
    animation: cardEnter 1s var(--ease-apple) forwards;
    opacity: 0;
    transform: translateY(30px);
}

@keyframes cardEnter {
    to { opacity: 1; transform: translateY(0); }
}

/* ===============================
   HEADER
================================ */
.header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.icon-box {
    width: 64px;
    height: 64px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    background: linear-gradient(135deg, #1c1c1e, #000);
    box-shadow: 
        0 10px 30px rgba(0,0,0,0.5),
        inset 0 1px 0 rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.05);
}

.header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
    letter-spacing: -0.01em;
}

.header p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin: 0;
}

/* ===============================
   INPUTS (MANUAL ONLY)
================================ */
.form-group {
    margin-bottom: 1.5rem;
}

.label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.6rem;
    margin-left: 4px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.input-container {
    position: relative;
    transition: transform 0.2s;
}

.ios-input {
    width: 100%;
    padding: 16px;
    font-size: 1rem;
    
    background-color: var(--bg-input) !important;
    border: 1px solid var(--border-dim);
    border-radius: 14px;
    
    color: var(--text-primary);
    transition: all 0.3s var(--ease-apple);
    
    background-image: none !important;
}

.ios-input::placeholder {
    color: #3a3a3a;
}

.ios-input:focus {
    outline: none;
    background-color: #000 !important;
    border-color: var(--text-primary); /* Putih */
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.08);
}

/* Hack Cursor Readonly */
.ios-input:read-only {
    cursor: text;
}

/* ===============================
   BUTTON
================================ */
.btn-submit {
    width: 100%;
    padding: 16px;
    margin-top: 1.2rem;
    
    background: #ffffff;
    color: #000000;
    
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    
    transition: transform 0.2s var(--ease-apple), opacity 0.2s;
    box-shadow: 0 10px 20px rgba(255,255,255,0.1);
}

.btn-submit:hover {
    opacity: 0.9;
    transform: scale(0.98);
    box-shadow: 0 5px 15px rgba(255,255,255,0.05);
}

/* Utility */
.toggle-eye {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #444;
    cursor: pointer;
    transition: color 0.2s;
}
.toggle-eye:hover { color: #fff; }

.error-text {
    margin-top: 0.5rem;
    font-size: 0.8rem;
    color: #fff;
    opacity: 0.6;
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>

<div class="bg-liquid"></div> <div class="bg-smoke"></div>  <div class="bg-grid"></div>   <div class="login-card">
    
    <div class="header">
        <div class="icon-box">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <circle cx="12" cy="11" r="3"/>
            </svg>
        </div>
        <h1>Portal Admin</h1>
        <p>Silakan masuk untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login.store') }}" autocomplete="off">
        @csrf

        <input type="email" style="display:none">
        <input type="password" style="display:none">

        <div class="form-group">
            <label class="label">Identifier / Email</label>
            <div class="input-container">
                <input type="email" name="email" 
                       class="ios-input" 
                       placeholder="user@system.local"
                       value="{{ old('email') }}"
                       autocomplete="off"
                       readonly
                       onfocus="this.removeAttribute('readonly');"
                       required>
            </div>
            @error('email')
                <div class="error-text">
                    <span>•</span> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="label">Passcode</label>
            <div class="input-container">
                <input type="password" id="password" name="password" 
                       class="ios-input" 
                       placeholder="••••••••"
                       autocomplete="new-password"
                       readonly
                       onfocus="this.removeAttribute('readonly');"
                       required>
                
                <button type="button" class="toggle-eye" onclick="togglePassword()">
                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="error-text">
                     <span>•</span> {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Verifikasi Masuk</button>
    </form>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
}
</script>

@endsection