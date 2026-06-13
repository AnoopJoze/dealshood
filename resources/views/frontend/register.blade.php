<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.ico">
    <title>Create Account — DealsHood</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">
    <style>
    :root {
        --ink:#0f172a; --ink-2:#334155; --ink-3:#64748b;
        --surf:#f8fafc; --surf-2:#f1f5f9; --surf-3:#e2e8f0;
        --white:#fff; --accent:#0f3f7e;
        --green:#25d366; --r:12px;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:var(--white);color:var(--ink);min-height:100vh;}

    .auth-page {
        min-height: 100vh; display: grid;
        grid-template-columns: 1fr 1fr;
    }
    @media(max-width:860px){ .auth-page{grid-template-columns:1fr;} }

    /* Left panel */
    .auth-left {
        background: linear-gradient(160deg, #0a0f1e 0%, #0f3f7e 60%, #1e40af 100%);
        position: relative; overflow: hidden;
        display: flex; flex-direction: column;
        justify-content: center; padding: 60px 52px;
    }
    .auth-left::before {
        content: ''; position: absolute; inset: 0;
        background: url('/frontend/img/illustrations/IMG_4871.png') center/cover no-repeat;
        opacity: .15;
    }
    .al-circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,.04); }
    .al-c1 { width:320px;height:320px;bottom:-80px;left:-70px; }
    .al-c2 { width:200px;height:200px;top:35%;right:8%; }
    .al-c3 { width:120px;height:120px;top:12%;left:30%; }
    .al-content { position: relative; z-index: 1; }
    .al-logo { height: 36px; filter:brightness(0) invert(1); opacity:.9; margin-bottom:40px; display:block; }
    .al-title { font-size:clamp(1.8rem,2.8vw,2.6rem); font-weight:800; color:#fff; line-height:1.18; letter-spacing:-.025em; margin-bottom:12px; }
    .al-sub { font-size:.93rem; color:rgba(255,255,255,.5); font-weight:300; line-height:1.72; margin-bottom:40px; }
    .al-feat { display:flex; align-items:center; gap:13px; margin-bottom:16px; font-size:.87rem; color:rgba(255,255,255,.72); }
    .al-feat-icon {
        width:36px;height:36px;border-radius:10px;flex-shrink:0;
        background:rgba(255,255,255,.1);backdrop-filter:blur(8px);
        border:1px solid rgba(255,255,255,.12);
        display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#fff;
    }
    /* stats row */
    .al-stats { display:flex; gap:24px; margin-top:44px; }
    .al-stat { }
    .al-stat-val { font-size:1.5rem; font-weight:800; color:#fff; line-height:1; }
    .al-stat-label { font-size:.72rem; color:rgba(255,255,255,.45); margin-top:3px; }
    @media(max-width:860px){ .auth-left{display:none;} }

    /* Right panel */
    .auth-right {
        display: flex; align-items: flex-start; justify-content: center;
        padding: 40px 24px 60px; background: var(--white); overflow-y: auto;
    }
    .auth-box { width:100%; max-width:420px; padding-top:8px; }

    .auth-head { margin-bottom:28px; }
    .auth-head-logo { height:34px; margin-bottom:24px; display:block; }
    .auth-head h1 { font-size:1.65rem; font-weight:800; color:var(--ink); letter-spacing:-.025em; margin-bottom:5px; }
    .auth-head p { font-size:.87rem; color:var(--ink-3); }
    .auth-head a { color:var(--accent); font-weight:600; text-decoration:none; }
    .auth-head a:hover { text-decoration:underline; }

    /* Fields */
    .f { margin-bottom:16px; }
    .f label { display:block; font-size:.76rem; font-weight:600; color:var(--ink-2); margin-bottom:5px; }
    .f label span.req { color:#ef4444; }
    .f label span.opt { color:var(--ink-3); font-weight:400; }
    .f-wrap { position:relative; }
    .f-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.76rem;pointer-events:none; }
    .f-input {
        width:100%;padding:11px 14px 11px 36px;
        border:1.5px solid var(--surf-3);border-radius:var(--r);
        font-size:.87rem;color:var(--ink);background:#fff;outline:none;
        transition:border-color .15s,box-shadow .15s;font-family:inherit;
    }
    .f-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(15,63,126,.1); }
    .f-input.err { border-color:#ef4444; }
    .f-input.err:focus { box-shadow:0 0 0 3px rgba(239,68,68,.1); }
    .f-err { font-size:.71rem;color:#ef4444;margin-top:4px;display:flex;align-items:center;gap:4px; }
    /* Eye toggle */
    .f-eye {
        position:absolute;right:11px;top:50%;transform:translateY(-50%);
        background:none;border:none;cursor:pointer;color:#94a3b8;font-size:.8rem;padding:4px;
    }
    .f-eye:hover { color:var(--ink); }
    .has-eye .f-input { padding-right:38px; }
    /* Phone field */
    .phone-wrap { display:flex; }
    .phone-prefix {
        display:flex;align-items:center;padding:0 12px;
        background:var(--surf-2);border:1.5px solid var(--surf-3);
        border-right:none;border-radius:var(--r) 0 0 var(--r);
        color:#94a3b8;font-size:.8rem;white-space:nowrap;
    }
    .phone-wrap .f-input {
        border-radius:0 var(--r) var(--r) 0;padding-left:14px;
    }

    /* Password strength */
    .pw-bars { display:flex;gap:3px;margin:8px 0 4px; }
    .pw-bar { flex:1;height:3.5px;border-radius:2px;background:var(--surf-3);transition:background .25s; }
    .pw-strength-label { font-size:.71rem;font-weight:600;color:#94a3b8;transition:color .2s; }

    /* Requirements grid */
    .pw-reqs { list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:4px 10px;margin-top:8px; }
    .pw-req { display:flex;align-items:center;gap:6px;font-size:.71rem;color:#94a3b8;transition:color .2s; }
    .req-dot { width:13px;height:13px;border-radius:50%;flex-shrink:0;border:1.5px solid #cbd5e1;
               display:flex;align-items:center;justify-content:center;font-size:.45rem;transition:all .2s; }
    .pw-req.met { color:#15803d; }
    .pw-req.met .req-dot { background:#22c55e;border-color:#22c55e;color:#fff; }
    .pw-req.fail { color:#ef4444; }
    .pw-req.fail .req-dot { background:#fef2f2;border-color:#ef4444;color:#ef4444; }

    /* Match */
    .pw-match { font-size:.71rem;margin-top:5px;display:flex;align-items:center;gap:5px;min-height:17px; }
    .pw-match.ok { color:#15803d; }
    .pw-match.bad { color:#ef4444; }

    /* Submit */
    .btn-primary {
        width:100%;padding:13px;border:none;border-radius:var(--r);
        background:var(--ink);color:#fff;font-size:.9rem;font-weight:700;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:8px;
        transition:background .15s,transform .15s,box-shadow .15s;
        font-family:inherit;letter-spacing:.01em;
    }
    .btn-primary:hover { background:var(--accent);transform:translateY(-1px);box-shadow:0 6px 20px rgba(15,63,126,.3); }
    .btn-primary:active { transform:none; }
    .btn-primary:disabled { opacity:.6;cursor:not-allowed;transform:none; }

    /* Divider */
    .divider { display:flex;align-items:center;gap:12px;margin:18px 0;font-size:.75rem;color:var(--ink-3); }
    .divider::before,.divider::after { content:'';flex:1;height:1px;background:var(--surf-3); }

    /* WhatsApp */
    .btn-wa {
        width:100%;padding:12px;border:none;border-radius:var(--r);
        background:var(--green);color:#fff;font-size:.87rem;font-weight:600;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:8px;
        text-decoration:none;transition:filter .15s,transform .15s;font-family:inherit;
    }
    .btn-wa:hover { filter:brightness(1.08);transform:translateY(-1px);color:#fff; }

    /* Alerts */
    .alert { padding:12px 15px;border-radius:var(--r);font-size:.81rem;
             display:flex;align-items:flex-start;gap:9px;margin-bottom:16px; }
    .alert-err { background:#fef2f2;border:1px solid #fecaca;color:#b91c1c; }
    .alert-ok  { background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d; }
    .alert i { margin-top:1px;flex-shrink:0; }

    /* Terms */
    .terms { font-size:.71rem;color:var(--ink-3);text-align:center;margin-top:14px;line-height:1.6; }
    .terms a { color:var(--accent);text-decoration:none; }
    .terms a:hover { text-decoration:underline; }

    /* Spinner */
    .spin { width:15px;height:15px;border-radius:50%;display:none;
            border:2px solid rgba(255,255,255,.35);border-top-color:#fff;
            animation:spin .6s linear infinite; }
    @keyframes spin { to{transform:rotate(360deg);} }
    </style>
</head>
<body>

<div class="auth-page">

    {{-- Left visual --}}
    <div class="auth-left">
        <span class="al-circle al-c1"></span>
        <span class="al-circle al-c2"></span>
        <span class="al-circle al-c3"></span>
        <div class="al-content">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" class="al-logo">
            <h1 class="al-title">Find the best deals in your area.</h1>
            <p class="al-sub">Join thousands of people discovering local offers every day. Free to join, free to browse.</p>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fas fa-map-marker-alt"></i></span>
                Browse deals by locality
            </div>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fas fa-tags"></i></span>
                Filter by category instantly
            </div>
            <div class="al-feat">
                <span class="al-feat-icon"><i class="fab fa-whatsapp"></i></span>
                Contact sellers on WhatsApp
            </div>
            <div class="al-stats">
                <div class="al-stat">
                    <div class="al-stat-val">10K+</div>
                    <div class="al-stat-label">Active deals</div>
                </div>
                <div class="al-stat">
                    <div class="al-stat-val">50+</div>
                    <div class="al-stat-label">Localities</div>
                </div>
                <div class="al-stat">
                    <div class="al-stat-val">Free</div>
                    <div class="al-stat-label">Always</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right form --}}
    <div class="auth-right">
        <div class="auth-box">

            <div class="auth-head">
                <img src="/frontend/img/dealshood.png" alt="DealsHood" class="auth-head-logo d-md-none">
                <h1>Create your account</h1>
                <p>Already have one? <a href="{{ route('login') }}">Sign in</a></p>
            </div>

            @if(session('success'))
                <div class="alert alert-ok">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-err">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="regForm" novalidate>
                @csrf

                {{-- Name --}}
                <div class="f">
                    <label for="name">Full Name <span class="req">*</span></label>
                    <div class="f-wrap">
                        <i class="fas fa-user f-icon"></i>
                        <input type="text" id="name" name="name"
                               class="f-input {{ $errors->has('name')?'err':'' }}"
                               value="{{ old('name') }}" placeholder="John Smith"
                               autocomplete="name" required>
                    </div>
                    @error('name')
                        <div class="f-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="f">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <div class="f-wrap">
                        <i class="fas fa-envelope f-icon"></i>
                        <input type="email" id="email" name="email"
                               class="f-input {{ $errors->has('email')?'err':'' }}"
                               value="{{ old('email') }}" placeholder="you@example.com"
                               autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="f-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="f">
                    <label for="phone">Phone <span class="opt">(optional)</span></label>
                    <div class="phone-wrap">
                        <span class="phone-prefix">
                            <i class="fab fa-whatsapp" style="color:var(--green);font-size:.95rem;margin-right:5px;"></i>
                            WA
                        </span>
                        <input type="tel" id="phone" name="phone"
                               class="f-input" value="{{ old('phone') }}"
                               placeholder="+971 50 123 4567" autocomplete="tel">
                    </div>
                </div>

                {{-- Password --}}
                <div class="f">
                    <label for="password">Password <span class="req">*</span></label>
                    <div class="f-wrap has-eye">
                        <i class="fas fa-lock f-icon"></i>
                        <input type="password" id="password" name="password"
                               class="f-input {{ $errors->has('password')?'err':'' }}"
                               placeholder="Create a strong password"
                               autocomplete="new-password" required>
                        <button type="button" class="f-eye" id="pwEye">
                            <i class="fas fa-eye" id="pwEyeIcon"></i>
                        </button>
                    </div>
                    {{-- Strength --}}
                    <div id="strengthWrap" style="display:none;margin-top:8px;">
                        <div class="pw-bars">
                            <span class="pw-bar" id="sb1"></span>
                            <span class="pw-bar" id="sb2"></span>
                            <span class="pw-bar" id="sb3"></span>
                            <span class="pw-bar" id="sb4"></span>
                        </div>
                        <div class="pw-strength-label" id="pwLabel">Too weak</div>
                    </div>
                    {{-- Requirements --}}
                    <ul class="pw-reqs" id="pwReqs">
                        <li class="pw-req" data-rule="length"><span class="req-dot"><i class="fas fa-check"></i></span>8+ characters</li>
                        <li class="pw-req" data-rule="upper"> <span class="req-dot"><i class="fas fa-check"></i></span>Uppercase</li>
                        <li class="pw-req" data-rule="lower"> <span class="req-dot"><i class="fas fa-check"></i></span>Lowercase</li>
                        <li class="pw-req" data-rule="number"><span class="req-dot"><i class="fas fa-check"></i></span>Number</li>
                        <li class="pw-req" data-rule="special"><span class="req-dot"><i class="fas fa-check"></i></span>Symbol</li>
                        <li class="pw-req" data-rule="nospace"><span class="req-dot"><i class="fas fa-check"></i></span>No spaces</li>
                    </ul>
                    @error('password')
                        <div class="f-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm --}}
                <div class="f">
                    <label for="password_confirmation">Confirm Password <span class="req">*</span></label>
                    <div class="f-wrap has-eye">
                        <i class="fas fa-lock f-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="f-input" placeholder="Re-enter password"
                               autocomplete="new-password" required>
                        <button type="button" class="f-eye" id="pwEye2">
                            <i class="fas fa-eye" id="pwEyeIcon2"></i>
                        </button>
                    </div>
                    <div class="pw-match" id="pwMatch"></div>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <span id="btnTxt">Create Account</span>
                    <span class="spin" id="btnSpin"></span>
                </button>

                <p class="terms">
                    By signing up you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.
                </p>
            </form>

            <div class="divider">or</div>

            <a href="https://wa.me/918086087050?text=Hello%2C+I+want+to+register+on+DealsHood"
               target="_blank" class="btn-wa">
                <i class="fab fa-whatsapp" style="font-size:1rem;"></i>
                Register via WhatsApp
            </a>

        </div>
    </div>

</div>

<script>
/* Eye toggles */
function eyeToggle(inpId, iconId){
    const inp=document.getElementById(inpId), ico=document.getElementById(iconId);
    if(inp.type==='password'){ inp.type='text'; ico.classList.replace('fa-eye','fa-eye-slash'); }
    else { inp.type='password'; ico.classList.replace('fa-eye-slash','fa-eye'); }
}
document.getElementById('pwEye') .addEventListener('click',()=>eyeToggle('password','pwEyeIcon'));
document.getElementById('pwEye2').addEventListener('click',()=>eyeToggle('password_confirmation','pwEyeIcon2'));

/* Rules */
const RULES={
    length: v=>v.length>=8,
    upper:  v=>/[A-Z]/.test(v),
    lower:  v=>/[a-z]/.test(v),
    number: v=>/[0-9]/.test(v),
    special:v=>/[^A-Za-z0-9]/.test(v),
    nospace:v=>v.length>0&&!/\s/.test(v),
};
const LEVELS=[
    {bars:1,color:'#ef4444',label:'Too weak'},
    {bars:1,color:'#ef4444',label:'Weak'},
    {bars:2,color:'#f59e0b',label:'Fair'},
    {bars:3,color:'#3b82f6',label:'Good'},
    {bars:4,color:'#22c55e',label:'Strong'},
    {bars:4,color:'#15803d',label:'Very strong'},
    {bars:4,color:'#15803d',label:'Excellent ✓'},
];
let pwStrong=false, pwMatch=false;

function checkStrength(val){
    const wrap=document.getElementById('strengthWrap');
    const lbl=document.getElementById('pwLabel');
    const bars=[...Array(4)].map((_,i)=>document.getElementById('sb'+(i+1)));
    if(!val){ wrap.style.display='none'; document.querySelectorAll('.pw-req').forEach(r=>r.classList.remove('met','fail')); pwStrong=false; return; }
    wrap.style.display='block';
    let met=0;
    document.querySelectorAll('.pw-req').forEach(r=>{
        const ok=RULES[r.dataset.rule]?RULES[r.dataset.rule](val):false;
        r.classList.toggle('met',ok); r.classList.toggle('fail',!ok);
        if(ok) met++;
    });
    pwStrong=(met===6);
    const lv=LEVELS[Math.min(met,6)];
    lbl.textContent=lv.label; lbl.style.color=lv.color;
    bars.forEach((b,i)=>b.style.background=i<lv.bars?lv.color:'#e2e8f0');
}

function checkMatch(){
    const pw=document.getElementById('password').value;
    const cf=document.getElementById('password_confirmation').value;
    const el=document.getElementById('pwMatch');
    if(!cf){ el.textContent=''; el.className='pw-match'; pwMatch=false; return; }
    if(pw===cf){ el.innerHTML='<i class="fas fa-check-circle"></i> Passwords match'; el.className='pw-match ok'; pwMatch=true; }
    else { el.innerHTML='<i class="fas fa-times-circle"></i> Passwords do not match'; el.className='pw-match bad'; pwMatch=false; }
}

document.getElementById('password').addEventListener('input',function(){ checkStrength(this.value); if(document.getElementById('password_confirmation').value) checkMatch(); });
document.getElementById('password_confirmation').addEventListener('input',checkMatch);

/* Block submit */
document.getElementById('regForm').addEventListener('submit',function(e){
    if(!pwStrong){
        e.preventDefault();
        document.querySelectorAll('.pw-req:not(.met)').forEach(r=>{
            r.classList.add('fail');
            r.animate([{transform:'translateX(-3px)'},{transform:'translateX(3px)'},{transform:'none'}],{duration:280,iterations:2});
        });
        document.getElementById('password').focus();
        return;
    }
    const pw=document.getElementById('password').value;
    const cf=document.getElementById('password_confirmation').value;
    if(pw!==cf){
        e.preventDefault();
        document.getElementById('pwMatch').innerHTML='<i class="fas fa-times-circle"></i> Passwords do not match';
        document.getElementById('pwMatch').className='pw-match bad';
        document.getElementById('password_confirmation').focus();
        return;
    }
    document.getElementById('btnTxt').textContent='Creating account…';
    document.getElementById('btnSpin').style.display='block';
    document.getElementById('submitBtn').disabled=true;
});
</script>
</body>
</html>
