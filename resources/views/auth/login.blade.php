@extends('layout')

@section('title', 'Login')

@section('content')
<div style="min-height: calc(100vh - 140px); display: flex; align-items: center; justify-content: center; background-color: #f8fafc; padding: 20px;">
    <div style="max-width: 400px; width: 100%; background: white; padding: 2.5rem; border-radius: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);">

        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="display: inline-flex; align-items: center; justify-content: center; height: 56px; width: 56px; border-radius: 16px; background: linear-gradient(135deg, #2563eb, #1d4ed8); margin-bottom: 1rem; color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1e293b;">Login</h3>
            <p style="margin: 0.5rem 0 0; color: #64748b; font-size: 0.875rem;">Masuk ke sistem Buku Tamu Digital</p>
        </div>

        <form method="post" action="{{ route('login.store') }}">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; outline: none; transition: all 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                    required autofocus>
                @error('email')
                    <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Password</label>
                <input id="password" type="password" name="password"
                    placeholder="••••••••"
                    style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; outline: none; transition: all 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37, 99, 235, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
                    required>
                @error('password')
                    <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; font-weight: 500;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" style="width: 100%; padding: 0.875rem; border-radius: 0.75rem; border: none; background: #2563eb; color: white; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; margin-bottom: 2rem;"
                onmouseover="this.style.background='#1d4ed8'"
                onmouseout="this.style.background='#2563eb'">
                Masuk Sekarang
            </button>

            <div style="padding: 1rem; border-radius: 1rem; background-color: #f8fafc; border: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                    <div style="height: 6px; width: 6px; border-radius: 50%; background-color: #2563eb;"></div>
                    <span style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Akun Demo</span>
                </div>
                <div style="font-size: 0.813rem; color: #64748b;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Email:</span>
                        <span style="color: #0f172a; font-weight: 600;">aide@admin.com</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Password:</span>
                        <span style="color: #0f172a; font-weight: 600;">123</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection