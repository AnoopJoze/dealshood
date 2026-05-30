@php
    // Build a clean breadcrumb from the URL segments
    $segments   = collect(Request::segments())->filter(fn($s) => $s !== 'admin');
    $pageTitle  = $segments->last()
        ? ucwords(str_replace(['-','_'], ' ', $segments->last()))
        : 'Dashboard';

    $breadcrumbs = [];
    $path = 'admin';
    foreach ($segments as $seg) {
        $path .= '/' . $seg;
        $breadcrumbs[] = [
            'label' => ucwords(str_replace(['-','_'], ' ', $seg)),
            'url'   => url($path),
        ];
    }
@endphp

<style>
/* ── Top navbar ─────────────────────────────────────────────── */
.dh-navbar {
    position: sticky;
    top: 0;
    z-index: 999;
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(0,0,0,.07);
    padding: 0 28px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

/* ── Left: breadcrumb ───────────────────────────────────────── */
.dh-breadcrumb {
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}
.dh-breadcrumb-trail {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .68rem;
    color: #94a3b8;
    flex-wrap: nowrap;
    white-space: nowrap;
    overflow: hidden;
}
.dh-breadcrumb-trail a {
    color: #94a3b8;
    text-decoration: none;
    transition: color .15s;
}
.dh-breadcrumb-trail a:hover { color: #0f172a; }
.dh-breadcrumb-trail .sep { opacity: .4; font-size: .6rem; }
.dh-breadcrumb-trail .current { color: #0f172a; font-weight: 600; }
.dh-page-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -.02em;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── Right: actions ─────────────────────────────────────────── */
.dh-navbar-right {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

/* Search */
.dh-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.dh-search-input {
    width: 0;
    padding: 0;
    border: none;
    outline: none;
    background: transparent;
    font-size: .83rem;
    color: #0f172a;
    transition: width .3s ease, padding .3s ease;
    border-radius: 8px;
}
.dh-search-wrap.open .dh-search-input {
    width: 200px;
    padding: 7px 12px 7px 34px;
    background: #f1f5f9;
}
.dh-search-icon-btn {
    position: absolute;
    left: 0;
    width: 36px;
    height: 36px;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    border-radius: 8px;
    font-size: .8rem;
    transition: background .15s, color .15s;
    z-index: 1;
}
.dh-search-icon-btn:hover { background: #f1f5f9; color: #0f172a; }
.dh-search-wrap.open .dh-search-icon-btn { background: transparent; }

/* Icon button */
.dh-icon-btn {
    position: relative;
    width: 36px;
    height: 36px;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: .82rem;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.dh-icon-btn:hover { background: #f1f5f9; color: #0f172a; }
.dh-icon-btn .dh-badge {
    position: absolute;
    top: 4px; right: 4px;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #ef4444;
    border: 2px solid #fff;
}

/* Divider */
.dh-nav-sep {
    width: 1px;
    height: 22px;
    background: #e2e8f0;
    margin: 0 4px;
}

/* User chip */
.dh-user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 10px 5px 5px;
    border-radius: 100px;
    border: none;
    background: transparent;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s;
}
.dh-user-chip:hover { background: #f1f5f9; }
.dh-user-chip .avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    font-weight: 700;
    font-size: .78rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.dh-user-chip .uname {
    font-size: .78rem;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
}
.dh-user-chip .urole {
    font-size: .65rem;
    color: #94a3b8;
    white-space: nowrap;
}

/* Notification dropdown */
.dh-notif-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 320px;
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: 14px;
    box-shadow: 0 12px 40px rgba(0,0,0,.12);
    display: none;
    z-index: 9999;
    overflow: hidden;
}
.dh-notif-dropdown.open { display: block; animation: fadeSlideDown .2s both; }
@keyframes fadeSlideDown {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}
.dh-notif-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px 10px;
    border-bottom: 1px solid #f1f5f9;
}
.dh-notif-head h6 {
    font-size: .82rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.dh-notif-mark {
    font-size: .7rem;
    color: #6366f1;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 500;
    padding: 0;
}
.dh-notif-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    text-decoration: none;
    transition: background .12s;
    border-bottom: 1px solid #f8fafc;
}
.dh-notif-item:hover { background: #f8fafc; }
.dh-notif-item:last-child { border-bottom: none; }
.dh-notif-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
}
.dh-notif-title {
    font-size: .78rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.dh-notif-time {
    font-size: .67rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.dh-notif-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #6366f1;
    flex-shrink: 0;
}
.dh-notif-footer {
    padding: 10px 18px;
    border-top: 1px solid #f1f5f9;
    text-align: center;
}
.dh-notif-footer a {
    font-size: .75rem;
    color: #6366f1;
    text-decoration: none;
    font-weight: 500;
}
.dh-notif-footer a:hover { text-decoration: underline; }

/* Mobile toggle */
.dh-mobile-toggle {
    display: none;
}
@media(max-width:768px) {
    .dh-mobile-toggle { display: flex; }
    .dh-user-chip .uname,
    .dh-user-chip .urole { display: none; }
    .dh-search-wrap.open .dh-search-input { width: 140px; }
    .dh-page-title { font-size: .9rem; }
}
</style>

<nav class="dh-navbar" id="dhNavbar">

    {{-- ── Left: breadcrumb + title ──────────────────────────── --}}
    <div class="dh-breadcrumb">
        <div class="dh-breadcrumb-trail">
            <a href="{{ url('admin/dashboard') }}">
                <i class="fas fa-home" style="font-size:.6rem;"></i>
            </a>
            @foreach ($breadcrumbs as $i => $crumb)
                <span class="sep"><i class="fas fa-chevron-right"></i></span>
                @if ($loop->last)
                    <span class="current">{{ $crumb['label'] }}</span>
                @else
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @endif
            @endforeach
        </div>
        <div class="dh-page-title">{{ $pageTitle }}</div>
    </div>

    {{-- ── Right: actions ────────────────────────────────────── --}}
    <div class="dh-navbar-right">

        {{-- Search --}}
        <div class="dh-search-wrap" id="searchWrap">
            <button class="dh-search-icon-btn" id="searchToggle" title="Search">
                <i class="fas fa-search"></i>
            </button>
            <input class="dh-search-input" id="globalSearchInput"
                   type="text" placeholder="Search anything…" autocomplete="off">
        </div>

        <div class="dh-nav-sep"></div>

        {{-- Notifications --}}
        <div style="position:relative;">
            <button class="dh-icon-btn" id="notifToggle" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="dh-badge"></span>
            </button>

            <div class="dh-notif-dropdown" id="notifDropdown">
                <div class="dh-notif-head">
                    <h6>Notifications</h6>
                    <button class="dh-notif-mark" id="markAllRead">Mark all read</button>
                </div>

                <a href="javascript:;" class="dh-notif-item">
                    <span class="dh-notif-dot"></span>
                    <span class="dh-notif-icon" style="background:#ede9fe;color:#7c3aed;">
                        <i class="fas fa-newspaper"></i>
                    </span>
                    <div style="min-width:0;">
                        <p class="dh-notif-title">New post submitted for review</p>
                        <span class="dh-notif-time"><i class="fas fa-clock"></i> 5 min ago</span>
                    </div>
                </a>

                <a href="javascript:;" class="dh-notif-item">
                    <span class="dh-notif-dot" style="background:transparent;"></span>
                    <span class="dh-notif-icon" style="background:#d1fae5;color:#059669;">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    <div style="min-width:0;">
                        <p class="dh-notif-title">New user registered</p>
                        <span class="dh-notif-time"><i class="fas fa-clock"></i> 1 hour ago</span>
                    </div>
                </a>

                <a href="javascript:;" class="dh-notif-item">
                    <span class="dh-notif-dot" style="background:transparent;"></span>
                    <span class="dh-notif-icon" style="background:#fef3c7;color:#d97706;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                    <div style="min-width:0;">
                        <p class="dh-notif-title">3 posts expiring this week</p>
                        <span class="dh-notif-time"><i class="fas fa-clock"></i> 2 hours ago</span>
                    </div>
                </a>

                <div class="dh-notif-footer">
                    <a href="javascript:;">View all notifications</a>
                </div>
            </div>
        </div>

        {{-- Mobile sidenav toggle --}}
        <a href="javascript:;" class="dh-icon-btn dh-mobile-toggle" id="iconNavbarSidenav" title="Menu">
            <i class="fas fa-bars"></i>
        </a>

        <div class="dh-nav-sep"></div>

        {{-- User chip + logout --}}
        @auth
            @php
                $authUser = auth()->user();
                $initial  = strtoupper(substr($authUser->name, 0, 1));
                $role     = $authUser->roles->first()?->name ?? 'User';
            @endphp

            <a href="{{ url('admin/profile') }}" class="dh-user-chip">
                <div class="avatar">{{ $initial }}</div>
                <div>
                    <div class="uname">{{ $authUser->name }}</div>
                    <div class="urole">{{ ucfirst($role) }}</div>
                </div>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="dh-icon-btn" title="Sign out"
                        style="color:#94a3b8;"
                        onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626';"
                        onmouseout="this.style.background='transparent';this.style.color='#94a3b8';">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        @endauth

    </div>

</nav>

<script>
(function () {

    /* ── Search expand/collapse ────────────────────────────── */
    const searchWrap  = document.getElementById('searchWrap');
    const searchBtn   = document.getElementById('searchToggle');
    const searchInput = document.getElementById('globalSearchInput');

    searchBtn.addEventListener('click', function () {
        searchWrap.classList.toggle('open');
        if (searchWrap.classList.contains('open')) searchInput.focus();
        else searchInput.value = '';
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            searchWrap.classList.remove('open');
            searchInput.value = '';
        }
        // Ctrl/Cmd + K to open search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchWrap.classList.add('open');
            searchInput.focus();
        }
    });

    /* ── Notifications dropdown ────────────────────────────── */
    const notifBtn      = document.getElementById('notifToggle');
    const notifDropdown = document.getElementById('notifDropdown');

    notifBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        notifDropdown.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.remove('open');
        }
    });

    /* ── Mark all read ─────────────────────────────────────── */
    document.getElementById('markAllRead').addEventListener('click', function () {
        document.querySelectorAll('.dh-notif-dot').forEach(d => {
            d.style.background = 'transparent';
        });
        notifBtn.querySelector('.dh-badge').style.display = 'none';
    });

})();
</script>