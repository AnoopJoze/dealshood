@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <style>
        #datatable thead th, #datatable tbody td { vertical-align: middle; white-space: nowrap; }

        /* ── Modal tabs ──────────────────────────────── */
        .modal-tab-nav .nav-link {
            color: #6c757d; border-radius: 8px;
            padding: 6px 14px; font-size: .75rem; font-weight: 600;
        }
        .modal-tab-nav .nav-link.active {
            background: linear-gradient(195deg,#42424a,#191919); color:#fff;
        }
        .modal-tab-nav .tab-err-dot {
            display:inline-block; width:7px; height:7px;
            background:#ea0606; border-radius:50%;
            margin-left:5px; vertical-align:middle;
        }

        /* ── Dropzone ────────────────────────────────── */
        .dropzone {
            border: 2px dashed #dee2e6; border-radius:.75rem;
            background:#f8f9fa; min-height:80px; padding:1rem;
        }
        .dropzone:hover { border-color:#6c757d; }
        .dropzone .dz-message { margin:.5em 0; font-size:.85rem; color:#9e9e9e; }

        /* ── Existing images strip ────────────────────── */
        .img-strip { display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:.75rem; }
        .img-strip .img-wrap { position:relative; }
        .img-strip img {
            width:56px; height:56px; object-fit:cover;
            border-radius:.45rem; border:2px solid #dee2e6; cursor:pointer;
            transition: border-color .2s;
        }
        .img-strip img:hover { border-color:#6c757d; }
        .img-strip .btn-del-media {
            position:absolute; top:-6px; right:-6px;
            width:18px; height:18px; padding:0; font-size:9px;
            border-radius:50%; line-height:18px; text-align:center;
        }

        /* ── Stat cards ──────────────────────────────── */
        .stat-card {
            border-radius:12px; padding:14px 18px;
            display:flex; align-items:center; gap:12px;
        }
        .stat-card .stat-icon {
            width:38px; height:38px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            font-size:.9rem; flex-shrink:0;
        }
        .stat-card .stat-val { font-size:1.3rem; font-weight:700; line-height:1; }
        .stat-card .stat-lbl { font-size:.65rem; text-transform:uppercase;
                                letter-spacing:.05em; color:#9e9e9e; margin-top:2px; }

        /* ── Inline status colour flash ──────────────── */
        .inline-status { cursor:pointer; transition:background .2s; }
    </style>
@endpush

<div>

    {{-- ─── Stat row ─────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card" style="background:#eff6ff;">
                <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div>
                    <div class="stat-val text-dark" id="stat-total">—</div>
                    <div class="stat-lbl">Total Posts</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card" style="background:#ecfdf5;">
                <div class="stat-icon" style="background:#d1fae5;color:#059669;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-val" style="color:#059669;" id="stat-published">—</div>
                    <div class="stat-lbl">Published</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card" style="background:#f9fafb;">
                <div class="stat-icon" style="background:#f3f4f6;color:#6b7280;">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <div class="stat-val text-secondary" id="stat-draft">—</div>
                    <div class="stat-lbl">Draft</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card" style="background:#fffbeb;">
                <div class="stat-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="fas fa-archive"></i>
                </div>
                <div>
                    <div class="stat-val" style="color:#d97706;" id="stat-archived">—</div>
                    <div class="stat-lbl">Archived</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ─── Main card ─────────────────────────────────────────── --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="card-header bg-white border-0 py-4 px-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">Posts Management</h4>
                            <p class="text-sm text-muted mb-0">Create, edit and manage all posts</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light border rounded-3 px-3" id="toggleFilters">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <button class="btn bg-gradient-primary" id="addPostBtn"
                                    data-bs-toggle="modal" data-bs-target="#postModal">
                                <i class="fas fa-plus me-1"></i> Add Post
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="card-body pt-0 px-4 pb-4">

                    {{-- Collapsible Filters --}}
                    <div id="filterPanel" class="collapse mb-4 pt-3">
                        <div class="card card-body border rounded-4 bg-light shadow-none p-3">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label text-xs fw-semibold text-muted mb-1">Category</label>
                                    <select id="filter_category" class="form-select form-select-sm">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label text-xs fw-semibold text-muted mb-1">Status</label>
                                    <select id="filter_status" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label text-xs fw-semibold text-muted mb-1">From Date</label>
                                    <input type="date" id="filter_start" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label text-xs fw-semibold text-muted mb-1">To Date</label>
                                    <input type="date" id="filter_end" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label text-xs fw-semibold text-muted mb-1">Quick Search</label>
                                    <input type="text" id="globalSearch" class="form-control form-control-sm"
                                           placeholder="Search title, category…">
                                </div>

                                <div class="col-12 d-flex gap-2">
                                    <button id="applyFilter" class="btn btn-sm bg-gradient-primary px-3">
                                        <i class="fas fa-search me-1"></i> Apply
                                    </button>
                                    <button id="clearFilter" class="btn btn-sm btn-light border px-3">
                                        <i class="fas fa-times me-1"></i> Clear
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:100px">Action</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Title</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Subcategory</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Locality</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">User</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Expiry</th>
                                    <th class="text-uppercase text-secondary text-xxs fw-bolder">Created</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════════
     CREATE / EDIT POST MODAL  —  4 tabs
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalIcon"
                         class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="postModalTitle">Create Post</h5>
                        <p class="text-xs text-muted mb-0" id="postModalSubtitle">Fill in the details below</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tab nav --}}
            <div class="px-4 pt-2 pb-0 border-bottom">
                <ul class="nav modal-tab-nav gap-1 pb-2" id="postModalTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-tab="tab-basic" href="javascript:void(0)">
                            <i class="fas fa-align-left me-1"></i> Basic Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="tab-location" href="javascript:void(0)">
                            <i class="fas fa-map-marker-alt me-1"></i> Location
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="tab-media" href="javascript:void(0)">
                            <i class="fas fa-images me-1"></i> Media
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="tab-seo" href="javascript:void(0)">
                            <i class="fas fa-search me-1"></i> SEO
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="post_id">

                {{-- ══ TAB 1: Basic Info ══ --}}
                <div class="modal-tab-pane" id="tab-basic">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Post Information</p>
                    <div class="row g-3 mb-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="post_title" class="form-control"
                                   placeholder="Enter post title…">
                            <small class="text-danger d-none" id="err_title"></small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Description</label>
                            <textarea id="description" class="form-control" rows="4"
                                      placeholder="Post content…"></textarea>
                            <small class="text-danger d-none" id="err_description"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select id="post_category_id" class="form-select">
                                <option value="">— Select Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="err_category_id"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Subcategory</label>
                            <select id="post_subcategory_id" class="form-select">
                                <option value="">— Select Subcategory —</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Locality</label>
                            <select id="post_locality_id" class="form-select">
                                <option value="">— Select Locality —</option>
                                @foreach ($localities as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="post_status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            <small class="text-danger d-none" id="err_status"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Assigned User</label>
                            <select id="post_user_id" class="form-select">
                                <option value="">— Select User —</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Expiry Date</label>
                            <input type="date" id="post_expiry_date" class="form-control">
                        </div>

                    </div>

                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">Options</p>
                    <div class="d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="post_is_featured">
                            <label class="form-check-label text-sm" for="post_is_featured">
                                <i class="fas fa-star me-1 text-warning"></i> Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="post_is_active" checked>
                            <label class="form-check-label text-sm" for="post_is_active">
                                <i class="fas fa-toggle-on me-1 text-success"></i> Active
                            </label>
                        </div>
                    </div>

                </div>

                {{-- ══ TAB 2: Location ══ --}}
                <div class="modal-tab-pane d-none" id="tab-location">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Location Details</p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Country</label>
                            <input type="text" id="post_country" class="form-control" placeholder="e.g. UAE">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">State</label>
                            <input type="text" id="post_state" class="form-control" placeholder="e.g. Dubai">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">City</label>
                            <input type="text" id="post_city" class="form-control" placeholder="e.g. Downtown">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Location Description</label>
                            <input type="text" id="post_location" class="form-control"
                                   placeholder="Detailed location or landmark…">
                        </div>

                    </div>

                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">
                        GPS Coordinates <span class="text-muted fw-normal">(optional)</span>
                    </p>
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LAT</span>
                                <input type="number" id="post_latitude" class="form-control border-start-0"
                                       placeholder="25.2048" step="any" min="-90" max="90">
                            </div>
                            <small class="text-danger d-none" id="err_latitude"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LNG</span>
                                <input type="number" id="post_longitude" class="form-control border-start-0"
                                       placeholder="55.2708" step="any" min="-180" max="180">
                            </div>
                            <small class="text-danger d-none" id="err_longitude"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Google Maps URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-map text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="post_google_map_url" class="form-control border-start-0"
                                       placeholder="https://maps.google.com/…">
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ══ TAB 3: Media ══ --}}
                <div class="modal-tab-pane d-none" id="tab-media">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Images</p>

                    {{-- Existing images --}}
                    <div id="existingImages" class="img-strip"></div>

                    {{-- Dropzone --}}
                    <form action="{{ route('posts.mediaUpload') }}" class="dropzone" id="postDropzone">
                        @csrf
                        <div class="dz-message">
                            <i class="fas fa-cloud-upload-alt me-2 text-muted"></i>
                            <span class="fw-semibold">Drop images here</span>
                            <span class="text-muted"> or click to upload</span>
                            <br><small class="text-muted">Max 5 MB per file · JPG, PNG, WEBP</small>
                        </div>
                    </form>

                    <hr class="horizontal dark my-4">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">Video</p>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Upload Video File</label>
                            <input type="file" id="post_video" class="form-control" accept="video/*">
                            <small class="text-muted text-xs">Uploading a new file replaces the existing one</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Video URL</label>
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

                {{-- ══ TAB 4: SEO ══ --}}
                <div class="modal-tab-pane d-none" id="tab-seo">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">SEO & Meta</p>
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Meta Title</label>
                            <input type="text" id="post_meta_title" class="form-control"
                                   placeholder="SEO title (defaults to post title if blank)">
                            <small class="text-muted text-xs">Recommended: 50–60 characters</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Meta Description</label>
                            <textarea id="post_meta_description" class="form-control" rows="3"
                                      placeholder="Short description for search engines…"></textarea>
                            <small class="text-muted text-xs">Recommended: 150–160 characters</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Keywords</label>
                            <input type="text" id="post_keywords" class="form-control"
                                   placeholder="keyword1, keyword2, keyword3…">
                            <small class="text-muted text-xs">Comma-separated keywords</small>
                        </div>

                    </div>

                </div>

            </div>{{-- /modal-body --}}

            {{-- Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2 justify-content-between">
                <div id="modalMeta" class="text-xs text-muted d-none">
                    <i class="fas fa-clock me-1"></i>
                    <span id="modalMetaText"></span>
                </div>
                <div class="d-flex gap-2 ms-auto">
                    <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button class="btn bg-gradient-primary px-4" id="savePost">
                        <span id="savePostText"><i class="fas fa-save me-2"></i> Save Post</span>
                        <span id="savePostSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span> Saving…
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
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<script>
// ── Globals ──────────────────────────────────────────────────────────────────
let editorInstance, table;
let postId     = null;
let isEditMode = false;

// ── CKEditor ─────────────────────────────────────────────────────────────────
ClassicEditor.create(document.querySelector('#description'))
    .then(e => { editorInstance = e; })
    .catch(console.error);

// ── Dropzone ─────────────────────────────────────────────────────────────────
Dropzone.autoDiscover = false;

const myDropzone = new Dropzone('#postDropzone', {
    url             : '{{ route("posts.mediaUpload") }}',
    autoProcessQueue: false,
    parallelUploads : 5,
    maxFilesize     : 5,
    acceptedFiles   : 'image/*',
    headers         : { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    sending         : (file, xhr, fd) => fd.append('post_id', postId),
    success         : function (file, res) {
        if (res.url) appendImageThumb(res.id, res.url);
        this.removeFile(file);
    },
    error: (file, msg) => console.error('Dropzone error:', msg),
});

// ── Helpers ───────────────────────────────────────────────────────────────────
function appendImageThumb(id, url) {
    $('#existingImages').append(`
        <div class="img-wrap" id="media-wrap-${id}">
            <a href="${url}" data-fancybox="edit-gallery">
                <img src="${url}" alt="image">
            </a>
            <button type="button" class="btn btn-danger btn-del-media" data-id="${id}" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>`);
}

/*
|--------------------------------------------------------------------------
| Tab switching
|--------------------------------------------------------------------------
*/
var fieldTabMap = {
    title: 'tab-basic', description: 'tab-basic',
    category_id: 'tab-basic', status: 'tab-basic',
    latitude: 'tab-location', longitude: 'tab-location',
    meta_title: 'tab-seo', meta_description: 'tab-seo', keywords: 'tab-seo',
};

$(document).on('click', '#postModalTabs .nav-link', function () {
    var target = $(this).data('tab');
    $('#postModalTabs .nav-link').removeClass('active');
    $(this).addClass('active');
    $('.modal-tab-pane').addClass('d-none');
    $('#' + target).removeClass('d-none');
});

function switchToTab(tabId) {
    $('#postModalTabs .nav-link[data-tab="' + tabId + '"]').trigger('click');
}

/*
|--------------------------------------------------------------------------
| Error helpers
|--------------------------------------------------------------------------
*/
function clearErrors() {
    $('.text-danger[id^="err_"]').addClass('d-none').text('');
    $('.form-control, .form-select').removeClass('is-invalid');
    $('#postModalTabs .tab-err-dot').remove();
}

function showErrors(errors) {
    var firstTab = null, tabsWithErrors = {};
    $.each(errors, function (field, msgs) {
        $('#err_' + field).removeClass('d-none').text(msgs[0]);
        $('#post_' + field).addClass('is-invalid');
        var tab = fieldTabMap[field] || 'tab-basic';
        tabsWithErrors[tab] = true;
        if (!firstTab) firstTab = tab;
    });
    $.each(tabsWithErrors, function (tab) {
        var link = $('#postModalTabs .nav-link[data-tab="' + tab + '"]');
        if (!link.find('.tab-err-dot').length) {
            link.append('<span class="tab-err-dot"></span>');
        }
    });
    if (firstTab) switchToTab(firstTab);
}

/*
|--------------------------------------------------------------------------
| Reset modal
|--------------------------------------------------------------------------
*/
function resetModal() {
    isEditMode = false; postId = null;
    clearErrors();
    switchToTab('tab-basic');

    // Header
    $('#postModalTitle').text('Create Post');
    $('#postModalSubtitle').text('Fill in the details below');
    $('#modalIcon').removeClass('bg-gradient-warning').addClass('bg-gradient-primary');
    $('#modalMeta').addClass('d-none');
    $('#savePostText').html('<i class="fas fa-plus me-2"></i> Create Post');

    // Fields – Basic
    $('#post_id, #post_title, #post_expiry_date').val('');
    $('#post_category_id, #post_locality_id, #post_user_id').val('');
    $('#post_subcategory_id').html('<option value="">— Select Subcategory —</option>');
    $('#post_status').val('draft');
    $('#post_is_featured').prop('checked', false);
    $('#post_is_active').prop('checked', true);
    editorInstance && editorInstance.setData('');

    // Fields – Location
    $('#post_country, #post_state, #post_city, #post_location').val('');
    $('#post_latitude, #post_longitude, #post_google_map_url').val('');

    // Fields – Media
    $('#existingImages').empty();
    $('#post_video').val('');
    $('#post_video_url').val('');
    myDropzone.removeAllFiles(true);

    // Fields – SEO
    $('#post_meta_title, #post_meta_description, #post_keywords').val('');
}

/*
|--------------------------------------------------------------------------
| Open CREATE
|--------------------------------------------------------------------------
*/
$('#addPostBtn').on('click', resetModal);

$('#postModal').on('hidden.bs.modal', function () {
    resetModal();
});

/*
|--------------------------------------------------------------------------
| Category → Subcategory cascade
|--------------------------------------------------------------------------
*/
$(document).on('change', '#post_category_id', function () {
    var id = $(this).val();
    $('#post_subcategory_id').html('<option value="">— Select Subcategory —</option>');
    if (!id) return;
    $.get('{{ url("admin/get-subcategories") }}/' + id, function (res) {
        var html = '<option value="">— Select Subcategory —</option>';
        res.forEach(item => { html += '<option value="' + item.id + '">' + item.name + '</option>'; });
        $('#post_subcategory_id').html(html);
    });
});

/*
|--------------------------------------------------------------------------
| Open EDIT
|--------------------------------------------------------------------------
*/
$(document).on('click', '.editPost', function () {
    var id = $(this).data('id');
    resetModal();

    $.get('{{ url("admin/posts") }}/' + id + '/edit-data', function (res) {
        isEditMode = true;
        postId     = res.id;

        // Header
        $('#post_id').val(res.id);
        $('#postModalTitle').text('Edit Post');
        $('#postModalSubtitle').text('Editing post #' + res.id);
        $('#modalIcon').removeClass('bg-gradient-primary').addClass('bg-gradient-warning');
        $('#savePostText').html('<i class="fas fa-save me-2"></i> Save Changes');

        // Meta footer
        if (res.updated_at) {
            $('#modalMeta').removeClass('d-none');
            $('#modalMetaText').text('Last updated: ' + res.updated_at);
        }

        // Basic
        $('#post_title').val(res.title);
        $('#post_status').val(res.status);
        $('#post_user_id').val(res.user_id);
        $('#post_locality_id').val(res.locality_id);
        $('#post_expiry_date').val(res.expiry_date ?? '');
        $('#post_is_featured').prop('checked', !!res.is_featured);
        $('#post_is_active').prop('checked',   !!res.is_active);
        editorInstance.setData(res.description ?? '');

        // Category cascade then set subcategory
        if (res.category_id) {
            $('#post_category_id').val(res.category_id).trigger('change');
            setTimeout(() => $('#post_subcategory_id').val(res.subcategory_id), 450);
        }

        // Location
        $('#post_country').val(res.country       ?? '');
        $('#post_state').val(res.state           ?? '');
        $('#post_city').val(res.city             ?? '');
        $('#post_location').val(res.location     ?? '');
        $('#post_latitude').val(res.latitude     ?? '');
        $('#post_longitude').val(res.longitude   ?? '');
        $('#post_google_map_url').val(res.google_map_url ?? '');

        // Media – existing images
        if (res.images && res.images.length) {
            res.images.forEach(img => appendImageThumb(img.id, img.url));
        }
        $('#post_video_url').val(res.video_url ?? '');

        // SEO
        $('#post_meta_title').val(res.meta_title       ?? '');
        $('#post_meta_description').val(res.meta_description ?? '');
        $('#post_keywords').val(res.keywords           ?? '');

        $('#postModal').modal('show');
    }).fail(function () {
        Swal.fire('Error', 'Could not load post data.', 'error');
    });
});

/*
|--------------------------------------------------------------------------
| Save (create or update)
|--------------------------------------------------------------------------
*/
$('#savePost').on('click', function () {
    clearErrors();
    $('#savePostText').addClass('d-none');
    $('#savePostSpinner').removeClass('d-none');
    $('#savePost').prop('disabled', true);

    var url    = isEditMode ? '{{ url("admin/posts") }}/' + postId : '{{ route("posts.ajaxStore") }}';
    var method = isEditMode ? 'PUT' : 'POST';

    $.ajax({
        url  : url,
        type : 'POST',
        data : {
            _token           : '{{ csrf_token() }}',
            _method          : method,
            title            : $('#post_title').val(),
            user_id          : $('#post_user_id').val(),
            category_id      : $('#post_category_id').val(),
            subcategory_id   : $('#post_subcategory_id').val(),
            locality_id      : $('#post_locality_id').val(),
            status           : $('#post_status').val(),
            expiry_date      : $('#post_expiry_date').val(),
            is_featured      : $('#post_is_featured').is(':checked') ? 1 : 0,
            is_active        : $('#post_is_active').is(':checked')   ? 1 : 0,
            description      : editorInstance.getData(),
            // Location
            country          : $('#post_country').val(),
            state            : $('#post_state').val(),
            city             : $('#post_city').val(),
            location         : $('#post_location').val(),
            latitude         : $('#post_latitude').val(),
            longitude        : $('#post_longitude').val(),
            google_map_url   : $('#post_google_map_url').val(),
            // Media
            video_url        : $('#post_video_url').val(),
            // SEO
            meta_title       : $('#post_meta_title').val(),
            meta_description : $('#post_meta_description').val(),
            keywords         : $('#post_keywords').val(),
        },

        success: function (res) {
            if (!res.success) return;

            postId = res.data.id;

            // Process queued dropzone images now that we have a post ID
            if (myDropzone.getQueuedFiles().length) {
                myDropzone.processQueue();
            }

            $('#postModal').modal('hide');
            table.ajax.reload(null, false);

            Swal.fire({
                icon : 'success',
                title: isEditMode ? 'Post updated!' : 'Post created!',
                timer: 1600, showConfirmButton: false,
            });
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                showErrors(xhr.responseJSON.errors ?? {});
            } else {
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        },

        complete: function () {
            $('#savePostText').removeClass('d-none');
            $('#savePostSpinner').addClass('d-none');
            $('#savePost').prop('disabled', false);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Delete media item
|--------------------------------------------------------------------------
*/
$(document).on('click', '.btn-del-media', function () {
    var mediaId = $(this).data('id');
    var $wrap   = $('#media-wrap-' + mediaId);

    Swal.fire({
        title: 'Remove image?', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, remove',
    }).then(function (r) {
        if (!r.isConfirmed) return;
        $.ajax({
            url  : '{{ url("admin/posts/media") }}/' + mediaId,
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (res) { if (res.success) $wrap.remove(); }
        });
    });
});

/*
|--------------------------------------------------------------------------
| Delete post
|--------------------------------------------------------------------------
*/
$(document).on('click', '.deletePost', function () {
    var id    = $(this).data('id');
    var title = $(this).data('title');

    Swal.fire({
        title            : 'Delete Post?',
        html             : 'You are about to delete <strong>' + title + '</strong>.<br>This cannot be undone.',
        icon             : 'warning',
        showCancelButton : true,
        confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url  : '{{ url("admin/posts") }}/' + id,
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (res) {
                if (res.success) {
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false });
                }
            }
        });
    });
});

/*
|--------------------------------------------------------------------------
| Inline status change
|--------------------------------------------------------------------------
*/
$(document).on('change', '.inline-status', function () {
    var $el = $(this), val = $el.val();
    $.post('{{ route("posts.inlineUpdate") }}', {
        _token: '{{ csrf_token() }}',
        id    : $el.data('id'),
        field : 'status',
        value : val,
    }, function (res) {
        if (!res.success) Swal.fire('Error', 'Status update failed', 'error');
    });
});

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/
$('#toggleFilters').on('click', function () { $('#filterPanel').collapse('toggle'); });

$('#applyFilter').on('click', function () { table.ajax.reload(); });

$('#clearFilter').on('click', function () {
    $('#filter_category, #filter_status').val('');
    $('#filter_start, #filter_end, #globalSearch').val('');
    table.search('').ajax.reload();
});

$('#globalSearch').on('keyup', function () { table.search(this.value).draw(); });

$('#filter_status, #filter_category, #filter_start, #filter_end').on('change', function () {
    table.ajax.reload();
});

/*
|--------------------------------------------------------------------------
| DataTable
|--------------------------------------------------------------------------
*/
$(function () {

    table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url : '{{ route("posts.data") }}',
            type: 'POST',
            data: function (d) {
                d._token      = '{{ csrf_token() }}';
                d.status      = $('#filter_status').val();
                d.category_id = $('#filter_category').val();
                d.start_date  = $('#filter_start').val();
                d.end_date    = $('#filter_end').val();
            }
        },
        columns: [
            { data: 'action',      name: 'action',      orderable: false, searchable: false },
            { data: 'title',       name: 'title' },
            { data: 'category',    name: 'category',    orderable: false },
            { data: 'subcategory', name: 'subcategory', orderable: false },
            { data: 'locality',    name: 'locality',    orderable: false },
            { data: 'user',        name: 'user',        orderable: false },
            { data: 'status',      name: 'status',      searchable: false },
            { data: 'expiry',      name: 'expiry',      searchable: false },
            { data: 'created_at',  name: 'created_at',  searchable: false },
        ],
        order      : [[8, 'desc']],
        pageLength : 10,
        lengthMenu : [10, 25, 50, 100],
        orderCellsTop: true,

        drawCallback: function (settings) {
            var json = settings.json ?? {};
            $('#stat-total').text(json.totalCount     ?? '—');
            $('#stat-published').text(json.publishedCount ?? '—');
            $('#stat-draft').text(json.draftCount     ?? '—');
            $('#stat-archived').text(json.archivedCount ?? '—');
            // Reinit fancybox after each draw
            Fancybox.bind('[data-fancybox^="gallery-"]');
        },
    });

    // Auto-open edit if coming back from show page
    var autoId = sessionStorage.getItem('autoEditPostId');
    if (autoId) {
        sessionStorage.removeItem('autoEditPostId');
        setTimeout(function () {
            var $btn = $('.editPost[data-id="' + autoId + '"]');
            if ($btn.length) $btn.trigger('click');
            else {
                resetModal();
                $.get('{{ url("admin/posts") }}/' + autoId + '/edit-data', function (res) {
                    // populate edit modal manually if row not visible
                });
            }
        }, 800);
    }
});
</script>
@endpush