@extends('layouts.user_type.auth')
@section('content')
@push('css')
<style>
:root { --dk:#0f172a; --dk2:#1e293b; --accent:#6366f1; --surface:#f8fafc; --border:#f1f5f9; --muted:#64748b; --muted2:#94a3b8; --r:10px; --sh:0 2px 16px rgba(15,23,42,.07); --sh-hover:0 6px 28px rgba(15,23,42,.12); }

.ps-card { background:#fff; border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); margin-bottom:1.1rem; overflow:hidden; }
.ps-card-hd { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1.2rem; border-bottom:1px solid var(--border); }
.ps-card-title { font-size:.63rem; font-weight:700; letter-spacing:.11em; text-transform:uppercase; color:var(--muted2); margin:0; display:flex; align-items:center; gap:7px; }
.ps-card-title i { color:var(--accent); font-size:.72rem; }
.ps-card-body { padding:1rem 1.2rem; }

.role-cover { height:110px; border-radius:var(--r) var(--r) 0 0; background:linear-gradient(135deg,var(--dk) 0%,#312e81 100%); }
.role-avatar { width:66px; height:66px; font-size:1.6rem; font-weight:800; border:3px solid #fff; margin-top:-33px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.18); }

.ps-btn { display:inline-flex; align-items:center; gap:6px; font-size:.77rem; font-weight:600; border-radius:8px; padding:.45rem .9rem; cursor:pointer; border:1.5px solid; transition:all .14s; text-decoration:none; }
.ps-btn-warn { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; border-color:transparent; }
.ps-btn-warn:hover { filter:brightness(1.08); color:#fff; }
.ps-btn-ghost { background:#fff; color:var(--muted); border-color:var(--border); }
.ps-btn-ghost:hover { background:var(--surface); color:var(--dk); }
.ps-btn-danger { background:#fff; color:#dc2626; border-color:#fecaca; }
.ps-btn-danger:hover { background:#fef2f2; }

.ps-meta-row { display:flex; justify-content:space-between; align-items:center; padding:.5rem 0; border-bottom:1px solid var(--border); font-size:.8rem; }
.ps-meta-row:last-child { border-bottom:none; }
.ps-meta-lbl { font-size:.68rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--muted2); }

.user-row { display:flex; align-items:center; gap:.75rem; padding:.6rem 0; border-bottom:1px solid var(--border); }
.user-row:last-child { border-bottom:none; }
.u-av { width:34px; height:34px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; font-weight:700; font-size:.82rem; display:flex; align-items:center; justify-content:center; }

.perm-group-card { border:1px solid var(--border); border-radius:var(--r); overflow:hidden; }
.perm-group-hd { background:var(--surface); padding:.6rem 1rem; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--muted2); display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--border); }
.perm-item { display:flex; align-items:center; gap:.6rem; padding:.42rem .85rem; border-bottom:1px solid var(--border); transition:background .14s; cursor:pointer; }
.perm-item:last-child { border-bottom:none; }
.perm-item:hover { background:var(--surface); }
.perm-item input[type=checkbox] { width:15px; height:15px; cursor:pointer; flex-shrink:0; accent-color:var(--accent); }
.perm-item label { cursor:pointer; font-size:.82rem; margin:0; flex:1; color:var(--dk); }
.perm-item .perm-mono { font-size:.62rem; padding:.15rem .5rem; border-radius:2rem; background:var(--surface); border:1px solid var(--border); color:var(--muted2); white-space:nowrap; }
.perm-item.has-perm { background:#eff6ff; }
.perm-item.has-perm label { color:var(--accent); font-weight:600; }
.perm-item.has-perm .perm-mono { background:#dbeafe; border-color:#bfdbfe; color:var(--accent); }

.save-bar { position:sticky; bottom:1rem; z-index:100; background:#fff; border:1px solid var(--border); border-radius:var(--r); padding:.85rem 1.25rem; box-shadow:0 8px 30px rgba(0,0,0,.12); display:flex; align-items:center; gap:.75rem; }

/* Edit modal */
.ps-modal .modal-content { border:none; border-radius:14px; box-shadow:0 24px 60px rgba(15,23,42,.18); }
.ps-modal .modal-header { padding:1.2rem 1.4rem .9rem; border-bottom:1px solid var(--border); }
.ps-modal-icon { width:44px; height:44px; border-radius:10px; flex-shrink:0; background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1rem; }
.ps-modal .form-control, .ps-modal .form-select { font-size:.84rem; border-color:var(--border); border-radius:8px; }
.ps-modal .form-control:focus, .ps-modal .form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.ps-modal .form-label { font-size:.78rem; font-weight:600; color:var(--dk); margin-bottom:5px; }
.ps-modal .modal-footer { padding:.9rem 1.4rem; border-top:1px solid var(--border); }
</style>
@endpush

{{-- Breadcrumb --}}
<div class="d-flex align-items-center gap-2 mb-4" style="font-size:.82rem;">
    <a href="{{ route('roles.index') }}" style="color:var(--muted);text-decoration:none;">
        <i class="fas fa-shield-alt me-1"></i> Roles
    </a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;color:var(--muted2);"></i>
    <span style="font-weight:700;color:var(--dk);">{{ ucfirst($role->name) }}</span>
</div>

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-4">

        {{-- Profile card --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius:var(--r);">
            <div class="role-cover"></div>
            <div class="card-body text-center pt-0 pb-4 px-4">
                <div class="role-avatar mx-auto">{{ strtoupper(substr($role->name, 0, 1)) }}</div>
                <h5 class="fw-bold mt-3 mb-1" style="color:var(--dk);">{{ ucfirst($role->name) }}</h5>
                <p style="font-size:.78rem;color:var(--muted);">Guard: <code>{{ $role->guard_name }}</code></p>
                <div class="d-flex justify-content-center gap-4 mb-4">
                    <div>
                        <div class="fw-bold" style="font-size:1.25rem;color:var(--dk);">{{ $role->permissions->count() }}</div>
                        <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);">Permissions</div>
                    </div>
                    <div class="border-start ps-4">
                        <div class="fw-bold" style="font-size:1.25rem;color:var(--dk);">{{ $role->users->count() }}</div>
                        <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);">Users</div>
                    </div>
                    <div class="border-start ps-4">
                        <div class="fw-bold" style="font-size:1.25rem;color:var(--dk);">#{{ $role->id }}</div>
                        <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted2);">ID</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="ps-btn ps-btn-warn flex-fill" data-bs-toggle="modal" data-bs-target="#editRoleModal">
                        <i class="fas fa-pen"></i> Edit
                    </button>
                    <button class="ps-btn ps-btn-danger flex-fill" id="deleteRoleBtn" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        {{-- Assigned users --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-users"></i> Assigned Users</p>
                <span class="badge rounded-pill px-2" style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.68rem;">{{ $role->users->count() }}</span>
            </div>
            <div class="ps-card-body" style="padding-top:.5rem;padding-bottom:.5rem;max-height:300px;overflow-y:auto;">
                @forelse ($role->users as $u)
                    <div class="user-row">
                        <div class="u-av">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                        <div class="flex-fill" style="min-width:0;">
                            <div class="fw-semibold text-truncate" style="font-size:.82rem;color:var(--dk);">{{ $u->name }}</div>
                            <div class="text-truncate" style="font-size:.7rem;color:var(--muted);">{{ $u->email }}</div>
                        </div>
                        <a href="{{ route('users.show', $u->id) }}" class="ps-btn ps-btn-ghost" style="padding:.3rem .6rem;font-size:.7rem;">
                            <i class="fas fa-eye" style="color:var(--accent);"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-4" style="color:var(--muted2);font-size:.82rem;">
                        <i class="fas fa-users mb-2" style="font-size:1.5rem;opacity:.3;display:block;"></i>
                        No users assigned yet
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Meta --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-info-circle"></i> Details</p></div>
            <div class="ps-card-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                <div class="ps-meta-row">
                    <span class="ps-meta-lbl">Role ID</span>
                    <span class="badge rounded-pill px-2" style="background:var(--surface);color:var(--muted);border:1px solid var(--border);">#{{ $role->id }}</span>
                </div>
                <div class="ps-meta-row">
                    <span class="ps-meta-lbl">Guard</span>
                    <code style="font-size:.78rem;">{{ $role->guard_name }}</code>
                </div>
                <div class="ps-meta-row">
                    <span class="ps-meta-lbl">Created</span>
                    <span style="font-size:.8rem;font-weight:600;color:var(--dk);">{{ $role->created_at->format('d M Y') }}</span>
                </div>
                <div class="ps-meta-row">
                    <span class="ps-meta-lbl">Updated</span>
                    <span style="font-size:.78rem;color:var(--muted);">{{ $role->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-8">
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-key"></i> Permission Management</p>
                <span class="badge rounded-pill px-3 py-1" style="background:#ede9fe;color:#7c3aed;font-size:.68rem;" id="permCountBadge">
                    {{ $role->permissions->count() }} assigned
                </span>
            </div>
            <div class="ps-card-body">
                {{-- Controls --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <input type="text" id="permSearch" class="form-control form-control-sm"
                           style="max-width:220px;border-color:var(--border);border-radius:8px;"
                           placeholder="Search permissions…">
                    <button type="button" class="ps-btn ps-btn-ghost" id="selectAll" style="font-size:.72rem;padding:.35rem .7rem;"><i class="fas fa-check-square"></i> Select All</button>
                    <button type="button" class="ps-btn ps-btn-ghost" id="deselectAll" style="font-size:.72rem;padding:.35rem .7rem;"><i class="fas fa-square"></i> Deselect All</button>
                    <span class="ms-auto" style="font-size:.72rem;color:var(--muted2);" id="selectionInfo">
                        {{ $role->permissions->count() }} of {{ \Spatie\Permission\Models\Permission::count() }} selected
                    </span>
                </div>

                {{-- Grid --}}
                <div class="row g-3" id="permGroupContainer">
                    @foreach ($allPermissions as $group => $group_permissions)
                        <div class="col-md-6 perm-group-wrapper" data-group="{{ strtolower($group) }}">
                            <div class="perm-group-card">
                                <div class="perm-group-hd">
                                    <div><i class="fas fa-folder me-1"></i>{{ $group }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill px-2" style="background:#ede9fe;color:#7c3aed;font-size:.6rem;" id="gc-{{ $group }}">
                                            {{ $group_permissions->whereIn('id', $assignedIds)->count() }}/{{ $group_permissions->count() }}
                                        </span>
                                        <button type="button" class="toggle-group-btn ps-btn ps-btn-ghost" data-group="{{ $group }}" style="font-size:.65rem;padding:.15rem .5rem;">Toggle</button>
                                    </div>
                                </div>
                                @foreach ($group_permissions as $perm)
                                    @php $assigned = in_array($perm->id, $assignedIds); @endphp
                                    <div class="perm-item {{ $assigned ? 'has-perm' : '' }}" data-perm-name="{{ strtolower($perm->name) }}">
                                        <input type="checkbox" class="perm-check form-check-input" id="sp_{{ $perm->id }}" value="{{ $perm->id }}" data-group="{{ $group }}" {{ $assigned ? 'checked' : '' }}>
                                        <label for="sp_{{ $perm->id }}">{{ ucwords(str_replace(['.','_'], ' ', $perm->name)) }}</label>
                                        <span class="perm-mono font-monospace">{{ $perm->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Sticky save bar --}}
                <div class="save-bar mt-4">
                    <div>
                        <div class="fw-semibold" style="font-size:.84rem;color:var(--dk);"><span id="saveBarCount">{{ $role->permissions->count() }}</span> permissions selected</div>
                        <div style="font-size:.7rem;color:var(--muted2);">Changes are not saved until you click Save</div>
                    </div>
                    <div class="ms-auto d-flex gap-2">
                        <a href="{{ route('roles.index') }}" class="ps-btn ps-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
                        <button class="ps-btn ps-btn-primary" id="savePermissionsBtn">
                            <span id="savePermText"><i class="fas fa-save"></i> Save Permissions</span>
                            <span id="savePermSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit modal --}}
<div class="modal fade ps-modal" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon"><i class="fas fa-pen"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">Edit Role</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">Update the role name and guard</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_role_name" class="form-control" value="{{ $role->name }}" placeholder="e.g. admin">
                        <small class="text-danger d-none" id="edit_err_name"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guard</label>
                        <select id="edit_role_guard" class="form-select">
                            <option value="web" {{ $role->guard_name === 'web' ? 'selected' : '' }}>web</option>
                            <option value="api" {{ $role->guard_name === 'api' ? 'selected' : '' }}>api</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                <button class="ps-btn ps-btn-warn" id="saveEditRoleBtn">
                    <span id="saveEditRoleText"><i class="fas fa-save"></i> Save</span>
                    <span id="saveEditRoleSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
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

    function updateCounts(){
        var n=$('.perm-check:checked').length, total=$('.perm-check').length;
        $('#permCountBadge').text(n+' assigned'); $('#saveBarCount').text(n); $('#selectionInfo').text(n+' of '+total+' selected');
        $('.perm-check').each(function(){ $(this).closest('.perm-item').toggleClass('has-perm',$(this).is(':checked')); });
        $('.perm-group-card').each(function(){
            var t=$(this).find('.perm-check').length, c=$(this).find('.perm-check:checked').length;
            var g=$(this).find('.toggle-group-btn').data('group'); $('#gc-'+g).text(c+'/'+t);
        });
    }

    $(document).on('change','.perm-check',updateCounts);
    $('#selectAll').on('click',function(){ $('.perm-check:visible').prop('checked',true); updateCounts(); });
    $('#deselectAll').on('click',function(){ $('.perm-check:visible').prop('checked',false); updateCounts(); });
    $(document).on('click','.toggle-group-btn',function(){
        var group=$(this).data('group'), $c=$('.perm-check[data-group="'+group+'"]'), allOn=$c.filter(':checked').length===$c.length;
        $c.prop('checked',!allOn); updateCounts();
    });

    $('#permSearch').on('input',function(){
        var q=$(this).val().toLowerCase().trim();
        if(!q){ $('.perm-group-wrapper').show(); $('.perm-item').show(); return; }
        $('.perm-group-wrapper').each(function(){
            var anyMatch=false;
            $(this).find('.perm-item').each(function(){
                var matches=$(this).data('perm-name')?.includes(q);
                $(this).toggle(!!matches); if(matches) anyMatch=true;
            });
            $(this).toggle(anyMatch);
        });
    });

    $('#savePermissionsBtn').on('click',function(){
        var selected=$('.perm-check:checked').map(function(){return $(this).val();}).get();
        $('#savePermText').addClass('d-none'); $('#savePermSpinner').removeClass('d-none'); $('#savePermissionsBtn').prop('disabled',true);
        $.ajax({ url:'/admin/roles/'+ROLE_ID+'/sync-permissions', type:'POST', data:{_token:'{{ csrf_token() }}',permissions:selected},
            success:function(res){ if(res.success){ Swal.fire({icon:'success',title:'Permissions Saved!',text:res.count+' permission(s) assigned.',timer:1800,showConfirmButton:false}); updateCounts(); }},
            error:function(){ Swal.fire('Error','Could not save permissions.','error'); },
            complete:function(){ $('#savePermText').removeClass('d-none'); $('#savePermSpinner').addClass('d-none'); $('#savePermissionsBtn').prop('disabled',false); }
        });
    });

    $('#saveEditRoleBtn').on('click',function(){
        $('#edit_err_name').addClass('d-none').text(''); $('#edit_role_name').removeClass('is-invalid');
        $('#saveEditRoleText').addClass('d-none'); $('#saveEditRoleSpinner').removeClass('d-none'); $('#saveEditRoleBtn').prop('disabled',true);
        $.ajax({ url:'/admin/roles/'+ROLE_ID+'/ajax-update', type:'POST', data:{_token:'{{ csrf_token() }}',name:$('#edit_role_name').val(),guard_name:$('#edit_role_guard').val()},
            success:function(res){ if(res.success){ $('#editRoleModal').modal('hide'); Swal.fire({icon:'success',title:'Updated!',text:res.message,timer:1600,showConfirmButton:false}).then(()=>location.reload()); }},
            error:function(xhr){ var e=xhr.responseJSON?.errors??{}; if(e.name){ $('#edit_err_name').removeClass('d-none').text(e.name[0]); $('#edit_role_name').addClass('is-invalid'); } else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveEditRoleText').removeClass('d-none'); $('#saveEditRoleSpinner').addClass('d-none'); $('#saveEditRoleBtn').prop('disabled',false); }
        });
    });

    $('#deleteRoleBtn').on('click',function(){
        var id=$(this).data('id'),name=$(this).data('name');
        Swal.fire({title:'Delete Role?',html:'Delete <strong>'+name+'</strong>?<br><small class="text-muted">Roles with assigned users cannot be deleted.</small>',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/roles/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ Swal.fire({icon:'success',title:'Deleted!',text:res.message,timer:1500,showConfirmButton:false}).then(()=>window.location.href='{{ route("roles.index") }}'); }},
                error:function(xhr){ Swal.fire('Cannot Delete',xhr.responseJSON?.message??'Error','error'); }});
        });
    });
});
</script>
@endpush