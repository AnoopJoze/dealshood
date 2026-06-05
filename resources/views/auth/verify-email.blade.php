<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>Verify Your Email — DealsHood</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    :root {
        --ink:#0f172a; --ink-2:#334155; --ink-3:#64748b;
        --surf:#f8fafc; --surf-2:#f1f5f9; --surf-3:#e2e8f0;
        --white:#fff; --accent:#0f3f7e; --r:14px;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body {
        font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
        background:var(--surf); color:var(--ink);
        min-height:100vh; display:flex; flex-direction:column;
        align-items:center; justify-content:center; padding:24px;
    }

    .card {
        background:var(--white); border-radius:20px;
        border:1px solid var(--surf-3);
        box-shadow:0 4px 24px rgba(0,0,0,.09);
        width:100%; max-width:460px;
        padding:44px 40px;
        text-align:center;
        animation:up .4s both;
    }
    @media(max-width:480px){ .card{ padding:32px 24px; } }
    @keyframes up{ from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:none;} }

    /* Icon */
    .icon-wrap {
        width:72px; height:72px; border-radius:50%; margin:0 auto 24px;
        background:linear-gradient(135deg,#0f3f7e,#3b82f6);
        display:flex; align-items:center; justify-content:center;
        font-size:1.8rem; color:#fff;
        box-shadow:0 8px 24px rgba(15,63,126,.3);
    }
    /* Logo */
    .logo { height:32px; margin-bottom:28px; }

    h1 { font-size:1.45rem; font-weight:800; color:var(--ink); letter-spacing:-.02em; margin-bottom:12px; }
    .sub {
        font-size:.9rem; color:var(--ink-3); line-height:1.7;
        margin-bottom:28px;
    }
    .sub strong { color:var(--ink-2); font-weight:600; }

    /* Success alert */
    .alert-ok {
        background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;
        padding:12px 16px; font-size:.83rem; color:#15803d;
        display:flex; align-items:center; gap:8px; margin-bottom:20px;
        text-align:left;
    }

    /* Resend form */
    .resend-btn {
        width:100%; padding:13px; border:none; border-radius:var(--r);
        background:var(--accent); color:#fff;
        font-size:.9rem; font-weight:700; cursor:pointer;
        display:flex; align-items:center; justify-content:center; gap:8px;
        transition:background .15s, transform .15s, box-shadow .15s;
        font-family:inherit; letter-spacing:.01em;
    }
    .resend-btn:hover {
        background:#0c3166; transform:translateY(-1px);
        box-shadow:0 6px 20px rgba(15,63,126,.3);
    }
    .resend-btn:active { transform:none; }
    .resend-btn:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    /* Divider */
    .divider {
        display:flex; align-items:center; gap:10px;
        margin:18px 0; font-size:.75rem; color:var(--ink-3);
    }
    .divider::before,.divider::after { content:''; flex:1; height:1px; background:var(--surf-3); }

    /* Logout link */
    .logout-form { text-align:center; }
    .logout-btn {
        background:none; border:none; cursor:pointer;
        font-size:.82rem; color:var(--ink-3); font-family:inherit;
        text-decoration:underline; transition:color .15s;
    }
    .logout-btn:hover { color:var(--accent); }

    /* Steps */
    .steps {
        display:flex; flex-direction:column; gap:12px;
        text-align:left; margin-bottom:28px;
    }
    .step {
        display:flex; align-items:flex-start; gap:12px;
        font-size:.84rem; color:var(--ink-2);
    }
    .step-num {
        width:24px; height:24px; border-radius:50%; flex-shrink:0;
        background:var(--accent); color:#fff;
        font-size:.68rem; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        margin-top:1px;
    }

    /* Spinner */
    .spin {
        width:15px; height:15px; border-radius:50%; display:none;
        border:2px solid rgba(255,255,255,.35); border-top-color:#fff;
        animation:spin .6s linear infinite;
    }
    @keyframes spin{ to{transform:rotate(360deg);} }
    </style>
</head>
<body>

<div class="card">

    <img src="/frontend/img/dealshood.png" alt="DealsHood" class="logo">

    <div class="icon-wrap">
        <i class="fas fa-envelope-open-text"></i>
    </div>

    <h1>Check your inbox</h1>
    <p class="sub">
        We sent a verification link to<br>
        <strong>{{ auth()->user()->email }}</strong>
    </p>

    {{-- Resent success --}}
    @if(session('resent'))
        <div class="alert-ok">
            <i class="fas fa-check-circle" style="flex-shrink:0;"></i>
            A fresh verification link has been sent to your email.
        </div>
    @endif

    {{-- Steps --}}
    <div class="steps">
        <div class="step">
            <span class="step-num">1</span>
            <span>Open the email from <strong>DealsHood</strong> in your inbox (check spam too).</span>
        </div>
        <div class="step">
            <span class="step-num">2</span>
            <span>Click the <strong>Verify Email Address</strong> button in the email.</span>
        </div>
        <div class="step">
            <span class="step-num">3</span>
            <span>You'll be redirected back and logged in automatically.</span>
        </div>
    </div>

    {{-- Resend --}}
    <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
        @csrf
        <button type="submit" class="resend-btn" id="resendBtn">
            <span id="resendTxt"><i class="fas fa-paper-plane"></i> &nbsp;Resend Verification Email</span>
            <span class="spin" id="resendSpin"></span>
        </button>
    </form>

    <div class="divider">or</div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-btn">
            Sign out and use a different account
        </button>
    </form>

</div>

<script>
document.getElementById('resendForm').addEventListener('submit', function(){
    const btn = document.getElementById('resendBtn');
    document.getElementById('resendTxt').style.display = 'none';
    document.getElementById('resendSpin').style.display = 'block';
    btn.disabled = true;
    // Re-enable after 60s (throttle limit is 6/min)
    setTimeout(() => {
        document.getElementById('resendTxt').style.display = 'flex';
        document.getElementById('resendSpin').style.display = 'none';
        btn.disabled = false;
    }, 60000);
});
</script>
</body>
</html>