@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <style>
        /* ── Inline edit ─────────────────────────────────── */
        .inline-edit {
            transition: box-shadow .15s, background .15s;
            border-radius: .4rem !important;
        }
        .inline-edit:focus {
            background: #fff !important;
            box-shadow: 0 0 0 2px #1a56db33 !important;
        }
        .inline-edit.saving { opacity: .5; pointer-events: none; }
        .inline-edit.saved  { background: #ecfdf5 !important; transition: background .3s; }
        .inline-edit.error  { background: #fef2f2 !important; }

        /* ── Status select colour ─────────────────────────── */
        .status-select option[value="1"] { color: #059669; }
        .status-select option[value="0"] { color: #dc2626; }

        /* ── Stat card ────────────────────────────────────── */
        .stat-card { border-radius: 12px; padding: 14px 18px;
                     display: flex; align-items: center; gap: 12px; }
        .stat-card .sc-icon { width: 38px; height: 38px; border-radius: 10px;
                               display: flex; align-items: center; justify-content: center;
                               font-size: .9rem; flex-shrink: 0; }
        .stat-card .sc-val  { font-size: 1.3rem; font-weight: 700; line-height: 1; }
        .stat-card .sc-lbl  { font-size: .65rem; text-transform: uppercase;
                               letter-spacing: .05em; color: #9ca3af; margin-top: 2px; }

        /* ── Category + status filter pills ──────────────── */
        .pill-filter span {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .28rem .85rem; border-radius: 2rem;
            font-size: .72rem; font-weight: 600; cursor: pointer;
            border: 1px solid #e5e7eb; background: #f9fafb;
            transition: all .15s; user-select: none;
        }
        .pill-filter span.active,
        .pill-filter span:hover {
            background: linear-gradient(195deg, #42424a, #191919);
            color: #fff; border-color: transparent;
        }

        #datatable thead th,
        #datatable tbody td { vertical-align: middle; }
    </style>
@endpush

<div>

    {{-- ── Stat cards ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#ede9fe;color:#7c3aed;">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <div class="sc-val text-dark">{{ $stats['total'] }}</div>
                    <div class="sc-lbl">Total Subcategories</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#d1fae5;color:#059669;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="sc-val" style="color:#059669;">{{ $stats['active'] }}</div>
                    <div class="sc-lbl">Active</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#fef2f2;color:#dc2626;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="sc-val" style="color:#dc2626;">{{ $stats['inactive'] }}</div>
                    <div class="sc-lbl">Inactive</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Main card ─────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- Header --}}
        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Subcategories Management</h4>
                    <p class="text-sm text-muted mb-0">
                        Manage all subcategories — click any cell to edit inline
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light border rounded-3 px-3" id="toggleFilters">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <button class="btn bg-gradient-primary"
                            data-bs-toggle="modal" data-bs-target="#subcategoryModal">
                        <i class="fas fa-plus me-1"></i> Add Subcategory
                    </button>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body pt-0 px-4 pb-4">

            {{-- ── Collapsible filters ───────────────────── --}}
            <div id="filterPanel" class="collapse mb-3 pt-3">
                <div class="card card-body border rounded-4 bg-light shadow-none p-3">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">Search</label>
                            <input type="text" id="globalSearch"
                                   class="form-control form-control-sm" placeholder="Subcategory name…">
                        </div>

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
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">From</label>
                            <input type="date" id="filter_start" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">To</label>
                            <input type="date" id="filter_end" class="form-control form-control-sm">
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

            {{-- ── Category quick-filter pills ──────────────── --}}
            <div class="pill-filter d-flex flex-wrap gap-2 mb-3" id="categoryPills">
                <span class="active" data-cat="">
                    <i class="fas fa-th"></i> All
                    <strong class="ms-1">{{ $stats['total'] }}</strong>
                </span>
                @foreach ($categories as $cat)
                    <span data-cat="{{ $cat->id }}">
                        {{ $cat->name }}
                        <strong class="ms-1">{{ $cat->subcategories()->count() }}</strong>
                    </span>
                @endforeach
            </div>

            {{-- ── Status quick-filter pills ─────────────────── --}}
            <div class="pill-filter d-flex flex-wrap gap-2 mb-3" id="statusPills">
                <span class="active" data-status="">
                    <i class="fas fa-th-list"></i> All Status
                </span>
                <span data-status="1" style="background:#d1fae5;color:#059669;border-color:#a7f3d0;">
                    <i class="fas fa-check-circle"></i> Active
                    <strong class="ms-1">{{ $stats['active'] }}</strong>
                </span>
                <span data-status="0" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;">
                    <i class="fas fa-times-circle"></i> Inactive
                    <strong class="ms-1">{{ $stats['inactive'] }}</strong>
                </span>
            </div>

            {{-- ── Table ──────────────────────────────────────── --}}
            <div class="table-responsive">
                <table id="datatable" class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:50px">#</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Subcategory</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Category</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Status</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Created</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder text-center" style="width:60px">Del</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <p class="text-xs text-muted mt-3 mb-0">
                <i class="fas fa-info-circle me-1 text-info"></i>
                Click any <strong>Subcategory</strong>, <strong>Category</strong> or <strong>Status</strong> cell to edit directly — changes save automatically.
            </p>

        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════
     ADD SUBCATEGORY MODAL
════════════════════════════════════════════════════ --}}
<div class="modal fade" id="subcategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Add Subcategory</h5>
                        <p class="text-xs text-muted mb-0">Create a new subcategory under a category</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">

                {{-- Category --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-sm mb-1">
                        Category <span class="text-danger">*</span>
                    </label>
                    <select id="sub_category_id" class="form-select">
                        <option value="">— Select Category —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger d-none" id="err_sub_category_id"></small>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-sm mb-1">
                        Subcategory Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="sub_name" class="form-control"
                           placeholder="e.g. Smartphones, Dresses, Pizza…">
                    <small class="text-danger d-none" id="err_sub_name"></small>
                </div>

                <div class="bg-light rounded-3 p-3">
                    <p class="text-xs text-muted mb-0">
                        <i class="fas fa-info-circle me-1 text-info"></i>
                        The slug is generated automatically. Status defaults to Active.
                    </p>
                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-primary px-4" id="saveSubcategory">
                    <span id="saveSubText"><i class="fas fa-plus me-2"></i> Create Subcategory</span>
                    <span id="saveSubSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span> Saving…
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>

<script>
$(function () {

    var activeCat    = '';
    var activeStatus = '';
    var saveTimeout  = null;

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */
    var table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route("subcategories.data") }}',
            type : 'POST',
            data : function (d) {
                d._token      = '{{ csrf_token() }}';
                d.category_id = activeCat;
                d.status      = activeStatus;
                d.start_date  = $('#filter_start').val();
                d.end_date    = $('#filter_end').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name',        name: 'name' },
            { data: 'category',    name: 'category',  orderable: false },
            { data: 'status',      name: 'status',    orderable: false, searchable: false },
            { data: 'created_at',  name: 'created_at', searchable: false },
            { data: 'action',      name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
        order      : [[1, 'asc']],
        pageLength : 25,
        lengthMenu : [10, 25, 50, 100],
    });

    /*
    |--------------------------------------------------------------------------
    | Category quick-filter pills
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '#categoryPills span', function () {
        $('#categoryPills span').removeClass('active').css({ background: '', color: '', borderColor: '' });
        $(this).addClass('active');
        activeCat = $(this).data('cat');
        // Sync the filter panel dropdown too
        $('#filter_category').val(activeCat);
        table.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Status quick-filter pills
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '#statusPills span', function () {
        $('#statusPills span').removeClass('active').css({ background: '', color: '', borderColor: '' });
        $(this).addClass('active');
        activeStatus = $(this).data('status');
        $('#filter_status').val(activeStatus);
        table.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Collapsible filters
    |--------------------------------------------------------------------------
    */
    $('#toggleFilters').on('click', function () { $('#filterPanel').collapse('toggle'); });

    $('#applyFilter').on('click', function () {
        activeCat    = $('#filter_category').val();
        activeStatus = $('#filter_status').val();
        table.ajax.reload();
    });

    $('#clearFilter').on('click', function () {
        activeCat = activeStatus = '';
        $('#filter_category, #filter_status').val('');
        $('#filter_start, #filter_end, #globalSearch').val('');
        $('#categoryPills span, #statusPills span').removeClass('active');
        $('#categoryPills span:first, #statusPills span:first').addClass('active');
        table.search('').ajax.reload();
    });

    $('#globalSearch').on('keyup', function () { table.search(this.value).draw(); });

    $('#filter_start, #filter_end').on('change', function () { table.ajax.reload(); });

    /*
    |--------------------------------------------------------------------------
    | Inline edit — save on change
    |--------------------------------------------------------------------------
    */
    function inlineSave($el) {
        $el.addClass('saving');

        $.ajax({
            url  : '{{ route("subcategories.inlineUpdate") }}',
            type : 'POST',
            data : {
                _token: '{{ csrf_token() }}',
                id    : $el.data('id'),
                field : $el.data('field'),
                value : $el.val(),
            },
            success: function (res) {
                if (res.success) {
                    $el.removeClass('saving').addClass('saved');
                    setTimeout(function () { $el.removeClass('saved'); }, 1200);
                }
            },
            error: function (xhr) {
                $el.removeClass('saving').addClass('error');
                setTimeout(function () { $el.removeClass('error'); }, 2000);
                var msg = xhr.responseJSON?.errors?.value?.[0] ?? 'Could not save change';
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: msg, timer: 2500, showConfirmButton: false,
                });
            }
        });
    }

    // Selects → save immediately
    $(document).on('change', 'select.inline-edit', function () { inlineSave($(this)); });

    // Text inputs → debounce 600 ms, save on blur
    $(document).on('keyup', 'input.inline-edit', function () {
        clearTimeout(saveTimeout);
        var $el = $(this);
        saveTimeout = setTimeout(function () { inlineSave($el); }, 600);
    });
    $(document).on('blur', 'input.inline-edit', function () {
        clearTimeout(saveTimeout);
        inlineSave($(this));
    });

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.delete-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title            : 'Delete Subcategory?',
            html             : 'Delete <strong>' + name + '</strong>?<br>'
                             + '<small class="text-muted">This cannot be undone.</small>',
            icon             : 'warning',
            showCancelButton : true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url  : '/admin/subcategories/' + id,
                type : 'POST',
                data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },

                success: function (res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success',
                            title: res.message, timer: 1800, showConfirmButton: false,
                        });
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Could not delete subcategory.', 'error');
                }
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Add Subcategory modal — reset on close
    |--------------------------------------------------------------------------
    */
    $('#subcategoryModal').on('hidden.bs.modal', function () {
        $('#sub_name').val('').removeClass('is-invalid');
        $('#sub_category_id').val('');
        $('#err_sub_name, #err_sub_category_id').addClass('d-none').text('');
    });

    // Enter key submits
    $('#sub_name').on('keydown', function (e) {
        if (e.key === 'Enter') $('#saveSubcategory').trigger('click');
    });

    /*
    |--------------------------------------------------------------------------
    | Save new subcategory
    |--------------------------------------------------------------------------
    */
    $('#saveSubcategory').on('click', function () {
        $('#err_sub_name, #err_sub_category_id').addClass('d-none').text('');
        $('#sub_name, #sub_category_id').removeClass('is-invalid');

        $('#saveSubText').addClass('d-none');
        $('#saveSubSpinner').removeClass('d-none');
        $('#saveSubcategory').prop('disabled', true);

        $.ajax({
            url  : '{{ route("subcategories.ajaxStore") }}',
            type : 'POST',
            data : {
                _token      : '{{ csrf_token() }}',
                category_id : $('#sub_category_id').val(),
                name        : $('#sub_name').val(),
            },

            success: function (res) {
                if (res.success) {
                    $('#subcategoryModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: res.message, timer: 2000, showConfirmButton: false,
                    });
                }
            },

            error: function (xhr) {
                var errors = xhr.responseJSON?.errors ?? {};
                if (errors.name) {
                    $('#err_sub_name').removeClass('d-none').text(errors.name[0]);
                    $('#sub_name').addClass('is-invalid');
                }
                if (errors.category_id) {
                    $('#err_sub_category_id').removeClass('d-none').text(errors.category_id[0]);
                    $('#sub_category_id').addClass('is-invalid');
                }
                if (!errors.name && !errors.category_id) {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            },

            complete: function () {
                $('#saveSubText').removeClass('d-none');
                $('#saveSubSpinner').addClass('d-none');
                $('#saveSubcategory').prop('disabled', false);
            }
        });
    });

});
</script>
@endpush
