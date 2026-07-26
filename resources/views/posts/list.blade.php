@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">

<style>
/* ── Design tokens (match sidenav / navbar / dashboard) ─── */
:root {
    --dk:      #0f172a;
    --dk2:     #1e293b;
    --accent:  #6366f1;
    --surface: #f8fafc;
    --border:  #f1f5f9;
    --muted:   #64748b;
    --muted2:  #94a3b8;
    --r:       10px;
    --sh:      0 2px 16px rgba(15,23,42,.07);
    --sh-hover:0 6px 28px rgba(15,23,42,.12);
}

/* ── Stat cards ──────────────────────────────────────────── */
.ps-kpi {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh);
    padding: 1rem 1.2rem;
    display: flex; align-items: center; gap: 14px;
    transition: transform .16s, box-shadow .16s;
}
.ps-kpi:hover { transform: translateY(-2px); box-shadow: var(--sh-hover); }
.ps-kpi-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
}
.ps-kpi-val { font-size: 1.5rem; font-weight: 800; line-height: 1; color: var(--dk); }
.ps-kpi-lbl {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted2); margin-top: 3px;
}

/* ── Main card ───────────────────────────────────────────── */
.ps-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh);
    overflow: hidden;
}
.ps-card-header {
    padding: 1.1rem 1.4rem .9rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.ps-page-title {
    font-size: 1rem; font-weight: 800; color: var(--dk);
    margin: 0; letter-spacing: -.01em;
}
.ps-page-sub { font-size: .75rem; color: var(--muted2); margin: 2px 0 0; }

/* ── View tabs (All / Trash) ─────────────────────────────── */
.ps-view-tabs {
    display: flex; gap: 4px;
    border-bottom: 1px solid var(--border);
    padding: 0 1.4rem;
}
.ps-view-tab {
    font-size: .77rem; font-weight: 600;
    padding: .6rem .9rem; border: none;
    background: transparent; cursor: pointer;
    color: var(--muted); border-bottom: 2px solid transparent;
    margin-bottom: -1px; transition: color .14s, border-color .14s;
    display: inline-flex; align-items: center; gap: 6px;
}
.ps-view-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
.ps-view-tab .ps-tab-badge {
    font-size: .62rem; font-weight: 700; padding: 1px 6px;
    border-radius: 100px;
}

/* ── Filter panel ────────────────────────────────────────── */
.ps-filter-panel {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 1rem 1.4rem;
    display: none;
}
.ps-filter-panel.open { display: block; }
.ps-filter-panel .form-label {
    font-size: .65rem; font-weight: 700; letter-spacing: .09em;
    text-transform: uppercase; color: var(--muted2); margin-bottom: 5px;
}
.ps-filter-panel .form-control,
.ps-filter-panel .form-select {
    font-size: .82rem; border-color: var(--border);
    border-radius: 8px; background: #fff;
}
.ps-filter-panel .form-control:focus,
.ps-filter-panel .form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
}

/* ── Toolbar buttons ─────────────────────────────────────── */
.ps-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .77rem; font-weight: 600; border-radius: 8px;
    padding: .48rem .9rem; cursor: pointer; border: 1.5px solid;
    transition: background .14s, color .14s, box-shadow .14s;
    text-decoration: none; white-space: nowrap;
}
.ps-btn-primary {
    background: var(--dk); color: #fff; border-color: var(--dk);
}
.ps-btn-primary:hover {
    background: var(--accent); border-color: var(--accent); color: #fff;
    box-shadow: 0 3px 12px rgba(99,102,241,.3);
}
.ps-btn-ghost {
    background: #fff; color: var(--muted); border-color: var(--border);
}
.ps-btn-ghost:hover { background: var(--surface); color: var(--dk); }
.ps-btn-danger {
    background: #fff; color: #dc2626; border-color: #fecaca;
}
.ps-btn-danger:hover { background: #fef2f2; border-color: #dc2626; }

/* ── Table ───────────────────────────────────────────────── */
#datatable thead th {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted2);
    background: var(--surface); border-bottom: 1px solid var(--border);
    vertical-align: middle; white-space: nowrap; padding: .7rem 1rem;
}
#datatable tbody td {
    vertical-align: middle; white-space: nowrap;
    font-size: .82rem; color: var(--dk);
    padding: .65rem 1rem; border-bottom: 1px solid var(--border);
}
#datatable tbody tr:hover td { background: var(--surface); }
#datatable tbody tr:last-child td { border-bottom: none; }

/* ── Row action buttons ──────────────────────────────────── */
.row-btn {
    width: 30px; height: 30px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .72rem; border: 1px solid var(--border);
    background: #fff; cursor: pointer; transition: all .14s;
    text-decoration: none;
}
.row-btn:hover { background: var(--surface); }
.row-btn.view:hover  { border-color: #6366f1; color: #6366f1; }
.row-btn.edit:hover  { border-color: #d97706; color: #d97706; }
.row-btn.del:hover   { border-color: #dc2626; color: #dc2626; background: #fef2f2; }
.row-btn.restore:hover{ border-color: #059669; color: #059669; background: #f0fdf4; }
.row-btn.fdel:hover  { border-color: #dc2626; color: #fff; background: #dc2626; }

/* ── Inline status select ────────────────────────────────── */
.inline-status {
    font-size: .72rem; font-weight: 600; border: none;
    border-radius: 100px; padding: 3px 10px; cursor: pointer;
    outline: none; transition: background .2s;
}

/* ── Status badges ───────────────────────────────────────── */
.s-published { background:#d1fae5;color:#059669; }
.s-draft     { background:#f1f5f9;color:#64748b; }
.s-archived  { background:#fef3c7;color:#d97706; }

/* ── Modal — design token overrides ─────────────────────── */
.ps-modal .modal-content {
    border: none; border-radius: 14px;
    box-shadow: 0 24px 60px rgba(15,23,42,.18);
}
.ps-modal .modal-header {
    padding: 1.2rem 1.4rem .9rem;
    border-bottom: 1px solid var(--border);
}
.ps-modal-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: var(--dk); color: #fff;
    display: flex; align-items: center;
    justify-content: center; font-size: 1rem; flex-shrink: 0;
}
.ps-modal-icon.edit-mode { background: linear-gradient(135deg,#d97706,#f59e0b); }

/* Tab nav */
.ps-tab-nav {
    display: flex; gap: 2px;
    padding: 0 1.4rem;
    border-bottom: 1px solid var(--border);
}
.ps-tab-link {
    font-size: .75rem; font-weight: 600; padding: .6rem .85rem;
    border: none; background: transparent; cursor: pointer;
    color: var(--muted); border-bottom: 2px solid transparent;
    margin-bottom: -1px; transition: color .14s, border-color .14s;
    display: inline-flex; align-items: center; gap: 5px;
}
.ps-tab-link.active { color: var(--dk); border-bottom-color: var(--dk); }
.ps-tab-link .tab-err-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #ef4444; display: inline-block;
}

/* Section label inside modal */
.modal-section-label {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted2);
    margin: 0 0 .75rem;
}

/* Form controls inside modal */
.ps-modal .form-control,
.ps-modal .form-select {
    font-size: .84rem; border-color: var(--border);
    border-radius: 8px; color: var(--dk);
}
.ps-modal .form-control:focus,
.ps-modal .form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.ps-modal .form-label {
    font-size: .78rem; font-weight: 600; color: var(--dk);
    margin-bottom: 5px;
}

/* Dropzone */
.dropzone {
    border: 2px dashed var(--border); border-radius: 10px;
    background: var(--surface); min-height: 80px; padding: 1rem;
    transition: border-color .15s;
}
.dropzone:hover { border-color: var(--accent); }
.dropzone .dz-message { margin: .5em 0; font-size: .84rem; color: var(--muted2); }

/* Image strip */
.img-strip { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .75rem; }
.img-strip .img-wrap { position: relative; }
.img-strip img {
    width: 56px; height: 56px; object-fit: cover;
    border-radius: 8px; border: 2px solid var(--border); cursor: pointer;
    transition: border-color .15s;
}
.img-strip img:hover { border-color: var(--accent); }
.img-strip .btn-del-media {
    position: absolute; top: -6px; right: -6px;
    width: 18px; height: 18px; padding: 0; font-size: 9px;
    border-radius: 50%; line-height: 18px; text-align: center;
}

/* Modal footer */
.ps-modal .modal-footer {
    padding: .9rem 1.4rem; border-top: 1px solid var(--border);
}

/* Empty trash notice */
.trash-notice {
    background: #fef2f2; border: 1px solid #fecaca;
    border-radius: 8px; padding: .65rem 1rem;
    font-size: .78rem; color: #b91c1c;
    display: flex; align-items: center; gap: 8px;
    display: none;
}
.trash-notice.visible { display: flex; }
</style>
@endpush

<div>

{{-- ═══ KPI CARDS ═══════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#dbeafe;">
                <i class="fas fa-newspaper" style="color:#1d4ed8;"></i>
            </div>
            <div>
                <div class="ps-kpi-val" id="stat-total">—</div>
                <div class="ps-kpi-lbl">Total Posts</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;">
                <i class="fas fa-check-circle" style="color:#059669;"></i>
            </div>
            <div>
                <div class="ps-kpi-val" style="color:#059669;" id="stat-published">—</div>
                <div class="ps-kpi-lbl">Published</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#f1f5f9;">
                <i class="fas fa-pencil-alt" style="color:#64748b;"></i>
            </div>
            <div>
                <div class="ps-kpi-val" style="color:#64748b;" id="stat-draft">—</div>
                <div class="ps-kpi-lbl">Draft</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef3c7;">
                <i class="fas fa-archive" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="ps-kpi-val" style="color:#d97706;" id="stat-archived">—</div>
                <div class="ps-kpi-lbl">Archived</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MAIN TABLE CARD ══════════════════════════════════════ --}}
<div class="ps-card">

    {{-- Header --}}
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Posts Management</h4>
            <p class="ps-page-sub">Create, edit and manage all posts</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            {{-- Trash notice --}}
            <div class="trash-notice" id="trashEmptyBtn">
                <i class="fas fa-trash-alt"></i>
                <span id="trashedCount">0</span> in trash —
                <button class="btn btn-link p-0 text-danger fw-semibold"
                        style="font-size:.78rem;" id="emptyTrashBtn">
                    Empty trash
                </button>
            </div>

            <button class="ps-btn ps-btn-ghost" id="toggleFilters">
                <i class="fas fa-sliders-h"></i> Filters
            </button>
            <button class="ps-btn ps-btn-primary" id="addPostBtn"
                    data-bs-toggle="modal" data-bs-target="#postModal">
                <i class="fas fa-plus"></i> Add Post
            </button>
            <a href="{{ route('posts.reorder') }}" class="ps-btn ps-btn-ghost">
                <i class="fas fa-arrows-alt-v"></i> Reorder
            </a>
        </div>
    </div>

    {{-- View tabs: All / Trash --}}
    <div class="ps-view-tabs">
        <button class="ps-view-tab active" data-view="all" id="tabAll">
            <i class="fas fa-list"></i> All Posts
            <span class="ps-tab-badge" style="background:#ede9fe;color:#7c3aed;"
                  id="tab-badge-all">—</span>
        </button>
        <button class="ps-view-tab" data-view="trashed" id="tabTrashed">
            <i class="fas fa-trash-alt"></i> Trash
            <span class="ps-tab-badge" style="background:#fef2f2;color:#dc2626;"
                  id="tab-badge-trashed">—</span>
        </button>
    </div>

    {{-- Filter panel --}}
    <div class="ps-filter-panel" id="filterPanel">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select id="filter_category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select id="filter_status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" id="filter_start" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" id="filter_end" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label">Quick Search</label>
                <input type="text" id="globalSearch" class="form-control form-control-sm"
                       placeholder="Search title…">
            </div>
            <div class="col-12 d-flex gap-2">
                <button id="applyFilter" class="ps-btn ps-btn-primary">
                    <i class="fas fa-search"></i> Apply
                </button>
                <button id="clearFilter" class="ps-btn ps-btn-ghost">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:90px;">Action</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Locality</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Expiry</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>{{-- /ps-card --}}
</div>

{{-- ═══════════════════════════════════════════════════════════
     CREATE / EDIT POST MODAL — 4 tabs
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade ps-modal" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon" id="modalIcon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);"
                            id="postModalTitle">Create Post</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);"
                           id="postModalSubtitle">Fill in the details below</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tab nav --}}
            <div class="ps-tab-nav" id="postModalTabs">
                <button class="ps-tab-link active" data-tab="tab-basic">
                    <i class="fas fa-align-left"></i> Basic Info
                </button>
                <button class="ps-tab-link" data-tab="tab-location">
                    <i class="fas fa-map-marker-alt"></i> Contact Details
                </button>
                <button class="ps-tab-link" data-tab="tab-media">
                    <i class="fas fa-images"></i> Media
                </button>
                <button class="ps-tab-link" data-tab="tab-seo">
                    <i class="fas fa-search"></i> SEO
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="post_id">

                {{-- ── Tab 1: Basic ─────────────────────────────── --}}
                <div class="modal-tab-pane" id="tab-basic">
                    <p class="modal-section-label">Post Information</p>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" id="post_title" class="form-control"
                                   placeholder="Enter post title…">
                            <small class="text-danger d-none" id="err_title"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea id="description" class="form-control" rows="4"
                                      placeholder="Post content…"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Disclaimer</label>
                            <textarea id="post_disclaimer" class="form-control" rows="3"
                                      placeholder="Custom disclaimer shown on this post's detail page…"></textarea>
                            <small style="font-size:.72rem;color:var(--muted2);">
                                Shown at the bottom of this post's detail page. Leave blank to use the default disclaimer for its category.
                            </small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select id="post_category_id" class="form-select">
                                <option value="">— Select Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="err_category_id"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subcategory</label>
                            <select id="post_subcategory_id" class="form-select">
                                <option value="">— Select Subcategory —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Locality</label>
                            <select id="post_locality_id" class="form-select">
                                <option value="">— Select Locality —</option>
                                @foreach ($localities as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="post_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            <small class="text-danger d-none" id="err_status"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Assigned User</label>
                            <select id="post_user_id" class="form-select">
                                <option value="">— Select User —</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" id="post_expiry_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Offer Percentage</label>
                            <div class="input-group">
                                <input type="number" id="post_offer_percentage" class="form-control"
                                    placeholder="e.g. 20" min="0" max="100" step="0.01">
                                <span class="input-group-text bg-light">%</span>
                            </div>
                            <small class="text-danger d-none" id="err_offer_percentage"></small>
                        </div>
                    </div>
                    <hr class="my-3" style="border-color:var(--border);">
                    <p class="modal-section-label">Options</p>
                    <div class="d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="post_is_featured">
                            <label class="form-check-label" style="font-size:.82rem;" for="post_is_featured">
                                <i class="fas fa-star me-1 text-warning"></i> Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="post_is_active" checked>
                            <label class="form-check-label" style="font-size:.82rem;" for="post_is_active">
                                <i class="fas fa-toggle-on me-1 text-success"></i> Active
                            </label>
                        </div>
                    </div>

                    @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
                    <hr class="my-3" style="border-color:var(--border);">
                    <p class="modal-section-label">Share to Social Media</p>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="post_share_facebook">
                            <label class="form-check-label" style="font-size:.82rem;" for="post_share_facebook">
                                <i class="fab fa-facebook me-1" style="color:#1877f2;"></i> Share to Facebook
                            </label>
                            <span class="badge bg-success-subtle text-success rounded-pill d-none ms-1" id="fbSharedBadge" style="font-size:.62rem;">
                                <i class="fas fa-check-circle"></i> Already shared
                            </span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="post_share_instagram">
                            <label class="form-check-label" style="font-size:.82rem;" for="post_share_instagram">
                                <i class="fab fa-instagram me-1" style="color:#e1306c;"></i> Share to Instagram
                            </label>
                            <span class="badge bg-success-subtle text-success rounded-pill d-none ms-1" id="igSharedBadge" style="font-size:.62rem;">
                                <i class="fas fa-check-circle"></i> Already shared
                            </span>
                        </div>
                    </div>
                    <small style="font-size:.72rem;color:var(--muted2);">
                        Requires Facebook/Instagram API credentials configured in Settings. Instagram needs at least one image on this post.
                    </small>
                    @endif
                </div>

                {{-- ── Tab 2: Location ───────────────────────────── --}}
                <div class="modal-tab-pane d-none" id="tab-location">
 
    {{-- ── Contact ─────────────────────────────────────── --}}
    <p class="modal-section-label">Contact Information</p>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Company Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-building" style="font-size:.75rem;color:var(--muted2);"></i>
                </span>
                <input type="text" id="post_company_name" class="form-control border-start-0"
                       placeholder="e.g. Acme Ltd.">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-phone" style="font-size:.75rem;color:var(--muted2);"></i>
                </span>
                <input type="text" id="post_phone_number" class="form-control border-start-0"
                       placeholder="+971 50 123 4567">
                <small class="text-danger d-none w-100" id="err_phone_number"></small>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">WhatsApp Number</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"
                      style="background:#d1fae5!important;">
                    <i class="fab fa-whatsapp" style="font-size:.85rem;color:#25d366;"></i>
                </span>
                <input type="text" id="post_whatsapp_number" class="form-control border-start-0"
                       placeholder="+971 50 123 4567">
                <small class="text-danger d-none w-100" id="err_whatsapp_number"></small>
            </div>
        </div>
    </div>
 
    <hr class="my-3" style="border-color:var(--border);">
 
    {{-- ── Location ─────────────────────────────────────── --}}
    <p class="modal-section-label">Location Details</p>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Country</label>
            <input type="text" id="post_country" class="form-control" placeholder="e.g. UAE">
        </div>
        <div class="col-md-4">
            <label class="form-label">State</label>
            <input type="text" id="post_state" class="form-control" placeholder="e.g. Dubai">
        </div>
        <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" id="post_city" class="form-control" placeholder="e.g. Downtown">
        </div>
        <div class="col-12">
            <label class="form-label">Location Description</label>
            <input type="text" id="post_location" class="form-control"
                   placeholder="Detailed location or landmark…">
        </div>
    </div>
 
    <hr class="my-3" style="border-color:var(--border);">
 
    {{-- ── GPS + Map URL ────────────────────────────────── --}}
    <p class="modal-section-label">GPS & Map
        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--muted);">
            (optional)
        </span>
    </p>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Latitude</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-xs"
                      style="color:var(--muted2);">LAT</span>
                <input type="number" id="post_latitude" class="form-control border-start-0"
                       placeholder="25.2048" step="any" min="-90" max="90">
            </div>
            <small class="text-danger d-none" id="err_latitude"></small>
        </div>
        <div class="col-md-3">
            <label class="form-label">Longitude</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-xs"
                      style="color:var(--muted2);">LNG</span>
                <input type="number" id="post_longitude" class="form-control border-start-0"
                       placeholder="55.2708" step="any" min="-180" max="180">
            </div>
            <small class="text-danger d-none" id="err_longitude"></small>
        </div>
        <div class="col-md-6">
            <label class="form-label">Google Maps URL</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-map" style="font-size:.75rem;color:var(--muted2);"></i>
                </span>
                <input type="text" id="post_google_map_url" class="form-control border-start-0"
                       placeholder="https://maps.google.com/…">
            </div>
        </div>
    </div>
 
</div>

                {{-- ── Tab 3: Media ──────────────────────────────── --}}
                <div class="modal-tab-pane d-none" id="tab-media">
                    <p class="modal-section-label">Images</p>
                    <div id="existingImages" class="img-strip"></div>
                    <form action="{{ route('posts.mediaUpload') }}" class="dropzone" id="postDropzone">
                        @csrf
                        <div class="dz-message">
                            <i class="fas fa-cloud-upload-alt me-2" style="color:var(--accent);"></i>
                            <span class="fw-semibold" style="color:var(--dk);">Drop images here</span>
                            <span style="color:var(--muted2);"> or click to upload</span>
                            <br><small style="color:var(--muted2);">Max 5 MB · JPG, PNG, WEBP</small>
                        </div>
                    </form>
                    <hr class="my-4" style="border-color:var(--border);">
                    <p class="modal-section-label">Video</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Video File</label>
                            <input type="file" id="post_video" class="form-control" accept="video/*">
                            <small style="font-size:.72rem;color:var(--muted2);">
                                Uploading a new file replaces the existing one
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Video URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-youtube text-danger" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="post_video_url" class="form-control border-start-0"
                                       placeholder="https://youtube.com/watch?v=…">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Tab 4: SEO ────────────────────────────────── --}}
                <div class="modal-tab-pane d-none" id="tab-seo">
                    <p class="modal-section-label">SEO & Meta</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meta Title</label>
                            <input type="text" id="post_meta_title" class="form-control"
                                   placeholder="SEO title (defaults to post title if blank)">
                            <small style="font-size:.72rem;color:var(--muted2);">Recommended: 50–60 characters</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta Description</label>
                            <textarea id="post_meta_description" class="form-control" rows="3"
                                      placeholder="Short description for search engines…"></textarea>
                            <small style="font-size:.72rem;color:var(--muted2);">Recommended: 150–160 characters</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keywords</label>
                            <input type="text" id="post_keywords" class="form-control"
                                   placeholder="keyword1, keyword2, keyword3…">
                            <small style="font-size:.72rem;color:var(--muted2);">Comma-separated</small>
                        </div>
                    </div>
                </div>

            </div>{{-- /modal-body --}}

            {{-- Footer --}}
            <div class="modal-footer justify-content-between">
                <div id="modalMeta" class="d-none" style="font-size:.72rem;color:var(--muted2);">
                    <i class="fas fa-clock me-1"></i>
                    <span id="modalMetaText"></span>
                </div>
                <div class="d-flex gap-2 ms-auto">
                    <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="ps-btn ps-btn-primary" id="savePost">
                        <span id="savePostText"><i class="fas fa-save"></i> Save Post</span>
                        <span id="savePostSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm"></span> Saving…
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<script>
let editorInstance, table;
let postId = null, isEditMode = false, viewMode = 'all';

// ── CKEditor ──────────────────────────────────────────────────
ClassicEditor.create(document.querySelector('#description'))
    .then(e => { editorInstance = e; }).catch(console.error);

// ── Dropzone ──────────────────────────────────────────────────
Dropzone.autoDiscover = false;
const myDropzone = new Dropzone('#postDropzone', {
    url: '{{ route("posts.mediaUpload") }}',
    autoProcessQueue: false,
    parallelUploads: 5,
    maxFilesize: 5,
    acceptedFiles: 'image/*',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    sending: (file, xhr, fd) => fd.append('post_id', postId),
    success: function(file, res) {
        if (res.url) appendImageThumb(res.id, res.url);
        this.removeFile(file);
    },
    error: (file, msg) => console.error(msg),
    // ── ADD THIS ──
    queuecomplete: function() {
        if (pendingModalClose) {
            pendingModalClose = false;
            finishSave();
        }
    },
});

function appendImageThumb(id, url) {
    $('#existingImages').append(`
        <div class="img-wrap" id="media-wrap-${id}">
            <a href="${url}" data-fancybox="edit-gallery">
                <img src="${url}" alt="">
            </a>
            <button type="button" class="btn btn-danger btn-del-media"
                    data-id="${id}" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>`);
}

/* ── Tab switching (view) ─────────────────────────────────── */
$(document).on('click', '.ps-view-tab', function() {
    $('.ps-view-tab').removeClass('active');
    $(this).addClass('active');
    viewMode = $(this).data('view');
    const isTrashed = viewMode === 'trashed';
    $('#trashEmptyBtn').toggleClass('visible', isTrashed);
    table.ajax.reload();
});

/* ── Tab switching (modal) ────────────────────────────────── */
var fieldTabMap = {
    title:'tab-basic', description:'tab-basic', disclaimer:'tab-basic',
    category_id:'tab-basic', status:'tab-basic',
    company_name:'tab-location', phone_number:'tab-location', whatsapp_number:'tab-location',
    latitude:'tab-location', longitude:'tab-location',
    offer_percentage: 'tab-basic',
    meta_title:'tab-seo', meta_description:'tab-seo', keywords:'tab-seo',
};

$(document).on('click', '#postModalTabs .ps-tab-link', function() {
    var target = $(this).data('tab');
    $('#postModalTabs .ps-tab-link').removeClass('active');
    $(this).addClass('active');
    $('.modal-tab-pane').addClass('d-none');
    $('#' + target).removeClass('d-none');
});

function switchToTab(tabId) {
    $('#postModalTabs .ps-tab-link[data-tab="' + tabId + '"]').trigger('click');
}

/* ── Error helpers ────────────────────────────────────────── */
function clearErrors() {
    $('.text-danger[id^="err_"]').addClass('d-none').text('');
    $('.form-control, .form-select').removeClass('is-invalid');
    $('#postModalTabs .tab-err-dot').remove();
}
function showErrors(errors) {
    var firstTab = null, tabsWithErrors = {};
    $.each(errors, function(field, msgs) {
        $('#err_' + field).removeClass('d-none').text(msgs[0]);
        $('#post_' + field).addClass('is-invalid');
        var tab = fieldTabMap[field] || 'tab-basic';
        tabsWithErrors[tab] = true;
        if (!firstTab) firstTab = tab;
    });
    $.each(tabsWithErrors, function(tab) {
        var link = $('#postModalTabs .ps-tab-link[data-tab="' + tab + '"]');
        if (!link.find('.tab-err-dot').length)
            link.append('<span class="tab-err-dot ms-1"></span>');
    });
    if (firstTab) switchToTab(firstTab);
}

/* ── Reset modal ──────────────────────────────────────────── */
function resetModal() {
    isEditMode = false; postId = null;
    clearErrors();
    switchToTab('tab-basic');
    $('#postModalTitle').text('Create Post');
    $('#postModalSubtitle').text('Fill in the details below');
    $('#modalIcon').removeClass('edit-mode');
    $('#modalMeta').addClass('d-none');
    $('#savePostText').html('<i class="fas fa-save"></i> Save Post');
    $('#post_id, #post_title, #post_expiry_date').val('');
    $('#post_disclaimer').val('');
    $('#post_offer_percentage').val('');
    $('#post_category_id, #post_locality_id, #post_user_id').val('');
    $('#post_subcategory_id').html('<option value="">— Select Subcategory —</option>');
    $('#post_status').val('draft');
    $('#post_is_featured').prop('checked', false);
    $('#post_is_active').prop('checked', true);
    editorInstance?.setData('');
    $('#post_company_name, #post_phone_number, #post_whatsapp_number').val('');
    $('#post_country, #post_state, #post_city, #post_location').val('');
    $('#post_latitude, #post_longitude, #post_google_map_url').val('');
    $('#existingImages').empty();
    $('#post_video').val('');
    $('#post_video_url').val('');
    myDropzone.removeAllFiles(true);
    $('#post_meta_title, #post_meta_description, #post_keywords').val('');
    $('#post_share_facebook, #post_share_instagram').prop('checked', false);
    $('#fbSharedBadge, #igSharedBadge').addClass('d-none');
}

$('#addPostBtn').on('click', resetModal);
$('#postModal').on('hidden.bs.modal', resetModal);

/* ── Category → Subcategory cascade ──────────────────────── */
$(document).on('change', '#post_category_id', function() {
    var id = $(this).val();
    $('#post_subcategory_id').html('<option value="">— Select Subcategory —</option>');
    if (!id) return;
    $.get('{{ url("admin/get-subcategories") }}/' + id, function(res) {
        var html = '<option value="">— Select Subcategory —</option>';
        res.forEach(r => { html += `<option value="${r.id}">${r.name}</option>`; });
        $('#post_subcategory_id').html(html);
    });
});

/* ── Open EDIT ────────────────────────────────────────────── */
$(document).on('click', '.editPost', function() {
    var id = $(this).data('id');
    resetModal();
    $.get('{{ url("admin/posts") }}/' + id + '/edit-data', function(res) {
        isEditMode = true; postId = res.id;
        $('#post_id').val(res.id);
        $('#postModalTitle').text('Edit Post');
        $('#postModalSubtitle').text('Editing post #' + res.id);
        $('#modalIcon').addClass('edit-mode');
        $('#savePostText').html('<i class="fas fa-save"></i> Save Changes');
        if (res.updated_at) {
            $('#modalMeta').removeClass('d-none');
            $('#modalMetaText').text('Last updated: ' + res.updated_at);
        }
        $('#post_title').val(res.title);
        $('#post_status').val(res.status);
        $('#post_user_id').val(res.user_id);
        $('#post_locality_id').val(res.locality_id);
        $('#post_expiry_date').val(res.expiry_date ?? '');
        $('#post_offer_percentage').val(res.offer_percentage ?? '');
        $('#post_is_featured').prop('checked', !!res.is_featured);
        $('#post_is_active').prop('checked', !!res.is_active);
        editorInstance.setData(res.description ?? '');
        $('#post_disclaimer').val(res.disclaimer ?? '');
        if (res.category_id) {
            $('#post_category_id').val(res.category_id).trigger('change');
            setTimeout(() => $('#post_subcategory_id').val(res.subcategory_id), 450);
        }
       
        $('#post_company_name').val(res.company_name ?? '');
        $('#post_phone_number').val(res.phone_number ?? '');
        $('#post_whatsapp_number').val(res.whatsapp_number ?? '');
        $('#post_country').val(res.country ?? '');
        $('#post_state').val(res.state ?? '');
        $('#post_city').val(res.city ?? '');
        $('#post_location').val(res.location ?? '');
        $('#post_latitude').val(res.latitude ?? '');
        $('#post_longitude').val(res.longitude ?? '');
        $('#post_google_map_url').val(res.google_map_url ?? '');
        (res.images ?? []).forEach(img => appendImageThumb(img.id, img.url));
        $('#post_video_url').val(res.video_url ?? '');
        $('#post_meta_title').val(res.meta_title ?? '');
        $('#post_meta_description').val(res.meta_description ?? '');
        $('#post_keywords').val(res.keywords ?? '');
        $('#fbSharedBadge').toggleClass('d-none', !res.shared_to_facebook);
        $('#igSharedBadge').toggleClass('d-none', !res.shared_to_instagram);
        $('#postModal').modal('show');
    }).fail(() => Swal.fire('Error', 'Could not load post data.', 'error'));
});

// Flag to track whether modal should close after upload
let pendingModalClose = false;
let pendingSocialShare = { facebook: false, instagram: false };

// Close modal + refresh table + show success toast
function finishSave() {
    $('#postModal').modal('hide');
    table.ajax.reload(null, false);

    const platforms = [];
    if (pendingSocialShare.facebook)  platforms.push('facebook');
    if (pendingSocialShare.instagram) platforms.push('instagram');
    pendingSocialShare = { facebook: false, instagram: false };

    if (!platforms.length) {
        Swal.fire({
            icon: 'success',
            title: isEditMode ? 'Post updated!' : 'Post created!',
            timer: 1500,
            showConfirmButton: false
        });
        return;
    }

    Swal.fire({
        title: 'Sharing…',
        text: 'Posting to ' + platforms.map(p => p[0].toUpperCase() + p.slice(1)).join(' & ') + '…',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    $.post('{{ url("admin/posts") }}/' + postId + '/share', {
        _token: '{{ csrf_token() }}',
        platforms: platforms,
    }).done(function (res) {
        const results = res.results || {};
        const allOk = Object.values(results).every(r => r.success);
        const lines = Object.entries(results).map(([platform, r]) =>
            (r.success ? '✅ ' : '❌ ') + platform.charAt(0).toUpperCase() + platform.slice(1) + ': ' + r.message
        );
        Swal.fire({
            icon: allOk ? 'success' : 'warning',
            title: 'Social sharing',
            html: lines.join('<br>'),
        });
    }).fail(function () {
        Swal.fire('Error', 'Could not reach the social sharing endpoint.', 'error');
    });
}
/* ── Save ─────────────────────────────────────────────────── */
$('#savePost').on('click', function() {
    clearErrors();
    $('#savePostText').addClass('d-none');
    $('#savePostSpinner').removeClass('d-none');
    $('#savePost').prop('disabled', true);
    pendingSocialShare = {
        facebook:  $('#post_share_facebook').is(':checked'),
        instagram: $('#post_share_instagram').is(':checked'),
    };
    $.ajax({
        url  : isEditMode ? '{{ url("admin/posts") }}/' + postId : '{{ route("posts.ajaxStore") }}',
        type : 'POST',
        data : {
            _token:'{{ csrf_token() }}', _method: isEditMode ? 'PUT' : 'POST',
            title:$('#post_title').val(), user_id:$('#post_user_id').val(),
            category_id:$('#post_category_id').val(),
            subcategory_id:$('#post_subcategory_id').val(),
            locality_id:$('#post_locality_id').val(),
            status:$('#post_status').val(),
            expiry_date:$('#post_expiry_date').val(),
            offer_percentage: $('#post_offer_percentage').val(),
            is_featured:$('#post_is_featured').is(':checked')?1:0,
            is_active:$('#post_is_active').is(':checked')?1:0,
            description:editorInstance.getData(),
            disclaimer:$('#post_disclaimer').val(),
            country:$('#post_country').val(), state:$('#post_state').val(),
            city:$('#post_city').val(), location:$('#post_location').val(),
            latitude:$('#post_latitude').val(), longitude:$('#post_longitude').val(),            
            company_name:$('#post_company_name').val(),
            phone_number:$('#post_phone_number').val(),
            whatsapp_number:$('#post_whatsapp_number').val(),
            google_map_url:$('#post_google_map_url').val(),
            video_url:$('#post_video_url').val(),
            meta_title:$('#post_meta_title').val(),
            meta_description:$('#post_meta_description').val(),
            keywords:$('#post_keywords').val(),
        },
        success: function(res) {
            if (!res.success) return;
            postId = res.data.id;

            if (myDropzone.getQueuedFiles().length > 0) {
                // Upload files first — modal closes in queuecomplete
                pendingModalClose = true;
                myDropzone.processQueue();
            } else {
                // No files — close immediately
                finishSave();
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) showErrors(xhr.responseJSON.errors ?? {});
            else Swal.fire('Error', 'Something went wrong.', 'error');
        },
        complete: function() {
            $('#savePostText').removeClass('d-none');
            $('#savePostSpinner').addClass('d-none');
            $('#savePost').prop('disabled', false);
        }
    });
});

/* ── Soft delete ──────────────────────────────────────────── */
$(document).on('click', '.deletePost', function() {
    var id = $(this).data('id'), title = $(this).data('title');
    Swal.fire({
        title:'Move to Trash?',
        html:'<strong>' + title + '</strong> will be moved to the trash.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc2626', cancelButtonColor:'#64748b',
        confirmButtonText:'Move to trash',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url:'{{ url("admin/posts") }}/' + id, type:'POST',
            data:{ _token:'{{ csrf_token() }}', _method:'DELETE' },
            success: res => { if (res.success) { table.ajax.reload(null,false); toastSuccess(res.message); } }
        });
    });
});

/* ── Restore ──────────────────────────────────────────────── */
$(document).on('click', '.restorePost', function() {
    var id = $(this).data('id');
    $.post('{{ url("admin/posts") }}/' + id + '/restore', { _token:'{{ csrf_token() }}' },
        res => { if (res.success) { table.ajax.reload(null,false); toastSuccess(res.message); }
    });
});

/* ── Force delete ─────────────────────────────────────────── */
$(document).on('click', '.forceDeletePost', function() {
    var id = $(this).data('id'), title = $(this).data('title');
    Swal.fire({
        title:'Delete Permanently?',
        html:'<strong>' + title + '</strong> and all its media will be deleted forever.',
        icon:'error', showCancelButton:true,
        confirmButtonColor:'#dc2626', cancelButtonColor:'#64748b',
        confirmButtonText:'Delete forever',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url:'{{ url("admin/posts") }}/' + id, type:'POST',
            data:{ _token:'{{ csrf_token() }}', _method:'DELETE' },
            success: res => { if (res.success) { table.ajax.reload(null,false); toastSuccess(res.message); } },
            error: function(xhr) {
                console.error(xhr.status, xhr.responseText);
                Swal.fire('Error', xhr.responseJSON?.message || 'Delete failed (HTTP ' + xhr.status + ')', 'error');
            }
        });
    });
});

/* ── Empty trash ──────────────────────────────────────────── */
$('#emptyTrashBtn').on('click', function() {
    Swal.fire({
        title:'Empty Trash?', text:'All trashed posts will be permanently deleted.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc2626', confirmButtonText:'Empty trash',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post('{{ url("admin/posts/empty-trash") }}', { _token:'{{ csrf_token() }}' },
            res => { if (res.success) { table.ajax.reload(null,false); toastSuccess(res.message); }
        });
    });
});

/* ── Delete media ─────────────────────────────────────────── */
$(document).on('click', '.btn-del-media', function() {
    var id = $(this).data('id');
    Swal.fire({
        title:'Remove image?', icon:'warning',
        showCancelButton:true, confirmButtonColor:'#dc2626',
        confirmButtonText:'Remove',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url:'{{ url("admin/posts/media") }}/' + id, type:'POST',
            data:{ _token:'{{ csrf_token() }}', _method:'DELETE' },
            success: res => { if (res.success) $('#media-wrap-' + id).remove(); }
        });
    });
});

/* ── Inline status ────────────────────────────────────────── */
$(document).on('change', '.inline-status', function() {
    var $el=$(this);
    $.post('{{ route("posts.inlineUpdate") }}', {
        _token:'{{ csrf_token() }}', id:$el.data('id'), field:'status', value:$el.val(),
    }, res => { if (!res.success) Swal.fire('Error','Status update failed','error'); });
});

/* ── Filters ──────────────────────────────────────────────── */
$('#toggleFilters').on('click', () => $('#filterPanel').toggleClass('open'));
$('#applyFilter').on('click',  () => table.ajax.reload());
$('#clearFilter').on('click',  () => {
    $('#filter_category, #filter_status').val('');
    $('#filter_start, #filter_end, #globalSearch').val('');
    table.search('').ajax.reload();
});
$('#globalSearch').on('keyup', function() { table.search(this.value).draw(); });
$('#filter_status,#filter_category,#filter_start,#filter_end').on('change', () => table.ajax.reload());

/* ── Toast helper ─────────────────────────────────────────── */
function toastSuccess(msg) {
    Swal.fire({ toast:true, position:'top-end', icon:'success',
        title:msg, timer:1800, showConfirmButton:false });
}

/* ── DataTable ────────────────────────────────────────────── */
$(function() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : '{{ route("posts.data") }}',
            type: 'POST',
            data: function(d) {
                d._token      = '{{ csrf_token() }}';
                d.trashed     = viewMode === 'trashed' ? 1 : 0;
                d.status      = $('#filter_status').val();
                d.category_id = $('#filter_category').val();
                d.start_date  = $('#filter_start').val();
                d.end_date    = $('#filter_end').val();
            }
        },
        columns: [
            { data:'action',      name:'action',      orderable:false, searchable:false },
            { data:'title',       name:'title' },
            { data:'category',    name:'category',    orderable:false },
            { data:'subcategory', name:'subcategory', orderable:false },
            { data:'locality',    name:'locality',    orderable:false },
            { data:'user',        name:'user',        orderable:false },
            { data:'status',      name:'status',      searchable:false },
            { data:'expiry',      name:'expiry',      searchable:false },
            { data:'created_at',  name:'created_at',  searchable:false },
        ],
        order:[[8,'desc']],
        pageLength:10,
        lengthMenu:[10,25,50,100],

        drawCallback: function(settings) {
            var json = settings.json ?? {};
            $('#stat-total').text(json.totalCount ?? '—');
            $('#stat-published').text(json.publishedCount ?? '—');
            $('#stat-draft').text(json.draftCount ?? '—');
            $('#stat-archived').text(json.archivedCount ?? '—');
            var tc = json.trashedCount ?? 0;
            $('#tab-badge-all').text(json.totalCount ?? '—');
            $('#tab-badge-trashed').text(tc);
            $('#trashedCount').text(tc);
            Fancybox.bind('[data-fancybox^="gallery-"]');
        },

        language: {
            processing: '<div class="d-flex align-items-center gap-2 justify-content-center py-3">'
                + '<div class="spinner-border spinner-border-sm" style="color:var(--accent);"></div>'
                + '<span style="font-size:.82rem;color:var(--muted);">Loading…</span></div>',
        }
    });
});

@if(auth()->user()->hasRole('author'))
// Authors: lock status to draft, hide user selector
$('#post_status').find('option[value="published"], option[value="archived"]').hide();
$('#post_status').val('draft');
// Hide "Assigned User" field — always their own account
$('#post_user_id').closest('.col-md-4').hide();
@endif
 
@if(auth()->user()->hasRole('author'))
$('#post_status').find('option[value="published"], option[value="archived"]').hide();
$('#post_status').val('draft');
$('#post_user_id').closest('.col-md-4').hide();
@endif
</script>
@endpush