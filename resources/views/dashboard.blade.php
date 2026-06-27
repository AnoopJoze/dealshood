@extends('layouts.user_type.auth')

@section('content')

@push('css')
<style>
/* ══════════════════════════════════════════════════
   DESIGN TOKENS — match sidenav + navbar
══════════════════════════════════════════════════ */
:root {
    --dk:       #0f172a;   /* same as sidenav text / active bg     */
    --dk2:      #1e293b;   /* sidenav active gradient end          */
    --accent:   #6366f1;   /* indigo accent (sidenav section color)*/
    --surface:  #f8fafc;   /* page background                      */
    --border:   #f1f5f9;   /* same as sidenav dividers             */
    --muted:    #64748b;   /* sidenav inactive text                */
    --muted2:   #94a3b8;   /* sidenav section label                */
    --r:        10px;      /* same corner radius as sidenav links  */
    --sh:       0 2px 16px rgba(15,23,42,.07);
    --sh-hover: 0 6px 28px rgba(15,23,42,.13);
}

/* ── Cards ──────────────────────────────────────── */
.kpi-card, .dash-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh);
    transition: transform .18s, box-shadow .18s;
}
.kpi-card { padding: 1.1rem 1.25rem; height: 100%; }
.kpi-card:hover, .dash-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--sh-hover);
}
.dash-card { height: 100%; }

/* Card header — same label style as sidenav section titles */
.dash-card-header {
    padding: .9rem 1.2rem .7rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.dash-card-title {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted2);    /* exact match with sidenav .dh-nav-section */
    margin: 0;
}
.dash-card-body { padding: 1rem 1.2rem; }

/* ── KPI ────────────────────────────────────────── */
.kpi-val { font-size: 1.65rem; font-weight: 800; line-height: 1; color: var(--dk); }
.kpi-lbl { font-size: .68rem; color: var(--muted2); text-transform: uppercase;
            letter-spacing: .1em; margin-top: 3px; font-weight: 600; }
.kpi-sub { font-size: .74rem; margin-top: 5px; color: var(--muted); }

/* Icon bubble — same size/radius as sidenav .dh-nav-icon */
.kpi-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}

/* ── Greeting banner — dark gradient matches sidenav active ── */
.greeting-banner {
    background: linear-gradient(135deg, var(--dk) 0%, #312e81 100%);
    border-radius: var(--r);
    padding: 1.4rem 1.75rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 28px rgba(15,23,42,.28);
}
.greeting-banner::after {
    content: '';
    position: absolute; right: -50px; top: -50px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(255,255,255,.05); pointer-events: none;
}
.greeting-banner::before {
    content: '';
    position: absolute; right: 60px; bottom: -70px;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(255,255,255,.04); pointer-events: none;
}
.greeting-banner .btn-light {
    background: #fff; color: var(--dk); border: none;
    font-weight: 600; border-radius: 8px;
    font-size: .78rem; padding: .45rem 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
    transition: box-shadow .15s, transform .15s;
}
.greeting-banner .btn-light:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.2); transform: translateY(-1px);
}
.greeting-banner .btn-outline-light {
    border: 1.5px solid rgba(255,255,255,.3); color: #fff;
    font-weight: 600; border-radius: 8px;
    font-size: .78rem; padding: .45rem 1rem;
    transition: background .15s;
}
.greeting-banner .btn-outline-light:hover { background: rgba(255,255,255,.1); }

/* ── Status badges ──────────────────────────────── */
.s-published { background:#d1fae5; color:#059669; }
.s-draft     { background:#f1f5f9; color:#64748b; }
.s-archived  { background:#fef3c7; color:#d97706; }

/* ── Progress bar ───────────────────────────────── */
.bar-row { margin-bottom: .8rem; }
.bar-row .bar-label {
    display: flex; justify-content: space-between;
    font-size: .77rem; margin-bottom: .3rem;
    color: var(--muted);
}
.bar-row .bar-label .bar-name { font-weight: 600; color: var(--dk); }
.bar-row .progress { height: 6px; border-radius: 3px; background: var(--border); }

/* ── Post rows ──────────────────────────────────── */
.post-row {
    display: flex; align-items: center; gap: .8rem;
    padding: .65rem 0; border-bottom: 1px solid var(--border);
}
.post-row:last-child { border-bottom: none; }
.post-thumb {
    width: 42px; height: 42px; border-radius: 9px;
    object-fit: cover; flex-shrink: 0; background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    color: var(--muted2); font-size: .9rem; overflow: hidden;
}
.post-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* ── User rows ──────────────────────────────────── */
.user-row {
    display: flex; align-items: center; gap: .8rem;
    padding: .6rem 0; border-bottom: 1px solid var(--border);
}
.user-row:last-child { border-bottom: none; }

/* Avatar — same style as sidenav / navbar user chip */
.u-av {
    width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff; font-weight: 700; font-size: .82rem;
    display: flex; align-items: center; justify-content: center;
}

/* ── Quick-link item ────────────────────────────── */
.ql-item {
    display: flex; align-items: center; gap: .85rem;
    padding: .55rem .65rem; border-radius: var(--r);
    text-decoration: none; transition: background .14s;
    color: var(--dk);
}
.ql-item:hover { background: var(--surface); }
.ql-icon {
    width: 34px; height: 34px; border-radius: 9px;
    flex-shrink: 0; display: flex;
    align-items: center; justify-content: center;
    font-size: .82rem;
}

/* ── View-all link — accent colour matching sidenav ── */
.dash-view-all {
    font-size: .72rem; font-weight: 600;
    color: var(--accent); text-decoration: none;
    display: inline-flex; align-items: center; gap: 4px;
    transition: opacity .15s;
}
.dash-view-all:hover { opacity: .75; color: var(--accent); }

/* ── Donut legend ───────────────────────────────── */
.donut-legend { list-style: none; padding: 0; margin: 0; width: 100%; }
.donut-legend li {
    display: flex; align-items: center; gap: .55rem;
    font-size: .78rem; margin-bottom: .45rem; color: var(--muted);
}
.donut-legend .dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.donut-legend strong { color: var(--dk); font-weight: 700; margin-left: auto; }

/* ── Action btn inside table ────────────────────── */
.dash-action-btn {
    width: 28px; height: 28px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .72rem; border: 1px solid var(--border);
    background: #fff; text-decoration: none;
    color: var(--muted); transition: background .14s, color .14s;
}
.dash-action-btn:hover { background: var(--surface); color: var(--dk); }
</style>
@endpush

{{-- ══════════════════════════════════════════════
     GREETING
══════════════════════════════════════════════ --}}
<div class="greeting-banner mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3"
         style="position:relative;z-index:1;">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:1.28rem;">
                Welcome back, {{ auth()->user()->name }} 👋
            </h4>
            <p class="mb-0" style="opacity:.7;font-size:.82rem;">
                {{ now()->format('l, d F Y') }} &mdash; Here's what's happening today
            </p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('posts.index') }}" class="btn btn-light">
                <i class="fas fa-plus me-1"></i> New Post
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-light">
                <i class="fas fa-users me-1"></i> Users
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ROW 1 — KPI cards
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($stats['posts_total']) }}</div>
                    <div class="kpi-lbl">Total Posts</div>
                    <div class="kpi-sub">
                        <span class="fw-semibold text-success">+{{ $stats['posts_today'] }}</span> today
                    </div>
                </div>
                <div class="kpi-icon" style="background:#dbeafe;">
                    <i class="fas fa-newspaper" style="color:#1d4ed8;"></i>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge s-published rounded-pill px-2">{{ $stats['posts_published'] }} Published</span>
                <span class="badge s-draft rounded-pill px-2">{{ $stats['posts_draft'] }} Draft</span>
                <span class="badge s-archived rounded-pill px-2">{{ $stats['posts_archived'] }} Archived</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($stats['users_total']) }}</div>
                    <div class="kpi-lbl">Total Users</div>
                    <div class="kpi-sub">
                        <span class="fw-semibold text-success">+{{ $stats['users_today'] }}</span> today
                    </div>
                </div>
                <div class="kpi-icon" style="background:#d1fae5;">
                    <i class="fas fa-users" style="color:#059669;"></i>
                </div>
            </div>
            <div class="mt-3">
                @php $activePct = $stats['users_total'] > 0 ? round(($stats['users_active'] / $stats['users_total']) * 100) : 0; @endphp
                <div class="d-flex justify-content-between mb-1" style="font-size:.72rem;color:var(--muted);">
                    <span>Active users</span>
                    <span class="fw-semibold text-success">{{ $activePct }}%</span>
                </div>
                <div class="progress"><div class="progress-bar bg-success" style="width:{{ $activePct }}%"></div></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($stats['total_views']) }}</div>
                    <div class="kpi-lbl">Total Views</div>
                    <div class="kpi-sub">
                        Avg <span class="fw-semibold" style="color:var(--accent);">
                            {{ $stats['posts_published'] > 0 ? number_format($stats['total_views'] / $stats['posts_published']) : 0 }}
                        </span> per post
                    </div>
                </div>
                <div class="kpi-icon" style="background:#ede9fe;">
                    <i class="fas fa-eye" style="color:#7c3aed;"></i>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge bg-warning-subtle text-warning rounded-pill px-2">
                    <i class="fas fa-star me-1" style="font-size:.58rem;"></i>{{ $stats['posts_featured'] }} Featured
                </span>
                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">
                    <i class="fas fa-clock me-1" style="font-size:.58rem;"></i>{{ $stats['posts_expired'] }} Expired
                </span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ $stats['categories_total'] }}</div>
                    <div class="kpi-lbl">Categories</div>
                    <div class="kpi-sub">
                        <span class="fw-semibold" style="color:var(--accent);">{{ $stats['subcategories_total'] }}</span> subcategories
                    </div>
                </div>
                <div class="kpi-icon" style="background:#fef3c7;">
                    <i class="fas fa-tags" style="color:#d97706;"></i>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge rounded-pill px-2" style="background:#f1f5f9;color:#64748b;">
                    <i class="fas fa-map-marker-alt me-1" style="font-size:.58rem;"></i>{{ $stats['localities_total'] }} Localities
                </span>
                <span class="badge rounded-pill px-2" style="background:#ede9fe;color:#7c3aed;">
                    <i class="fas fa-shield-alt me-1" style="font-size:.58rem;"></i>{{ $stats['roles_total'] }} Roles
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 1.5 — Google Analytics
══════════════════════════════════════════════ --}}
@if ($gaTotals)
{{-- <div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($gaTotals['users']) }}</div>
                    <div class="kpi-lbl">GA Users (28d)</div>
                </div>
                <div class="kpi-icon" style="background:#fee2e2;">
                    <i class="fab fa-google" style="color:#dc2626;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($gaTotals['sessions']) }}</div>
                    <div class="kpi-lbl">Sessions (28d)</div>
                </div>
                <div class="kpi-icon" style="background:#dbeafe;">
                    <i class="fas fa-chart-line" style="color:#1d4ed8;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ number_format($gaTotals['pageviews']) }}</div>
                    <div class="kpi-lbl">Pageviews (28d)</div>
                </div>
                <div class="kpi-icon" style="background:#d1fae5;">
                    <i class="fas fa-eye" style="color:#059669;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val">{{ gmdate('i:s', $gaTotals['avgDuration']) }}</div>
                    <div class="kpi-lbl">Avg Session Duration</div>
                </div>
                <div class="kpi-icon" style="background:#ede9fe;">
                    <i class="fas fa-clock" style="color:#7c3aed;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Google Analytics — Last 7 Days</p>
                <div class="d-flex gap-3">
                    <span style="font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#6366f1;display:inline-block;"></span>Users
                    </span>
                    <span style="font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#059669;display:inline-block;"></span>Pageviews
                    </span>
                </div>
            </div>
            <div class="dash-card-body">
                <div style="position:relative;height:240px;">
                    <canvas id="gaLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endif
{{-- ══════════════════════════════════════════════
     ROW 2 — Line chart + Donut
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Posts Overview — Last 12 Months</p>
                <div class="d-flex gap-3">
                    <span style="font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#6366f1;display:inline-block;"></span>Published
                    </span>
                    <span style="font-size:.72rem;color:var(--muted);display:flex;align-items:center;gap:5px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#cbd5e1;display:inline-block;"></span>Draft
                    </span>
                </div>
            </div>
            <div class="dash-card-body">
                <div style="position:relative;height:240px;">
                    <canvas id="postsLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Post Status Split</p>
            </div>
            <div class="dash-card-body d-flex flex-column align-items-center">
                <div style="position:relative;height:180px;width:180px;">
                    <canvas id="statusDonut"></canvas>
                </div>
                <ul class="donut-legend mt-3">
                    <li>
                        <span class="dot" style="background:#059669;"></span>
                        <span class="flex-fill">Published</span>
                        <strong>{{ $stats['posts_published'] }}</strong>
                    </li>
                    <li>
                        <span class="dot" style="background:#64748b;"></span>
                        <span class="flex-fill">Draft</span>
                        <strong>{{ $stats['posts_draft'] }}</strong>
                    </li>
                    <li>
                        <span class="dot" style="background:#d97706;"></span>
                        <span class="flex-fill">Archived</span>
                        <strong>{{ $stats['posts_archived'] }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 3 — Recent Posts + Recent Users
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-lg-7">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Recent Posts</p>
                <a href="{{ route('posts.index') }}" class="dash-view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body" style="max-height:360px;overflow-y:auto;">
                @forelse ($recentPosts as $post)
                    <div class="post-row">
                        <div class="post-thumb">
                            @php $thumb = $post->getMedia('posts')->first(); @endphp
                            @if ($thumb)
                                <img src="{{ $thumb->getUrl() }}" alt="{{ $post->title }}">
                            @else
                                <i class="fas fa-image"></i>
                            @endif
                        </div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-semibold text-truncate"
                                 style="font-size:.82rem;color:var(--dk);max-width:240px;">
                                {{ $post->title }}
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap"
                                 style="font-size:.7rem;color:var(--muted);">
                                @if ($post->category)
                                    <span class="badge rounded-pill px-2"
                                          style="background:#ede9fe;color:#7c3aed;font-size:.62rem;">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span><i class="fas fa-eye me-1" style="font-size:.58rem;"></i>{{ number_format($post->views ?? 0) }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <span class="badge s-{{ $post->status }} rounded-pill px-2 flex-shrink-0"
                              style="font-size:.62rem;">
                            {{ ucfirst($post->status) }}
                        </span>
                        <a href="{{ route('posts.show', $post->id) }}" class="dash-action-btn">
                            <i class="fas fa-eye" style="color:#6366f1;"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-center py-4 mb-0" style="font-size:.82rem;color:var(--muted);">
                        No posts yet.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Recent Users</p>
                <a href="{{ route('users.index') }}" class="dash-view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body">
                @forelse ($recentUsers as $user)
                    <div class="user-row">
                        <div class="u-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:.82rem;color:var(--dk);">
                                {{ $user->name }}
                            </div>
                            <div class="text-truncate" style="font-size:.7rem;color:var(--muted);">
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge rounded-pill px-2"
                                  style="font-size:.62rem;
                                         background:{{ $user->status === 'Active' ? '#d1fae5' : '#fef2f2' }};
                                         color:{{ $user->status === 'Active' ? '#059669' : '#dc2626' }};">
                                {{ $user->status ?? 'Active' }}
                            </span>
                            <div style="font-size:.68rem;color:var(--muted2);margin-top:2px;">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-4 mb-0" style="font-size:.82rem;color:var(--muted);">No users yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 4 — Top Categories + User Registrations + Top Localities
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Top Categories</p>
                <a href="{{ route('categories.index') }}" class="dash-view-all">
                    Manage <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body">
                @php $maxPosts = $topCategories->max('posts_count') ?: 1; @endphp
                @forelse ($topCategories as $cat)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span class="bar-name">{{ $cat->name }}</span>
                            <span>{{ $cat->posts_count }} posts</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar"
                                 style="width:{{ round(($cat->posts_count / $maxPosts) * 100) }}%;
                                        background:linear-gradient(135deg,var(--accent),#818cf8);">
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="font-size:.82rem;color:var(--muted);" class="mb-0">No categories yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">User Registrations — 12 Months</p>
            </div>
            <div class="dash-card-body">
                <div style="position:relative;height:220px;">
                    <canvas id="usersBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Top Localities</p>
                <a href="{{ route('localities.index') }}" class="dash-view-all">
                    Manage <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="dash-card-body">
                @php $maxLoc = $topLocalities->max('posts_count') ?: 1; @endphp
                @forelse ($topLocalities as $loc)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span class="bar-name">{{ $loc->name }}</span>
                            <span>{{ $loc->posts_count }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar"
                                 style="width:{{ round(($loc->posts_count / $maxLoc) * 100) }}%;
                                        background:linear-gradient(135deg,var(--dk),var(--dk2));">
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="font-size:.82rem;color:var(--muted);" class="mb-0">No locality data.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 5 — Category bar chart + Quick links
══════════════════════════════════════════════ --}}
<div class="row g-3">

    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Published Posts by Category</p>
            </div>
            <div class="dash-card-body">
                <div style="position:relative;height:240px;">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Quick Links</p>
            </div>
            <div class="dash-card-body d-flex flex-column gap-1">
                @php
                    $links = [
                        ['route'=>'posts.index',         'icon'=>'fa-newspaper',      'label'=>'All Posts',          'bg'=>'#dbeafe','ic'=>'#1d4ed8'],
                        ['route'=>'users.index',         'icon'=>'fa-users',          'label'=>'All Users',          'bg'=>'#d1fae5','ic'=>'#059669'],
                        ['route'=>'categories.index',    'icon'=>'fa-tags',           'label'=>'Categories',         'bg'=>'#fef3c7','ic'=>'#d97706'],
                        ['route'=>'subcategories.index', 'icon'=>'fa-sitemap',        'label'=>'Subcategories',      'bg'=>'#ede9fe','ic'=>'#7c3aed'],
                        ['route'=>'localities.index',    'icon'=>'fa-map-marker-alt', 'label'=>'Localities',         'bg'=>'#fce7f3','ic'=>'#db2777'],
                        ['route'=>'roles.index',         'icon'=>'fa-shield-alt',     'label'=>'Roles & Permissions','bg'=>'#f0fdf4','ic'=>'#16a34a'],
                    ];
                @endphp
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}" class="ql-item">
                        <div class="ql-icon"
                             style="background:{{ $link['bg'] }};color:{{ $link['ic'] }};">
                            <i class="fas {{ $link['icon'] }}"></i>
                        </div>
                        <span class="flex-fill fw-semibold" style="font-size:.81rem;">{{ $link['label'] }}</span>
                        <i class="fas fa-chevron-right" style="font-size:.6rem;color:var(--muted2);"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection

@push('dashboard')
<script>
window.addEventListener('load', function () {

    // ── Shared options ──────────────────────────────────
    const gridColor = '#f1f5f9';
    const tickColor = '#94a3b8';
    const tickFont  = { size: 11, family: '-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif' };
    const baseScale = {
        y: { beginAtZero:true, grid:{ drawBorder:false, color:gridColor },
             ticks:{ color:tickColor, font:tickFont } },
        x: { grid:{ display:false, drawBorder:false },
             ticks:{ color:tickColor, font:tickFont } },
    };

    // ── Posts line chart ────────────────────────────────
    (function(){
        var ctx = document.getElementById('postsLineChart').getContext('2d');
        var g1  = ctx.createLinearGradient(0,0,0,260);
        g1.addColorStop(0,'rgba(99,102,241,.28)'); g1.addColorStop(1,'rgba(99,102,241,.0)');
        var g2  = ctx.createLinearGradient(0,0,0,260);
        g2.addColorStop(0,'rgba(203,213,225,.45)'); g2.addColorStop(1,'rgba(203,213,225,.0)');

        new Chart(ctx, {
            type:'line',
            data:{
                labels: @json($postsByMonth->pluck('label')),
                datasets:[
                    { label:'Published', data:@json($postsByMonth->pluck('published')),
                      borderColor:'#6366f1', borderWidth:2.5, backgroundColor:g1,
                      fill:true, tension:.4, pointRadius:3, pointBackgroundColor:'#6366f1' },
                    { label:'Draft', data:@json($postsByMonth->pluck('draft')),
                      borderColor:'#cbd5e1', borderWidth:2, backgroundColor:g2,
                      fill:true, tension:.4, pointRadius:3, pointBackgroundColor:'#cbd5e1' },
                ],
            },
            options:{ responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false} },
                interaction:{ intersect:false, mode:'index' },
                scales:baseScale },
        });
    })();

    // ── Status donut ────────────────────────────────────
    (function(){
        var ctx = document.getElementById('statusDonut').getContext('2d');
        new Chart(ctx, {
            type:'doughnut',
            data:{
                labels:['Published','Draft','Archived'],
                datasets:[{ data:[{{ $stats['posts_published'] }},{{ $stats['posts_draft'] }},{{ $stats['posts_archived'] }}],
                            backgroundColor:['#059669','#64748b','#d97706'],
                            borderWidth:0, hoverOffset:6 }],
            },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'74%',
                plugins:{ legend:{display:false},
                    tooltip:{ callbacks:{ label:function(c){
                        var t=c.dataset.data.reduce((a,b)=>a+b,0);
                        return ' '+c.label+': '+c.raw+' ('+Math.round((c.raw/t)*100)+'%)';
                    }}}}},
        });
    })();

    // ── User registrations bar ──────────────────────────
    (function(){
        var ctx = document.getElementById('usersBarChart').getContext('2d');
        new Chart(ctx, {
            type:'bar',
            data:{
                labels:@json($usersByMonth->pluck('label')),
                datasets:[{ label:'Registrations', data:@json($usersByMonth->pluck('count')),
                            backgroundColor:'rgba(99,102,241,.75)',
                            borderRadius:5, borderSkipped:false, maxBarThickness:16 }],
            },
            options:{ responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false} },
                scales:{ ...baseScale,
                    x:{ grid:{display:false,drawBorder:false},
                        ticks:{ color:tickColor, font:{...tickFont,size:10},
                                maxRotation:45, minRotation:45 } } } },
        });
    })();

    // ── Category bar chart ──────────────────────────────
    (function(){
        var ctx = document.getElementById('categoryBarChart').getContext('2d');
        var cats = @json($postsByCategory->pluck('name'));
        // Generate a range of indigo→purple shades based on count
        var colors = cats.map(function(_,i){
            var hue = 240 + (i * 15) % 60;
            return 'hsl('+hue+',70%,'+(52 + (i%3)*6)+'%)';
        });

        new Chart(ctx, {
            type:'bar',
            data:{
                labels:cats,
                datasets:[{ label:'Published Posts',
                            data:@json($postsByCategory->pluck('posts_count')),
                            backgroundColor:colors, borderRadius:6,
                            borderSkipped:false, maxBarThickness:34 }],
            },
            options:{ responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false} },
                scales:baseScale },
        });
    })();

    // ── Google Analytics line chart ─────────────────────
    @if ($gaTotals)
    (function(){
        var el = document.getElementById('gaLineChart');
        if (!el) return;
        var ctx = el.getContext('2d');
        var g1  = ctx.createLinearGradient(0,0,0,240);
        g1.addColorStop(0,'rgba(99,102,241,.25)'); g1.addColorStop(1,'rgba(99,102,241,.0)');
        var g2  = ctx.createLinearGradient(0,0,0,240);
        g2.addColorStop(0,'rgba(5,150,105,.2)'); g2.addColorStop(1,'rgba(5,150,105,.0)');

        new Chart(ctx, {
            type:'line',
            data:{
                labels: @json($gaChartData->pluck('date')),
                datasets:[
                    { label:'Users', data:@json($gaChartData->pluck('users')),
                      borderColor:'#6366f1', borderWidth:2.5, backgroundColor:g1,
                      fill:true, tension:.4, pointRadius:3, pointBackgroundColor:'#6366f1' },
                    { label:'Pageviews', data:@json($gaChartData->pluck('pageviews')),
                      borderColor:'#059669', borderWidth:2, backgroundColor:g2,
                      fill:true, tension:.4, pointRadius:3, pointBackgroundColor:'#059669' },
                ],
            },
            options:{ responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{display:false} },
                interaction:{ intersect:false, mode:'index' },
                scales:baseScale },
        });
    })();
    @endif
});
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1905M4BG0P"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1905M4BG0P');
</script>
@endpush
