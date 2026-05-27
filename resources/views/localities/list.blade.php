@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <style>
        /* ── Type badges ──────────────────────────────────── */
        .type-badge-country { background:#dbeafe; color:#1d4ed8; }
        .type-badge-state   { background:#d1fae5; color:#059669; }
        .type-badge-city    { background:#fef3c7; color:#d97706; }
        .type-badge-area    { background:#fce7f3; color:#db2777; }

        /* ── Inline edit inputs/selects ───────────────────── */
        .inline-edit {
            transition: box-shadow .15s, background .15s;
            border-radius: .4rem !important;
        }
        .inline-edit:focus {
            background: #fff !important;
            box-shadow: 0 0 0 2px #1a56db33 !important;
        }
        .inline-edit.saving { opacity: .5; pointer-events: none; }
        .inline-edit.saved  { background: #ecfdf5 !important; }
        .inline-edit.error  { background: #fef2f2 !important; }

        /* ── Status colour ────────────────────────────────── */
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

        /* ── Type filter pills ────────────────────────────── */
        .type-filter span {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .28rem .85rem; border-radius: 2rem;
            font-size: .72rem; font-weight: 600; cursor: pointer;
            border: 1px solid #e5e7eb; background: #f9fafb;
            transition: all .15s; user-select: none;
        }
        .type-filter span.active,
        .type-filter span:hover {
            background: linear-gradient(195deg,#42424a,#191919);
            color: #fff; border-color: transparent;
        }

        #datatable thead th, #datatable tbody td { vertical-align: middle; }
    </style>
@endpush

<div>

    {{-- ── Stat cards ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#dbeafe;color:#1d4ed8;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <div class="sc-val text-dark">{{ $stats['total'] }}</div>
                    <div class="sc-lbl">Total</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
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

        <div class="col-6 col-md-3">
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

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#fce7f3;color:#db2777;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <div class="sc-val text-dark">
                        {{ $stats['country'] }}/{{ $stats['state'] }}/{{ $stats['city'] }}/{{ $stats['area'] }}
                    </div>
                    <div class="sc-lbl">C / S / C / A</div>
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
                    <h4 class="mb-1 fw-bold text-dark">Localities Management</h4>
                    <p class="text-sm text-muted mb-0">
                        Manage countries, states, cities and areas — click any cell to edit inline
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light border rounded-3 px-3" id="toggleFilters">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <button class="btn bg-gradient-primary"
                            data-bs-toggle="modal" data-bs-target="#localityModal">
                        <i class="fas fa-plus me-1"></i> Add Locality
                    </button>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body pt-0 px-4 pb-4">

            {{-- ── Collapsible filters ─────────────────────── --}}
            <div id="filterPanel" class="collapse mb-3 pt-3">
                <div class="card card-body border rounded-4 bg-light shadow-none p-3">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">Search</label>
                            <input type="text" id="globalSearch" class="form-control form-control-sm"
                                   placeholder="Name or parent…">
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

                        <div class="col-md-3 d-flex gap-2 align-items-end">
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

            {{-- ── Type filter pills ─────────────────────────── --}}
            <div class="type-filter d-flex flex-wrap gap-2 mb-3">
                <span class="active" data-type="">
                    <i class="fas fa-globe"></i> All
                </span>
                <span data-type="country" style="background:#dbeafe;color:#1d4ed8;border-color:#bfdbfe;">
                    <i class="fas fa-flag"></i> Country
                    <strong class="ms-1">{{ $stats['country'] }}</strong>
                </span>
                <span data-type="state" style="background:#d1fae5;color:#059669;border-color:#a7f3d0;">
                    <i class="fas fa-map"></i> State
                    <strong class="ms-1">{{ $stats['state'] }}</strong>
                </span>
                <span data-type="city" style="background:#fef3c7;color:#d97706;border-color:#fde68a;">
                    <i class="fas fa-city"></i> City
                    <strong class="ms-1">{{ $stats['city'] }}</strong>
                </span>
                <span data-type="area" style="background:#fce7f3;color:#db2777;border-color:#fbcfe8;">
                    <i class="fas fa-map-pin"></i> Area
                    <strong class="ms-1">{{ $stats['area'] }}</strong>
                </span>
            </div>

            {{-- ── Table ─────────────────────────────────────── --}}
            <div class="table-responsive">
                <table id="datatable" class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:50px">#</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Name</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Type</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Parent</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Status</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Created</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder text-center" style="width:60px">Del</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            {{-- Inline edit hint --}}
            <p class="text-xs text-muted mt-3 mb-0">
                <i class="fas fa-info-circle me-1 text-info"></i>
                Click any <strong>Name</strong>, <strong>Type</strong>, <strong>Parent</strong> or <strong>Status</strong> cell to edit it directly — changes save automatically.
            </p>

        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════
     ADD LOCALITY MODAL
════════════════════════════════════════════════════ --}}
<div class="modal fade" id="localityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Add Locality</h5>
                        <p class="text-xs text-muted mb-0">Create a new country, state, city or area</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-sm mb-1">
                        Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="locality_name" class="form-control"
                           placeholder="e.g. Dubai, United Arab Emirates…">
                    <small class="text-danger d-none" id="err_name"></small>
                </div>

                {{-- Type --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-sm mb-1">
                        Type <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-2">
                        @foreach (['country','state','city','area'] as $t)
                            @php
                                $colors = ['country'=>'primary','state'=>'success','city'=>'warning','area'=>'danger'];
                            @endphp
                            <div class="form-check d-none">
                                <input class="form-check-input" type="radio"
                                       name="locality_type_radio" id="type_{{ $t }}"
                                       value="{{ $t }}">
                            </div>
                            <label for="type_{{ $t }}"
                                   class="type-pill-btn btn btn-sm btn-outline-{{ $colors[$t] }} rounded-pill px-3"
                                   data-value="{{ $t }}">
                                {{ ucfirst($t) }}
                            </label>
                        @endforeach
                    </div>
                    <input type="hidden" id="locality_type">
                    <small class="text-danger d-none" id="err_type"></small>
                </div>

                {{-- Parent --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold text-sm mb-1">Parent Locality</label>
                    <select id="locality_parent_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($localities->groupBy('type') as $type => $group)
                            <optgroup label="{{ ucfirst($type) }}">
                                @foreach ($group as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <small class="text-danger d-none" id="err_parent_id"></small>
                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-primary px-4" id="saveLocality">
                    <span id="saveLocalityText"><i class="fas fa-plus me-2"></i> Add Locality</span>
                    <span id="saveLocalitySpinner" class="d-none">
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

    var activeType  = '';
    var saveTimeout = null;

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */
    var table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route("localities.data") }}',
            type : 'POST',
            data : function (d) {
                d._token     = '{{ csrf_token() }}';
                d.type       = activeType;
                d.status     = $('#filter_status').val();
                d.start_date = $('#filter_start').val();
                d.end_date   = $('#filter_end').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name',        name: 'name' },
            { data: 'type',        name: 'type',    orderable: false },
            { data: 'parent',      name: 'parent',  orderable: false },
            { data: 'status',      name: 'status',  orderable: false, searchable: false },
            { data: 'created_at',  name: 'created_at', searchable: false },
            { data: 'action',      name: 'action',  orderable: false, searchable: false, className: 'text-center' },
        ],
        order      : [[1, 'asc']],
        pageLength : 25,
        lengthMenu : [10, 25, 50, 100],
    });

    /*
    |--------------------------------------------------------------------------
    | Type filter pills
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.type-filter span', function () {
        $('.type-filter span').removeClass('active')
            .css({ background: '', color: '', borderColor: '' });
        $(this).addClass('active');
        activeType = $(this).data('type');
        table.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Collapsible filters
    |--------------------------------------------------------------------------
    */
    $('#toggleFilters').on('click', function () { $('#filterPanel').collapse('toggle'); });
    $('#applyFilter').on('click',   function () { table.ajax.reload(); });
    $('#clearFilter').on('click',   function () {
        $('#filter_status').val('');
        $('#filter_start, #filter_end').val('');
        $('#globalSearch').val('');
        table.search('').ajax.reload();
    });
    $('#globalSearch').on('keyup', function () { table.search(this.value).draw(); });
    $('#filter_status, #filter_start, #filter_end').on('change', function () { table.ajax.reload(); });

    /*
    |--------------------------------------------------------------------------
    | Inline edit — debounced auto-save on input change
    |--------------------------------------------------------------------------
    */
    $(document).on('change', '.inline-edit', function () {
        var $el   = $(this);
        var id    = $el.data('id');
        var field = $el.data('field');
        var value = $el.val();

        $el.addClass('saving');

        $.ajax({
            url  : '{{ route("localities.inlineUpdate") }}',
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', id: id, field: field, value: value },

            success: function (res) {
                if (res.success) {
                    $el.removeClass('saving').addClass('saved');
                    setTimeout(function () { $el.removeClass('saved'); }, 1200);
                }
            },

            error: function () {
                $el.removeClass('saving').addClass('error');
                setTimeout(function () { $el.removeClass('error'); }, 2000);
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'Could not save change', timer: 2000, showConfirmButton: false,
                });
            }
        });
    });

    // For text inputs debounce on keyup, fire on blur
    $(document).on('keyup', '.inline-edit[type="text"]', function () {
        clearTimeout(saveTimeout);
        var $el = $(this);
        saveTimeout = setTimeout(function () { $el.trigger('change'); }, 600);
    });
    $(document).on('blur', '.inline-edit[type="text"]', function () {
        clearTimeout(saveTimeout);
        $(this).trigger('change');
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
            title            : 'Delete Locality?',
            html             : 'Delete <strong>' + name + '</strong>?<br>'
                             + '<small class="text-muted">Child localities will be unlinked (not deleted).</small>',
            icon             : 'warning',
            showCancelButton : true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url  : '/admin/localities/' + id,
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
                error: function () { Swal.fire('Error', 'Could not delete.', 'error'); }
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Type pill buttons inside the Add modal
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.type-pill-btn', function () {
        $('.type-pill-btn').removeClass('active');
        $(this).addClass('active');
        $('#locality_type').val($(this).data('value'));
        $('input[name="locality_type_radio"][value="' + $(this).data('value') + '"]').prop('checked', true);
    });

    /*
    |--------------------------------------------------------------------------
    | Add Locality modal — reset on close
    |--------------------------------------------------------------------------
    */
    $('#localityModal').on('hidden.bs.modal', function () {
        $('#locality_name').val('');
        $('#locality_type').val('');
        $('#locality_parent_id').val('');
        $('.type-pill-btn').removeClass('active');
        $('#err_name, #err_type, #err_parent_id').addClass('d-none').text('');
    });

    /*
    |--------------------------------------------------------------------------
    | Save new locality
    |--------------------------------------------------------------------------
    */
    $('#saveLocality').on('click', function () {
        $('#err_name, #err_type, #err_parent_id').addClass('d-none').text('');

        $('#saveLocalityText').addClass('d-none');
        $('#saveLocalitySpinner').removeClass('d-none');
        $('#saveLocality').prop('disabled', true);

        $.ajax({
            url  : '{{ route("localities.ajaxStore") }}',
            type : 'POST',
            data : {
                _token    : '{{ csrf_token() }}',
                name      : $('#locality_name').val(),
                type      : $('#locality_type').val(),
                parent_id : $('#locality_parent_id').val(),
            },

            success: function (res) {
                if (res.success) {
                    $('#localityModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: res.message, timer: 2000, showConfirmButton: false,
                    });
                }
            },

            error: function (xhr) {
                var errors = xhr.responseJSON?.errors ?? {};
                if (errors.name)      $('#err_name').removeClass('d-none').text(errors.name[0]);
                if (errors.type)      $('#err_type').removeClass('d-none').text(errors.type[0]);
                if (errors.parent_id) $('#err_parent_id').removeClass('d-none').text(errors.parent_id[0]);
            },

            complete: function () {
                $('#saveLocalityText').removeClass('d-none');
                $('#saveLocalitySpinner').addClass('d-none');
                $('#saveLocality').prop('disabled', false);
            }
        });
    });

});
</script>
@endpush