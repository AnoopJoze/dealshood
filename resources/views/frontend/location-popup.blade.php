{{--
  ═══════════════════════════════════════════════════
  LOCATION SELECTION POPUP
  resources/views/frontend/location-popup.blade.php

  @include('frontend.location-popup', ['localities' => $localities])

  Shows on first visit. Remembers choice in localStorage.
  Integrates with existing activeLocSlug / reloadContent().
  ═══════════════════════════════════════════════════
--}}

<style>
/* ══════════════════════════════════════════════
   LOCATION POPUP
══════════════════════════════════════════════ */
.lp-overlay {
    position: fixed; inset: 0;
    background: rgba(10,10,20,.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9800;
    display: flex; align-items: flex-end; justify-content: center;
    opacity: 0; transition: opacity .3s ease;
    pointer-events: none;
}
.lp-overlay.show {
    opacity: 1; pointer-events: all;
}

/* ── Sheet (mobile: slides up / desktop: centered card) ── */
.lp-sheet {
    background: #fff;
    width: 100%;
    max-width: 560px;
    border-radius: 24px 24px 0 0;
    max-height: 92vh;
    display: flex; flex-direction: column;
    transform: translateY(100%);
    transition: transform .38s cubic-bezier(.4,0,.2,1);
    overflow: hidden;
    box-shadow: 0 -8px 48px rgba(0,0,0,.22);
}
.lp-overlay.show .lp-sheet {
    transform: translateY(0);
}

@media (min-width: 600px) {
    .lp-overlay {
        align-items: center;
    }
    .lp-sheet {
        border-radius: 24px;
        max-height: 80vh;
        margin: 24px;
        box-shadow: 0 32px 80px rgba(0,0,0,.28), 0 8px 24px rgba(0,0,0,.12);
        transform: translateY(32px) scale(.96);
    }
    .lp-overlay.show .lp-sheet {
        transform: translateY(0) scale(1);
    }
}

/* ── Drag handle (mobile) ── */
.lp-handle {
    width: 36px; height: 4px; border-radius: 2px;
    background: #e2e8f0; margin: 12px auto 0;
    flex-shrink: 0;
}
@media (min-width: 600px) { .lp-handle { display: none; } }

/* ── Header ── */
.lp-head {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.lp-head-top {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 14px;
}
.lp-icon-wrap {
    width: 52px; height: 52px; border-radius: 16px;
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
    animation: pinBounce .6s .3s both;
}
@keyframes pinBounce {
    0%   { transform: scale(.5) translateY(-10px); opacity: 0; }
    60%  { transform: scale(1.15) translateY(2px); }
    80%  { transform: scale(.95); }
    100% { transform: scale(1); opacity: 1; }
}
.lp-skip {
    font-size: .75rem; font-weight: 600; color: #94a3b8;
    background: none; border: none; cursor: pointer;
    padding: 6px 10px; border-radius: 8px; transition: all .15s;
    -webkit-tap-highlight-color: transparent;
}
.lp-skip:hover { background: #f8fafc; color: #64748b; }

.lp-title {
    font-size: 1.25rem; font-weight: 800;
    color: #0f172a; margin: 0 0 4px;
    letter-spacing: -.02em;
}
.lp-sub {
    font-size: .83rem; color: #64748b; margin: 0;
    font-weight: 300; line-height: 1.5;
}

/* ── Search ── */
.lp-search-wrap {
    padding: 12px 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0;
}
.lp-search-box {
    display: flex; align-items: center; gap: 9px;
    background: #f8fafc; border: 1.5px solid #f1f5f9;
    border-radius: 12px; padding: 10px 14px;
    transition: border-color .15s, box-shadow .15s;
}
.lp-search-box:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
    background: #fff;
}
.lp-search-box i { color: #94a3b8; font-size: .85rem; flex-shrink: 0; }
.lp-search-box input {
    border: none; background: transparent; outline: none;
    font-size: .88rem; color: #0f172a; flex: 1; min-width: 0;
}
.lp-search-box input::placeholder { color: #94a3b8; }

/* ── "All Areas" pill (always visible) ── */
.lp-all-wrap {
    padding: 12px 20px 4px;
    flex-shrink: 0;
}
.lp-all-btn {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 12px 16px;
    background: #0f172a; color: #fff;
    border: none; border-radius: 12px; cursor: pointer;
    font-size: .88rem; font-weight: 600;
    transition: background .15s, transform .12s;
    -webkit-tap-highlight-color: transparent;
    text-align: left;
}
.lp-all-btn:hover  { background: #1e293b; }
.lp-all-btn:active { transform: scale(.98); }
.lp-all-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; flex-shrink: 0;
}

/* ── Locality grid ── */
.lp-grid-wrap {
    flex: 1; overflow-y: auto; padding: 8px 20px 24px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent;
}
.lp-grid-wrap::-webkit-scrollbar { width: 4px; }
.lp-grid-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

.lp-section-label {
    font-size: .62rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: #94a3b8;
    margin: 14px 0 8px;
}

.lp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 8px;
}
@media (max-width: 400px) {
    .lp-grid { grid-template-columns: 1fr 1fr; }
}

.lp-loc-card {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px;
    background: #f8fafc; border: 1.5px solid #f1f5f9;
    border-radius: 12px; cursor: pointer;
    transition: all .15s;
    -webkit-tap-highlight-color: transparent;
    position: relative; overflow: hidden;
}
.lp-loc-card:hover {
    border-color: #6366f1; background: #f5f3ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(99,102,241,.15);
}
.lp-loc-card:active { transform: scale(.96); }
.lp-loc-card.selected {
    border-color: #6366f1; background: #ede9fe;
    box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}
.lp-loc-card.selected .lp-card-icon { background: #6366f1; color: #fff; }

.lp-card-icon {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: #e2e8f0; color: #64748b;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; transition: all .15s;
}
.lp-card-name {
    font-size: .8rem; font-weight: 600; color: #0f172a;
    line-height: 1.3; flex: 1; min-width: 0;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.lp-card-check {
    position: absolute; top: 6px; right: 6px;
    width: 16px; height: 16px; border-radius: 50%;
    background: #6366f1; color: #fff;
    display: none; align-items: center; justify-content: center;
    font-size: .5rem;
}
.lp-loc-card.selected .lp-card-check { display: flex; }

/* ── No results ── */
.lp-no-results {
    text-align: center; padding: 32px 16px;
    color: #94a3b8; font-size: .85rem;
}
.lp-no-results i { font-size: 2rem; opacity: .3; display: block; margin-bottom: 10px; }

/* ── Confirm button ── */
.lp-confirm-wrap {
    padding: 12px 20px calc(12px + env(safe-area-inset-bottom, 0px));
    border-top: 1px solid #f1f5f9;
    flex-shrink: 0;
    background: #fff;
}
.lp-confirm-btn {
    width: 100%; padding: 14px;
    background: #6366f1; color: #fff;
    border: none; border-radius: 14px; cursor: pointer;
    font-size: .92rem; font-weight: 700; letter-spacing: .01em;
    transition: background .15s, transform .12s, box-shadow .15s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    -webkit-tap-highlight-color: transparent;
}
.lp-confirm-btn:hover {
    background: #4f46e5;
    box-shadow: 0 6px 20px rgba(99,102,241,.4);
}
.lp-confirm-btn:active { transform: scale(.98); }
.lp-confirm-btn:disabled { background: #e2e8f0; color: #94a3b8; cursor: default; box-shadow: none; }
</style>

{{-- ── POPUP HTML ── --}}
<div class="lp-overlay" id="lpOverlay" role="dialog" aria-modal="true" aria-label="Select your area">
    <div class="lp-sheet" id="lpSheet">

        <div class="lp-handle"></div>

        {{-- Header --}}
        <div class="lp-head">
            <div class="lp-head-top">
                <div class="lp-icon-wrap">📍</div>
                <button class="lp-skip" id="lpSkip" type="button">
                    Skip <i class="fas fa-times ms-1" style="font-size:.7rem;"></i>
                </button>
            </div>
            <h2 class="lp-title">Where are you looking?</h2>
            <p class="lp-sub">Pick your area to see the best local deals near you.</p>
        </div>

        {{-- Search --}}
        <div class="lp-search-wrap">
            <div class="lp-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="lpSearch" placeholder="Search your area…" autocomplete="off">
            </div>
        </div>

        {{-- All Areas option --}}
        <div class="lp-all-wrap" id="lpAllWrap">
            <button class="lp-all-btn" id="lpAllAreas" type="button">
                <span class="lp-all-icon"><i class="fas fa-globe"></i></span>
                <div>
                    <div style="font-size:.88rem;font-weight:700;">All Areas</div>
                    <div style="font-size:.72rem;opacity:.6;font-weight:400;">Show deals from everywhere</div>
                </div>
            </button>
        </div>

        {{-- Grid --}}
        <div class="lp-grid-wrap">
            <div class="lp-section-label" id="lpSectionLabel">Choose your area</div>
            <div class="lp-grid" id="lpGrid">
                @foreach ($localities as $i => $loc)
                    @php
                        $icons = ['fa-map-marker-alt','fa-city','fa-home','fa-store','fa-building',
                                  'fa-map','fa-location-dot','fa-compass'];
                        $colors = [
                            ['bg'=>'#dbeafe','ic'=>'#1d4ed8'],['bg'=>'#d1fae5','ic'=>'#059669'],
                            ['bg'=>'#fef3c7','ic'=>'#d97706'],['bg'=>'#fce7f3','ic'=>'#db2777'],
                            ['bg'=>'#ede9fe','ic'=>'#7c3aed'],['bg'=>'#cffafe','ic'=>'#0891b2'],
                            ['bg'=>'#fff7ed','ic'=>'#ea580c'],['bg'=>'#f0f9ff','ic'=>'#0284c7'],
                        ];
                        $c = $colors[$i % count($colors)];
                        $icon = $icons[$i % count($icons)];
                    @endphp
                    <div class="lp-loc-card"
                         data-slug="{{ $loc->slug }}"
                         data-name="{{ $loc->name }}"
                         role="option">
                        <span class="lp-card-icon" style="background:{{ $c['bg'] }};color:{{ $c['ic'] }};">
                            <i class="fas {{ $icon }}"></i>
                        </span>
                        <span class="lp-card-name">{{ $loc->name }}</span>
                        <span class="lp-card-check"><i class="fas fa-check"></i></span>
                    </div>
                @endforeach
            </div>
            <div class="lp-no-results d-none" id="lpNoResults">
                <i class="fas fa-search-minus"></i>
                No areas found for "<span id="lpNoResultsQuery"></span>"
            </div>
        </div>

        {{-- Confirm --}}
        <div class="lp-confirm-wrap">
            <button class="lp-confirm-btn" id="lpConfirm" type="button" disabled>
                <i class="fas fa-map-marker-alt"></i>
                <span id="lpConfirmText">Select an area to continue</span>
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    const STORAGE_KEY = 'dh_locality_v1';
    const overlay     = document.getElementById('lpOverlay');
    const grid        = document.getElementById('lpGrid');
    const searchInput = document.getElementById('lpSearch');
    const confirmBtn  = document.getElementById('lpConfirm');
    const confirmTxt  = document.getElementById('lpConfirmText');
    const noResults   = document.getElementById('lpNoResults');
    const noResQuery  = document.getElementById('lpNoResultsQuery');
    const sectionLbl  = document.getElementById('lpSectionLabel');
    const allWrap     = document.getElementById('lpAllWrap');

    let selected = { slug: null, name: null };

    /* ── Open / close ── */
    function openPopup() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 400);
    }
    function closePopup() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    /* ── Should we show the popup? ──
       Show if:
       - Never chosen before (no localStorage key)
       - OR forced via ?choose-area query param
    ── */
    const stored   = (() => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)); } catch(e){ return null; } })();
    const forceOpen = new URLSearchParams(location.search).has('choose-area');

    if (!stored || forceOpen) {
        // Small delay so page renders first
        setTimeout(openPopup, 320);
    } else {
        // Apply stored preference immediately (no popup)
        if (stored.slug) applyLocality(stored.slug, stored.name, false);
    }

    /* ── Card click (select) ── */
    grid.addEventListener('click', function (e) {
        const card = e.target.closest('.lp-loc-card');
        if (!card) return;
        selectCard(card);
    });

    function selectCard(card) {
        grid.querySelectorAll('.lp-loc-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        selected.slug = card.dataset.slug;
        selected.name = card.dataset.name;
        confirmBtn.disabled = false;
        confirmTxt.textContent = 'Show deals in ' + selected.name;
        confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
        // Scroll card into view smoothly
        card.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }

    /* ── All Areas ── */
    document.getElementById('lpAllAreas').addEventListener('click', function () {
        grid.querySelectorAll('.lp-loc-card').forEach(c => c.classList.remove('selected'));
        selected.slug = '';
        selected.name = 'All Areas';
        confirmBtn.disabled = false;
        confirmTxt.textContent = 'Browse all areas';
        confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
    });

    /* ── Confirm ── */
    confirmBtn.addEventListener('click', function () {
        save();
        applyLocality(selected.slug, selected.name, true);
        closePopup();
    });

    /* ── Skip ── */
    document.getElementById('lpSkip').addEventListener('click', function () {
        // Store "skipped" so popup doesn't show again this session
        try { sessionStorage.setItem(STORAGE_KEY + '_skip', '1'); } catch(e){}
        closePopup();
    });

    /* ── Close on backdrop click ── */
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closePopup();
    });

    /* ── Escape key ── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('show')) closePopup();
    });

    /* ── Search/filter ── */
    searchInput.addEventListener('input', function () {
        const q    = this.value.toLowerCase().trim();
        const cards = grid.querySelectorAll('.lp-loc-card');
        let   any  = false;

        allWrap.style.display = q ? 'none' : '';
        sectionLbl.textContent = q ? 'Search results' : 'Choose your area';

        cards.forEach(card => {
            const match = !q || card.dataset.name.toLowerCase().includes(q);
            card.style.display = match ? '' : 'none';
            if (match) any = true;
        });

        noResults.classList.toggle('d-none', any);
        if (!any) noResQuery.textContent = this.value;
    });

    /* ── Save to localStorage ── */
    function save() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                slug: selected.slug,
                name: selected.name,
                ts:   Date.now(),
            }));
        } catch(e) {}
    }

    /* ── Apply locality to the page ──
       Calls the existing setLocUI + reloadContent from the home page JS.
       Wrapped in a try so it doesn't error on pages that don't have those functions.
    ── */
    function applyLocality(slug, name, reload) {
        /* Update the location picker button label */
        const lbl = document.getElementById('locLabel');
        if (lbl) lbl.textContent = name || 'All Areas';
        const locBtn = document.getElementById('locBtn');
        if (locBtn) slug ? locBtn.classList.add('has-loc') : locBtn.classList.remove('has-loc');

        /* Call page-level JS (home page) */
        try {
            if (typeof setLocUI === 'function')   setLocUI(slug, name);
            if (reload && typeof reloadContent === 'function') reloadContent();
        } catch(e) {}
    }

    /* ── "Change area" trigger ──
       Any element with data-open-location-picker triggers the popup again.
       e.g. <button data-open-location-picker>Change area</button>
    ── */
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-open-location-picker]')) openPopup();
    });

    /* Expose open function globally so loc-btn can also trigger it */
    window.openLocationPopup = openPopup;

})();
</script>