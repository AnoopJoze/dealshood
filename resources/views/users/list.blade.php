@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <style>
        /* ── Modal tabs ──────────────────────────────────── */
        .modal-tab-nav .nav-link {
            color: #6c757d; border-radius: 8px;
            padding: 6px 14px; font-size: .75rem; font-weight: 600;
        }
        .modal-tab-nav .nav-link.active {
            background: linear-gradient(195deg, #42424a, #191919); color: #fff;
        }
        .modal-tab-nav .tab-err-dot {
            display: inline-block; width: 7px; height: 7px;
            background: #ea0606; border-radius: 50%;
            margin-left: 5px; vertical-align: middle;
        }

        /* ── Stat card ────────────────────────────────────── */
        .stat-card { border-radius: 12px; padding: 14px 18px;
                     display: flex; align-items: center; gap: 12px; }
        .stat-card .sc-icon { width: 38px; height: 38px; border-radius: 10px;
                               display: flex; align-items: center; justify-content: center;
                               font-size: .9rem; flex-shrink: 0; }
        .stat-card .sc-val  { font-size: 1.3rem; font-weight: 700; line-height: 1; }
        .stat-card .sc-lbl  { font-size: .65rem; text-transform: uppercase;
                               letter-spacing: .05em; color: #9ca3af; margin-top: 2px; }

        /* ── Status filter pills ──────────────────────────── */
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

        #datatable thead th, #datatable tbody td { vertical-align: middle; }
    </style>
@endpush

<div>

    {{-- ── Stat cards ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm stat-card">
                <div class="sc-icon" style="background:#dbeafe;color:#1d4ed8;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="sc-val text-dark">{{ $stats['total'] }}</div>
                    <div class="sc-lbl">Total Users</div>
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
                <div class="sc-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="sc-val" style="color:#d97706;">{{ $stats['today'] }}</div>
                    <div class="sc-lbl">Joined Today</div>
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
                    <h4 class="mb-1 fw-bold text-dark">Users Management</h4>
                    <p class="text-sm text-muted mb-0">Manage all registered users from here</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light border rounded-3 px-3" id="toggleFilters">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <button class="btn bg-gradient-primary" id="addUserBtn"
                            data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-plus me-1"></i> Add User
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
                            <label class="form-label text-xs fw-semibold text-muted mb-1">Name</label>
                            <input type="text" id="filter_name" class="form-control form-control-sm"
                                   placeholder="Search name…">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">Email</label>
                            <input type="text" id="filter_email" class="form-control form-control-sm"
                                   placeholder="Search email…">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label text-xs fw-semibold text-muted mb-1">Role</label>
                            <select id="filter_role" class="form-select form-select-sm">
                                <option value="">All Roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
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

            {{-- ── Status quick-filter pills ─────────────── --}}
            <div class="pill-filter d-flex flex-wrap gap-2 mb-3" id="statusPills">
                <span class="active" data-status="">
                    <i class="fas fa-th-list"></i> All
                    <strong class="ms-1">{{ $stats['total'] }}</strong>
                </span>
                <span data-status="Active" style="background:#d1fae5;color:#059669;border-color:#a7f3d0;">
                    <i class="fas fa-check-circle"></i> Active
                    <strong class="ms-1">{{ $stats['active'] }}</strong>
                </span>
                <span data-status="Inactive" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;">
                    <i class="fas fa-times-circle"></i> Inactive
                    <strong class="ms-1">{{ $stats['inactive'] }}</strong>
                </span>
            </div>

            {{-- ── Table ──────────────────────────────────── --}}
            <div class="table-responsive">
                <table id="datatable" class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:50px">#</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">User</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Email</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Role</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Status</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Joined</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder text-center" style="width:110px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════
     ADD / EDIT USER MODAL  — 3 tabs
════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalAvatar"
                         class="rounded-circle bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center fw-bold shadow-sm"
                         style="width:44px;height:44px;font-size:1.1rem;flex-shrink:0;">
                        <i class="fas fa-user-plus" id="modalAvatarIcon"></i>
                        <span id="modalAvatarLetter" class="d-none"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="userModalLabel">Add New User</h5>
                        <p class="text-xs text-muted mb-0" id="modalSubtitle">Fill in the details to create a new user</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs --}}
            <div class="px-4 pt-2 pb-0 border-bottom">
                <ul class="nav modal-tab-nav gap-1 pb-2" id="userModalTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-tab="tab-account" href="javascript:void(0)">
                            <i class="fas fa-user me-1"></i> Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="tab-business" href="javascript:void(0)">
                            <i class="fas fa-building me-1"></i> Business
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="tab-address" href="javascript:void(0)">
                            <i class="fas fa-map-marker-alt me-1"></i> Address & Location
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="user_id">

                {{-- ══ TAB 1: Account ══ --}}
                <div class="modal-tab-pane" id="tab-account">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Account Information</p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="user_name" class="form-control" placeholder="Enter full name">
                            <small class="text-danger d-none" id="err_name"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Email <span class="text-danger">*</span></label>
                            <input type="email" id="user_email" class="form-control" placeholder="Enter email">
                            <small class="text-danger d-none" id="err_email"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Password
                                <span class="text-danger" id="pw_required_star">*</span>
                                <span class="text-muted fw-normal d-none" id="pw_optional_hint">(blank = keep current)</span>
                            </label>
                            <div class="input-group">
                                <input type="password" id="user_password" class="form-control" placeholder="Min. 8 characters">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-danger d-none" id="err_password"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Confirm Password
                                <span class="text-danger" id="pwc_required_star">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" id="user_password_confirmation" class="form-control" placeholder="Repeat password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Role <span class="text-danger">*</span></label>
                            <select id="user_role" class="form-select">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="err_role"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Status <span class="text-danger">*</span></label>
                            <select id="user_status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <small class="text-danger d-none" id="err_status"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_phone" class="form-control border-start-0"
                                       placeholder="+971 xx xxx xxxx">
                            </div>
                            <small class="text-danger d-none" id="err_phone"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-whatsapp text-success" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_whatsapp_number" class="form-control border-start-0"
                                       placeholder="+971 xx xxx xxxx">
                            </div>
                            <small class="text-danger d-none" id="err_whatsapp_number"></small>
                        </div>

                    </div>
                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">About</p>
                    <textarea id="user_about_me" class="form-control" rows="3"
                              placeholder="Short bio…"></textarea>
                    <small class="text-danger d-none" id="err_about_me"></small>
                </div>

                {{-- ══ TAB 2: Business ══ --}}
                <div class="modal-tab-pane d-none" id="tab-business">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Business Information</p>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Company Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-building text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_company_name" class="form-control border-start-0"
                                       placeholder="Enter company name">
                            </div>
                            <small class="text-danger d-none" id="err_company_name"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Website</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-globe text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="url" id="user_website" class="form-control border-start-0"
                                       placeholder="https://example.com">
                            </div>
                            <small class="text-danger d-none" id="err_website"></small>
                        </div>

                    </div>
                </div>

                {{-- ══ TAB 3: Address & Location ══ --}}
                <div class="modal-tab-pane d-none" id="tab-address">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Address</p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">City / Region</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-city text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_location" class="form-control border-start-0"
                                       placeholder="City or region">
                            </div>
                            <small class="text-danger d-none" id="err_location"></small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Full Address</label>
                            <textarea id="user_address" class="form-control" rows="2"
                                      placeholder="Street, building, area…"></textarea>
                            <small class="text-danger d-none" id="err_address"></small>
                        </div>

                    </div>
                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">
                        GPS Coordinates <span class="text-muted fw-normal">(optional)</span>
                    </p>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LAT</span>
                                <input type="number" id="user_latitude" class="form-control border-start-0"
                                       placeholder="25.2048" step="any" min="-90" max="90">
                            </div>
                            <small class="text-danger d-none" id="err_latitude"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LNG</span>
                                <input type="number" id="user_longitude" class="form-control border-start-0"
                                       placeholder="55.2708" step="any" min="-180" max="180">
                            </div>
                            <small class="text-danger d-none" id="err_longitude"></small>
                        </div>

                    </div>
                </div>

            </div>{{-- /modal-body --}}

            {{-- Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-primary px-4" id="saveUserBtn">
                    <span id="saveBtnText"><i class="fas fa-save me-2"></i> Save User</span>
                    <span id="saveBtnSpinner" class="d-none">
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

    var editingId    = null;
    var activeStatus = '';

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */
    var table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route("users.getlist") }}',
            type : 'POST',
            data : function (d) {
                d._token     = '{{ csrf_token() }}';
                d.name       = $('#filter_name').val();
                d.email      = $('#filter_email').val();
                d.status     = activeStatus || $('#filter_status').val();
                d.role       = $('#filter_role').val();
                d.start_date = $('#filter_start').val();
                d.end_date   = $('#filter_end').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false,
              render: function (d, t, r, m) { return m.row + 1; } },
            { data: 'name',       name: 'name' },
            { data: 'email',      name: 'email' },
            { data: 'role',       name: 'role',       orderable: false },
            { data: 'status',     name: 'status',     orderable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action',     name: 'action', orderable: false, searchable: false,
              className: 'text-center' },
        ],
        order      : [[5, 'desc']],
        pageLength : 25,
        lengthMenu : [10, 25, 50, 100],
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
        table.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Collapsible filters
    |--------------------------------------------------------------------------
    */
    $('#toggleFilters').on('click', function () { $('#filterPanel').collapse('toggle'); });

    $('#applyFilter').on('click', function () {
        activeStatus = '';
        $('#statusPills span').removeClass('active');
        $('#statusPills span:first').addClass('active');
        table.ajax.reload();
    });

    $('#clearFilter').on('click', function () {
        activeStatus = '';
        $('#filter_name, #filter_email, #filter_start, #filter_end').val('');
        $('#filter_role').val('');
        $('#statusPills span').removeClass('active');
        $('#statusPills span:first').addClass('active');
        table.ajax.reload();
    });

    $('#filterPanel input, #filterPanel select').on('keyup change', function (e) {
        if (e.key === 'Enter' || e.type === 'change') table.ajax.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | Tab switching
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '#userModalTabs .nav-link', function () {
        var target = $(this).data('tab');
        $('#userModalTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        $('.modal-tab-pane').addClass('d-none');
        $('#' + target).removeClass('d-none');
    });

    function switchToTab(tabId) {
        $('#userModalTabs .nav-link[data-tab="' + tabId + '"]').trigger('click');
    }

    /*
    |--------------------------------------------------------------------------
    | Error helpers
    |--------------------------------------------------------------------------
    */
    var fieldTabMap = {
        name: 'tab-account', email: 'tab-account', password: 'tab-account',
        role: 'tab-account', status: 'tab-account', phone: 'tab-account',
        whatsapp_number: 'tab-account', about_me: 'tab-account',
        company_name: 'tab-business', website: 'tab-business',
        location: 'tab-address', address: 'tab-address',
        latitude: 'tab-address', longitude: 'tab-address',
    };

    function clearErrors() {
        $('.text-danger[id^="err_"]').addClass('d-none').text('');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#userModalTabs .tab-err-dot').remove();
    }

    function showErrors(errors) {
        var firstTab = null, tabsWithErrors = {};
        $.each(errors, function (field, messages) {
            var errEl   = $('#err_' + field);
            var inputEl = $('#user_' + field);
            if (errEl.length)   errEl.removeClass('d-none').text(messages[0]);
            if (inputEl.length) inputEl.addClass('is-invalid');
            var tab = fieldTabMap[field] || 'tab-account';
            tabsWithErrors[tab] = true;
            if (!firstTab) firstTab = tab;
        });
        $.each(tabsWithErrors, function (tab) {
            var link = $('#userModalTabs .nav-link[data-tab="' + tab + '"]');
            if (!link.find('.tab-err-dot').length)
                link.append('<span class="tab-err-dot"></span>');
        });
        if (firstTab) switchToTab(firstTab);
    }

    function resetModal() {
        clearErrors();
        $('#user_name, #user_email, #user_phone, #user_whatsapp_number,' +
          '#user_company_name, #user_website, #user_location,' +
          '#user_latitude, #user_longitude').val('');
        $('#user_about_me, #user_address').val('');
        $('#user_password, #user_password_confirmation').val('');
        $('#user_role').val('');
        $('#user_status').val('Active');
        $('#user_id').val('');
        switchToTab('tab-account');
    }

    /*
    |--------------------------------------------------------------------------
    | Create / Edit mode helpers
    |--------------------------------------------------------------------------
    */
    function setCreateMode() {
        editingId = null;
        $('#userModalLabel').text('Add New User');
        $('#modalSubtitle').text('Fill in the details to create a new user');
        $('#modalAvatar').removeClass('bg-gradient-warning').addClass('bg-gradient-primary');
        $('#modalAvatarIcon').removeClass('d-none');
        $('#modalAvatarLetter').addClass('d-none').text('');
        $('#pw_required_star, #pwc_required_star').removeClass('d-none');
        $('#pw_optional_hint').addClass('d-none');
        $('#saveBtnText').html('<i class="fas fa-user-plus me-2"></i> Create User');
    }

    function setEditMode(user) {
        editingId = user.id;
        $('#user_id').val(user.id);
        $('#user_name').val(user.name);
        $('#user_email').val(user.email);
        $('#user_phone').val(user.phone            ?? '');
        $('#user_whatsapp_number').val(user.whatsapp_number ?? '');
        $('#user_about_me').val(user.about_me      ?? '');
        $('#user_status').val(user.status);
        $('#user_role').val(user.role);
        $('#user_company_name').val(user.company_name ?? '');
        $('#user_website').val(user.website        ?? '');
        $('#user_location').val(user.location      ?? '');
        $('#user_address').val(user.address        ?? '');
        $('#user_latitude').val(user.latitude      ?? '');
        $('#user_longitude').val(user.longitude    ?? '');

        $('#userModalLabel').text('Edit User');
        $('#modalSubtitle').text('Editing ' + user.name + ' — leave password blank to keep current');
        $('#modalAvatar').removeClass('bg-gradient-primary').addClass('bg-gradient-warning');
        $('#modalAvatarIcon').addClass('d-none');
        $('#modalAvatarLetter').removeClass('d-none').text(user.name.charAt(0).toUpperCase());
        $('#pw_required_star, #pwc_required_star').addClass('d-none');
        $('#pw_optional_hint').removeClass('d-none');
        $('#saveBtnText').html('<i class="fas fa-save me-2"></i> Save Changes');
    }

    /*
    |--------------------------------------------------------------------------
    | Open CREATE
    |--------------------------------------------------------------------------
    */
    $('#addUserBtn').on('click', function () { resetModal(); setCreateMode(); });

    $('#userModal').on('hidden.bs.modal', function () { resetModal(); setCreateMode(); });

    /*
    |--------------------------------------------------------------------------
    | Open EDIT
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        resetModal();

        $.get('/admin/users/' + id + '/edit-data', function (user) {
            setEditMode(user);
            $('#userModal').modal('show');
        }).fail(function () {
            Swal.fire('Error', 'Could not load user data.', 'error');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Save (create OR update)
    |--------------------------------------------------------------------------
    */
    $('#saveUserBtn').on('click', function () {
        clearErrors();
        $('#saveBtnText').addClass('d-none');
        $('#saveBtnSpinner').removeClass('d-none');
        $('#saveUserBtn').prop('disabled', true);

        var isEdit = (editingId !== null);
        var url    = isEdit
            ? '/admin/users/' + editingId + '/ajax-update'
            : '{{ route("users.ajaxStore") }}';

        $.ajax({
            url  : url,
            type : 'POST',
            data : {
                _token                : '{{ csrf_token() }}',
                name                  : $('#user_name').val(),
                email                 : $('#user_email').val(),
                password              : $('#user_password').val(),
                password_confirmation : $('#user_password_confirmation').val(),
                role                  : $('#user_role').val(),
                status                : $('#user_status').val(),
                phone                 : $('#user_phone').val(),
                whatsapp_number       : $('#user_whatsapp_number').val(),
                about_me              : $('#user_about_me').val(),
                company_name          : $('#user_company_name').val(),
                website               : $('#user_website').val(),
                location              : $('#user_location').val(),
                address               : $('#user_address').val(),
                latitude              : $('#user_latitude').val(),
                longitude             : $('#user_longitude').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#userModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: res.message, timer: 2000, showConfirmButton: false,
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) showErrors(xhr.responseJSON.errors ?? {});
                else Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            },
            complete: function () {
                $('#saveBtnText').removeClass('d-none');
                $('#saveBtnSpinner').addClass('d-none');
                $('#saveUserBtn').prop('disabled', false);
            }
        });
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
            title            : 'Delete User?',
            html             : 'Delete <strong>' + name + '</strong>?<br>'
                             + '<small class="text-muted">This cannot be undone.</small>',
            icon             : 'warning',
            showCancelButton : true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url  : '/admin/users/' + id,
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
                    Swal.fire('Error', 'Could not delete user.', 'error');
                }
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Password visibility toggle
    |--------------------------------------------------------------------------
    */
    $(document).on('click', '.toggle-pw', function () {
        var target = $('#' + $(this).data('target'));
        target.attr('type', target.attr('type') === 'password' ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

});
</script>
@endpush