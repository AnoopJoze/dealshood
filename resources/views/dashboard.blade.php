@extends('layouts.user_type.auth')

@section('content')

@push('css')
<style>
    /* ── Design tokens ──────────────────────────────────── */
    :root {
        --r: .75rem;
        --sh: 0 2px 12px rgba(0,0,0,.08);
    }

    /* ── Stat cards ─────────────────────────────────────── */
    .kpi-card {
        border-radius: var(--r); padding: 1.1rem 1.25rem;
        border: 1px solid #f0f0f0; background: #fff;
        box-shadow: var(--sh); transition: transform .15s, box-shadow .15s;
        height: 100%;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.12); }
    .kpi-icon {
        width: 46px; height: 46px; border-radius: .65rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .kpi-val  { font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .kpi-lbl  { font-size: .72rem; color: #9ca3af; text-transform: uppercase;
                letter-spacing: .05em; margin-top: 3px; }
    .kpi-sub  { font-size: .75rem; margin-top: 4px; }

    /* ── Section card ────────────────────────────────────── */
    .dash-card {
        background: #fff; border: 1px solid #f0f0f0;
        border-radius: var(--r); box-shadow: var(--sh);
        height: 100%;
    }
    .dash-card .dash-card-header {
        padding: 1rem 1.25rem .5rem;
        border-bottom: 1px solid #f5f5f5;
        display: flex; align-items: center; justify-content: space-between;
    }
    .dash-card-title {
        font-size: .8rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; color: #374151; margin: 0;
    }
    .dash-card-body { padding: 1rem 1.25rem; }

    /* ── Post row ────────────────────────────────────────── */
    .post-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .6rem 0; border-bottom: 1px solid #f5f5f5;
    }
    .post-row:last-child { border-bottom: none; }
    .post-thumb {
        width: 44px; height: 44px; border-radius: .5rem;
        object-fit: cover; flex-shrink: 0; background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        color: #d1d5db; font-size: 1rem;
    }
    .post-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: .5rem; }

    /* ── User row ────────────────────────────────────────── */
    .user-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .55rem 0; border-bottom: 1px solid #f5f5f5;
    }
    .user-row:last-child { border-bottom: none; }
    .u-av {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(195deg,#42424a,#191919);
        color: #fff; font-weight: 700; font-size: .85rem;
        display: flex; align-items: center; justify-content: center;
    }

    /* ── Progress bar ────────────────────────────────────── */
    .bar-row { margin-bottom: .75rem; }
    .bar-row .bar-label {
        display: flex; justify-content: space-between;
        font-size: .78rem; margin-bottom: .25rem;
    }
    .bar-row .progress { height: 6px; border-radius: 3px; }

    /* ── Status badge ────────────────────────────────────── */
    .s-published { background:#d1fae5;color:#059669; }
    .s-draft      { background:#f3f4f6;color:#6b7280; }
    .s-archived   { background:#fef3c7;color:#d97706; }

    /* ── Chart wrapper ───────────────────────────────────── */
    .chart-wrap { position: relative; }

    /* ── Donut legend ────────────────────────────────────── */
    .donut-legend li {
        display: flex; align-items: center; gap: .5rem;
        font-size: .78rem; margin-bottom: .4rem;
    }
    .donut-legend .dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    }

    /* ── Greeting banner ─────────────────────────────────── */
    .greeting-banner {
        background: linear-gradient(135deg,#1a56db 0%,#6d28d9 100%);
        border-radius: var(--r); padding: 1.5rem 1.75rem;
        color: #fff; position: relative; overflow: hidden;
        box-shadow: 0 4px 20px rgba(26,86,219,.35);
    }
    .greeting-banner::after {
        content: '';
        position: absolute; right: -40px; top: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,.06);
        pointer-events: none;
    }
    .greeting-banner::before {
        content: '';
        position: absolute; right: 40px; bottom: -60px;
        width: 240px; height: 240px; border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
</style>
@endpush

{{-- ══════════════════════════════════════════════
     GREETING BANNER
══════════════════════════════════════════════ --}}
<div class="greeting-banner mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3"
         style="position:relative;z-index:1;">
        <div>
            <h4 class="fw-bold mb-1" style="font-size:1.35rem;">
                Welcome back, {{ auth()->user()->name }} 👋
            </h4>
            <p class="mb-0" style="opacity:.8;font-size:.85rem;">
                {{ now()->format('l, d F Y') }} &mdash; Here's what's happening today
            </p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-light fw-semibold px-3">
                <i class="fas fa-plus me-1"></i> New Post
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light fw-semibold px-3">
                <i class="fas fa-users me-1"></i> Users
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ROW 1 — Primary KPI Cards
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Total Posts --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val text-dark">{{ number_format($stats['posts_total']) }}</div>
                    <div class="kpi-lbl">Total Posts</div>
                    <div class="kpi-sub text-muted">
                        <span class="text-success fw-semibold">
                            +{{ $stats['posts_today'] }}
                        </span> today
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

    {{-- Total Users --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val text-dark">{{ number_format($stats['users_total']) }}</div>
                    <div class="kpi-lbl">Total Users</div>
                    <div class="kpi-sub text-muted">
                        <span class="text-success fw-semibold">+{{ $stats['users_today'] }}</span> today
                    </div>
                </div>
                <div class="kpi-icon" style="background:#d1fae5;">
                    <i class="fas fa-users" style="color:#059669;"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between text-xs text-muted mb-1">
                    <span>Active users</span>
                    <span class="fw-semibold text-success">
                        {{ $stats['users_total'] > 0 ? round(($stats['users_active'] / $stats['users_total']) * 100) : 0 }}%
                    </span>
                </div>
                <div class="progress" style="height:5px;">
                    <div class="progress-bar bg-success" style="width:{{ $stats['users_total'] > 0 ? round(($stats['users_active'] / $stats['users_total']) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Views --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val text-dark">{{ number_format($stats['total_views']) }}</div>
                    <div class="kpi-lbl">Total Post Views</div>
                    <div class="kpi-sub text-muted">
                        Avg
                        <span class="fw-semibold text-primary">
                            {{ $stats['posts_published'] > 0 ? number_format($stats['total_views'] / $stats['posts_published']) : 0 }}
                        </span>
                        per post
                    </div>
                </div>
                <div class="kpi-icon" style="background:#ede9fe;">
                    <i class="fas fa-eye" style="color:#7c3aed;"></i>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge bg-warning-subtle text-warning rounded-pill px-2">
                    <i class="fas fa-star me-1" style="font-size:.6rem;"></i>{{ $stats['posts_featured'] }} Featured
                </span>
                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">
                    <i class="fas fa-clock me-1" style="font-size:.6rem;"></i>{{ $stats['posts_expired'] }} Expired
                </span>
            </div>
        </div>
    </div>

    {{-- Taxonomy summary --}}
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="kpi-val text-dark">{{ $stats['categories_total'] }}</div>
                    <div class="kpi-lbl">Categories</div>
                    <div class="kpi-sub text-muted">
                        <span class="fw-semibold text-primary">{{ $stats['subcategories_total'] }}</span> subcategories
                    </div>
                </div>
                <div class="kpi-icon" style="background:#fef3c7;">
                    <i class="fas fa-tags" style="color:#d97706;"></i>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge bg-light text-secondary border rounded-pill px-2">
                    <i class="fas fa-map-marker-alt me-1" style="font-size:.6rem;"></i>{{ $stats['localities_total'] }} Localities
                </span>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2">
                    <i class="fas fa-shield-alt me-1" style="font-size:.6rem;"></i>{{ $stats['roles_total'] }} Roles
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 2 — Charts
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Posts over time — line chart --}}
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Posts Overview — Last 12 Months</p>
                <div class="d-flex gap-2">
                    <span class="text-xs d-flex align-items-center gap-1">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#1a56db;"></span>
                        Published
                    </span>
                    <span class="text-xs d-flex align-items-center gap-1">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#d1d5db;"></span>
                        Draft
                    </span>
                </div>
            </div>
            <div class="dash-card-body">
                <div class="chart-wrap" style="height:240px;">
                    <canvas id="postsLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Post status — donut chart --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Post Status</p>
            </div>
            <div class="dash-card-body d-flex flex-column align-items-center">
                <div class="chart-wrap" style="height:180px;width:180px;">
                    <canvas id="statusDonut"></canvas>
                </div>
                <ul class="donut-legend mt-3 ps-0 w-100">
                    <li>
                        <span class="dot" style="background:#059669;"></span>
                        <span class="flex-fill">Published</span>
                        <strong>{{ $stats['posts_published'] }}</strong>
                    </li>
                    <li>
                        <span class="dot" style="background:#6b7280;"></span>
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

    {{-- Recent Posts --}}
    <div class="col-lg-7">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Recent Posts</p>
                <a href="{{ route('posts.index') }}" class="text-xs text-primary fw-semibold">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="dash-card-body" style="max-height:380px;overflow-y:auto;">

                @forelse ($recentPosts as $post)
                    <div class="post-row">

                        {{-- Thumb --}}
                        <div class="post-thumb">
                            @php $thumb = $post->getMedia('posts')->first(); @endphp
                            @if ($thumb)
                                <img src="{{ $thumb->getUrl() }}" alt="{{ $post->title }}">
                            @else
                                <i class="fas fa-image"></i>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-semibold text-sm text-dark text-truncate" style="max-width:240px;">
                                {{ $post->title }}
                            </div>
                            <div class="text-xs text-muted mt-1 d-flex align-items-center gap-2 flex-wrap">
                                @if ($post->category)
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span>
                                    <i class="fas fa-eye me-1" style="font-size:.6rem;"></i>
                                    {{ number_format($post->views ?? 0) }}
                                </span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="badge s-{{ $post->status }} rounded-pill px-2 flex-shrink-0">
                            {{ ucfirst($post->status) }}
                        </span>

                        {{-- Link --}}
                        <a href="{{ route('posts.show', $post->id) }}"
                           class="btn btn-sm btn-light border flex-shrink-0"
                           title="View">
                            <i class="fas fa-eye text-info" style="font-size:.7rem;"></i>
                        </a>

                    </div>
                @empty
                    <p class="text-muted text-sm text-center py-4 mb-0">No posts yet.</p>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="col-lg-5">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Recent Users</p>
                <a href="{{ route('users.index') }}" class="text-xs text-primary fw-semibold">
                    View all <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="dash-card-body">

                @forelse ($recentUsers as $user)
                    <div class="user-row">
                        <div class="u-av">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-semibold text-sm text-truncate">{{ $user->name }}</div>
                            <div class="text-xs text-muted text-truncate">{{ $user->email }}</div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge rounded-pill px-2
                                {{ $user->status === 'Active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $user->status ?? 'Active' }}
                            </span>
                            <div class="text-xs text-muted mt-1">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-sm text-center py-4 mb-0">No users yet.</p>
                @endforelse

            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 4 — Top Categories + User registrations + Top Localities
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Top categories by post count --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Top Categories</p>
                <a href="{{ route('categories.index') }}" class="text-xs text-primary fw-semibold">
                    Manage <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="dash-card-body">
                @php $maxPosts = $topCategories->max('posts_count') ?: 1; @endphp
                @forelse ($topCategories as $cat)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span class="fw-medium text-dark">{{ $cat->name }}</span>
                            <span class="text-muted">{{ $cat->posts_count }} posts</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-primary"
                                 style="width:{{ round(($cat->posts_count / $maxPosts) * 100) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-sm mb-0">No categories yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- User registrations — bar chart --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">User Registrations — Last 12 Months</p>
            </div>
            <div class="dash-card-body">
                <div class="chart-wrap" style="height:220px;">
                    <canvas id="usersBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top localities --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Top Localities</p>
                <a href="{{ route('localities.index') }}" class="text-xs text-primary fw-semibold">
                    Manage <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="dash-card-body">
                @php $maxLoc = $topLocalities->max('posts_count') ?: 1; @endphp
                @forelse ($topLocalities as $loc)
                    <div class="bar-row">
                        <div class="bar-label">
                            <span class="fw-medium text-dark d-flex align-items-center gap-1">
                                <span class="badge rounded-pill px-2 py-1 text-xs"
                                      style="background:#f3f4f6;color:#6b7280;">
                                    {{ ucfirst($loc->type ?? '') }}
                                </span>
                                {{ $loc->name }}
                            </span>
                            <span class="text-muted">{{ $loc->posts_count }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar"
                                 style="width:{{ round(($loc->posts_count / $maxLoc) * 100) }}%;
                                        background:linear-gradient(195deg,#42424a,#191919);">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-sm mb-0">No locality data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ROW 5 — Posts by Category bar chart + Quick Links
══════════════════════════════════════════════ --}}
<div class="row g-3">

    {{-- Posts by category bar chart --}}
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Published Posts by Category</p>
            </div>
            <div class="dash-card-body">
                <div class="chart-wrap" style="height:240px;">
                    <canvas id="categoryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick links --}}
    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <p class="dash-card-title">Quick Links</p>
            </div>
            <div class="dash-card-body d-flex flex-column gap-2">

                @php
                    $links = [
                        ['route' => 'posts.index',         'icon' => 'fas fa-newspaper',       'label' => 'All Posts',        'color' => '#dbeafe', 'ic' => '#1d4ed8'],
                        ['route' => 'users.index',         'icon' => 'fas fa-users',           'label' => 'All Users',        'color' => '#d1fae5', 'ic' => '#059669'],
                        ['route' => 'categories.index',    'icon' => 'fas fa-tags',            'label' => 'Categories',       'color' => '#fef3c7', 'ic' => '#d97706'],
                        ['route' => 'subcategories.index', 'icon' => 'fas fa-sitemap',         'label' => 'Subcategories',    'color' => '#ede9fe', 'ic' => '#7c3aed'],
                        ['route' => 'localities.index',    'icon' => 'fas fa-map-marker-alt',  'label' => 'Localities',       'color' => '#fce7f3', 'ic' => '#db2777'],
                        ['route' => 'roles.index',         'icon' => 'fas fa-shield-alt',      'label' => 'Roles & Permissions','color' => '#f0fdf4','ic' => '#16a34a'],
                    ];
                @endphp

                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none"
                       style="transition:background .15s;"
                       onmouseover="this.style.background='#f9fafb'"
                       onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;border-radius:.5rem;flex-shrink:0;
                                    background:{{ $link['color'] }};
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="{{ $link['icon'] }}" style="color:{{ $link['ic'] }};font-size:.85rem;"></i>
                        </div>
                        <span class="fw-semibold text-sm text-dark flex-fill">{{ $link['label'] }}</span>
                        <i class="fas fa-chevron-right text-muted" style="font-size:.65rem;"></i>
                    </a>
                @endforeach

            </div>
        </div>
    </div>

</div>

@endsection

@push('dashboard')
<script>
// ── Shared gradient helper ────────────────────────────────────────────────────
function gradient(ctx, color1, color2) {
    var g = ctx.createLinearGradient(0, 0, 0, 300);
    g.addColorStop(0, color1);
    g.addColorStop(1, color2);
    return g;
}

window.addEventListener('load', function () {

    /*
    |--------------------------------------------------------------------------
    | Posts Line Chart (last 12 months)
    |--------------------------------------------------------------------------
    */
    var ctx1 = document.getElementById('postsLineChart').getContext('2d');
    var g1   = gradient(ctx1, 'rgba(26,86,219,.35)', 'rgba(26,86,219,.0)');
    var g2   = gradient(ctx1, 'rgba(209,213,219,.5)', 'rgba(209,213,219,.0)');

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($postsByMonth->pluck('label')),
            datasets: [
                {
                    label: 'Published',
                    data : @json($postsByMonth->pluck('published')),
                    borderColor: '#1a56db', borderWidth: 2.5,
                    backgroundColor: g1, fill: true,
                    tension: 0.4, pointRadius: 3,
                    pointBackgroundColor: '#1a56db',
                },
                {
                    label: 'Draft',
                    data : @json($postsByMonth->pluck('draft')),
                    borderColor: '#d1d5db', borderWidth: 2,
                    backgroundColor: g2, fill: true,
                    tension: 0.4, pointRadius: 3,
                    pointBackgroundColor: '#d1d5db',
                },
            ],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            interaction: { intersect: false, mode: 'index' },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false, color: '#f3f4f6' },
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                },
            },
        },
    });

    /*
    |--------------------------------------------------------------------------
    | Status Donut Chart
    |--------------------------------------------------------------------------
    */
    var ctx2 = document.getElementById('statusDonut').getContext('2d');

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Published', 'Draft', 'Archived'],
            datasets: [{
                data: [
                    {{ $stats['posts_published'] }},
                    {{ $stats['posts_draft'] }},
                    {{ $stats['posts_archived'] }},
                ],
                backgroundColor: ['#059669', '#6b7280', '#d97706'],
                borderWidth: 0,
                hoverOffset: 6,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            var total = c.dataset.data.reduce((a, b) => a + b, 0);
                            var pct   = total > 0 ? Math.round((c.raw / total) * 100) : 0;
                            return ' ' + c.label + ': ' + c.raw + ' (' + pct + '%)';
                        }
                    }
                }
            },
        },
    });

    /*
    |--------------------------------------------------------------------------
    | Users Bar Chart (registrations per month)
    |--------------------------------------------------------------------------
    */
    var ctx3 = document.getElementById('usersBarChart').getContext('2d');

    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: @json($usersByMonth->pluck('label')),
            datasets: [{
                label: 'Registrations',
                data : @json($usersByMonth->pluck('count')),
                backgroundColor: 'rgba(5,150,105,.75)',
                borderRadius: 5,
                borderSkipped: false,
                maxBarThickness: 16,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false, color: '#f3f4f6' },
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        color: '#9ca3af', font: { size: 10 },
                        maxRotation: 45, minRotation: 45,
                    },
                },
            },
        },
    });

    /*
    |--------------------------------------------------------------------------
    | Category Bar Chart (posts per category)
    |--------------------------------------------------------------------------
    */
    var ctx4 = document.getElementById('categoryBarChart').getContext('2d');
    var catColors = [
        '#1a56db','#059669','#d97706','#7c3aed',
        '#db2777','#0891b2','#65a30d','#dc2626'
    ];

    new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: @json($postsByCategory->pluck('name')),
            datasets: [{
                label: 'Published Posts',
                data : @json($postsByCategory->pluck('posts_count')),
                backgroundColor: catColors,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 32,
            }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false, color: '#f3f4f6' },
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                },
            },
        },
    });

});
</script>
@endpush