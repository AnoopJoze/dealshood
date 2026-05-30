@php
    $nav = [
        [
            'label' => null,
            'items' => [
                [
                    'label'  => 'Dashboard',
                    'href'   => url('admin/dashboard'),
                    'match'  => 'admin/dashboard',
                    'icon'   => 'fas fa-chart-pie',
                    'color'  => '#6366f1',
                    'bg'     => '#ede9fe',
                ],
            ],
        ],
        [
            'label' => 'Content',
            'items' => [
                [
                    'label'  => 'Posts',
                    'href'   => url('admin/posts'),
                    'match'  => 'admin/posts*',
                    'icon'   => 'fas fa-newspaper',
                    'color'  => '#0ea5e9',
                    'bg'     => '#e0f2fe',
                ],
                [
                    'label'  => 'Categories',
                    'href'   => url('admin/categories'),
                    'match'  => 'admin/categories*',
                    'icon'   => 'fas fa-tags',
                    'color'  => '#f59e0b',
                    'bg'     => '#fef3c7',
                ],
                [
                    'label'  => 'Subcategories',
                    'href'   => url('admin/subcategories'),
                    'match'  => 'admin/subcategories*',
                    'icon'   => 'fas fa-sitemap',
                    'color'  => '#f97316',
                    'bg'     => '#fff7ed',
                ],
                [
                    'label'  => 'Localities',
                    'href'   => url('admin/localities'),
                    'match'  => 'admin/localities*',
                    'icon'   => 'fas fa-map-marker-alt',
                    'color'  => '#10b981',
                    'bg'     => '#d1fae5',
                ],
            ],
        ],
        [
            'label' => 'Settings',
            'items' => [
                [
                    'label'  => 'Users',
                    'href'   => url('admin/users'),
                    'match'  => 'admin/users*',
                    'icon'   => 'fas fa-users',
                    'color'  => '#8b5cf6',
                    'bg'     => '#ede9fe',
                ],
                [
                    'label'  => 'Roles',
                    'href'   => url('admin/roles'),
                    'match'  => 'admin/roles*',
                    'icon'   => 'fas fa-shield-alt',
                    'color'  => '#ec4899',
                    'bg'     => '#fce7f3',
                ],
                [
                    'label'  => 'Permissions',
                    'href'   => url('admin/permissions'),
                    'match'  => 'admin/permissions*',
                    'icon'   => 'fas fa-key',
                    'color'  => '#14b8a6',
                    'bg'     => '#ccfbf1',
                ],
            ],
        ],
        [
            'label' => 'Account',
            'items' => [
                [
                    'label'  => 'My Profile',
                    'href'   => url('admin/profile'),
                    'match'  => 'admin/profile*',
                    'icon'   => 'fas fa-user-circle',
                    'color'  => '#64748b',
                    'bg'     => '#f1f5f9',
                ],
            ],
        ],
    ];
@endphp

<style>
/* ── Sidenav overrides ───────────────────────────────────────── */
#sidenav-main {
    background: #fff;
    border-right: 1px solid #f0f0f0;
    box-shadow: 4px 0 24px rgba(0,0,0,.06);
}
.sidenav-header {
    padding: 20px 16px 12px;
    border-bottom: 1px solid #f4f4f4;
}
.sidenav-header .navbar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}
.sidenav-header .navbar-brand img {
    height: 36px;
    width: auto;
}
.sidenav-header .brand-name {
    font-size: .88rem;
    font-weight: 800;
    color: #0d0d0d;
    letter-spacing: -.02em;
    line-height: 1.1;
}
.sidenav-header .brand-sub {
    font-size: .65rem;
    color: #9ca3af;
    font-weight: 400;
    letter-spacing: .04em;
}

/* ── Nav scroll area ─────────────────────────────────────────── */
.dh-nav-scroll {
    overflow-y: auto;
    overflow-x: hidden;
    height: calc(100% - 160px);
    padding: 8px 12px 16px;
    scrollbar-width: none;
}
.dh-nav-scroll::-webkit-scrollbar { display: none; }

/* ── Section label ───────────────────────────────────────────── */
.dh-nav-section {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #c4c9d4;
    padding: 18px 8px 6px;
    margin: 0;
}

/* ── Nav item ────────────────────────────────────────────────── */
.dh-nav-item { margin-bottom: 2px; }

.dh-nav-link {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 9px 12px;
    border-radius: 10px;
    text-decoration: none;
    color: #64748b;
    font-size: .81rem;
    font-weight: 500;
    transition: background .15s, color .15s, transform .1s;
    position: relative;
    white-space: nowrap;
}
.dh-nav-link:hover {
    background: #f8fafc;
    color: #0f172a;
    transform: translateX(2px);
}
.dh-nav-link.active {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(15,23,42,.22);
}
.dh-nav-link.active .dh-nav-icon {
    background: rgba(255,255,255,.15) !important;
    color: #fff !important;
}
.dh-nav-link.active .dh-nav-arrow { color: rgba(255,255,255,.5); }

/* ── Icon bubble ─────────────────────────────────────────────── */
.dh-nav-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    transition: transform .15s;
}
.dh-nav-link:hover .dh-nav-icon { transform: scale(1.08); }

/* ── Badge ───────────────────────────────────────────────────── */
.dh-nav-badge {
    margin-left: auto;
    font-size: .6rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 100px;
    background: #fee2e2;
    color: #dc2626;
    min-width: 20px;
    text-align: center;
}
.dh-nav-link.active .dh-nav-badge {
    background: rgba(255,255,255,.2);
    color: #fff;
}

/* ── Divider ─────────────────────────────────────────────────── */
.dh-nav-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 10px 8px;
}

/* ── User card at bottom ─────────────────────────────────────── */
.dh-sidenav-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px 16px;
    border-top: 1px solid #f1f5f9;
    background: #fff;
}
.dh-user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 10px;
    border-radius: 10px;
    background: #f8fafc;
    text-decoration: none;
    transition: background .15s;
}
.dh-user-card:hover { background: #f1f5f9; }
.dh-user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-weight: 700;
    font-size: .85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dh-user-name {
    font-size: .78rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.2;
}
.dh-user-role {
    font-size: .65rem;
    color: #94a3b8;
    margin-top: 1px;
}
.dh-user-logout {
    margin-left: auto;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: .78rem;
    transition: background .15s, color .15s;
    text-decoration: none;
    border: none;
    background: transparent;
    cursor: pointer;
}
.dh-user-logout:hover { background: #fee2e2; color: #dc2626; }
</style>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
       id="sidenav-main">

    {{-- ── Header ─────────────────────────────────────────── --}}
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/img/dealshood.png') }}" alt="DealsHood">
            <div>
                <div class="brand-name">DealsHood</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </a>
    </div>

    {{-- ── Nav items ───────────────────────────────────────── --}}
    <div class="dh-nav-scroll">

        @foreach ($nav as $group)
            @if ($group['label'])
                <p class="dh-nav-section">{{ $group['label'] }}</p>
            @else
                <div style="height:8px;"></div>
            @endif

            @foreach ($group['items'] as $item)
                @php
                    $active = Request::is($item['match']);
                @endphp
                <div class="dh-nav-item">
                    <a href="{{ $item['href'] }}"
                       class="dh-nav-link {{ $active ? 'active' : '' }}">
                        <span class="dh-nav-icon"
                              style="background:{{ $active ? 'rgba(255,255,255,.15)' : $item['bg'] }};
                                     color:{{ $active ? '#fff' : $item['color'] }};">
                            <i class="{{ $item['icon'] }}"></i>
                        </span>
                        <span>{{ $item['label'] }}</span>

                        @if (!empty($item['badge']))
                            <span class="dh-nav-badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                </div>
            @endforeach
        @endforeach

    </div>

    {{-- ── User footer card ───────────────────────────────── --}}
    <div class="dh-sidenav-footer">
        @auth
            @php
                $authUser = auth()->user();
                $initial  = strtoupper(substr($authUser->name, 0, 1));
                $role     = $authUser->roles->first()?->name ?? 'User';
            @endphp
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;
                        border-radius:10px;background:#f8fafc;">
                <div class="dh-user-avatar">{{ $initial }}</div>
                <div style="min-width:0;">
                    <div class="dh-user-name text-truncate">{{ $authUser->name }}</div>
                    <div class="dh-user-role">{{ ucfirst($role) }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="dh-user-logout" title="Sign out">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        @endauth
    </div>

</aside>