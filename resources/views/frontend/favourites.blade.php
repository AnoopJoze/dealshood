<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    <title>My Favourites — DealsHood</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">
    <link href="/frontend/css/dh-header-footer.css?v=1.0.0" rel="stylesheet">

    <style>
    :root {
        --ink:#0f172a; --ink-2:#374151; --ink-muted:#6b7280;
        --surf:#f8fafc; --surf-2:#f1f5f9; --surf-3:#e2e8f0;
        --white:#fff; --accent:#0f3f7e;
        --red:#ef4444; --red-bg:#fef2f2;
        --r:14px; --rlg:18px;
        --sh:0 1px 4px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.07);
    }
    *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
    body {
        font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
        background:var(--surf); color:var(--ink);
    }
    /* Navbar is shared — see /frontend/css/dh-header-footer.css */

    /* ── Hero strip ── */
    .page-hero {
        background:linear-gradient(160deg,rgba(13,13,13,.78) 0%,rgba(13,13,13,.32) 55%,rgba(15,63,126,.2) 100%);
        padding:114px 20px 48px; text-align:center;
        position:relative; overflow:hidden;
    }
    .page-hero::before {
        content:''; position:absolute; inset:0;
        background:url('/frontend/img/illustrations/IMG_4871.png') center/cover; opacity:.52;
    }
    .page-hero-content { position:relative; z-index:1; }
    .hero-icon {
        width:60px; height:60px; border-radius:50%; margin:0 auto 16px;
        background:rgba(255,255,255,.12); backdrop-filter:blur(8px);
        border:1.5px solid rgba(255,255,255,.2);
        display:flex; align-items:center; justify-content:center;
        font-size:1.4rem; color:#fff;
    }
    .page-hero h1 {
        font-size:clamp(1.5rem,3vw,2rem); font-weight:800;
        color:#fff; letter-spacing:-.02em; margin-bottom:6px;
    }
    .page-hero p {
        font-size:.88rem; color:rgba(255,255,255,.55); font-weight:300;
    }
    .hero-wave {
        position:absolute; bottom:-1px; left:0; right:0; z-index:2; line-height:0;
    }
    .hero-wave svg { display:block; width:100%; }

    /* ── Main ── */
    .page-body { max-width:1100px; margin:0 auto; padding:32px 20px 120px; }
    @media(max-width:768px){ .page-body { padding:20px 14px 140px; } }

    /* ── Toolbar ── */
    .toolbar {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:12px; margin-bottom:24px;
    }
    .toolbar-info { font-size:.84rem; color:var(--ink-muted); }
    .toolbar-info strong { color:var(--ink); font-weight:700; }
    .sort-row { display:flex; gap:6px; }
    .sort-pill {
        font-size:.74rem; font-weight:500; padding:6px 14px;
        border-radius:100px; cursor:pointer;
        border:1.5px solid var(--surf-3); background:var(--white);
        color:var(--ink-muted); transition:all .15s; user-select:none;
    }
    .sort-pill:hover { border-color:var(--accent); color:var(--accent); }
    .sort-pill.active { background:var(--ink); color:#fff; border-color:var(--ink); }

    /* ── Grid ── */
    .fav-grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:20px;
    }
    @media(max-width:900px){ .fav-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:560px){ .fav-grid { grid-template-columns:1fr; gap:14px; } }

    /* ── Card ── */
    .dh-card {
        background:var(--white); border-radius:var(--rlg);
        border:1px solid var(--surf-3); box-shadow:var(--sh);
        display:flex; flex-direction:column;
        transition:transform .22s, box-shadow .22s;
        overflow:hidden; position:relative;
    }
    .dh-card:hover { transform:translateY(-4px); box-shadow:0 8px 32px rgba(0,0,0,.12); }

    /* Unfav button */
    .unfav-btn {
        position:absolute; top:10px; right:10px; z-index:5;
        width:34px; height:34px; border-radius:50%;
        background:rgba(255,255,255,.92); backdrop-filter:blur(6px);
        border:1.5px solid rgba(0,0,0,.08); cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        font-size:.78rem; color:var(--red);
        transition:all .18s; box-shadow:0 2px 8px rgba(0,0,0,.12);
    }
    .unfav-btn:hover {
        background:var(--red); color:#fff; border-color:var(--red);
        transform:scale(1.1);
    }
    .unfav-btn:active { transform:scale(.95); }

    .dh-card-media { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-card-media img {
        width:100%; height:190px; object-fit:cover; display:block;
        transition:transform .35s;
    }
    .dh-card:hover .dh-card-media img { transform:scale(1.04); }
    .dh-card-media .ratio { height:190px; }

    .dh-card-body { padding:15px 16px 16px; display:flex; flex-direction:column; flex:1; }

    .dh-badges { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:9px; }
    .dh-b {
        font-size:.6rem; font-weight:600; letter-spacing:.07em;
        text-transform:uppercase; padding:3px 9px; border-radius:100px;
    }
    .dh-b-cat { background:rgba(15,63,126,.08); color:var(--accent); }
    .dh-b-loc { background:var(--surf-2); color:var(--ink-muted); }

    .dh-card-title {
        font-size:.95rem; font-weight:700; color:var(--ink); line-height:1.35;
        margin-bottom:6px; text-decoration:none; display:block; transition:color .15s;
        display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    .dh-card-title:hover { color:var(--accent); }

    .dh-card-meta {
        display:flex; align-items:center; gap:6px; flex-wrap:wrap;
        padding-top:10px; border-top:1px solid var(--surf-2);
        margin-top:auto; padding-top:10px;
    }
    .dh-meta-box {
        display:flex; align-items:center; gap:5px; padding:3px 8px;
        border-radius:12px; background:var(--surf-2); border:1px solid var(--surf-3);
        font-size:.72rem; color:var(--ink-muted);
    }
    .dh-meta-box i { font-size:.68rem; }
    .dh-meta-time { margin-left:auto; font-size:.71rem; color:var(--ink-muted); }

    /* Card action buttons */
    .dh-card-actions { display:flex; gap:7px; margin-top:10px; }
    .dh-btn {
        display:inline-flex; align-items:center; justify-content:center; gap:5px;
        font-size:.74rem; font-weight:600; border-radius:100px; padding:7px 14px;
        text-decoration:none; border:1.5px solid; cursor:pointer;
        transition:all .15s; flex:1;
    }
    .dh-btn-primary { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
    .dh-btn-wa {
        background:transparent; color:#25d366; border-color:#25d366;
        flex:0 0 auto; padding:7px 10px;
    }
    .dh-btn-wa:hover { background:#25d366; color:#fff; }

    /* ── Empty state ── */
    .empty-state {
        grid-column:1/-1; text-align:center; padding:80px 24px;
    }
    .empty-icon {
        width:80px; height:80px; border-radius:50%; margin:0 auto 20px;
        background:var(--red-bg); border:2px solid #fecaca;
        display:flex; align-items:center; justify-content:center;
        font-size:1.8rem; color:var(--red);
    }
    .empty-title { font-size:1.15rem; font-weight:800; color:var(--ink); margin-bottom:8px; }
    .empty-sub { font-size:.88rem; color:var(--ink-muted); line-height:1.6; margin-bottom:24px; }
    .browse-btn {
        display:inline-flex; align-items:center; gap:8px;
        font-size:.84rem; font-weight:700; color:#fff;
        background:var(--accent); border:none; border-radius:100px;
        padding:12px 28px; text-decoration:none;
        transition:transform .15s, box-shadow .15s;
    }
    .browse-btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(15,63,126,.3); color:#fff; }

    /* ── Loader ── */
    .dh-loader { display:none; text-align:center; padding:32px 0; grid-column:1/-1; }
    .dh-dots { display:inline-flex; gap:6px; }
    .dh-dots span {
        width:8px; height:8px; border-radius:50%; background:var(--accent);
        animation:dotPulse 1.2s infinite both;
    }
    .dh-dots span:nth-child(2){ animation-delay:.2s; }
    .dh-dots span:nth-child(3){ animation-delay:.4s; }
    @keyframes dotPulse{0%,80%,100%{opacity:.2;transform:scale(.75);}40%{opacity:1;transform:scale(1);}}

    /* ── End message ── */
    .end-msg {
        display:none; grid-column:1/-1; text-align:center;
        padding:24px 0; font-size:.77rem; color:var(--ink-muted);
    }
    .end-msg::before,.end-msg::after {
        content:''; display:inline-block; width:28px; height:1px;
        background:var(--surf-3); vertical-align:middle; margin:0 8px;
    }

    /* ── Toast ── */
    .toast-wrap {
        position:fixed; bottom:90px; left:50%; transform:translateX(-50%);
        z-index:9000; pointer-events:none;
    }
    .toast {
        background:rgba(15,23,42,.92); backdrop-filter:blur(10px);
        color:#fff; font-size:.82rem; font-weight:500;
        padding:10px 20px; border-radius:100px;
        display:flex; align-items:center; gap:8px;
        white-space:nowrap;
        animation:toastIn .25s both;
        pointer-events:none;
    }
    @keyframes toastIn{ from{opacity:0;transform:translateY(8px);} to{opacity:1;transform:none;} }

    /* card fade-out animation */
    @keyframes fadeOut{ to{opacity:0;transform:scale(.9);} }
    .removing { animation:fadeOut .3s forwards; }

    @keyframes fadeUp{ from{opacity:0;transform:translateY(16px);} to{opacity:1;transform:none;} }
    .au { animation:fadeUp .4s both; }
    *{ -webkit-tap-highlight-color:transparent; }
    </style>
    {{-- ══════════ NEW NAVY THEME ══════════ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    :root{ --accent:#123f8f; }
    body{ font-family:'Poppins',-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif; }
    .btn-submit,.btn-primary,.auth-btn,.sort-pill.active,.dh-btn-primary{ background:#0a2a68; border-color:#0a2a68; }
    .btn-submit:hover,.btn-primary:hover,.auth-btn:hover,.dh-btn-primary:hover{ background:#071e4d; }
    </style>
</head>
<body>

@include('frontend.partials.nav', ['categories' => $categories ?? collect(), 'transparent' => true])

{{-- Hero --}}
<div class="page-hero">
    <div class="page-hero-content">
        <div class="hero-icon"><i class="fas fa-heart"></i></div>
        <h1>My Favourites</h1>
        <p>
            {{ number_format($posts->total()) }} saved deal{{ $posts->total() !== 1 ? 's' : '' }}
            · {{ (auth()->user())?auth()->user()->name:'' }}
        </p>
    </div>
    <div class="hero-wave">
        <svg viewBox="0 0 1440 48" fill="none">
            <path d="M0 48H1440V24C1200 48 960 0 720 0C480 0 240 48 0 24V48Z" fill="#f8fafc"/>
        </svg>
    </div>
</div>

{{-- Main --}}
<div class="page-body">

    @if($posts->isNotEmpty())
    {{-- Toolbar --}}
    <div class="toolbar">
        <p class="toolbar-info">
            Showing <strong>{{ number_format($posts->total()) }}</strong>
            saved deal{{ $posts->total() !== 1 ? 's' : '' }}
        </p>
        {{-- <div class="sort-row">
            <span class="sort-pill active" data-sort="latest">
                <i class="bi bi-clock"></i> Latest
            </span>
            <span class="sort-pill" data-sort="popular">
                <i class="bi bi-eye"></i> Popular
            </span>
        </div> --}}
    </div>
    @endif

    {{-- Grid --}}
    <div class="fav-grid" id="favGrid">

        @forelse($posts as $i => $post)
        <div class="dh-card au" style="animation-delay:{{ $i * 0.04 }}s" id="card-{{ $post->id }}">

            {{-- Un-favourite button --}}
            <button class="unfav-btn likeBtn" data-id="{{ $post->id }}" title="Remove from favourites">
                <i class="fas fa-heart"></i>
            </button>

            {{-- Media --}}
            <div class="dh-card-media">
                <a href="{{ $post->url }}">
                    @php $thumb = $post->getFirstMediaUrl('posts'); @endphp
                    @if($thumb)
                        <img src="{{ $thumb }}" alt="{{ $post->title }}" loading="lazy">
                    @else
                        <div class="ratio" style="background:var(--surf-2);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-image" style="font-size:2rem;color:var(--surf-3);"></i>
                        </div>
                    @endif
                </a>
            </div>

            {{-- Body --}}
            <div class="dh-card-body">
                <div class="dh-badges">
                    @if($post->category)
                        <span class="dh-b dh-b-cat">{{ $post->category->name }}</span>
                    @endif
                    @if($post->locality)
                        <span class="dh-b dh-b-loc">{{ $post->locality->name }}</span>
                    @endif
                </div>

                <a href="{{ $post->url }}" class="dh-card-title">
                    {{ $post->title }}
                </a>

                <div class="dh-card-meta">
                    <span class="dh-meta-box">
                        <i class="bi bi-eye"></i>
                        {{ number_format($post->viewsData->count()) }}
                    </span>
                    <span class="dh-meta-box">
                        <i class="fas fa-heart" style="color:var(--red);"></i>
                        <span id="lc-{{ $post->id }}">{{ number_format($post->likesData->count()) }}</span>
                    </span>
                    <span class="dh-meta-time">{{ $post->created_at->diffForHumans() }}</span>
                </div>

                <div class="dh-card-actions">
                    <a href="{{ $post->url }}" class="dh-btn dh-btn-primary">
                        <i class="bi bi-arrow-right-circle"></i> View Deal
                    </a>
                    @if($post->whatsapp_number)
                        <a href="{{ $post->whatsapp_link }}" target="_blank" class="dh-btn dh-btn-wa">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-heart-broken"></i></div>
            <h2 class="empty-title">No favourites yet</h2>
            <p class="empty-sub">
                Deals you like will appear here.<br>
                Tap the ❤️ on any deal to save it.
            </p>
            <a href="{{ route('posts.listing') }}" class="browse-btn">
                <i class="bi bi-compass"></i> Browse Deals
            </a>
        </div>
        @endforelse

        {{-- Infinite scroll sentinel --}}
        <div class="dh-loader" id="loading">
            <div class="dh-dots"><span></span><span></span><span></span></div>
        </div>
        <div class="end-msg" id="endMsg">You've seen all your saved deals</div>
        <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">

    </div>

</div>

{{-- Toast container --}}
<div class="toast-wrap" id="toastWrap" style="display:none;"></div>

@include('frontend.partials.footer', ['categories' => $categories ?? collect()])

@include('frontend.frontend-mobile')

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
const CSRF = '{{ csrf_token() }}';
let isLoading = false;

/* ── Toast helper ── */
function showToast(msg, icon = 'fas fa-check-circle') {
    const wrap = document.getElementById('toastWrap');
    wrap.style.display = 'block';
    wrap.innerHTML = `<div class="toast"><i class="${icon}"></i> ${msg}</div>`;
    setTimeout(() => { wrap.style.display = 'none'; wrap.innerHTML = ''; }, 2800);
}

/* ── Unlike / Remove from favourites ── */
$(document).on('click', '.likeBtn', function () {
    const btn = $(this);
    const id  = btn.data('id');
    const card = $('#card-' + id);

    $.post('/posts/' + id + '/toggle-like', { _token: CSRF }, function (res) {
        if (!res.liked) {
            // User un-liked — remove card with animation
            card.addClass('removing');
            setTimeout(() => {
                card.remove();
                // Update toolbar count
                const remaining = $('#favGrid .dh-card').length;
                if (remaining === 0) {
                    // Show empty state
                    $('#favGrid').append(`
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-heart-broken"></i></div>
                            <h2 class="empty-title">No favourites left</h2>
                            <p class="empty-sub">You removed all your saved deals.<br>Browse to find new ones!</p>
                            <a href="{{ route('posts.listing') }}" class="browse-btn">
                                <i class="bi bi-compass"></i> Browse Deals
                            </a>
                        </div>
                    `);
                }
            }, 300);
            showToast('Removed from favourites', 'fas fa-heart-broken');
        } else {
            showToast('Added to favourites', 'fas fa-heart');
        }
        // Update like count
        $('#lc-' + id).text(res.likes);
    });
});

/* ── Sort pills ── */
$(document).on('click', '.sort-pill', function () {
    $('.sort-pill').removeClass('active');
    $(this).addClass('active');
    const sort = $(this).data('sort');

    isLoading = true;
    document.getElementById('loading').style.display = 'block';

    fetch('{{ route("favourites") }}?sort=' + sort, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        if (data.html) {
            // Replace cards only (keep loader/endMsg)
            $('#favGrid .dh-card').remove();
            $(data.html).prependTo('#favGrid');
        }
        document.getElementById('next-page-url').value = data.next_page || '';
        document.getElementById('endMsg').style.display = data.next_page ? 'none' : 'block';
        isLoading = false;
    })
    .catch(() => { isLoading = false; document.getElementById('loading').style.display = 'none'; });
});

/* ── Infinite scroll ── */
window.addEventListener('scroll', function () {
    if (isLoading) return;
    if (document.body.offsetHeight - window.scrollY - window.innerHeight > 300) return;
    const next = document.getElementById('next-page-url').value;
    if (!next) return;

    isLoading = true;
    document.getElementById('loading').style.display = 'block';

    fetch(next, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            if (data.html) $(data.html).insertBefore('#loading');
            document.getElementById('next-page-url').value = data.next_page || '';
            if (!data.next_page) document.getElementById('endMsg').style.display = 'block';
            isLoading = false;
        })
        .catch(() => { isLoading = false; document.getElementById('loading').style.display = 'none'; });
}, { passive: true });
</script>

</body>
</html>