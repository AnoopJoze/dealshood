@extends('layouts.user_type.auth')

@section('content')

@push('css')
    <link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
    <style>
        .perm-group-filter span {
            display:inline-flex; align-items:center; gap:.35rem;
            padding:.3rem .85rem; border-radius:2rem; font-size:.72rem; font-weight:600;
            cursor:pointer; border:1px solid #e5e7eb; background:#f9fafb;
            transition:all .15s; user-select:none;
        }
        .perm-group-filter span.active,
        .perm-group-filter span:hover {
            background:linear-gradient(195deg,#42424a,#191919); color:#fff; border-color:transparent;
        }
    </style>
@endpush

<div>

    {{-- Stat strip --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-success text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" id="stat-total-perms" style="font-size:1.4rem;">—</div>
                    <div class="text-xs text-muted text-uppercase">Total Permissions</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-folder"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;">
                        {{ \Spatie\Permission\Models\Permission::get()->groupBy(fn($p) => explode('_', $p->name)[0])->count() }}
                    </div>
                    <div class="text-xs text-muted text-uppercase">Groups</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 bg-gradient-warning text-white d-flex align-items-center
                            justify-content-center" style="width:44px;height:44px;flex-shrink:0;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size:1.4rem;">
                        {{ \Spatie\Permission\Models\Role::count() }}
                    </div>
                    <div class="text-xs text-muted text-uppercase">Roles</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Main card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Permissions Management</h4>
                    <p class="text-sm text-muted mb-0">Create and manage all system permissions</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-light border rounded-3">
                        <i class="fas fa-shield-alt me-2"></i>Roles
                    </a>
                    <button class="btn bg-gradient-primary" id="addPermBtn"
                            data-bs-toggle="modal" data-bs-target="#permModal">
                        <i class="fas fa-plus me-1"></i> Add Permission
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body pt-0 px-4 pb-4">

            {{-- Quick search + group filter --}}
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="globalSearch" class="form-control border"
                           placeholder="Search permissions…">
                </div>
                <div class="col-md-8">
                    <div class="perm-group-filter d-flex flex-wrap gap-2" id="groupFilter">
                        <span class="active" data-group="">All</span>
                        @php
                            $groups = \Spatie\Permission\Models\Permission::get()
                                ->groupBy(fn($p) => ucfirst(explode('_', $p->name)[0]))
                                ->keys();
                        @endphp
                        @foreach ($groups as $g)
                            <span data-group="{{ strtolower($g) }}">{{ $g }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable" class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder ps-3" style="width:50px">#</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Permission</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Group</th>
                            <th class="text-uppercase text-secondary text-xxs fw-bolder">Used In Roles</th>
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

{{-- ════════════════════════════════════════
     ADD / EDIT PERMISSION MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="permModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="permModalIcon"
                         class="rounded-3 bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="permModalTitle">Add Permission</h5>
                        <p class="text-xs text-muted mb-0" id="permModalSubtitle">Create a new permission</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <input type="hidden" id="perm_id">

                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-sm mb-1">
                            Permission Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="perm_name" class="form-control"
                               placeholder="e.g. user_create">
                        <small class="text-muted text-xs">
                            Format: <code>module_action</code> — separate multiple with commas
                        </small>
                        <small class="text-danger d-none" id="err_perm_name"></small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-sm mb-1">Guard</label>
                        <select id="perm_guard" class="form-select">
                            <option value="web">web</option>
                            <option value="api">api</option>
                        </select>
                    </div>

                    {{-- Bulk hint (only on create) --}}
                    <div class="col-12" id="bulkHint">
                        <div class="bg-light rounded-3 p-3">
                            <p class="text-xs fw-semibold text-muted mb-1">
                                <i class="fas fa-bolt me-1 text-warning"></i> Bulk Create
                            </p>
                            <p class="text-xs text-muted mb-0">
                                Separate multiple permission names with commas:<br>
                                <code>user_list, user_create, user_edit, user_delete</code>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-primary px-4" id="savePermBtn">
                    <span id="savePermBtnText"><i class="fas fa-plus me-2"></i> Create</span>
                    <span id="savePermSpinner" class="d-none">
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

    var editingId   = null;
    var activeGroup = '';

    /*── DataTable ─────────────────────────────────────*/
    var table = $('#datatable').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '{{ route("permissions.getlist") }}',
            type : 'POST',
            data : function (d) {
                d._token = '{{ csrf_token() }}';
                d.group  = activeGroup;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false,
              render: function (d, t, r, m) { return m.row + 1; } },
            { data: 'name',        name: 'name' },
            { data: 'group',       name: 'group',       orderable: false, searchable: false },
            { data: 'roles_count', name: 'roles_count', orderable: false, searchable: false },
            { data: 'created_at',  name: 'created_at',  searchable: false },
            { data: 'action',      name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
        order      : [[1, 'asc']],
        pageLength : 25,
        lengthMenu : [10, 25, 50, 100],
        drawCallback: function (s) {
            $('#stat-total-perms').text(s.json?.recordsTotal ?? '—');
        }
    });

    /*── Global search ─────────────────────────────────*/
    $('#globalSearch').on('keyup', function () { table.search(this.value).draw(); });

    /*── Group filter pills ────────────────────────────*/
    $(document).on('click', '#groupFilter span', function () {
        $('#groupFilter span').removeClass('active');
        $(this).addClass('active');
        activeGroup = $(this).data('group');
        table.ajax.reload();
    });

    /*── Reset modal ───────────────────────────────────*/
    function resetModal() {
        editingId = null;
        $('#perm_id').val('');
        $('#perm_name').val('').removeClass('is-invalid');
        $('#perm_guard').val('web');
        $('#err_perm_name').addClass('d-none').text('');
        $('#permModalTitle').text('Add Permission');
        $('#permModalSubtitle').text('Create a new permission');
        $('#permModalIcon').removeClass('bg-gradient-warning').addClass('bg-gradient-primary');
        $('#savePermBtnText').html('<i class="fas fa-plus me-2"></i> Create');
        $('#bulkHint').show();
    }

    $('#addPermBtn').on('click', resetModal);
    $('#permModal').on('hidden.bs.modal', resetModal);

    /*── Open EDIT ─────────────────────────────────────*/
    $(document).on('click', '.edit-perm-btn', function () {
        resetModal();
        editingId = $(this).data('id');

        $('#perm_id').val(editingId);
        $('#perm_name').val($(this).data('name'));
        $('#perm_guard').val($(this).data('guard'));

        $('#permModalTitle').text('Edit Permission');
        $('#permModalSubtitle').text('Update permission name or guard');
        $('#permModalIcon').removeClass('bg-gradient-primary').addClass('bg-gradient-warning');
        $('#savePermBtnText').html('<i class="fas fa-save me-2"></i> Save Changes');
        $('#bulkHint').hide();

        $('#permModal').modal('show');
    });

    /*── Save ──────────────────────────────────────────*/
    $('#savePermBtn').on('click', function () {
        $('#err_perm_name').addClass('d-none').text('');
        $('#perm_name').removeClass('is-invalid');
        $('#savePermBtnText').addClass('d-none');
        $('#savePermSpinner').removeClass('d-none');
        $('#savePermBtn').prop('disabled', true);

        var isEdit = (editingId !== null);
        var url    = isEdit
            ? '/admin/permissions/' + editingId + '/ajax-update'
            : '{{ route("permissions.ajaxStore") }}';

        $.ajax({
            url  : url,
            type : 'POST',
            data : {
                _token    : '{{ csrf_token() }}',
                name      : $('#perm_name').val(),
                guard_name: $('#perm_guard').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#permModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon:'success', title:'Success', text:res.message, timer:1800, showConfirmButton:false });
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON?.errors ?? {};
                if (errors.name) {
                    $('#err_perm_name').removeClass('d-none').text(errors.name[0]);
                    $('#perm_name').addClass('is-invalid');
                } else {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            },
            complete: function () {
                $('#savePermBtnText').removeClass('d-none');
                $('#savePermSpinner').addClass('d-none');
                $('#savePermBtn').prop('disabled', false);
            }
        });
    });

    /*── Delete ────────────────────────────────────────*/
    $(document).on('click', '.delete-perm-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Delete Permission?',
            html : 'Delete <strong>' + name + '</strong>?<br><small class="text-muted">It will be removed from all roles.</small>',
            icon : 'warning',
            showCancelButton : true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
        }).then(function (r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url  : '/admin/permissions/' + id,
                type : 'POST',
                data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function (res) {
                    if (res.success) {
                        table.ajax.reload(null, false);
                        Swal.fire({ icon:'success', title:'Deleted!', text:res.message, timer:1500, showConfirmButton:false });
                    }
                },
                error: function () { Swal.fire('Error', 'Could not delete.', 'error'); }
            });
        });
    });

});
</script>
@endpush
