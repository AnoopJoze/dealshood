@extends('layouts.user_type.guest')

@section('content')
<style>
:root {
    --ink:#0f172a; --ink-2:#334155; --ink-3:#64748b;
    --surf:#f8fafc; --surf-2:#f1f5f9; --surf-3:#e2e8f0;
    --white:#fff; --accent:#0f3f7e;
    --r:12px; --sh:0 1px 4px rgba(0,0,0,.06),0 4px 20px rgba(0,0,0,.08);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

.auth-page {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: var(--white);
}
@media(max-width:860px){ .auth-page{grid-template-columns:1fr;} }

/* ── Left visual panel ── */
.auth-left {
    background: linear-gradient(160deg, #0a0f1e 0%);
    position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    justify-content: center; padding: 60px 56px;
}
.auth-left::before {
    content: ''; position: absolute; inset: 0;
    background: url('/frontend/img/illustrations/IMG_4871.png') center/cover no-repeat;
    opacity: .15;
}
/* floating circles */
.auth-left::after {
    content: ''; position: absolute;
    width: 400px; height: 400px; border-radius: 50%;
    background: rgba(255,255,255,.03);
    top: -100px; right: -100px;
}
.al-circle {
    position: absolute; border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.al-c1 { width:300px; height:300px; bottom:-80px; left:-60px; }
.al-c2 { width:180px; height:180px; top:40%; right:10%; }
.al-content { position: relative; z-index: 1; }
.al-logo { height: 38px; filter: brightness(0) invert(1); opacity:.9; margin-bottom: 44px; }
.al-title {
    font-size: clamp(1.9rem,3vw,2.8rem); font-weight: 800;
    color: #fff; line-height: 1.18; letter-spacing: -.025em; margin-bottom: 14px;
}
.al-sub {
    font-size: .95rem; color: rgba(255,255,255,.52);
    font-weight: 300; line-height: 1.7; margin-bottom: 44px;
}
.al-feat {
    display: flex; align-items: center; gap: 13px;
    margin-bottom: 18px; font-size: .88rem; color: rgba(255,255,255,.72);
}
.al-feat-icon {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    background: rgba(255,255,255,.1); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; color: #fff;
}
@media(max-width:860px){ .auth-left{display:none;} }

/* ── Right form panel ── */
.auth-right {
    display: flex; align-items: center; justify-content: center;
    padding: 40px 24px; background: var(--white);
    overflow-y: auto;
}
.auth-box { width: 100%; max-width: 400px; }

.auth-head { margin-bottom: 32px; }
.auth-head-logo {
    height: 36px; margin-bottom: 28px; display: block;
}
.auth-head h1 {
    font-size: 1.7rem; font-weight: 800; color: var(--ink);
    letter-spacing: -.025em; margin-bottom: 6px;
}
.auth-head p { font-size: .88rem; color: var(--ink-3); }
.auth-head a { color: var(--accent); font-weight: 600; text-decoration: none; }
.auth-head a:hover { text-decoration: underline; }

/* Fields */
.f { margin-bottom: 18px; }
.f label {
    display: block; font-size: .76rem; font-weight: 600;
    color: var(--ink-2); margin-bottom: 6px;
}
.f-wrap { position: relative; }
.f-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: .78rem; pointer-events: none;
}
.f-input {
    width: 100%; padding: 11px 14px 11px 38px;
    border: 1.5px solid var(--surf-3); border-radius: var(--r);
    font-size: .88rem; color: var(--ink); background: #fff; outline: none;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
}
.f-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(15,63,126,.1);
}
.f-input.err { border-color: #ef4444; }
.f-input.err:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
.f-err {
    font-size: .72rem; color: #ef4444;
    margin-top: 5px; display: flex; align-items: center; gap: 4px;
}

/* Remember + forgot */
.f-row {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 22px;
}
.f-check { display: flex; align-items: center; gap: 7px; cursor: pointer; }
.f-check input { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; }
.f-check span { font-size: .8rem; color: var(--ink-3); }
.f-forgot { font-size: .8rem; color: var(--accent); text-decoration: none; font-weight: 600; }
.f-forgot:hover { text-decoration: underline; }

/* Submit */
.btn-primary {
    width: 100%; padding: 13px; border: none; border-radius: var(--r);
    background: var(--ink); color: #fff;
    font-size: .9rem; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background .15s, transform .15s, box-shadow .15s;
    font-family: inherit; letter-spacing: .01em;
}
.btn-primary:hover {
    background: var(--accent); transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(15,63,126,.3);
}
.btn-primary:active { transform: none; }

/* Divider */
.divider {
    display: flex; align-items: center; gap: 12px;
    margin: 20px 0; font-size: .76rem; color: var(--ink-3);
}
.divider::before,.divider::after { content:''; flex:1; height:1px; background:var(--surf-3); }

/* WhatsApp btn */
.btn-wa {
    width: 100%; padding: 12px; border: none; border-radius: var(--r);
    background: #25d366; color: #fff;
    font-size: .88rem; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    text-decoration: none; transition: filter .15s, transform .15s; font-family: inherit;
}
.btn-wa:hover { filter: brightness(1.08); transform: translateY(-1px); color: #fff; }

/* Alert */
.alert {
    padding: 12px 15px; border-radius: var(--r);
    font-size: .82rem; display: flex; align-items: flex-start; gap: 9px;
    margin-bottom: 18px;
}
.alert-err { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }
.alert i { margin-top: 1px; flex-shrink: 0; }

/* Spinner */
.spin {
    width: 16px; height: 16px; border-radius: 50%; display: none;
    border: 2px solid rgba(255,255,255,.35); border-top-color: #fff;
    animation: spin .6s linear infinite;
}
@keyframes spin { to{ transform:rotate(360deg); } }
</style>

<div class="auth-page">

    {{-- Left --}}
    <div class="auth-left">
        <span class="al-circle al-c1"></span>
        <span class="al-circle al-c2"></span>
        <div class="al-content">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" class="al-logo">
            <h1 class="al-title">Welcome back to DealsHood.</h1>
            <p class="al-sub">Sign in to discover the best local deals around you, every single day.</p>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fas fa-map-marker-alt"></i></span>
                Browse deals by your locality
            </div>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fas fa-tags"></i></span>
                Filter by category
            </div>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fab fa-whatsapp"></i></span>
                Contact sellers on WhatsApp
            </div>
        </div>
    </div>

    {{-- Right --}}
    <div class="auth-right">
        <div class="auth-box">

            <div class="auth-head">
                <img src="/frontend/img/dealshood.png" alt="DealsHood" class="auth-head-logo d-md-none">
                <h1>Sign in</h1>
                <p>Don't have an account? <a href="{{ route('register') }}">Create one free</a></p>
            </div>

            @if ($errors->any())
                <div class="alert alert-err">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="/session" id="loginForm">
                @csrf

                <div class="f">
                    <label for="email">Email Address</label>
                    <div class="f-wrap">
                        <i class="fas fa-envelope f-icon"></i>
                        <input type="email" id="email" name="email"
                               class="f-input {{ $errors->has('email') ? 'err':'' }}"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="f-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="f">
                    <label for="password">Password</label>
                    <div class="f-wrap">
                        <i class="fas fa-lock f-icon"></i>
                        <input type="password" id="password" name="password"
                               class="f-input {{ $errors->has('password') ? 'err':'' }}"
                               placeholder="Your password"
                               autocomplete="current-password" required
                               style="padding-right:42px;">
                        <button type="button" id="pwEye"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.82rem;padding:4px;">
                            <i class="fas fa-eye" id="pwEyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="f-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="f-row">
                    <label class="f-check">
                        <input type="checkbox" name="remember" id="remember" checked>
                        <span>Remember me</span>
                    </label>
                    <a href="/login/forgot-password" class="f-forgot">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary" id="loginBtn">
                    <span id="loginTxt">Sign In</span>
                    <span class="spin" id="loginSpin"></span>
                </button>
            </form>

        </div>
    </div>

</div>

<script>
/* Password eye toggle */
document.getElementById('pwEye').addEventListener('click', function(){
    const inp = document.getElementById('password');
    const ico = document.getElementById('pwEyeIcon');
    if(inp.type==='password'){ inp.type='text'; ico.classList.replace('fa-eye','fa-eye-slash'); }
    else { inp.type='password'; ico.classList.replace('fa-eye-slash','fa-eye'); }
});

/* Spinner on submit */
document.getElementById('loginForm').addEventListener('submit', function(){
    document.getElementById('loginTxt').textContent = 'Signing in…';
    document.getElementById('loginSpin').style.display = 'block';
    document.getElementById('loginBtn').disabled = true;
});
</script>

@endsection
