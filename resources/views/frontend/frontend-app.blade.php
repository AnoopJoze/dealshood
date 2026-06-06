<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.ico">
    <title>DealsHood — Discover the Best Deals Near You</title>
    @php
        $ogImage = str_replace('http://', 'https://', url('/frontend/img/favicon.ico'));
        $ogTitle = 'DealsHood — Discover the Best Deals Near You';
        $ogDesc  = 'Find great offers from your neighbourhood, every day.';
        $ogUrl   = url()->current();
    @endphp
    <meta name="description"  content="{{ $ogDesc }}">
    <link rel="canonical"     href="{{ $ogUrl }}">
    <meta property="og:type"  content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url"   content="{{ $ogUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ setting('site_name', 'DealsHood') }}">
    <link rel="apple-touch-icon" href="/frontend/img/icons/icon-192x192.png">
    
    <script>
    // Register service worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('SW registered:', reg.scope))
                .catch(err => console.log('SW failed:', err));
        });
    }
    
    let deferredPrompt;

window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredPrompt = e;

    // Show install buttons
    const btn  = document.getElementById('pwaInstallBtn');
    const btnM = document.getElementById('pwaInstallBtnMobile');
    if (btn)  btn.style.display  = 'flex';
    if (btnM) btnM.style.display = 'flex';
});

function installPWA() {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(choice => {
        // Hide buttons after user responds
        const btn  = document.getElementById('pwaInstallBtn');
        const btnM = document.getElementById('pwaInstallBtnMobile');
        if (btn)  btn.style.display  = 'none';
        if (btnM) btnM.style.display = 'none';
        deferredPrompt = null;
    });
}

// Listen for successful install
window.addEventListener('appinstalled', () => {
    console.log('DealsHood installed as PWA');
    deferredPrompt = null;
});
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

    <style>
    :root {
        --ink:#0d0d0d; --ink-mid:#3a3a3a; --ink-muted:#6b6b6b;
        --surf:#faf9f7; --surf-2:#f2f1ef;
        --white:#ffffff; --accent:#0f3f7e;
        --r:14px; --rlg:20px;
        --sh-sm:0 2px 12px rgba(0,0,0,.07);
        --sh-md:0 6px 32px rgba(0,0,0,.10);
        --nav-h:64px;
    }
    *,*::before,*::after { box-sizing:border-box; }
    body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
           background:var(--surf); color:var(--ink); margin:0; }
    .wrap { max-width:1180px; margin:0 auto; padding:0 24px; }

    /* ── Navbar ── */
    .dh-nav { position:sticky; top:0; left:0; right:0; height:var(--nav-h);
              background:#fff; border-bottom:1px solid rgba(0,0,0,.07);
              z-index:1000; display:flex; align-items:center; }
    .dh-nav-inner { display:flex; align-items:center; justify-content:space-between;
                    width:100%; max-width:1180px; margin:0 auto; padding:0 24px;
                    position:relative; }
    .dh-nav-logo img { height:45px; display:block; }
    .dh-nav-actions { display:flex; align-items:center; gap:10px; }
    .dh-btn-nav { display:inline-flex; align-items:center; gap:6px; font-size:.75rem;
                  font-weight:500; letter-spacing:.04em; border:none; cursor:pointer;
                  border-radius:100px; padding:9px 18px; text-decoration:none; transition:transform .15s; }
    .dh-btn-nav:hover { transform:translateY(-1px); }
    .dh-btn-ig { background:#e1306c; color:#fff; }
    .dh-btn-wa { background:#25d366; color:#fff; }
    .dh-nav-toggle { display:none; background:none; border:none; cursor:pointer;
                     flex-direction:column; gap:5px; padding:6px; }
    .dh-nav-toggle span { display:block; width:22px; height:2px; background:var(--ink); border-radius:2px; }
    @media(max-width:640px){
        .dh-nav-toggle { display:flex; }
        .dh-nav-actions { display:none; position:absolute; top:var(--nav-h); left:0; right:0;
                          background:#fff; border-bottom:1px solid rgba(0,0,0,.08);
                          padding:16px 24px; flex-direction:column; align-items:flex-start; gap:10px; }
        .dh-nav-actions.open { display:flex; }
    }

    /* ── Hero ── */
    .dh-hero { position:relative; overflow:hidden;
               background:var(--ink); display:flex; flex-direction:column;
               align-items:center; justify-content:center; }
    .dh-hero-bg { position:absolute; inset:0;
                  background:url('/frontend/img/office-dark.jpg') center/cover no-repeat; opacity:.52; }
    .dh-hero-overlay { position:absolute; inset:0;
                       background:linear-gradient(160deg,rgba(13,13,13,.78) 0%,rgba(13,13,13,.32) 55%,rgba(15,63,126,.2) 100%); }
    .dh-hero-wave { position:absolute; bottom:-1px; left:0; right:0; z-index:3; line-height:0; pointer-events:none; }
    .dh-hero-wave svg { display:block; width:100%; }

    .dh-hero-text { position:relative; z-index:4; text-align:center;
                    max-width:620px; padding:40px 24px 0; animation:fadeUp .55s .1s both; }
    .dh-hero-title { font-size:clamp(2rem,4.5vw,3.2rem); font-weight:800; color:#fff;
                     line-height:1.14; letter-spacing:-.025em; margin:0 0 8px; }
    .dh-hero-sub { font-size:.9rem; color:rgba(255,255,255,.5); font-weight:300; margin:0 0 22px; }

    /* ── Location trigger button ── */
    .loc-trigger {
        display:inline-flex; align-items:center; gap:9px;
        background:rgba(255,255,255,.13); backdrop-filter:blur(14px);
        -webkit-backdrop-filter:blur(14px);
        border:1.5px solid rgba(255,255,255,.25); color:#fff;
        border-radius:100px; padding:11px 20px 11px 13px;
        font-size:.84rem; font-weight:600; cursor:pointer;
        transition:all .18s; -webkit-tap-highlight-color:transparent; white-space:nowrap;
    }
    .loc-trigger:hover { background:rgba(255,255,255,.22); border-color:rgba(255,255,255,.5);
                         transform:translateY(-1px); box-shadow:0 8px 28px rgba(0,0,0,.28); }
    .loc-trigger:active { transform:scale(.97); }
    .lt-pin { width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,.18);
              display:flex; align-items:center; justify-content:center;
              font-size:.7rem; flex-shrink:0; transition:background .15s; }
    .loc-trigger:hover .lt-pin { background:rgba(255,255,255,.28); }
    .lt-dot { width:8px; height:8px; border-radius:50%; background:#4ade80; flex-shrink:0;
              display:none; box-shadow:0 0 0 2px rgba(74,222,128,.3); }
    .loc-trigger.has-loc .lt-dot { display:block; }
    .lt-label { flex:1; text-align:left; }
    .lt-chevron { font-size:.58rem; opacity:.6; margin-left:4px; }

    .loc-pill { display:none; margin-top:9px; background:rgba(255,255,255,.12);
                backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.2);
                border-radius:100px; padding:5px 8px 5px 12px; color:#fff;
                font-size:.74rem; font-weight:500; align-items:center; gap:8px;
                cursor:pointer; transition:background .15s; }
    .loc-pill.show { display:inline-flex; }
    .loc-pill:hover { background:rgba(255,255,255,.2); }
    .lp-clear { width:18px; height:18px; border-radius:50%; background:rgba(255,255,255,.2);
                border:none; color:#fff; font-size:.55rem; cursor:pointer;
                display:flex; align-items:center; justify-content:center;
                -webkit-tap-highlight-color:transparent; flex-shrink:0; transition:background .14s; }
    .lp-clear:hover { background:rgba(255,255,255,.38); }

    /* ══════════════════════════════════════════
       CATEGORY TILE GRID
       Panel: full width, no max-width constraint.
       Grid: 2 columns, capped at 640px, centred.
       Tiles: horizontal (icon-left, text-right).
    ══════════════════════════════════════════ */
    .dh-hero-panel {
    position:relative; z-index:4;
    width:100%;
    align-self:stretch;   /* ← overrides parent align-items:center */
    padding:20px 24px 60px;
    animation:fadeUp .55s .25s both;
}
    .dh-glass-grid {
        display:grid;
        grid-template-columns:repeat(2, 1fr);
        gap:12px;
        width:100%;
        max-width:640px;              /* desktop cap */
        margin:0 auto;                /* centre on desktop */
    }

    .dh-gtile {
        display:flex; flex-direction:row; align-items:center; gap:12px;
        padding:14px 16px;
        border-radius:14px; color:#fff;
        font-size:.88rem; font-weight:700; line-height:1.3;
        background:rgba(255,255,255,.1); backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px); border:1.5px solid rgba(255,255,255,.16);
        transition:transform .2s, background .2s, box-shadow .2s, border-color .2s;
        cursor:pointer; user-select:none; text-decoration:none;
        width:100%; min-width:0;      /* allow shrinking */
    }
    .dh-gtile:hover { transform:translateY(-3px) scale(1.02); background:rgba(255,255,255,.2);
                      border-color:rgba(255,255,255,.4); box-shadow:0 10px 28px rgba(0,0,0,.28); color:#fff; }
    .dh-gtile:active { transform:scale(.97); }

    .dh-gtile .gtile-icon {
        width:42px; height:42px; border-radius:11px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:.95rem; background:rgba(255,255,255,.16); transition:transform .2s;
    }
    .dh-gtile:hover .gtile-icon { transform:scale(1.1); }

    .dh-gtile .gtile-name {
        flex:1; min-width:0;
        white-space:nowrap; 
        /* overflow:hidden;  */
        text-overflow:ellipsis;
    }

    /* ── Sections ── */
    .dh-sec-head { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:24px; }
    .dh-eyebrow { display:inline-flex; align-items:center; gap:8px; font-size:.67rem;
                  font-weight:500; letter-spacing:.14em; text-transform:uppercase;
                  color:var(--accent); margin-bottom:5px; }
    .dh-eyebrow::before { content:''; display:inline-block; width:18px; height:2px;
                           background:var(--accent); border-radius:2px; }
    .dh-sec-title { font-size:1.5rem; font-weight:700; color:var(--ink); margin:0; }
    .dh-view-all { font-size:.77rem; font-weight:500; color:var(--ink-muted); text-decoration:none;
                   display:inline-flex; align-items:center; gap:4px; transition:color .15s; }
    .dh-view-all:hover { color:var(--accent); }

    /* ── Carousels ── */
    .dh-carousel-sec { padding:32px 0 12px; background:var(--surf); }
    .dh-carousel-block { margin-bottom:40px; }
    .dh-carousel-block:last-child { margin-bottom:0; }
    .dh-carousel-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .dh-carousel-title { font-size:1.12rem; font-weight:700; color:var(--ink); margin:0;
                          display:flex; align-items:center; gap:10px; }
    .cat-badge { font-size:.61rem; font-weight:600; letter-spacing:.07em; text-transform:uppercase;
                 padding:3px 10px; border-radius:100px; }
    .dh-carousel-controls { display:flex; align-items:center; gap:8px; }
    .dh-c-btn { width:32px; height:32px; border-radius:50%; border:1.5px solid rgba(0,0,0,.12);
                background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;
                font-size:.8rem; color:var(--ink-muted); transition:all .15s; }
    .dh-c-btn:hover { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-track-outer { position:relative; overflow:hidden; }
    .dh-track { display:flex; gap:20px; overflow-x:auto; padding-bottom:16px;
                scroll-behavior:smooth; scroll-snap-type:x mandatory;
                -ms-overflow-style:none; scrollbar-width:none; cursor:grab; user-select:none; }
    .dh-track.is-dragging { cursor:grabbing; scroll-behavior:auto; }
    .dh-track::-webkit-scrollbar { display:none; }
    .dh-track-outer::after { content:''; position:absolute; right:0; top:0; bottom:16px; width:64px;
                              pointer-events:none; z-index:1;
                              background:linear-gradient(to left,var(--surf),transparent); }
    .dh-track .dh-card { flex:0 0 350px; scroll-snap-align:start; }
    .dh-track.is-dragging .dh-card { pointer-events:none; }
    @media(max-width:560px){ .dh-track .dh-card { flex:0 0 260px; } }

    .sec-spinner { text-align:center; padding:40px 0; }
    .sec-spinner span { display:inline-block; width:9px; height:9px; border-radius:50%;
                        background:var(--accent); margin:0 3px; animation:dotPulse 1.2s infinite both; }
    .sec-spinner span:nth-child(2){ animation-delay:.2s; }
    .sec-spinner span:nth-child(3){ animation-delay:.4s; }

    /* ── Post cards ── */
    .dh-latest-sec { padding:0 0 80px; background:var(--surf); }
    .dh-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    @media(max-width:900px){ .dh-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:560px){ .dh-grid { grid-template-columns:1fr; } }
    .dh-card { background:#fff; border-radius:var(--rlg); overflow:hidden; box-shadow:var(--sh-sm);
               border:1px solid rgba(0,0,0,.05); display:flex; flex-direction:column;
               transition:transform .22s,box-shadow .22s; }
    .dh-card:hover { transform:translateY(-5px); box-shadow:var(--sh-md); }
    .dh-card-media { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-card-media img,.dh-card-media video { width:100%; height:200px; object-fit:cover;
                                              display:block; transition:transform .35s; }
    .dh-card:hover .dh-card-media img { transform:scale(1.04); }
    .dh-card-media .ratio { height:200px; }
    .badge-feat { position:absolute; top:10px; right:10px; background:#f59e0b; color:#fff;
                  font-size:.6rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
                  padding:4px 10px; border-radius:100px; }
    .dh-card-body { padding:16px 18px 18px; display:flex; flex-direction:column; flex:1; }
    .dh-badges { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:10px; }
    .dh-b { font-size:.6rem; font-weight:500; letter-spacing:.07em; text-transform:uppercase;
             padding:3px 9px; border-radius:100px; }
    .dh-b-loc { background:var(--surf-2); color:var(--ink-muted); }
    .dh-b-cat { background:rgba(15,63,126,.08); color:var(--accent); }
    .dh-b-sub { background:rgba(59,130,246,.08); color:#1d4ed8; }
    .dh-card-title { font-size:.98rem; font-weight:700; color:var(--ink); line-height:1.35;
                     margin:0 0 7px; text-decoration:none; display:block; transition:color .15s; }
    .dh-card-title:hover { color:var(--accent); }
    .dh-card-desc { font-size:.8rem; line-height:1.6; color:var(--ink-muted);
                    font-weight:300; flex:1; margin-bottom:12px; }
    .dh-card-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap;
                    padding-top:10px; border-top:1px solid rgba(0,0,0,.06); margin-bottom:12px; }
    .dh-meta-btn,.dh-meta-box { display:flex; align-items:center; gap:6px; padding:4px 9px;
                                 border-radius:14px; background:#fff; border:1px solid #edf0f5;
                                 transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.04); }
    .dh-meta-btn { cursor:pointer; outline:none; }
    .dh-meta-btn:hover,.dh-meta-box:hover { transform:translateY(-2px); box-shadow:0 4px 14px rgba(0,0,0,.08); }
    .dh-meta-icon { font-size:11px; color:#6b7280; }
    .dh-meta-count { font-size:12px; font-weight:600; color:#1f2937; }
    .dh-meta-time { margin-left:auto; display:flex; align-items:center; gap:5px; font-size:11px; color:#6b7280; }
    .likeBtn.liked { background:rgba(255,77,109,.08); border-color:rgba(255,77,109,.18); }
    .likeBtn.liked .dh-meta-icon { color:#ff4d6d; }
    .likeBtn.liked .dh-meta-count { color:#ff4d6d; }
    .dh-card-actions { display:flex; gap:8px; }
    .dh-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px;
               font-size:.74rem; font-weight:500; border-radius:100px; padding:8px 16px;
               text-decoration:none; border:1.5px solid; cursor:pointer; transition:all .15s; flex:1; }
    .dh-btn-primary { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
    .dh-btn-ghost { background:transparent; color:var(--ink-muted);
                    border-color:rgba(0,0,0,.12); flex:0 0 auto; padding:8px 12px; }
    .dh-btn-ghost:hover { background:var(--surf-2); color:var(--ink); }
    .dh-empty { grid-column:1/-1; text-align:center; padding:64px 24px; color:var(--ink-muted); }
    .dh-show-more { text-align:center; margin-top:36px; }
    .dh-more-btn { display:inline-flex; align-items:center; gap:8px; font-size:.79rem;
                   font-weight:500; color:var(--ink); background:#fff; cursor:pointer;
                   border:1.5px solid rgba(0,0,0,.14); border-radius:100px; padding:12px 28px;
                   transition:all .18s; }
    .dh-more-btn:hover { background:var(--ink); color:#fff; transform:translateY(-2px); }

    /* ── Footer ── */
    .dh-footer { background:var(--ink); color:rgba(255,255,255,.7); padding:60px 0 0; font-size:.84rem; }
    .dh-footer-grid { display:grid; grid-template-columns:1.6fr 1fr 1fr; gap:48px; padding-bottom:48px; }
    @media(max-width:720px){ .dh-footer-grid { grid-template-columns:1fr 1fr; } }
    @media(max-width:440px){ .dh-footer-grid { grid-template-columns:1fr; } }
    .dh-footer-brand { font-size:1.1rem; color:#fff; margin:12px 0 5px; }
    .dh-footer-tag { font-size:.77rem; color:rgba(255,255,255,.38); margin:0; }
    .dh-footer-social { display:flex; gap:8px; margin-top:18px; }
    .dh-footer-social a { width:34px; height:34px; border-radius:50%; border:1px solid rgba(255,255,255,.15);
                           display:flex; align-items:center; justify-content:center;
                           color:rgba(255,255,255,.6); font-size:.88rem; text-decoration:none; transition:.15s; }
    .dh-footer-social a:hover { border-color:rgba(255,255,255,.5); color:#fff; }
    .dh-footer-col-title { font-size:.64rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase;
                            color:var(--accent); margin-bottom:14px; }
    .dh-footer-links { list-style:none; padding:0; margin:0; }
    .dh-footer-links li { margin-bottom:10px; }
    .dh-footer-links a { color:rgba(255,255,255,.52); text-decoration:none; transition:color .15s; }
    .dh-footer-links a:hover { color:#fff; }
    .dh-footer-bottom { border-top:1px solid rgba(255,255,255,.08); text-align:center;
                         padding:20px 0; font-size:.74rem; color:rgba(255,255,255,.3); }
    .dh-footer-bottom a { color:rgba(255,255,255,.48); text-decoration:none; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }
    @keyframes dotPulse { 0%,80%,100%{opacity:.2;transform:scale(.75);} 40%{opacity:1;transform:scale(1);} }

    /* ══════════════════════════════════════════
       MOBILE  ≤ 768px
       Panel goes edge-to-edge.
       Grid fills the full panel width with 2 cols.
    ══════════════════════════════════════════ */
    @media(max-width:768px){
       .dh-hero-panel {
        padding:16px 12px 48px;
        align-self:stretch;   /* keep it here too for specificity */
    }
    .dh-glass-grid {
        max-width:100%;
        width:100%;
        gap:10px;
    }
    .dh-gtile {
        padding:12px 14px;
        font-size:.82rem;
        gap:10px;
        border-radius:12px;
    }
    .dh-gtile .gtile-icon {
        width:36px; height:36px;
        font-size:.85rem; border-radius:9px;
    }

        /* Everything else unchanged */
        .dh-hero-text  { padding:28px 24px 0; }
        .dh-track { padding-left:16px; }
        .dh-track .dh-card:last-child { margin-right:16px; }
        .dh-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
        .dh-card-body { padding:12px 14px 14px; }
        .dh-card-title { font-size:.88rem; }
        .dh-card-desc  { display:none; }
        .dh-sec-head   { margin-bottom:14px; }
        .dh-sec-title  { font-size:1.15rem; }
        .dh-more-btn   { width:100%; justify-content:center; }
        .dh-footer-grid { grid-template-columns:1fr !important; gap:28px; }
        .dh-footer { padding:36px 0 0; }
        input,select,textarea { font-size:16px !important; }
    }

    * { -webkit-tap-highlight-color:transparent; -webkit-overflow-scrolling:touch; }
    </style>
</head>
<body>

<nav class="dh-nav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" style="height:45px;">
        </a>
        <button class="dh-nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="dh-nav-actions" id="navActions">
            <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj"
               target="_blank" class="dh-btn-nav dh-btn-ig">
                <i class="bi bi-instagram"></i> Follow
            </a>
            <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
               target="_blank" class="dh-btn-nav dh-btn-wa">
                <i class="bi bi-whatsapp"></i> Contact
            </a>
        </div>
    </div>
    <button id="pwaInstallBtn"
        onclick="installPWA()"
        style="display:none;align-items:center;gap:6px;
               font-size:.75rem;font-weight:600;
               border:1.5px solid rgba(0,0,0,.1);
               background:#fff;color:var(--ink);
               border-radius:100px;padding:8px 16px;
               cursor:pointer;transition:all .15s;">
    <i class="fas fa-download"></i> Install App
</button>
</nav>

@php
$palette = [
    ['bg'=>'rgba(219,234,254,.9)','ic'=>'#1d4ed8','icon'=>'fa-tags'],
    ['bg'=>'rgba(209,250,229,.9)','ic'=>'#059669','icon'=>'fa-leaf'],
    ['bg'=>'rgba(254,243,199,.9)','ic'=>'#d97706','icon'=>'fa-fire'],
    ['bg'=>'rgba(252,231,243,.9)','ic'=>'#db2777','icon'=>'fa-heart'],
    ['bg'=>'rgba(237,233,254,.9)','ic'=>'#7c3aed','icon'=>'fa-gem'],
    ['bg'=>'rgba(207,250,254,.9)','ic'=>'#0891b2','icon'=>'fa-bolt'],
    ['bg'=>'rgba(254,242,242,.9)','ic'=>'#dc2626','icon'=>'fa-percent'],
    ['bg'=>'rgba(236,253,245,.9)','ic'=>'#16a34a','icon'=>'fa-star'],
    ['bg'=>'rgba(255,247,237,.9)','ic'=>'#ea580c','icon'=>'fa-house'],
    ['bg'=>'rgba(240,249,255,.9)','ic'=>'#0284c7','icon'=>'fa-car'],
    ['bg'=>'rgba(253,244,255,.9)','ic'=>'#a21caf','icon'=>'fa-shirt'],
    ['bg'=>'rgba(248,250,252,.9)','ic'=>'#475569','icon'=>'fa-laptop'],
];
@endphp

<header class="dh-hero">
    <div class="dh-hero-bg" id="heroBg"></div>
    <div class="dh-hero-overlay"></div>

    <div class="dh-hero-text">
        <h1 class="dh-hero-title">Discover the best deals near you.</h1>
        {{-- <p class="dh-hero-sub" id="heroSub">Pick your area or browse by category below</p> --}}

        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
            <button class="loc-trigger" id="locTrigger" type="button"
                    onclick="window.openLocationPopup && window.openLocationPopup()">
                <span class="lt-pin"><i class="fas fa-map-marker-alt"></i></span>
                <span class="lt-dot"></span>
                <span class="lt-label" id="locLabel">Choose your area</span>
                <i class="fas fa-chevron-down lt-chevron"></i>
            </button>
            {{-- <div class="loc-pill" id="locPill"
                 onclick="window.openLocationPopup && window.openLocationPopup()">
                <i class="fas fa-map-marker-alt" style="font-size:.62rem;opacity:.75;"></i>
                <span id="locPillName"></span>
                <button class="lp-clear" id="locPillClear" title="Change area"
                        onclick="event.stopPropagation();clearLoc();">
                    <i class="fas fa-times"></i>
                </button>
            </div> --}}
        </div>
    </div>

    <div class="dh-hero-panel">
        <div class="dh-glass-grid" id="catGrid">
            @foreach ($categories as $i => $cat)
                @php $p = $palette[$i % count($palette)]; @endphp
                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                   class="dh-gtile"
                   data-base="{{ route('posts.listing', ['category_id' => $cat->slug]) }}">
                    <span class="gtile-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas {{ $p['icon'] }}"></i>
                    </span>
                    <span class="gtile-name">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="dh-hero-wave">
        <svg viewBox="0 0 1440 56" fill="none">
            <path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/>
        </svg>
    </div>
</header>

<section class="dh-carousel-sec">
    <div class="wrap">
        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Popular</div>
                <h2 class="dh-sec-title" id="carouselHeading">Top Deals by Category</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all" id="carouselViewAll">
                See all <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div id="carouselContent">
            @foreach ($categoryCarousels as $i => $cat)
                @if ($cat->posts->isNotEmpty())
                    @php $p = $palette[$i % count($palette)]; @endphp
                    <div class="dh-carousel-block">
                        <div class="dh-carousel-head">
                            <h3 class="dh-carousel-title">
                                <span style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                                             display:flex;align-items:center;justify-content:center;
                                             font-size:.85rem;background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                                    <i class="fas {{ $p['icon'] }}"></i>
                                </span>
                                {{ $cat->name }}
                                <span class="cat-badge" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">Popular</span>
                            </h3>
                            <div class="dh-carousel-controls">
                                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                                   class="dh-view-all me-1">
                                    See all {{ number_format($cat->posts_count) }}
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <button class="dh-c-btn c-prev" data-target="cr-{{ $cat->id }}">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="dh-c-btn c-next" data-target="cr-{{ $cat->id }}">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="dh-track-outer">
                            <div class="dh-track" id="cr-{{ $cat->id }}">
                                @foreach ($cat->posts as $post)
                                    @include('frontend.post-single-card', ['post' => $post])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<section class="dh-latest-sec">
    <div class="wrap">
        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Just in</div>
                <h2 class="dh-sec-title" id="latestHeading">Latest Deals</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all" id="latestViewAll">
                View all <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="dh-grid" id="postsGrid">
            @forelse ($posts as $post)
                @include('frontend.post-single-card', ['post' => $post])
            @empty
                <div class="dh-empty">
                    <p style="font-size:2rem;opacity:.3;">🔍</p>
                    <p>No deals yet — check back soon!</p>
                </div>
            @endforelse
        </div>
        @if ($posts->hasMorePages())
            <div class="dh-show-more" id="showMoreWrap">
                <button class="dh-more-btn" id="loadMoreBtn" data-next="{{ $posts->nextPageUrl() }}">
                    Load More Deals
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</section>

<footer class="dh-footer">
    <div class="wrap">
        <div class="dh-footer-grid">
            <div>
                <img src="/frontend/img/dealshood.png" alt="DealsHood"
                     style="height:32px;filter:brightness(0) invert(1);opacity:.8;">
                <p class="dh-footer-brand">DealsHood</p>
                <p class="dh-footer-tag">Discover the best deals around you.</p>
                <div class="dh-footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <p class="dh-footer-col-title">Company</p>
                <ul class="dh-footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Advertise</a></li>
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Support</p>
                <ul class="dh-footer-links">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="dh-footer-bottom">
            <p>&copy; <span id="footerYear"></span> <a href="#">DealsHood</a>. All rights reserved.</p>
        </div>
    </div>
</footer>

@include('frontend.frontend-mobile')
<style>
@media(max-width: 768px) {
    #postsGrid {
        grid-template-columns: 1fr !important;
        gap: 16px;
    }
}
</style>
<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
const CSRF        = '{{ csrf_token() }}';
const LISTING_URL = '{{ route("posts.listing") }}';
const HOME_URL    = '{{ route("home") }}';

document.getElementById('footerYear').textContent = new Date().getFullYear();

document.getElementById('navToggle') && document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navActions').classList.toggle('open');
});

const heroBg = document.getElementById('heroBg');
window.addEventListener('scroll', function () {
    if (heroBg) heroBg.style.transform = 'translateY(' + (scrollY * .25) + 'px)';
}, { passive: true });

function makeDraggable(el) {
    if (!el) return;
    let isDown=false, startX=0, sl=0, wasDragged=false;
    el.addEventListener('mousedown', e => { isDown=true; wasDragged=false; startX=e.pageX-el.offsetLeft; sl=el.scrollLeft; el.style.cursor='grabbing'; });
    el.addEventListener('mouseleave', () => { isDown=false; el.style.cursor=''; el.classList.remove('is-dragging','dragging'); });
    el.addEventListener('mouseup',    () => { isDown=false; el.style.cursor=''; el.classList.remove('is-dragging','dragging'); setTimeout(()=>wasDragged=false,50); });
    el.addEventListener('mousemove', e => {
        if(!isDown) return; e.preventDefault();
        const walk=(e.pageX-el.offsetLeft-startX)*1.4;
        if(Math.abs(walk)>6){ wasDragged=true; el.classList.add('is-dragging','dragging'); }
        el.scrollLeft=sl-walk;
    });
    el.addEventListener('click', e=>{ if(wasDragged){ e.preventDefault(); e.stopPropagation(); } }, true);
    let tx=0,ts=0;
    el.addEventListener('touchstart', e=>{ tx=e.touches[0].pageX; ts=el.scrollLeft; },{passive:true});
    el.addEventListener('touchmove',  e=>{ el.scrollLeft=ts+(tx-e.touches[0].pageX); },{passive:true});
}
function initDrag() {
    document.querySelectorAll('.dh-track:not([data-drag])').forEach(el => {
        el.setAttribute('data-drag','1'); makeDraggable(el);
    });
}
initDrag();

$(document).on('click', '.c-prev,.c-next', function () {
    const $t = $('#' + $(this).data('target'));
    if (!$t.length) return;
    const w = $t.find('.dh-card').first().outerWidth(true) || 300;
    $t[0].scrollBy({ left: $(this).hasClass('c-prev') ? -w*2 : w*2, behavior:'smooth' });
});

let activeLocSlug = '';
let activeLocName = '';

function setLocUI(slug, name) {
    activeLocSlug = slug || '';
    activeLocName = name || '';
    document.getElementById('locLabel').textContent = slug ? name : 'Choose your area';
    slug ? document.getElementById('locTrigger').classList.add('has-loc')
         : document.getElementById('locTrigger').classList.remove('has-loc');
    const pill = document.getElementById('locPill');
    document.getElementById('locPillName').textContent = name || '';
    slug ? pill.classList.add('show') : pill.classList.remove('show');
    document.getElementById('heroSub').textContent = slug
        ? 'Showing deals in ' + name
        : 'Pick your area or browse by category below';
    refreshLinks();
}

function clearLoc() {
    setLocUI('', '');
    reloadContent();
    try { localStorage.setItem('dh_locality_v1', JSON.stringify({slug:'',name:'All Areas',ts:Date.now()})); } catch(e){}
}

function refreshLinks() {
    document.querySelectorAll('#catGrid .dh-gtile[data-base]').forEach(el => {
        let href = el.dataset.base;
        if (activeLocSlug) href += (href.includes('?') ? '&' : '?') + 'locality_id=' + activeLocSlug;
        el.href = href;
    });
    const base = LISTING_URL + (activeLocSlug ? '?locality_id=' + activeLocSlug : '');
    document.getElementById('carouselViewAll').href = base;
    document.getElementById('latestViewAll').href   = base;
    document.querySelectorAll('#carouselContent a.dh-view-all').forEach(el => {
        const m = el.href.match(/category_id=([^&]+)/);
        let href = LISTING_URL;
        if (m) href += '?category_id=' + m[1];
        if (activeLocSlug) href += (m ? '&' : '?') + 'locality_id=' + activeLocSlug;
        el.href = href;
    });
}

function reloadContent() {
    const spinner = '<div class="sec-spinner"><span></span><span></span><span></span></div>';
    $('#carouselContent').html(spinner);
    $('#postsGrid').html('<div style="grid-column:1/-1;">' + spinner + '</div>');
    $('#showMoreWrap').hide();
    $.ajax({
        url: HOME_URL, type: 'GET',
        data: activeLocSlug ? { filter_locality: activeLocSlug } : {},
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            $('#carouselContent').html(res.carousel_html ||
                '<p style="text-align:center;color:var(--ink-muted);padding:32px 0;font-size:.85rem;">No popular deals in this area yet.</p>');
            $('#postsGrid').html(res.posts_html || '');
            if (res.next_page) {
                const btn = '<div class="dh-show-more" id="showMoreWrap"><button class="dh-more-btn" id="loadMoreBtn" data-next="'
                    + res.next_page + '">Load More Deals <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button></div>';
                $('#showMoreWrap').length
                    ? $('#showMoreWrap').show().find('#loadMoreBtn').data('next', res.next_page)
                    : $('#postsGrid').after(btn);
            } else { $('#showMoreWrap').hide(); }
            $('#carouselHeading').text(activeLocSlug ? activeLocName + ' — Popular Deals' : 'Top Deals by Category');
            $('#latestHeading').text(activeLocSlug ? activeLocName + ' — Latest Deals' : 'Latest Deals');
            refreshLinks(); initDrag();
        },
        error: function () { $('#carouselContent').html(''); }
    });
}

$(document).on('click', '.likeBtn', function () {
    const btn=$(this), id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like', {_token:CSRF}, function(res){
        $('#lc-'+id).text(res.likes);
        res.liked ? btn.addClass('liked') : btn.removeClass('liked');
    });
});
$(document).on('click', '.shareBtn', function () {
    const id=$(this).data('id'), url=$(this).data('url');
    navigator.share ? navigator.share({url}) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.post('/posts/'+id+'/share', {_token:CSRF, platform:'web'});
});
$(document).on('click', '#loadMoreBtn', function () {
    const btn=$(this), next=btn.data('next');
    if (!next) return;
    btn.text('Loading…').prop('disabled',true);
    $.get(next, function(res){
        if (res.html) {
            $('#postsGrid').append(res.html);
            res.next_page
                ? btn.data('next',res.next_page).text('Load More Deals').prop('disabled',false)
                : btn.closest('.dh-show-more').remove();
        }
    }).fail(()=> btn.text('Load More Deals').prop('disabled',false));
});
</script>

@include('frontend.location-popup', ['localities' => $localities])

</body>
</html>