<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>Create Account — DealsHood</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

    <style>
    :root {
        --ink:#0d0d0d; --ink-mid:#374151; --ink-muted:#6b7280;
        --surf:#f8fafc; --white:#fff; --accent:#0f3f7e;
        --green:#25d366; --r:14px; --nav-h:64px;
        --sh:0 4px 24px rgba(15,23,42,.10);
    }
    *, *::before, *::after { box-sizing:border-box; }
    body {
        font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
        background:var(--surf); color:var(--ink); margin:0; min-height:100vh;
        display:flex; flex-direction:column;
    }

    /* ── Navbar ── */
    .nav {
        height:var(--nav-h); background:#fff;
        border-bottom:1px solid rgba(0,0,0,.07);
        display:flex; align-items:center; padding:0 24px;
        position:sticky; top:0; z-index:100;
    }
    .nav a { text-decoration:none; }
    .nav img { height:42px; }

    /* ── Page layout ── */
    .page {
        flex:1; display:grid;
        grid-template-columns:1fr 1fr;
        min-height:calc(100vh - var(--nav-h));
    }
    @media(max-width:860px){ .page { grid-template-columns:1fr; } }

    /* ── Left panel ── */
    .left-panel {
        background:linear-gradient(160deg,#0d0d0d 0%,#0f3f7e 100%);
        display:flex; flex-direction:column; justify-content:center;
        padding:60px 56px; position:relative; overflow:hidden;
    }
    .left-panel::before {
        content:''; position:absolute; inset:0;
        background:url('/frontend/img/office-dark.jpg') center/cover no-repeat;
        opacity:.18;
    }
    .left-content { position:relative; z-index:1; }
    .left-logo { height:40px; filter:brightness(0) invert(1); opacity:.9; margin-bottom:40px; }
    .left-title {
        font-size:clamp(1.8rem,3vw,2.6rem); font-weight:800; color:#fff;
        line-height:1.2; letter-spacing:-.02em; margin:0 0 14px;
    }
    .left-sub { font-size:1rem; color:rgba(255,255,255,.55); font-weight:300; line-height:1.7; margin:0 0 40px; }
    .left-feature {
        display:flex; align-items:center; gap:12px;
        margin-bottom:16px; font-size:.88rem; color:rgba(255,255,255,.75);
    }
    .left-feature-icon {
        width:36px; height:36px; border-radius:10px;
        background:rgba(255,255,255,.12); backdrop-filter:blur(8px);
        display:flex; align-items:center; justify-content:center;
        font-size:.85rem; color:#fff; flex-shrink:0;
    }
    @media(max-width:860px){ .left-panel { display:none; } }

    /* ── Right panel / form ── */
    .right-panel {
        display:flex; flex-direction:column; justify-content:center; align-items:center;
        padding:48px 24px;
        background:#fff;
    }
    .form-wrap { width:100%; max-width:420px; }

    .form-head { margin-bottom:32px; }
    .form-head h1 {
        font-size:1.75rem; font-weight:800; color:var(--ink);
        letter-spacing:-.025em; margin:0 0 6px;
    }
    .form-head p { font-size:.9rem; color:var(--ink-muted); margin:0; }
    .form-head a { color:var(--accent); text-decoration:none; font-weight:600; }
    .form-head a:hover { text-decoration:underline; }

    /* Form fields */
    .field { margin-bottom:18px; }
    .field label {
        display:block; font-size:.78rem; font-weight:600;
        color:var(--ink); margin-bottom:6px;
    }
    .field label span { color:#dc2626; }
    .input-wrap { position:relative; }
    .input-wrap i {
        position:absolute; left:13px; top:50%; transform:translateY(-50%);
        color:#94a3b8; font-size:.8rem; pointer-events:none;
    }
    .input-wrap input {
        width:100%; padding:11px 14px 11px 38px;
        border:1.5px solid #e2e8f0; border-radius:var(--r);
        font-size:.88rem; color:var(--ink); background:#fff; outline:none;
        transition:border-color .15s, box-shadow .15s;
    }
    .input-wrap input:focus {
        border-color:var(--accent);
        box-shadow:0 0 0 3px rgba(15,63,126,.1);
    }
    .input-wrap input.is-invalid { border-color:#dc2626; }
    .input-wrap input.is-invalid:focus { box-shadow:0 0 0 3px rgba(220,38,38,.1); }

    /* Password toggle */
    .pw-toggle {
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
        cursor:pointer; color:#94a3b8; font-size:.85rem; padding:4px;
        background:none; border:none;
    }
    .pw-toggle:hover { color:var(--ink); }
    .input-wrap.has-toggle input { padding-right:40px; }

    /* Password strength */
    .pw-strength { margin-top:6px; display:flex; gap:4px; }
    .pw-bar {
        flex:1; height:3px; border-radius:2px;
        background:#e2e8f0; transition:background .25s;
    }
    .pw-hint { font-size:.7rem; color:var(--ink-muted); margin-top:4px; }

    /* Error messages */
    .field-error {
        font-size:.73rem; color:#dc2626;
        margin-top:5px; display:flex; align-items:center; gap:4px;
    }

    /* Alert */
    .alert-error {
        background:#fef2f2; border:1px solid #fecaca; border-radius:10px;
        padding:12px 16px; font-size:.83rem; color:#b91c1c;
        display:flex; align-items:flex-start; gap:10px; margin-bottom:20px;
    }
    .alert-success {
        background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;
        padding:12px 16px; font-size:.83rem; color:#15803d;
        display:flex; align-items:flex-start; gap:10px; margin-bottom:20px;
    }

    /* Submit button */
    .btn-submit {
        width:100%; padding:13px;
        background:var(--ink); color:#fff; border:none; border-radius:var(--r);
        font-size:.9rem; font-weight:700; cursor:pointer;
        display:flex; align-items:center; justify-content:center; gap:8px;
        transition:background .15s, transform .15s, box-shadow .15s;
        letter-spacing:.01em;
    }
    .btn-submit:hover {
        background:var(--accent); transform:translateY(-1px);
        box-shadow:0 6px 20px rgba(15,63,126,.3);
    }
    .btn-submit:active { transform:none; }
    .btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    /* Divider */
    .divider {
        display:flex; align-items:center; gap:12px;
        margin:20px 0; color:var(--ink-muted); font-size:.78rem;
    }
    .divider::before,.divider::after { content:''; flex:1; height:1px; background:#e2e8f0; }

    /* Social / WhatsApp */
    .btn-wa {
        width:100%; padding:12px;
        background:var(--green); color:#fff; border:none; border-radius:var(--r);
        font-size:.88rem; font-weight:600; cursor:pointer;
        display:flex; align-items:center; justify-content:center; gap:8px;
        text-decoration:none; transition:filter .15s, transform .15s;
    }
    .btn-wa:hover { filter:brightness(1.08); transform:translateY(-1px); color:#fff; }

    /* Terms */
    .terms {
        font-size:.72rem; color:var(--ink-muted); text-align:center; margin-top:16px; line-height:1.6;
    }
    .terms a { color:var(--accent); text-decoration:none; }
    .terms a:hover { text-decoration:underline; }

    /* Spinner */
    .spinner { display:none; width:16px; height:16px; border-radius:50%;
               border:2px solid rgba(255,255,255,.4); border-top-color:#fff;
               animation:spin .6s linear infinite; }
    @keyframes spin { to{ transform:rotate(360deg); } }
    </style>
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="nav">
    <a href="{{ route('home') }}">
        <img src="/frontend/img/dealshood.png" alt="DealsHood">
    </a>
</nav>

<div class="page">

    {{-- ── Left panel ── --}}
    <div class="left-panel">
        <div class="left-content">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" class="left-logo">
            <h1 class="left-title">Find the best deals in your area.</h1>
            <p class="left-sub">Join thousands of people discovering local offers every day. Free to join, free to browse.</p>

            <div class="left-feature">
                <span class="left-feature-icon"><i class="fas fa-map-marker-alt"></i></span>
                Browse deals by locality
            </div>
            <div class="left-feature">
                <span class="left-feature-icon"><i class="fas fa-tags"></i></span>
                Filter by category
            </div>
            <div class="left-feature">
                <span class="left-feature-icon"><i class="fab fa-whatsapp"></i></span>
                Contact sellers directly on WhatsApp
            </div>
        </div>
    </div>

    {{-- ── Form panel ── --}}
    <div class="right-panel">
        <div class="form-wrap">

            <div class="form-head">
                <h1>Create your account</h1>
                <p>Already have one? <a href="{{ route('login') }}">Sign in</a></p>
            </div>

            {{-- Success flash --}}
            @if (session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Global error --}}
            @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="regForm" novalidate>
                @csrf

                {{-- Name --}}
                <div class="field">
                    <label for="name">Full Name <span>*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="John Smith"
                               class="{{ $errors->has('name') ? 'is-invalid':'' }}"
                               autocomplete="name" required>
                    </div>
                    @error('name')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email Address <span>*</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               class="{{ $errors->has('email') ? 'is-invalid':'' }}"
                               autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone (optional) --}}
                <div class="field">
                    <label for="phone">Phone Number <span style="color:var(--ink-muted);font-weight:400;">(optional)</span></label>
                    <div class="input-group" style="display:flex;gap:0;">
                        <span style="display:flex;align-items:center;padding:0 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:var(--r) 0 0 var(--r);color:#94a3b8;font-size:.8rem;">
                            <i class="fab fa-whatsapp" style="color:var(--green);font-size:.95rem;"></i>
                        </span>
                        <input type="tel" id="phone" name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+971 50 123 4567"
                               style="flex:1;padding:11px 14px;border:1.5px solid #e2e8f0;border-left:none;border-radius:0 var(--r) var(--r) 0;font-size:.88rem;color:var(--ink);outline:none;transition:border-color .15s;"
                               autocomplete="tel">
                    </div>
                </div>

                <style>
/* ── Requirement list ── */
.pw-reqs {
    list-style:none; padding:0; margin:8px 0 0;
    display:grid; grid-template-columns:1fr 1fr; gap:4px 12px;
}
.pw-req {
    display:flex; align-items:center; gap:6px;
    font-size:.72rem; color:#94a3b8; transition:color .2s;
}
.pw-req .req-icon {
    width:14px; height:14px; border-radius:50%; flex-shrink:0;
    border:1.5px solid #cbd5e1;
    display:flex; align-items:center; justify-content:center;
    font-size:.5rem; transition:all .2s;
}
.pw-req.met { color:#15803d; }
.pw-req.met .req-icon {
    background:#22c55e; border-color:#22c55e; color:#fff;
}
.pw-req.unmet { color:#dc2626; }
.pw-req.unmet .req-icon {
    background:#fef2f2; border-color:#dc2626; color:#dc2626;
}
 
/* ── Strength bar ── */
.pw-strength-wrap { margin-top:10px; }
.pw-bars { display:flex; gap:4px; margin-bottom:5px; }
.pw-bar {
    flex:1; height:4px; border-radius:2px;
    background:#e2e8f0; transition:background .25s;
}
.pw-label {
    font-size:.72rem; font-weight:600;
    color:#94a3b8; transition:color .2s;
}
 
/* ── Match indicator ── */
.pw-match {
    font-size:.72rem; margin-top:5px;
    display:flex; align-items:center; gap:5px;
    min-height:18px;
}
.pw-match.ok    { color:#15803d; }
.pw-match.bad   { color:#dc2626; }
</style>
 
{{-- Password --}}
<div class="field">
    <label for="password">Password <span>*</span></label>
    <div class="input-wrap has-toggle">
        <i class="fas fa-lock"></i>
        <input type="password" id="password" name="password"
               placeholder="Create a strong password"
               class="{{ $errors->has('password') ? 'is-invalid':'' }}"
               autocomplete="new-password" required>
        <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1">
            <i class="fas fa-eye" id="pwToggleIcon"></i>
        </button>
    </div>
 
    {{-- Strength bars --}}
    <div class="pw-strength-wrap" id="strengthWrap" style="display:none;">
        <div class="pw-bars">
            <span class="pw-bar" id="sb1"></span>
            <span class="pw-bar" id="sb2"></span>
            <span class="pw-bar" id="sb3"></span>
            <span class="pw-bar" id="sb4"></span>
        </div>
        <div class="pw-label" id="pwLabel">Too weak</div>
    </div>
 
    {{-- Requirements checklist --}}
    <ul class="pw-reqs" id="pwReqs">
        <li class="pw-req" data-rule="length">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            8+ characters
        </li>
        <li class="pw-req" data-rule="upper">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            Uppercase letter
        </li>
        <li class="pw-req" data-rule="lower">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            Lowercase letter
        </li>
        <li class="pw-req" data-rule="number">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            Number
        </li>
        <li class="pw-req" data-rule="special">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            Special character
        </li>
        <li class="pw-req" data-rule="no-spaces">
            <span class="req-icon"><i class="fas fa-check"></i></span>
            No spaces
        </li>
    </ul>
 
    @error('password')
        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
    @enderror
</div>
 
{{-- Confirm Password --}}
<div class="field">
    <label for="password_confirmation">Confirm Password <span>*</span></label>
    <div class="input-wrap has-toggle">
        <i class="fas fa-lock"></i>
        <input type="password" id="password_confirmation" name="password_confirmation"
               placeholder="Re-enter your password"
               autocomplete="new-password" required>
        <button type="button" class="pw-toggle" id="pwToggle2" tabindex="-1">
            <i class="fas fa-eye" id="pwToggleIcon2"></i>
        </button>
    </div>
    <div class="pw-match" id="pwMatch"></div>
</div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Create Account</span>
                    <span class="spinner" id="btnSpinner"></span>
                </button>

                <p class="terms">
                    By creating an account you agree to our
                    <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                </p>

            </form>

            <div class="divider">or</div>

            {{-- WhatsApp CTA --}}
            <a href="https://wa.me/918086087050?text=Hello%2C+I+would+like+to+register+on+DealsHood"
               target="_blank" class="btn-wa">
                <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i>
                Register via WhatsApp
            </a>

        </div>
    </div>

</div>
<script>

/* ── Visibility toggles ── */
function togglePw(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.classList.replace('fa-eye','fa-eye-slash');
    } else {
        inp.type = 'password';
        ico.classList.replace('fa-eye-slash','fa-eye');
    }
}
document.getElementById('pwToggle') .addEventListener('click', () => togglePw('password','pwToggleIcon'));
document.getElementById('pwToggle2').addEventListener('click', () => togglePw('password_confirmation','pwToggleIcon2'));

/* ── Rules ── */
const RULES = {
    length:    v => v.length >= 8,
    upper:     v => /[A-Z]/.test(v),
    lower:     v => /[a-z]/.test(v),
    number:    v => /[0-9]/.test(v),
    special:   v => /[^A-Za-z0-9]/.test(v),
    'no-spaces': v => v.length > 0 && !/\s/.test(v),
};

const LEVELS = [
    { score:0, label:'Too weak',   bars:1, color:'#dc2626' },
    { score:1, label:'Weak',       bars:1, color:'#dc2626' },
    { score:2, label:'Fair',       bars:2, color:'#f59e0b' },
    { score:3, label:'Good',       bars:3, color:'#3b82f6' },
    { score:4, label:'Strong',     bars:4, color:'#22c55e' },
    { score:5, label:'Very strong',bars:4, color:'#15803d' },
    { score:6, label:'Excellent ✓',bars:4, color:'#15803d' },
];

let passwordStrong = false;
let passwordsMatch = false;

function updateStrength(val) {
    const wrap  = document.getElementById('strengthWrap');
    const label = document.getElementById('pwLabel');
    const bars  = [document.getElementById('sb1'), document.getElementById('sb2'),
                   document.getElementById('sb3'), document.getElementById('sb4')];

    if (!val) {
        wrap.style.display = 'none';
        /* reset all rules to neutral */
        document.querySelectorAll('.pw-req').forEach(el => el.classList.remove('met','unmet'));
        passwordStrong = false;
        return;
    }

    wrap.style.display = 'block';

    /* Evaluate each rule */
    let metCount = 0;
    document.querySelectorAll('.pw-req').forEach(el => {
        const rule = el.dataset.rule;
        const met  = RULES[rule] ? RULES[rule](val) : false;
        el.classList.toggle('met',   met);
        el.classList.toggle('unmet', !met);
        if (met) metCount++;
    });

    /* All 6 rules met = strong */
    passwordStrong = (metCount === 6);

    /* Strength display */
    const level = LEVELS[Math.min(metCount, LEVELS.length - 1)];
    label.textContent = level.label;
    label.style.color = level.color;
    bars.forEach((bar, i) => {
        bar.style.background = i < level.bars ? level.color : '#e2e8f0';
    });
}

function updateMatch() {
    const pw   = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;
    const el   = document.getElementById('pwMatch');

    if (!conf) { el.textContent = ''; el.className = 'pw-match'; passwordsMatch = false; return; }

    if (pw === conf) {
        el.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
        el.className = 'pw-match ok';
        passwordsMatch = true;
    } else {
        el.innerHTML = '<i class="fas fa-times-circle"></i> Passwords do not match';
        el.className = 'pw-match bad';
        passwordsMatch = false;
    }
}

document.getElementById('password').addEventListener('input', function () {
    updateStrength(this.value);
    if (document.getElementById('password_confirmation').value) updateMatch();
});
document.getElementById('password_confirmation').addEventListener('input', updateMatch);

/* ── Block submit if password weak or mismatched ── */
document.getElementById('regForm').addEventListener('submit', function (e) {
    const pw   = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;

    if (!passwordStrong) {
        e.preventDefault();
        /* Flash all unmet rules red */
        document.querySelectorAll('.pw-req:not(.met)').forEach(el => {
            el.classList.add('unmet');
            el.animate([{transform:'translateX(-4px)'},{transform:'translateX(4px)'},{transform:'none'}],
                       {duration:300, iterations:2});
        });
        document.getElementById('password').focus();
        return;
    }

    if (pw !== conf) {
        e.preventDefault();
        document.getElementById('pwMatch').innerHTML = '<i class="fas fa-times-circle"></i> Passwords do not match';
        document.getElementById('pwMatch').className = 'pw-match bad';
        document.getElementById('password_confirmation').focus();
        return;
    }

    /* All good — show spinner */
    const btn = document.getElementById('submitBtn');
    document.getElementById('btnText').textContent = 'Creating account…';
    document.getElementById('btnSpinner').style.display = 'block';
    btn.disabled = true;
});
</script>
</body>
</html>
