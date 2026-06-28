{{--
  ═══════════════════════════════════════════════════
  LOCATION SELECTION POPUP — drill-down: District → City → Area
  resources/views/frontend/location-popup.blade.php

  @include('frontend.location-popup', ['localities' => $localities])

  $localities = ALL localities flat list. The JS groups them by type:
    district → city (parent_id = district.id) → area (parent_id = city.id)
  Selecting at any level is valid.
  ═══════════════════════════════════════════════════
--}}

<style>
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
.lp-overlay.show { opacity: 1; pointer-events: all; }

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
.lp-overlay.show .lp-sheet { transform: translateY(0); }

@media (min-width: 600px) {
    .lp-overlay { align-items: center; }
    .lp-sheet {
        border-radius: 24px;
        max-height: 80vh;
        margin: 24px;
        box-shadow: 0 32px 80px rgba(0,0,0,.28), 0 8px 24px rgba(0,0,0,.12);
        transform: translateY(32px) scale(.96);
    }
    .lp-overlay.show .lp-sheet { transform: translateY(0) scale(1); }
}

.lp-handle {
    width: 36px; height: 4px; border-radius: 2px;
    background: #e2e8f0; margin: 12px auto 0;
    flex-shrink: 0;
}
@media (min-width: 600px) { .lp-handle { display: none; } }

/* ── Header ── */
.lp-head { padding: 18px 20px 14px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; }
.lp-head-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }

.lp-back-btn {
    width: 36px; height: 36px; border-radius: 10px;
    background: #f1f5f9; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #475569; font-size: .85rem;
    transition: background .15s, color .15s;
    -webkit-tap-highlight-color: transparent;
    flex-shrink: 0;
}
.lp-back-btn:hover { background: #e2e8f0; color: #0f172a; }
.lp-back-btn.hidden { visibility: hidden; pointer-events: none; }

.lp-skip {
    font-size: .75rem; font-weight: 600; color: #94a3b8;
    background: none; border: none; cursor: pointer;
    padding: 6px 10px; border-radius: 8px; transition: all .15s;
    -webkit-tap-highlight-color: transparent;
}
.lp-skip:hover { background: #f8fafc; color: #64748b; }

/* Breadcrumb */
.lp-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .75rem; color: #94a3b8; font-weight: 500;
    flex-wrap: wrap;
}
.lp-breadcrumb span { color: #0f172a; font-weight: 700; }
.lp-breadcrumb .sep { color: #cbd5e1; }

/* Level title */
.lp-level-title { font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 6px 0 2px; letter-spacing: -.02em; }
.lp-level-sub   { font-size: .8rem; color: #64748b; margin: 0; font-weight: 400; }

/* ── Search ── */
.lp-search-wrap { padding: 10px 20px; border-bottom: 1px solid #f1f5f9; flex-shrink: 0; }
.lp-search-box {
    display: flex; align-items: center; gap: 9px;
    background: #f8fafc; border: 1.5px solid #f1f5f9;
    border-radius: 12px; padding: 10px 14px;
    transition: border-color .15s, box-shadow .15s;
}
.lp-search-box:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); background: #fff; }
.lp-search-box i { color: #94a3b8; font-size: .85rem; flex-shrink: 0; }
.lp-search-box input { border: none; background: transparent; outline: none; font-size: .88rem; color: #0f172a; flex: 1; min-width: 0; }
.lp-search-box input::placeholder { color: #94a3b8; }

/* ── "All Areas" banner — shown only at district level ── */
.lp-all-wrap { padding: 10px 20px 2px; flex-shrink: 0; }
.lp-all-btn {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 11px 16px;
    background: #0f172a; color: #fff;
    border: none; border-radius: 12px; cursor: pointer;
    font-size: .88rem; font-weight: 600;
    transition: background .15s, transform .12s;
    -webkit-tap-highlight-color: transparent; text-align: left;
}
.lp-all-btn:hover  { background: #1e293b; }
.lp-all-btn:active { transform: scale(.98); }
.lp-all-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
}

/* ── List ── */
.lp-list-wrap { flex: 1; overflow-y: auto; padding: 6px 20px 20px; -webkit-overflow-scrolling: touch; scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
.lp-list-wrap::-webkit-scrollbar { width: 4px; }
.lp-list-wrap::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

.lp-list { display: flex; flex-direction: column; gap: 6px; margin-top: 8px; }

.lp-item {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 16px;
    background: #f8fafc; border: 1.5px solid #f1f5f9;
    border-radius: 14px; cursor: pointer;
    transition: all .15s;
    -webkit-tap-highlight-color: transparent;
    position: relative;
}
.lp-item:hover  { border-color: #6366f1; background: #f5f3ff; box-shadow: 0 3px 12px rgba(99,102,241,.12); }
.lp-item:active { transform: scale(.985); }
.lp-item.selected { border-color: #6366f1; background: #ede9fe; box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
.lp-item.selected .lp-item-icon { background: #6366f1; color: #fff; }

.lp-item-icon {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; transition: all .15s;
}
.lp-item-body { flex: 1; min-width: 0; }
.lp-item-name { font-size: .88rem; font-weight: 700; color: #0f172a; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lp-item-meta { font-size: .7rem; color: #94a3b8; font-weight: 500; margin-top: 1px; }

/* drill arrow shown for non-leaf levels */
.lp-item-arrow { color: #cbd5e1; font-size: .75rem; flex-shrink: 0; transition: color .15s; }
.lp-item:hover .lp-item-arrow { color: #6366f1; }
/* check shown when selected */
.lp-item-check {
    width: 20px; height: 20px; border-radius: 50%;
    background: #6366f1; color: #fff;
    display: none; align-items: center; justify-content: center;
    font-size: .55rem; flex-shrink: 0;
}
.lp-item.selected .lp-item-check { display: flex; }
.lp-item.selected .lp-item-arrow { display: none; }

.lp-no-results { text-align: center; padding: 32px 16px; color: #94a3b8; font-size: .85rem; }
.lp-no-results i { font-size: 2rem; opacity: .3; display: block; margin-bottom: 10px; }

/* ── Confirm bar ── */
.lp-confirm-wrap { padding: 12px 20px calc(12px + env(safe-area-inset-bottom,0px)); border-top: 1px solid #f1f5f9; flex-shrink: 0; background: #fff; }
.lp-confirm-btn {
    width: 100%; padding: 14px;
    background: #6366f1; color: #fff;
    border: none; border-radius: 14px; cursor: pointer;
    font-size: .92rem; font-weight: 700; letter-spacing: .01em;
    transition: background .15s, transform .12s, box-shadow .15s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    -webkit-tap-highlight-color: transparent;
}
.lp-confirm-btn:hover { background: #4f46e5; box-shadow: 0 6px 20px rgba(99,102,241,.4); }
.lp-confirm-btn:active { transform: scale(.98); }
.lp-confirm-btn:disabled { background: #e2e8f0; color: #94a3b8; cursor: default; box-shadow: none; }

/* slide animation for drill-down */
@keyframes lpSlideIn  { from { opacity:0; transform:translateX(28px);  } to { opacity:1; transform:translateX(0); } }
@keyframes lpSlideOut { from { opacity:0; transform:translateX(-28px); } to { opacity:1; transform:translateX(0); } }
.lp-slide-in  { animation: lpSlideIn  .22s ease both; }
.lp-slide-out { animation: lpSlideOut .22s ease both; }
.lp-all-btn.active {
    background: #4f46e5;
    box-shadow: 0 0 0 3px rgba(99,102,241,.2);
}
</style>

{{-- Pass localities as JSON to JS --}}
{{-- Pass localities as JSON to JS --}}
@php
$localitiesJson = $localities->map(function($l) {
    return [
        'id'        => $l->id,
        'name'      => $l->name,
        'slug'      => $l->slug,
        'type'      => $l->type,
        'parent_id' => $l->parent_id,
    ];
})->values()->toArray();
@endphp

<script>
window._dhLocalities = @json($localitiesJson);
console.log('localities loaded:', window._dhLocalities);
</script>

<div class="lp-overlay" id="lpOverlay" role="dialog" aria-modal="true" aria-label="Select your area">
    <div class="lp-sheet" id="lpSheet">

        <div class="lp-handle"></div>

        {{-- Header --}}
        <div class="lp-head">
            <div class="lp-head-top">
                <button class="lp-back-btn hidden" id="lpBack" type="button" aria-label="Go back">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="lp-skip" id="lpSkip" type="button">
                    Skip <i class="fas fa-times ms-1" style="font-size:.7rem;"></i>
                </button>
            </div>
            <div class="lp-breadcrumb" id="lpBreadcrumb">
                <i class="fas fa-map-marker-alt" style="color:#6366f1;"></i>
                <span>Choose your location</span>
            </div>
            <div class="lp-level-title" id="lpLevelTitle">Select District</div>
            <p class="lp-level-sub" id="lpLevelSub">Pick a district to browse deals nearby.</p>
        </div>

        {{-- Search --}}
        <div class="lp-search-wrap">
            <div class="lp-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="lpSearch" placeholder="Search…" autocomplete="off">
            </div>
        </div>

        {{-- All Areas (only at district level) --}}
        <div class="lp-all-wrap" id="lpAllWrap">
            <button class="lp-all-btn" id="lpAllAreas" type="button">
                <span class="lp-all-icon"><i class="fas fa-globe"></i></span>
                <div>
                    <div style="font-size:.85rem;font-weight:700;">All Areas</div>
                    <div style="font-size:.7rem;opacity:.6;font-weight:400;">Show deals from everywhere</div>
                </div>
            </button>
        </div>

        {{-- List --}}
        <div class="lp-list-wrap">
            <div class="lp-list" id="lpList"></div>
            <div class="lp-no-results d-none" id="lpNoResults">
                <i class="fas fa-search-minus"></i>
                No results for "<span id="lpNoResultsQuery"></span>"
            </div>
        </div>

        {{-- Confirm --}}
        <div class="lp-confirm-wrap">
            <button class="lp-confirm-btn" id="lpConfirm" type="button" disabled>
                <i class="fas fa-map-marker-alt"></i>
                <span id="lpConfirmText">Select a location to continue</span>
            </button>
        </div>

    </div>
</div>

<script>
(function () {
    const STORAGE_KEY = 'dh_locality_v1';

    /* ── DOM refs ── */
    const overlay     = document.getElementById('lpOverlay');
    const backBtn     = document.getElementById('lpBack');
    const skipBtn     = document.getElementById('lpSkip');
    const searchInput = document.getElementById('lpSearch');
    const allWrap     = document.getElementById('lpAllWrap');
    const list        = document.getElementById('lpList');
    const noResults   = document.getElementById('lpNoResults');
    const noResQuery  = document.getElementById('lpNoResultsQuery');
    const confirmBtn  = document.getElementById('lpConfirm');
    const confirmTxt  = document.getElementById('lpConfirmText');
    const breadcrumb  = document.getElementById('lpBreadcrumb');
    const levelTitle  = document.getElementById('lpLevelTitle');
    const levelSub    = document.getElementById('lpLevelSub');

    /* ── Data ── */
    const all       = window._dhLocalities || [];
    const byId      = Object.fromEntries(all.map(l => [l.id, l]));
    const districts = all.filter(l => l.type === 'district');
    const cities    = all.filter(l => l.type === 'city');
    const areas     = all.filter(l => l.type === 'area');

    /* ── State ── */
    // level: 'district' | 'city' | 'area'
    let level          = 'district';
    let activeDistrict = null;   // locality object
    let activeCity     = null;   // locality object
    let selected       = { slug: null, name: null };

    /* Colour palette cycling */
    const palettes = [
        {bg:'#dbeafe',ic:'#1d4ed8'}, {bg:'#d1fae5',ic:'#059669'},
        {bg:'#fef3c7',ic:'#d97706'}, {bg:'#fce7f3',ic:'#db2777'},
        {bg:'#ede9fe',ic:'#7c3aed'}, {bg:'#cffafe',ic:'#0891b2'},
        {bg:'#fff7ed',ic:'#ea580c'}, {bg:'#f0f9ff',ic:'#0284c7'},
    ];
    const typeIcons = { district:'fa-city', city:'fa-map', area:'fa-location-dot' };

    /* ── Open / Close ── */
    function openPopup() {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 400);
    }
    function closePopup() {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    /* ── Render list ── */
    function renderLevel(items, drillable, animDir) {
        searchInput.value = '';
        noResults.classList.add('d-none');

        list.className = 'lp-list ' + (animDir === 'in' ? 'lp-slide-in' : 'lp-slide-out');

        list.innerHTML = items.map((loc, i) => {
            const c    = palettes[i % palettes.length];
            const icon = typeIcons[loc.type] || 'fa-map-marker-alt';
            const childCount = drillable ? countChildren(loc) : 0;
            const meta = drillable
                ? (childCount > 0 ? childCount + (loc.type === 'district' ? ' cities' : ' areas') : '')
                : '';
            return `
            <div class="lp-item" data-id="${loc.id}" data-slug="${loc.slug}" data-name="${loc.name}" data-drillable="${drillable ? 1 : 0}">
                <span class="lp-item-icon" style="background:${c.bg};color:${c.ic};">
                    <i class="fas ${icon}"></i>
                </span>
                <span class="lp-item-body">
                    <span class="lp-item-name">${loc.name}</span>
                    ${meta ? `<span class="lp-item-meta">${meta}</span>` : ''}
                </span>
                ${drillable ? '<i class="fas fa-chevron-right lp-item-arrow"></i>' : ''}
                <span class="lp-item-check"><i class="fas fa-check"></i></span>
            </div>`;
        }).join('');

        /* Re-highlight if same slug already selected */
        if (selected.slug) {
            const card = list.querySelector(`[data-slug="${selected.slug}"]`);
            if (card) card.classList.add('selected');
        }
    }

    function countChildren(loc) {
        if (loc.type === 'district') return cities.filter(c => c.parent_id === loc.id).length;
        if (loc.type === 'city')     return areas.filter(a => a.parent_id === loc.id).length;
        return 0;
    }

    /* ── Level helpers ── */
    function goToDistricts(animDir) {
        level          = 'district';
        activeDistrict = null;
        activeCity     = null;

        backBtn.classList.add('hidden');
        allWrap.style.display = '';
        levelTitle.textContent = 'Select District';
        levelSub.textContent   = 'Pick a district to browse deals nearby.';
        breadcrumb.innerHTML   = '<i class="fas fa-map-marker-alt" style="color:#6366f1;"></i> <span>Choose your location</span>';

        renderLevel(districts, true, animDir || 'out');
    }

    function goToCities(district, animDir) {
        level          = 'city';
        activeDistrict = district;
        activeCity     = null;

        backBtn.classList.remove('hidden');
        allWrap.style.display = 'none';
        levelTitle.textContent = district.name;
        levelSub.textContent   = 'Select a city or pick the district directly.';
        breadcrumb.innerHTML   = `<i class="fas fa-city" style="color:#6366f1;font-size:.7rem;"></i>
            <span>${district.name}</span>`;

        const kids = cities.filter(c => c.parent_id === district.id);
        renderLevel(kids, true, animDir || 'in');
    }

    function goToAreas(city, animDir) {
        level      = 'area';
        activeCity = city;

        backBtn.classList.remove('hidden');
        allWrap.style.display = 'none';
        levelTitle.textContent = city.name;
        levelSub.textContent   = 'Select an area or pick the city directly.';
        breadcrumb.innerHTML   = `
            <i class="fas fa-city" style="color:#6366f1;font-size:.7rem;"></i>
            <span>${activeDistrict.name}</span>
            <span class="sep">›</span>
            <span>${city.name}</span>`;

        const kids = areas.filter(a => a.parent_id === city.id);
        renderLevel(kids, false, animDir || 'in');
    }

    /* ── List click (drill or select) ── */
    list.addEventListener('click', function (e) {
        const item = e.target.closest('.lp-item');
        if (!item) return;

        const drillable = item.dataset.drillable === '1';
        const loc = byId[+item.dataset.id];
        if (!loc) return;

        if (drillable) {
            /* Tapping a drillable item: still mark as selectable but also drill on arrow click */
            /* We distinguish: click on the arrow area → drill only */
            const arrow = item.querySelector('.lp-item-arrow');
            const hitArrow = arrow && arrow.contains(e.target);

            if (hitArrow) {
                drill(loc);
                return;
            }

            /* Tap on body: select the item AND show drill affordance */
            markSelected(item, loc);

            /* Also drill in (show children) */
            setTimeout(() => drill(loc), 180);
        } else {
            markSelected(item, loc);
        }
    });

    function drill(loc) {
        if (loc.type === 'district') goToCities(loc, 'in');
        if (loc.type === 'city')     goToAreas(loc, 'in');
    }

    function markSelected(item, loc) {
    const isAlreadySelected = item.classList.contains('selected');

    // Deselect all
    list.querySelectorAll('.lp-item').forEach(i => i.classList.remove('selected'));

    if (isAlreadySelected) {
        // Toggle OFF — fall back to parent level selection if available
        const fallback = level === 'area' ? activeCity
                       : level === 'city' ? activeDistrict
                       : null;

        if (fallback) {
            selected = { slug: fallback.slug, name: fallback.name };
            confirmBtn.disabled = false;
            confirmTxt.textContent = 'Show deals in ' + fallback.name;
            confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
        } else {
            selected = { slug: null, name: null };
            confirmBtn.disabled = true;
            confirmTxt.textContent = 'Select a location to continue';
            confirmBtn.querySelector('i').className = 'fas fa-map-marker-alt';
        }
    } else {
        // Select
        item.classList.add('selected');
        selected = { slug: loc.slug, name: loc.name };
        confirmBtn.disabled = false;
        confirmTxt.textContent = 'Show deals in ' + loc.name;
        confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
    }
}

    /* ── All Areas ── */
    document.getElementById('lpAllAreas').addEventListener('click', function () {
    const isActive = this.classList.contains('active');
    list.querySelectorAll('.lp-item').forEach(i => i.classList.remove('selected'));

    if (isActive) {
        this.classList.remove('active');
        selected = { slug: null, name: null };
        confirmBtn.disabled = true;
        confirmTxt.textContent = 'Select a location to continue';
        confirmBtn.querySelector('i').className = 'fas fa-map-marker-alt';
    } else {
        this.classList.add('active');
        selected = { slug: '', name: 'All Areas' };
        confirmBtn.disabled = false;
        confirmTxt.textContent = 'Browse all areas';
        confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
    }
});

    /* ── Back button ── */
    backBtn.addEventListener('click', function () {
        selected = { slug: null, name: null };
        confirmBtn.disabled   = true;
        confirmTxt.textContent = 'Select a location to continue';
        confirmBtn.querySelector('i').className = 'fas fa-map-marker-alt';

        if (level === 'city')  goToDistricts('out');
        if (level === 'area')  goToCities(activeDistrict, 'out');
    });

    /* ── Search ── */
    searchInput.addEventListener('input', function () {
        const q     = this.value.toLowerCase().trim();
        const items = list.querySelectorAll('.lp-item');
        let any     = false;
        items.forEach(item => {
            const match = !q || item.dataset.name.toLowerCase().includes(q);
            item.style.display = match ? '' : 'none';
            if (match) any = true;
        });
        noResults.classList.toggle('d-none', any || !q);
        if (!any && q) noResQuery.textContent = this.value;
    });

    /* ── Confirm ── */
    confirmBtn.addEventListener('click', function () {
        if (!selected.slug && selected.slug !== '') return;
        save(selected);
        applyLocality(selected.slug, selected.name, true);
        closePopup();
    });

    /* ── Skip ── */
    skipBtn.addEventListener('click', function () {
        try { sessionStorage.setItem(STORAGE_KEY + '_skip', '1'); } catch(e) {}
        closePopup();
    });

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closePopup();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('show')) closePopup();
    });

    /* ── Persist ── */
    function save(sel) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({ slug: sel.slug, name: sel.name, ts: Date.now() }));
        } catch(e) {}
    }

    /* ── Apply to page ── */
    /* ── Apply to page ── */
    function applyLocality(slug, name, reload) {
        const lbl = document.getElementById('locLabel');
        if (lbl) lbl.textContent = name || 'All Areas';
        const locTrigger = document.getElementById('locTrigger');
        if (locTrigger) slug ? locTrigger.classList.add('has-loc') : locTrigger.classList.remove('has-loc');
        try {
            // Pass skipReload=true so setLocUI only updates the UI, not triggers loadPosts
            // reloadContent handles the actual reload separately
            if (typeof setLocUI === 'function') setLocUI(slug, name, true);
            if (reload && typeof reloadContent === 'function') reloadContent();
        } catch(e) {}
    }

    /* ── Boot ── */
    /* ── Boot ── */
    const stored    = (() => { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)); } catch(e) { return null; } })();
    const forceOpen = new URLSearchParams(location.search).has('choose-area');

    /* Restore drill-down position from stored slug */
    function restoreLevel() {
        if (!stored || !stored.slug) {
            goToDistricts('in');
            return;
        }

        const storedLoc = all.find(l => l.slug === stored.slug);
        if (!storedLoc) {
            goToDistricts('in');
            return;
        }

        if (storedLoc.type === 'district') {
            goToDistricts('in');
            // pre-select the district
            selected = { slug: storedLoc.slug, name: storedLoc.name };

        } else if (storedLoc.type === 'city') {
            const district = byId[storedLoc.parent_id];
            if (district) {
                goToCities(district, 'in');
                selected = { slug: storedLoc.slug, name: storedLoc.name };
            } else {
                goToDistricts('in');
            }

        } else if (storedLoc.type === 'area') {
            const city = byId[storedLoc.parent_id];
            const district = city ? byId[city.parent_id] : null;
            if (city && district) {
                activeDistrict = district; // set before goToAreas
                goToAreas(city, 'in');
                selected = { slug: storedLoc.slug, name: storedLoc.name };
            } else if (city) {
                const dist = byId[city.parent_id];
                if (dist) { activeDistrict = dist; }
                goToAreas(city, 'in');
                selected = { slug: storedLoc.slug, name: storedLoc.name };
            } else {
                goToDistricts('in');
            }
        }

        // Highlight selected item after render
        setTimeout(() => {
            const card = list.querySelector(`[data-slug="${stored.slug}"]`);
            if (card) {
                card.classList.add('selected');
                card.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
            if (selected.slug !== null) {
                confirmBtn.disabled = false;
                confirmTxt.textContent = 'Show deals in ' + selected.name;
                confirmBtn.querySelector('i').className = 'fas fa-arrow-right';
            }
        }, 50);
    }

    restoreLevel();

    if (!stored || forceOpen) {
        setTimeout(openPopup, 320);
    } else {
        if (stored.slug !== undefined) {
            window.addEventListener('load', function () {
                applyLocality(stored.slug, stored.name, true);
            });
        }
    }
    /* External trigger */
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-open-location-picker]')) openPopup();
    });
    window.openLocationPopup = openPopup;

})();
</script>
