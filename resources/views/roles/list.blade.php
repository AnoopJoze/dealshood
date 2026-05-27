@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <style>
        .modal-tab-nav .nav-link {
            color:#6c757d; border-radius:8px; padding:6px 14px; font-size:.75rem; font-weight:600;
        }
        .modal-tab-nav .nav-link.active {
            background:linear-gradient(195deg,#42424a,#191919); color:#fff;
        }
        .modal-tab-nav .tab-err-dot {
            display:inline-block; width:7px; height:7px;
            background:#ea0606; border-radius:50%; margin-left:5px; vertical-align:middle;
        }

        /* Permission checkboxes */
        .perm-group-title {
            font-size:.68rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.06em; color:#6b7280; margin-bottom:.5rem;
            padding-bottom:.4rem; border-bottom:1px solid #f0f0f0;
        }
        .perm-item {
            display:flex; align-items:center; gap:.5rem;
            padding:.3rem .5rem; border-radius:.4rem;
            cursor:pointer; transition:background .15s;
        }
        .perm-item:hover { background:#f9fafb; }
        .perm-item label { cursor:pointer; font-size:.82rem; margin:0; }
        .perm-item input[type=checkbox] { width:15px; height:15px; cursor:pointer; flex-shrink:0; }
    </style>
@endpush

<div>

    {{-- ── Stat strip ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" id="stat-roles" style="font-size:1.4rem;">—</div>
                    <div class="text-xs text-muted text-uppercase">Total Roles</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-success text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" id="stat-perms" style="font-size:1.4rem;">
                        {{ \Spatie\Permission\Models\Permission::count() }}
                    </div>
                    <div class="text-xs text-muted text-uppercase">Total Permissions</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-warning text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;">
                        {{ \App\Models\User::count() }}
                    </div>
                    <div class="text-xs text-muted text-uppercase">Total Users</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Main card ─────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Roles Management</h4>
                    <p class="text-sm text-muted mb-0">Create and manage roles and their permissions</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('permissions.index') }}" class="btn btn-light border rounded-3">
                        <i class="fas fa-key me-2"></i>Permissions
                    </a>
                    <button class="btn bg-gradient-primary" id="addRoleBtn"
                            data-bs-toggle="modal" data-bs-target="#roleModal">
                        <i class="fas fa-plus me-1"></i> Add Role
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body pt-0 px-4 pb-4">
            <div class="table-responsive">
                <table id="datatable" class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:50px">#</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Role Name</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Permissions</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Users</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Created</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════
     ROLE MODAL  (create / edit)
════════════════════════════════════════════════════ --}}
<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="roleModalIcon"
                         class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="roleModalTitle">Create Role</h5>
                        <p class="text-xs text-muted mb-0" id="roleModalSubtitle">Set role name and assign permissions</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs --}}
            <div class="px-4 pt-2 pb-0 border-bottom">
                <ul class="nav modal-tab-nav gap-1 pb-2" id="roleModalTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-tab="rtab-info" href="javascript:void(0)">
                            <i class="fas fa-tag me-1"></i> Role Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="rtab-perms" href="javascript:void(0)">
                            <i class="fas fa-key me-1"></i> Permissions
                        </a>
                    </li>
                </ul>
            </div>

            <div class="modal-body px-4 py-3">
                <input type="hidden" id="role_id">

                {{-- ── TAB 1: Role Info ── --}}
                <div class="modal-tab-pane" id="rtab-info">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Role Details</p>
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Role Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="role_name" class="form-control"
                                   placeholder="e.g. admin, editor, moderator">
                            <small class="text-muted text-xs">Lowercase, no spaces (use underscores)</small>
                            <small class="text-danger d-none" id="err_role_name"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Guard</label>
                            <select id="role_guard" class="form-select">
                                <option value="web">web</option>
                                <option value="api">api</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="bg-light rounded-3 p-3">
                                <p class="text-xs fw-semibold text-muted mb-1">
                                    <i class="fas fa-info-circle me-1 text-info"></i> Naming Convention
                                </p>
                                <p class="text-xs text-muted mb-0">
                                    Use the format <code>module_action</code> for consistency.
                                    Examples: <code>admin</code>, <code>editor</code>, <code>moderator</code>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── TAB 2: Permissions ── --}}
                <div class="modal-tab-pane d-none" id="rtab-perms">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="text-xs fw-bold text-uppercase text-secondary mb-0">Assign Permissions</p>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3" id="checkAll">
                                <i class="fas fa-check-square me-1"></i> All
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3" id="uncheckAll">
                                <i class="fas fa-square me-1"></i> None
                            </button>
                        </div>
                    </div>

                    <div id="permissionsContainer" class="row g-3">
                        @foreach ($permissions as $group => $group_permissions)
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="perm-group-title mb-0">
                                            <i class="fas fa-folder me-1"></i>{{ $group }}
                                        </div>
                                        <button type="button"
                                                class="btn btn-xs btn-light border rounded-2 px-2 py-1 toggle-group text-xs"
                                                data-group="{{ $group }}"
                                                style="font-size:.68rem;">
                                            Toggle
                                        </button>
                                    </div>
                                    @foreach ($group_permissions as $perm)
                                        <div class="perm-item">
                                            <input type="checkbox"
                                                   class="perm-checkbox form-check-input"
                                                   id="perm_{{ $perm->id }}"
                                                   name="permissions[]"
                                                   value="{{ $perm->id }}"
                                                   data-group="{{ $group }}">
                                            <label for="perm_{{ $perm->id }}" class="text-sm">
                                                {{ str_replace('_', ' ', $perm->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <small class="text-danger d-none mt-2" id="err_role_perms"></small>

                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <div class="text-xs text-muted me-auto" id="rolePermCount"></div>
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-primary px-4" id="saveRoleBtn">
                    <span id="saveRoleText"><i class="fas fa-save me-2"></i> Save Role</span>
                    <span id="saveRoleSpinner" class="d-none">
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

    var editingId = null;

    /*── DataTable ────────────────────────────────────────*/
    var table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route("roles.getlist") }}',
            type : 'POST',
            data : function (d) { d._token = '{{ csrf_token() }}'; }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false,
              render: function (d, t, r, m) { return m.row + 1; } },
            { data: 'name',              name: 'name' },
            { data: 'permissions_count', name: 'permissions_count', orderable: false },
            { data: 'users_count',       name: 'users_count',       orderable: false },
            { data: 'created_at',        name: 'created_at',        searchable: false },
            { data: 'action',            name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
        order      : [[4, 'desc']],
        pageLength : 25,
        drawCallback: function (s) {
            $('#stat-roles').text(s.json?.recordsTotal ?? '—');
        }
    });

    /*── Tabs ─────────────────────────────────────────────*/
    $(document).on('click', '#roleModalTabs .nav-link', function () {
        var t = $(this).data('tab');
        $('#roleModalTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        $('.modal-tab-pane').addClass('d-none');
        $('#' + t).removeClass('d-none');
    });

    /*── Permission counter ───────────────────────────────*/
    function updatePermCount() {
        var n = $('.perm-checkbox:checked').length;
        $('#rolePermCount').text(n + ' permission(s) selected');
    }

    $(document).on('change', '.perm-checkbox', updatePermCount);

    /*── Check / Uncheck all ─────────────────────────────*/
    $('#checkAll').on('click', function () {
        $('.perm-checkbox').prop('checked', true);
        updatePermCount();
    });
    $('#uncheckAll').on('click', function () {
        $('.perm-checkbox').prop('checked', false);
        updatePermCount();
    });

    /*── Toggle group ─────────────────────────────────────*/
    $(document).on('click', '.toggle-group', function () {
        var group = $(this).data('group');
        var boxes = $('.perm-checkbox[data-group="' + group + '"]');
        var allChecked = boxes.filter(':checked').length === boxes.length;
        boxes.prop('checked', !allChecked);
        updatePermCount();
    });

    /*── Error helpers ────────────────────────────────────*/
    function clearErrors() {
        $('#err_role_name, #err_role_perms').addClass('d-none').text('');
        $('#role_name').removeClass('is-invalid');
        $('#roleModalTabs .tab-err-dot').remove();
    }

    function showErrors(errors) {
        if (errors.name) {
            $('#err_role_name').removeClass('d-none').text(errors.name[0]);
            $('#role_name').addClass('is-invalid');
            var link = $('#roleModalTabs .nav-link[data-tab="rtab-info"]');
            if (!link.find('.tab-err-dot').length) link.append('<span class="tab-err-dot"></span>');
            $('#roleModalTabs .nav-link[data-tab="rtab-info"]').trigger('click');
        }
        if (errors.permissions) {
            $('#err_role_perms').removeClass('d-none').text(errors.permissions[0]);
        }
    }

    /*── Reset modal ──────────────────────────────────────*/
    function resetModal() {
        editingId = null;
        clearErrors();
        $('#role_id').val('');
        $('#role_name').val('');
        $('#role_guard').val('web');
        $('.perm-checkbox').prop('checked', false);
        updatePermCount();
        $('#roleModalTitle').text('Create Role');
        $('#roleModalSubtitle').text('Set role name and assign permissions');
        $('#roleModalIcon').removeClass('bg-gradient-warning').addClass('bg-gradient-primary');
        $('#saveRoleText').html('<i class="fas fa-plus me-2"></i> Create Role');
        $('#roleModalTabs .nav-link[data-tab="rtab-info"]').trigger('click');
    }

    $('#addRoleBtn').on('click', resetModal);
    $('#roleModal').on('hidden.bs.modal', resetModal);

    /*── Open EDIT ────────────────────────────────────────*/
    $(document).on('click', '.edit-role-btn', function () {
        var id = $(this).data('id');
        resetModal();

        $.get('{{ url("admin/roles") }}/' + id + '/edit-data', function (res) {
            editingId = res.id;
            $('#role_id').val(res.id);
            $('#role_name').val(res.name);
            $('#role_guard').val(res.guard_name);

            // Check assigned permissions
            $('.perm-checkbox').prop('checked', false);
            res.permissions.forEach(function (pid) {
                $('#perm_' + pid).prop('checked', true);
            });
            updatePermCount();

            $('#roleModalTitle').text('Edit Role');
            $('#roleModalSubtitle').text('Editing role: ' + res.name);
            $('#roleModalIcon').removeClass('bg-gradient-primary').addClass('bg-gradient-warning');
            $('#saveRoleText').html('<i class="fas fa-save me-2"></i> Save Changes');

            $('#roleModal').modal('show');
        }).fail(function () {
            Swal.fire('Error', 'Could not load role data.', 'error');
        });
    });

    /*── Save ─────────────────────────────────────────────*/
    $('#saveRoleBtn').on('click', function () {
        clearErrors();
        $('#saveRoleText').addClass('d-none');
        $('#saveRoleSpinner').removeClass('d-none');
        $('#saveRoleBtn').prop('disabled', true);

        var selectedPerms = $('.perm-checkbox:checked').map(function () { return $(this).val(); }).get();

        var isEdit = (editingId !== null);
        var url    = isEdit
            ? '/admin/roles/' + editingId + '/ajax-update'
            : '{{ route("roles.ajaxStore") }}';

        $.ajax({
            url  : url,
            type : 'POST',
            data : {
                _token      : '{{ csrf_token() }}',
                name        : $('#role_name').val(),
                guard_name  : $('#role_guard').val(),
                permissions : selectedPerms,
            },
            success: function (res) {
                if (res.success) {
                    $('#roleModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon:'success', title:'Success', text:res.message, timer:1800, showConfirmButton:false });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) showErrors(xhr.responseJSON.errors ?? {});
                else Swal.fire('Error', 'Something went wrong.', 'error');
            },
            complete: function () {
                $('#saveRoleText').removeClass('d-none');
                $('#saveRoleSpinner').addClass('d-none');
                $('#saveRoleBtn').prop('disabled', false);
            }
        });
    });

    /*── Delete ───────────────────────────────────────────*/
    $(document).on('click', '.delete-role-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Delete Role?',
            html : 'Delete <strong>' + name + '</strong>?<br><small class="text-muted">Roles with assigned users cannot be deleted.</small>',
            icon : 'warning',
            showCancelButton : true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url  : '/admin/roles/' + id,
                type : 'POST',
                data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function (res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        Swal.fire({ icon:'success', title:'Deleted!', text:res.message, timer:1500, showConfirmButton:false });
                    }
                },
                error: function (xhr) {
                    Swal.fire('Cannot Delete', xhr.responseJSON?.message ?? 'Error', 'error');
                }
            });
        });
    });

});
</script>
@endpush
