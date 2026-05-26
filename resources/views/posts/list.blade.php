@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
    <style>
        #datatable thead th           { white-space: nowrap; vertical-align: middle; }
        #datatable tbody td           { vertical-align: middle; }

        /* Dropzone */
        .dropzone                     { border: 2px dashed #dee2e6; border-radius: .75rem;
                                        background: #f8f9fa; min-height: 80px; padding: 1rem; }
        .dropzone:hover               { border-color: #6c757d; }
        .dropzone .dz-message         { margin: .5em 0; font-size: .9rem; color: #6c757d; }

        /* Existing-image strip in edit modal */
        .img-strip                    { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .5rem; }
        .img-strip .img-wrap          { position: relative; }
        .img-strip img                { width: 52px; height: 52px; object-fit: cover;
                                        border-radius: .4rem; border: 2px solid #dee2e6; cursor: pointer; }
        .img-strip .btn-del-media     { position: absolute; top: -6px; right: -6px;
                                        width: 18px; height: 18px; padding: 0; font-size: 9px;
                                        border-radius: 50%; line-height: 18px; text-align: center; }

        /* Stat pills */
        .badge-published              { background: #d1fae5; color: #065f46; }
        .badge-draft                  { background: #f3f4f6; color: #374151; }
    </style>
@endpush

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
                    <button class="btn bg-gradient-primary" id="addPostBtn"
                            data-bs-toggle="modal" data-bs-target="#postModal">
                        <i class="fas fa-plus me-1"></i> Add Post
                    </button>
                </div>
            </div>

            <div class="card-body pt-0 px-4 pb-4">

                {{-- Stats + quick search --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group input-group-outline">
                            <span class="input-group-text bg-gray-100 border-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="globalSearch"
                                   class="form-control border-0 bg-gray-100"
                                   placeholder="Quick search…">
                        </div>
                    </div>
                    <div class="col-md-8 d-flex flex-wrap justify-content-md-end align-items-center gap-2">
                        <div class="badge bg-light text-dark px-3 py-2 rounded-pill" id="stat-total">Total: —</div>
                        <div class="badge px-3 py-2 rounded-pill badge-published" id="stat-published">Published: —</div>
                        <div class="badge px-3 py-2 rounded-pill badge-draft" id="stat-draft">Draft: —</div>
                    </div>
                </div>

                {{-- DataTable --}}
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder ps-2">Action</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Title</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Category</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Subcategory</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Locality</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">User</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Images</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Status</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Expiry</th>
                                <th class="text-uppercase text-secondary text-xxs fw-bolder">Created</th>
                            </tr>
                            {{-- Per-column filters --}}
                            <tr>
                                <th></th>
                                <th><input type="text" class="form-control form-control-sm border col-filter" data-col="1" placeholder="Title"></th>
                                <th><input type="text" class="form-control form-control-sm border col-filter" data-col="2" placeholder="Category"></th>
                                <th><input type="text" class="form-control form-control-sm border col-filter" data-col="3" placeholder="Subcategory"></th>
                                <th><input type="text" class="form-control form-control-sm border col-filter" data-col="4" placeholder="Locality"></th>
                                <th><input type="text" class="form-control form-control-sm border col-filter" data-col="5" placeholder="User"></th>
                                <th></th>
                                <th>
                                    <select id="statusFilter" class="form-select form-select-sm border">
                                        <option value="">All</option>
                                        <option value="published">Published</option>
                                        <option value="draft">Draft</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </th>
                                <th></th>
                                <th>
                                    <div class="d-flex gap-1">
                                        <input type="date" id="start_date" class="form-control form-control-sm border">
                                        <input type="date" id="end_date"   class="form-control form-control-sm border">
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════
     CREATE / EDIT POST MODAL
     ══════════════════════════════════ --}}
<div class="modal fade" id="postModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-white border-bottom py-3">
                <h5 class="modal-title fw-bold" id="postModalTitle">Create Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="post_id">

                <div class="row g-3">

                    {{-- Title --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" class="form-control" placeholder="Post title…">
                        <small class="text-danger d-none" id="title_error"></small>
                    </div>

                    {{-- Assigned user --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Assigned User</label>
                        <select id="user_id" class="form-select">
                            <option value="">— Select User —</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <select id="category_id" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger d-none" id="category_id_error"></small>
                    </div>

                    {{-- Subcategory (populated via AJAX) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Subcategory</label>
                        <select id="subcategory_id" class="form-select">
                            <option value="">— Select —</option>
                        </select>
                    </div>

                    {{-- Locality --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Locality</label>
                        <select id="locality_id" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($localities as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select id="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    {{-- Expiry date  ← field name: expiry_date (matches model fillable) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Expiry Date</label>
                        <input type="date" id="expiry_date" class="form-control">
                    </div>

                    {{-- Map / Google Maps URL  ← field name: google_map_url (matches model fillable) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Google Maps URL</label>
                        <input type="text" id="google_map_url" class="form-control"
                               placeholder="https://maps.google.com/…  or  lat,lng">
                    </div>

                    {{-- Description (CKEditor) --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea id="description" class="form-control" rows="4"></textarea>
                    </div>

                    {{-- Existing images (edit mode) --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Images</label>
                        <div id="existingImages" class="img-strip"></div>

                        {{-- Dropzone  ← action: posts.mediaUpload --}}
                        <form action="{{ route('posts.mediaUpload') }}"
                              class="dropzone" id="postDropzone">
                            @csrf
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                Drop images here or click to upload
                            </div>
                        </form>
                    </div>

                    {{-- Video file --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Upload Video</label>
                        <input type="file" id="video" class="form-control" accept="video/*">
                    </div>

                    {{-- Video URL (e.g. YouTube) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Video URL</label>
                        <input type="text" id="video_url" class="form-control"
                               placeholder="https://youtube.com/…">
                    </div>

                    {{-- Toggles --}}
                    <div class="col-12 d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured">
                            <label class="form-check-label" for="is_featured">Featured</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                </div>{{-- /row --}}
            </div>

            <div class="modal-footer bg-white border-top">
                <button class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn bg-gradient-primary" id="savePost">
                    <i class="fas fa-save me-1"></i>
                    <span id="savePostLabel">Save Post</span>
                </button>
            </div>

        </div>
    </div>
</div>


{{-- ═══════════════════════════
     DELETE CONFIRM MODAL
     ═══════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Post
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-0">Are you sure you want to delete this post? This cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger px-4" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
// ── CKEditor ──────────────────────────────────────────────────────────────────
CKEDITOR.replace('description', { height: 200 });

// ── Dropzone ──────────────────────────────────────────────────────────────────
Dropzone.autoDiscover = false;

let postId         = null;
let isEditMode     = false;
let deleteTargetId = null;

const myDropzone = new Dropzone('#postDropzone', {
    url             : '{{ route("posts.mediaUpload") }}',
    autoProcessQueue: false,
    uploadMultiple  : false,
    parallelUploads : 5,
    maxFilesize     : 5,            // MB
    acceptedFiles   : 'image/*',
    headers         : { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    sending         : (file, xhr, fd) => fd.append('post_id', postId),
    success         : function (file, res) {
        if (res.url) appendImageThumb(res.id, res.url);
        this.removeFile(file);     // clean dropzone preview after upload
    },
    error           : (file, msg) => console.error('Dropzone error:', msg),
});

// ── Helpers ───────────────────────────────────────────────────────────────────
function appendImageThumb(id, url) {
    $('#existingImages').append(`
        <div class="img-wrap" id="media-wrap-${id}">
            <a href="${url}" data-fancybox="edit-gallery">
                <img src="${url}" alt="image">
            </a>
            <button type="button"
                    class="btn btn-danger btn-del-media"
                    data-id="${id}"
                    title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>`);
}

function resetModal() {
    isEditMode = false;
    postId     = null;
    $('#post_id').val('');
    $('#postModalTitle').text('Create Post');
    $('#savePostLabel').text('Save Post');

    // clear all fields
    $('#title, #expiry_date, #google_map_url, #video_url').val('');
    $('#user_id, #category_id, #locality_id').val('');
    $('#subcategory_id').html('<option value="">— Select —</option>');
    $('#status').val('draft');
    $('#is_featured').prop('checked', false);
    $('#is_active').prop('checked', true);
    CKEDITOR.instances.description.setData('');
    $('#existingImages').empty();
    myDropzone.removeAllFiles(true);

    // clear any visible validation errors
    $('.text-danger[id$="_error"]').text('').addClass('d-none');
}

// ── Open for CREATE ───────────────────────────────────────────────────────────
$('#addPostBtn').on('click', resetModal);

// ── Category → Subcategory cascade ───────────────────────────────────────────
$('#category_id').on('change', function () {
    const id = $(this).val();
    $('#subcategory_id').html('<option value="">— Select —</option>');
    if (!id) return;

    $.get('{{ url("admin/get-subcategories") }}/' + id, function (res) {
        let html = '<option value="">— Select —</option>';
        res.forEach(item => { html += `<option value="${item.id}">${item.name}</option>`; });
        $('#subcategory_id').html(html);
    });
});

// ── Save Post (Create OR Update) ──────────────────────────────────────────────
$('#savePost').on('click', function () {

    const $btn = $(this).prop('disabled', true);
    $('.text-danger[id$="_error"]').text('').addClass('d-none');

    // For update:  PUT /admin/posts/{id}  →  posts.update  (resource route)
    // For create:  POST /admin/posts/ajax-store  →  posts.ajaxStore
    const url    = isEditMode
        ? '{{ url("admin/posts") }}/' + postId     // resource: posts.update (PUT)
        : '{{ route("posts.ajaxStore") }}';

    const method = isEditMode ? 'PUT' : 'POST';

    $.ajax({
        url,
        type   : 'POST',    // always POST; Laravel reads _method for spoofing
        data   : {
            _token        : '{{ csrf_token() }}',
            _method       : method,
            title         : $('#title').val(),
            user_id       : $('#user_id').val(),
            category_id   : $('#category_id').val(),
            subcategory_id: $('#subcategory_id').val(),
            locality_id   : $('#locality_id').val(),
            status        : $('#status').val(),
            expiry_date   : $('#expiry_date').val(),
            google_map_url: $('#google_map_url').val(),   // ← matches model fillable
            video_url     : $('#video_url').val(),
            is_featured   : $('#is_featured').is(':checked') ? 1 : 0,
            is_active     : $('#is_active').is(':checked')   ? 1 : 0,
            description   : CKEDITOR.instances.description.getData(),
        },
        success: function (res) {
            if (!res.success) { $btn.prop('disabled', false); return; }

            postId = res.data.id;

            if (myDropzone.getQueuedFiles().length) {
                myDropzone.processQueue();   // images upload AFTER post is saved
            }

            $('#postModal').modal('hide');
            table.ajax.reload(null, false);

            Swal.fire({
                icon : 'success',
                title: isEditMode ? 'Post updated!' : 'Post created!',
                timer: 1500,
                showConfirmButton: false,
            });
        },
        error  : function (xhr) {
            const errors = xhr.responseJSON?.errors ?? {};
            $.each(errors, (field, msgs) => {
                $(`#${field}_error`).text(msgs[0]).removeClass('d-none');
            });
            $btn.prop('disabled', false);
        },
        complete: () => $btn.prop('disabled', false),
    });
});

// ── Open for EDIT ─────────────────────────────────────────────────────────────
// Calls GET /admin/posts/{post}/edit-data  →  posts.editData
$(document).on('click', '.editPost', function () {

    resetModal();
    const id = $(this).data('id');

    $.get('{{ url("admin/posts") }}/' + id + '/edit-data', function (res) {

        isEditMode = true;
        postId     = res.id;

        $('#post_id').val(res.id);
        $('#postModalTitle').text('Edit Post');
        $('#savePostLabel').text('Update Post');

        $('#title').val(res.title);
        $('#user_id').val(res.user_id);
        $('#locality_id').val(res.locality_id);
        $('#status').val(res.status);
        $('#expiry_date').val(res.expiry_date ?? '');
        $('#google_map_url').val(res.google_map_url ?? '');
        $('#video_url').val(res.video_url ?? '');
        $('#is_featured').prop('checked', !!res.is_featured);
        $('#is_active').prop('checked', !!res.is_active);
        CKEDITOR.instances.description.setData(res.description ?? '');

        // Category cascade, then set subcategory after AJAX resolves
        if (res.category_id) {
            $('#category_id').val(res.category_id).trigger('change');
            setTimeout(() => $('#subcategory_id').val(res.subcategory_id), 450);
        }

        // Existing images
        if (res.images?.length) {
            res.images.forEach(img => appendImageThumb(img.id, img.url));
        }

        $('#postModal').modal('show');
    });
});

// ── Delete a media item ───────────────────────────────────────────────────────
// Calls DELETE /admin/posts/media/{id}  →  posts.mediaDelete
$(document).on('click', '.btn-del-media', function () {
    const mediaId = $(this).data('id');
    const $wrap   = $(`#media-wrap-${mediaId}`);

    if (!confirm('Remove this image?')) return;

    $.ajax({
        url   : '{{ url("admin/posts/media") }}/' + mediaId,
        type  : 'POST',
        data  : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function (res) { if (res.success) $wrap.remove(); },
    });
});

// ── Delete post ───────────────────────────────────────────────────────────────
$(document).on('click', '.deletePost', function () {
    deleteTargetId = $(this).data('id');
    $('#deleteModal').modal('show');
});

// Calls DELETE /admin/posts/{post}  →  posts.destroy
$('#confirmDelete').on('click', function () {
    if (!deleteTargetId) return;

    $.ajax({
        url   : '{{ url("admin/posts") }}/' + deleteTargetId,
        type  : 'POST',
        data  : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function (res) {
            if (res.success) {
                $('#deleteModal').modal('hide');
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false });
            }
        },
    });
});

// ── Inline status change ──────────────────────────────────────────────────────
$(document).on('change', '.inline-status', function () {
    $.post('{{ route("posts.inlineUpdate") }}', {
        _token: '{{ csrf_token() }}',
        id    : $(this).data('id'),
        field : 'status',
        value : $(this).val(),
    }, function (res) {
        if (!res.success) alert('Status update failed');
    });
});

// ── DataTable ─────────────────────────────────────────────────────────────────
let table;

$(function () {

    table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url : '{{ route("posts.data") }}',
            type: 'POST',
            data: function (d) {
                d._token     = '{{ csrf_token() }}';
                d.status     = $('#statusFilter').val();
                d.start_date = $('#start_date').val();
                d.end_date   = $('#end_date').val();
            },
        },
        columns: [
            { data: 'action',      name: 'action',      orderable: false, searchable: false },
            { data: 'title',       name: 'title' },
            { data: 'category',    name: 'category',    orderable: false },
            { data: 'subcategory', name: 'subcategory', orderable: false },
            { data: 'locality',    name: 'locality',    orderable: false },
            { data: 'user',        name: 'user',        orderable: false },
            { data: 'images',      name: 'images',      orderable: false, searchable: false },
            { data: 'status',      name: 'status',      searchable: false },
            { data: 'expires_at',  name: 'expires_at',  searchable: false },
            { data: 'created_at',  name: 'created_at',  searchable: false },
        ],
        order      : [[9, 'desc']],
        pageLength : 25,
        lengthMenu : [10, 25, 50, 100],
        orderCellsTop: true,
        fixedHeader  : false,

        // Update the live stat pills from the DataTables response
        drawCallback: function (settings) {
            const json = settings.json ?? {};
            $('#stat-total').text('Total: '      + (json.recordsTotal     ?? '—'));
            $('#stat-published').text('Published: ' + (json.publishedCount ?? '—'));
            $('#stat-draft').text('Draft: '      + (json.draftCount       ?? '—'));
        },
    });

    // Per-column text filters
    $('#datatable thead tr:eq(1) .col-filter').on('keyup change', function () {
        table.column($(this).data('col')).search(this.value).draw();
    });

    // Global search box
    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Status dropdown + date range filters
    $('#statusFilter, #start_date, #end_date').on('change', function () {
        table.ajax.reload(null, false);
    });
});
$(function () {
    const autoId = sessionStorage.getItem('autoEditPostId');
    if (autoId) {
        sessionStorage.removeItem('autoEditPostId');
        // Wait for DataTable to initialise, then fire the edit click
        setTimeout(function () {
            // If the row is visible just click its edit button
            const $btn = $('.editPost[data-id="' + autoId + '"]');
            if ($btn.length) {
                $btn.trigger('click');
            } else {
                // Row may be on a different page — fetch directly
                resetModal();
                $.get('{{ url("admin/posts") }}/' + autoId + '/edit-data', function (res) {
                    // same as the .editPost handler in list.blade.php
                    // (copy the body of that handler here if needed)
                });
            }
        }, 800);
    }
});

</script>
@endpush