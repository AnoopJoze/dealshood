@extends('layouts.user_type.auth')

@section('content')

@push('css')
<style>
    :root {
        --ink:    #111827; --ink-2:#6b7280; --ink-3:#9ca3af;
        --rule:   #e5e7eb; --surf:#fff; --surf-2:#f9fafb;
        --accent: #1a56db; --success:#10b981; --warn:#f59e0b; --danger:#ef4444;
        --r: 1rem; --sh: 0 1px 3px rgba(0,0,0,.08);
    }
    body { font-family:'DM Sans',sans-serif; color:var(--ink); }

    /* Cover strip */
    .role-cover { height:120px; border-radius:1rem 1rem 0 0;
                  background:linear-gradient(135deg,#1a56db 0%,#6d28d9 100%); }

    /* Stat pill */
    .stat-pill { background:var(--surf-2); border:1px solid var(--rule);
                 border-radius:.75rem; padding:.9rem 1.1rem;
                 display:flex; align-items:center; gap:.65rem; }
    .stat-pill .sp-icon { width:34px; height:34px; border-radius:.5rem;
                          display:flex; align-items:center; justify-content:center;
                          font-size:.85rem; flex-shrink:0; }
    .stat-pill .sp-val  { font-size:1.2rem; font-weight:700; line-height:1; }
    .stat-pill .sp-lbl  { font-size:.65rem; text-transform:uppercase;
                          letter-spacing:.05em; color:var(--ink-3); margin-top:2px; }

    /* Card */
    .ps-card { background:var(--surf); border:1px solid var(--rule);
               border-radius:var(--r); padding:1.5rem;
               margin-bottom:1.25rem; box-shadow:var(--sh); }
    .ps-card-title {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:var(--ink-3); margin-bottom:1rem;
        padding-bottom:.75rem; border-bottom:1px solid var(--rule);
        display:flex; align-items:center; gap:.45rem;
    }
    .ps-card-title i { color:var(--accent); }

    /* Permission grid */
    .perm-group-card {
        border:1px solid var(--rule); border-radius:.75rem;
        overflow:hidden; background:var(--surf);
    }
    .perm-group-header {
        background:var(--surf-2); padding:.65rem 1rem;
        font-size:.72rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.05em; color:var(--ink-2);
        display:flex; align-items:center; justify-content:space-between;
        border-bottom:1px solid var(--rule);
    }
    .perm-item {
        display:flex; align-items:center; gap:.6rem;
        padding:.45rem .85rem; border-bottom:1px solid var(--rule);
        transition:background .15s; cursor:pointer;
    }
    .perm-item:last-child { border-bottom:none; }
    .perm-item:hover { background:var(--surf-2); }
    .perm-item input[type=checkbox] { width:15px; height:15px; cursor:pointer; flex-shrink:0; }
    .perm-item label { cursor:pointer; font-size:.82rem; margin:0; flex:1; }
    .perm-item .perm-badge {
        font-size:.62rem; padding:.15rem .5rem; border-radius:2rem;
        background:var(--surf-2); border:1px solid var(--rule);
        color:var(--ink-3); white-space:nowrap;
    }
    .perm-item.has-perm { background:#eff6ff; }
    .perm-item.has-perm label { color:var(--accent); font-weight:600; }
    .perm-item.has-perm .perm-badge { background:#dbeafe; border-color:#bfdbfe; color:var(--accent); }

    /* User row */
    .user-row {
        display:flex; align-items:center; gap:.75rem;
        padding:.65rem 0; border-bottom:1px solid var(--rule);
        font-size:.85rem;
    }
    .user-row:last-child { border-bottom:none; }
    .user-av {
        width:34px; height:34px; border-radius:50%; flex-shrink:0;
        background:var(--accent); color:#fff; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        font-size:.85rem;
    }

    /* Save bar */
    .save-bar {
        position:sticky; bottom:1rem; z-index:100;
        background:var(--surf); border:1px solid var(--rule);
        border-radius:var(--r); padding:.85rem 1.25rem;
        box-shadow:0 4px 20px rgba(0,0,0,.12);
        display:flex; align-items:center; gap:.75rem;
    }
</style>
@endpush

{{-- Breadcrumb --}}
<div class="d-flex align-items-center gap-2 mb-4 text-sm">
    <a href="{{ route('roles.index') }}" class="text-muted text-decoration-none">
        <i class="fas fa-shield-alt me-1"></i> Roles
    </a>
    <i class="fas fa-chevron-right text-muted" style="font-size:.55rem;"></i>
    <span class="fw-semibold text-dark">{{ ucfirst($role->name) }}</span>
</div>

<div class="row g-4">

    {{-- ════ LEFT  ════ --}}
    <div class="col-lg-4">

        {{-- Profile card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="role-cover"></div>
            <div class="card-body text-center pt-0 pb-4 px-4">

                <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center
                            justify-content-center fw-bold shadow mx-auto"
                     style="width:72px;height:72px;font-size:1.9rem;
                            margin-top:-36px;border:4px solid #fff;">
                    {{ strtoupper(substr($role->name, 0, 1)) }}
                </div>

                <h5 class="fw-bold text-dark mt-3 mb-1">{{ ucfirst($role->name) }}</h5>
                <p class="text-muted text-sm mb-3">Guard: <code>{{ $role->guard_name }}</code></p>

                <div class="d-flex justify-content-center gap-3 mb-4">
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.25rem;">
                            {{ $role->permissions->count() }}
                        </div>
                        <div class="text-xs text-muted text-uppercase">Permissions</div>
                    </div>
                    <div class="border-start ps-3">
                        <div class="fw-bold text-dark" style="font-size:1.25rem;">
                            {{ $role->users->count() }}
                        </div>
                        <div class="text-xs text-muted text-uppercase">Users</div>
                    </div>
                    <div class="border-start ps-3">
                        <div class="fw-bold text-dark" style="font-size:1.25rem;">#{{ $role->id }}</div>
                        <div class="text-xs text-muted text-uppercase">ID</div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn bg-gradient-warning flex-fill btn-sm"
                            data-bs-toggle="modal" data-bs-target="#editRoleModal">
                        <i class="fas fa-pen me-1"></i> Edit
                    </button>
                    <button class="btn btn-outline-danger flex-fill btn-sm" id="deleteRoleBtn"
                            data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>

            </div>
        </div>

        {{-- Stat pills --}}
        <div class="row g-2 mb-4">
            <div class="col-6">
                <div class="stat-pill">
                    <div class="sp-icon" style="background:#dbeafe;color:#1d4ed8;">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <div class="sp-val">{{ $role->permissions->count() }}</div>
                        <div class="sp-lbl">Permissions</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-pill">
                    <div class="sp-icon" style="background:#d1fae5;color:#059669;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="sp-val">{{ $role->users->count() }}</div>
                        <div class="sp-lbl">Users</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users with this role --}}
        <div class="ps-card">
            <div class="ps-card-title">
                <i class="fas fa-users"></i> Assigned Users
                <span class="ms-auto badge bg-light text-secondary rounded-pill">
                    {{ $role->users->count() }}
                </span>
            </div>

            @if ($role->users->count())
                <div style="max-height:340px;overflow-y:auto;">
                    @foreach ($role->users as $u)
                        <div class="user-row">
                            <div class="user-av">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <div>
                                <div class="fw-semibold text-sm">{{ $u->name }}</div>
                                <div class="text-xs text-muted">{{ $u->email }}</div>
                            </div>
                            <a href="{{ route('users.show', $u->id) }}"
                               class="btn btn-sm btn-light border ms-auto rounded-3 px-2">
                                <i class="fas fa-eye text-info" style="font-size:.7rem;"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-3">
                    <i class="fas fa-users text-muted mb-2" style="font-size:1.5rem;opacity:.3;"></i>
                    <p class="text-muted text-sm mb-0">No users assigned to this role yet</p>
                </div>
            @endif
        </div>

        {{-- Meta --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-info-circle"></i> Details</div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <span class="text-xs text-muted">Role ID</span>
                    <span class="badge bg-light text-secondary rounded-pill">#{{ $role->id }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <span class="text-xs text-muted">Guard</span>
                    <code class="text-xs">{{ $role->guard_name }}</code>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                    <span class="text-xs text-muted">Created</span>
                    <span class="text-xs fw-semibold">{{ $role->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1">
                    <span class="text-xs text-muted">Updated</span>
                    <span class="text-xs fw-semibold">{{ $role->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

    </div>{{-- /col-lg-4 --}}

    {{-- ════ RIGHT  ════ --}}
    <div class="col-lg-8">

        {{-- Permission management card --}}
        <div class="ps-card">
            <div class="ps-card-title">
                <i class="fas fa-key"></i> Permission Management
                <span class="ms-auto">
                    <span id="permCountBadge"
                          class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                        {{ $role->permissions->count() }} assigned
                    </span>
                </span>
            </div>

            {{-- Controls --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <input type="text" id="permSearch" class="form-control form-control-sm border"
                       style="max-width:220px;" placeholder="Search permissions…">
                <button type="button" class="btn btn-sm btn-light border rounded-3 px-3" id="selectAll">
                    <i class="fas fa-check-square me-1"></i> Select All
                </button>
                <button type="button" class="btn btn-sm btn-light border rounded-3 px-3" id="deselectAll">
                    <i class="fas fa-square me-1"></i> Deselect All
                </button>
                <span class="text-xs text-muted ms-auto" id="selectionInfo">
                    {{ $role->permissions->count() }} of {{ \Spatie\Permission\Models\Permission::count() }} selected
                </span>
            </div>

            {{-- Permission groups grid --}}
            <div class="row g-3" id="permGroupContainer">
                @foreach ($allPermissions as $group => $group_permissions)
                    <div class="col-md-6 perm-group-wrapper" data-group="{{ strtolower($group) }}">
                        <div class="perm-group-card">
                            <div class="perm-group-header">
                                <div><i class="fas fa-folder me-1"></i>{{ $group }}</div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill group-count-{{ $group }}"
                                          style="font-size:.62rem;">
                                        {{ $group_permissions->whereIn('id', $assignedIds)->count() }}/{{ $group_permissions->count() }}
                                    </span>
                                    <button type="button"
                                            class="btn toggle-group-btn text-xs border-0 bg-transparent p-0"
                                            data-group="{{ $group }}"
                                            style="font-size:.7rem;color:var(--accent);">
                                        Toggle
                                    </button>
                                </div>
                            </div>
                            @foreach ($group_permissions as $perm)
                                @php $assigned = in_array($perm->id, $assignedIds); @endphp
                                <div class="perm-item {{ $assigned ? 'has-perm' : '' }}"
                                     data-perm-name="{{ strtolower($perm->name) }}">
                                    <input type="checkbox"
                                           class="perm-check form-check-input"
                                           id="sp_{{ $perm->id }}"
                                           value="{{ $perm->id }}"
                                           data-group="{{ $group }}"
                                           {{ $assigned ? 'checked' : '' }}>
                                    <label for="sp_{{ $perm->id }}" class="text-sm">
                                        {{ ucwords(str_replace('_', ' ', $perm->name)) }}
                                    </label>
                                    <span class="perm-badge font-monospace">{{ $perm->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Sticky save bar --}}
            <div class="save-bar mt-4" id="saveBar">
                <div>
                    <div class="fw-semibold text-sm text-dark">
                        <span id="saveBarCount">{{ $role->permissions->count() }}</span> permissions selected
                    </div>
                    <div class="text-xs text-muted">Changes are not saved until you click Save</div>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-light border rounded-3 px-3">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button class="btn bg-gradient-primary px-4" id="savePermissionsBtn">
                        <span id="savePermText"><i class="fas fa-save me-2"></i> Save Permissions</span>
                        <span id="savePermSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span> Saving…
                        </span>
                    </button>
                </div>
            </div>

        </div>

    </div>{{-- /col-lg-8 --}}
</div>

{{-- ════════════════════════════════════════
     EDIT ROLE MODAL
════════════════════════════════════════ --}}
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-gradient-warning text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Edit Role</h5>
                        <p class="text-xs text-muted mb-0">Update the role name and guard</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">
                <div class="row g-3">

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-sm mb-1">
                            Role Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="edit_role_name" class="form-control"
                               value="{{ $role->name }}" placeholder="e.g. admin">
                        <small class="text-danger d-none" id="edit_err_name"></small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-sm mb-1">Guard</label>
                        <select id="edit_role_guard" class="form-select">
                            <option value="web" {{ $role->guard_name === 'web' ? 'selected' : '' }}>web</option>
                            <option value="api" {{ $role->guard_name === 'api' ? 'selected' : '' }}>api</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-warning px-4" id="saveEditRoleBtn">
                    <span id="saveEditRoleText"><i class="fas fa-save me-2"></i> Save</span>
                    <span id="saveEditRoleSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span> Saving…
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>

<script>
$(function () {

    const ROLE_ID = {{ $role->id }};

    /*── Update counter & row styling ────────────────────*/
    function updateCounts() {
        var n     = $('.perm-check:checked').length;
        var total = $('.perm-check').length;

        $('#permCountBadge').text(n + ' assigned');
        $('#saveBarCount').text(n);
        $('#selectionInfo').text(n + ' of ' + total + ' selected');

        // Update group badges
        $('.perm-check').each(function () {
            var $item = $(this).closest('.perm-item');
            if ($(this).is(':checked')) {
                $item.addClass('has-perm');
            } else {
                $item.removeClass('has-perm');
            }
        });

        // Group counts
        $('.perm-group-card').each(function () {
            var total  = $(this).find('.perm-check').length;
            var checked = $(this).find('.perm-check:checked').length;
            // find data-group from header button
            var group = $(this).find('.toggle-group-btn').data('group');
            $('.group-count-' + group).text(checked + '/' + total);
        });
    }

    $(document).on('change', '.perm-check', updateCounts);

    /*── Select / Deselect all ────────────────────────────*/
    $('#selectAll').on('click', function () {
        $('.perm-check:visible').prop('checked', true);
        updateCounts();
    });
    $('#deselectAll').on('click', function () {
        $('.perm-check:visible').prop('checked', false);
        updateCounts();
    });

    /*── Toggle group ─────────────────────────────────────*/
    $(document).on('click', '.toggle-group-btn', function () {
        var group   = $(this).data('group');
        var $checks = $('.perm-check[data-group="' + group + '"]');
        var allOn   = $checks.filter(':checked').length === $checks.length;
        $checks.prop('checked', !allOn);
        updateCounts();
    });

    /*── Live search ──────────────────────────────────────*/
    $('#permSearch').on('input', function () {
        var q = $(this).val().toLowerCase().trim();

        if (!q) {
            $('.perm-group-wrapper').show();
            $('.perm-item').show();
            return;
        }

        $('.perm-group-wrapper').each(function () {
            var anyMatch = false;
            $(this).find('.perm-item').each(function () {
                var name = $(this).data('perm-name') || '';
                if (name.includes(q)) {
                    $(this).show();
                    anyMatch = true;
                } else {
                    $(this).hide();
                }
            });
            anyMatch ? $(this).show() : $(this).hide();
        });
    });

    /*── Save Permissions ─────────────────────────────────*/
    $('#savePermissionsBtn').on('click', function () {
        var selected = $('.perm-check:checked').map(function () { return $(this).val(); }).get();

        $('#savePermText').addClass('d-none');
        $('#savePermSpinner').removeClass('d-none');
        $('#savePermissionsBtn').prop('disabled', true);

        $.ajax({
            url  : '/admin/roles/' + ROLE_ID + '/sync-permissions',
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', permissions: selected },

            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon : 'success',
                        title: 'Permissions Saved!',
                        text : res.count + ' permission(s) assigned to this role.',
                        timer: 1800, showConfirmButton: false,
                    });
                    updateCounts();
                }
            },
            error: function () {
                Swal.fire('Error', 'Could not save permissions.', 'error');
            },
            complete: function () {
                $('#savePermText').removeClass('d-none');
                $('#savePermSpinner').addClass('d-none');
                $('#savePermissionsBtn').prop('disabled', false);
            }
        });
    });

    /*── Edit role name/guard ─────────────────────────────*/
    $('#saveEditRoleBtn').on('click', function () {
        $('#edit_err_name').addClass('d-none').text('');
        $('#edit_role_name').removeClass('is-invalid');
        $('#saveEditRoleText').addClass('d-none');
        $('#saveEditRoleSpinner').removeClass('d-none');
        $('#saveEditRoleBtn').prop('disabled', true);

        $.ajax({
            url  : '/admin/roles/' + ROLE_ID + '/ajax-update',
            type : 'POST',
            data : {
                _token    : '{{ csrf_token() }}',
                name      : $('#edit_role_name').val(),
                guard_name: $('#edit_role_guard').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#editRoleModal').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Updated!', text: res.message,
                        timer: 1600, showConfirmButton: false,
                    }).then(function () { window.location.reload(); });
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON?.errors ?? {};
                if (errors.name) {
                    $('#edit_err_name').removeClass('d-none').text(errors.name[0]);
                    $('#edit_role_name').addClass('is-invalid');
                } else {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            },
            complete: function () {
                $('#saveEditRoleText').removeClass('d-none');
                $('#saveEditRoleSpinner').addClass('d-none');
                $('#saveEditRoleBtn').prop('disabled', false);
            }
        });
    });

    /*── Delete role ──────────────────────────────────────*/
    $('#deleteRoleBtn').on('click', function () {
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
                        Swal.fire({ icon:'success', title:'Deleted!', text:res.message, timer:1500, showConfirmButton:false })
                            .then(function () { window.location.href = '{{ route("roles.index") }}'; });
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
