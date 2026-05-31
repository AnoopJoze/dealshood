@extends('layouts.user_type.auth')
@section('content')
@push('css')
<link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
<style>
:root {
    --dk:       #0f172a;
    --dk2:      #1e293b;
    --accent:   #6366f1;
    --surface:  #f8fafc;
    --border:   #f1f5f9;
    --muted:    #64748b;
    --muted2:   #94a3b8;
    --r:        10px;
    --sh:       0 2px 16px rgba(15,23,42,.07);
    --sh-hover: 0 6px 28px rgba(15,23,42,.12);
}

/* ── KPI cards ─────────────────────────────────────── */
.ps-kpi {
    background:#fff; border:1px solid var(--border);
    border-radius:var(--r); box-shadow:var(--sh);
    padding:1rem 1.2rem; display:flex; align-items:center;
    gap:14px; transition:transform .16s,box-shadow .16s;
}
.ps-kpi:hover { transform:translateY(-2px); box-shadow:var(--sh-hover); }
.ps-kpi-icon {
    width:42px; height:42px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:.95rem;
}
.ps-kpi-val  { font-size:1.5rem; font-weight:800; line-height:1; color:var(--dk); }
.ps-kpi-lbl  { font-size:.62rem; font-weight:700; letter-spacing:.1em;
               text-transform:uppercase; color:var(--muted2); margin-top:3px; }

/* ── Main card ─────────────────────────────────────── */
.ps-card {
    background:#fff; border:1px solid var(--border);
    border-radius:var(--r); box-shadow:var(--sh); overflow:hidden;
}
.ps-card-header {
    padding:1.1rem 1.4rem .9rem; border-bottom:1px solid var(--border);
    display:flex; align-items:center;
    justify-content:space-between; flex-wrap:wrap; gap:10px;
}
.ps-page-title { font-size:1rem; font-weight:800; color:var(--dk); margin:0; }
.ps-page-sub   { font-size:.75rem; color:var(--muted2); margin:2px 0 0; }

/* ── Filter panel ──────────────────────────────────── */
.ps-filter-panel {
    background:var(--surface); border-bottom:1px solid var(--border);
    padding:1rem 1.4rem; display:none;
}
.ps-filter-panel.open { display:block; }
.ps-filter-panel .form-label {
    font-size:.65rem; font-weight:700; letter-spacing:.09em;
    text-transform:uppercase; color:var(--muted2); margin-bottom:5px;
}
.ps-filter-panel .form-control,
.ps-filter-panel .form-select {
    font-size:.82rem; border-color:var(--border); border-radius:8px; background:#fff;
}
.ps-filter-panel .form-control:focus,
.ps-filter-panel .form-select:focus {
    border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.12);
}

/* ── Filter pills ──────────────────────────────────── */
.ps-pills span {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.28rem .85rem; border-radius:2rem;
    font-size:.72rem; font-weight:600; cursor:pointer;
    border:1px solid var(--border); background:var(--surface);
    transition:all .15s; user-select:none;
}
.ps-pills span.active,
.ps-pills span:hover {
    background:var(--dk); color:#fff; border-color:transparent;
}

/* ── Buttons ───────────────────────────────────────── */
.ps-btn {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.77rem; font-weight:600; border-radius:8px;
    padding:.48rem .9rem; cursor:pointer; border:1.5px solid;
    transition:all .14s; text-decoration:none; white-space:nowrap;
}
.ps-btn-primary {
    background:var(--dk); color:#fff; border-color:var(--dk);
}
.ps-btn-primary:hover {
    background:var(--accent); border-color:var(--accent); color:#fff;
    box-shadow:0 3px 12px rgba(99,102,241,.3);
}
.ps-btn-ghost { background:#fff; color:var(--muted); border-color:var(--border); }
.ps-btn-ghost:hover { background:var(--surface); color:var(--dk); }

/* ── Table ─────────────────────────────────────────── */
#datatable thead th {
    font-size:.62rem; font-weight:700; letter-spacing:.1em;
    text-transform:uppercase; color:var(--muted2);
    background:var(--surface); border-bottom:1px solid var(--border);
    vertical-align:middle; white-space:nowrap; padding:.7rem 1rem;
}
#datatable tbody td {
    vertical-align:middle; white-space:nowrap;
    font-size:.82rem; color:var(--dk);
    padding:.65rem 1rem; border-bottom:1px solid var(--border);
}
#datatable tbody tr:hover td { background:var(--surface); }
#datatable tbody tr:last-child td { border-bottom:none; }

/* ── Inline edit ───────────────────────────────────── */
.inline-edit {
    transition:box-shadow .15s,background .15s;
    border-radius:8px !important;
}
.inline-edit:focus {
    background:#fff !important;
    box-shadow:0 0 0 3px rgba(99,102,241,.12) !important;
    border-color:var(--accent) !important;
}
.inline-edit.saving { opacity:.5; pointer-events:none; }
.inline-edit.saved  { background:#f0fdf4 !important; transition:background .3s; }
.inline-edit.error  { background:#fef2f2 !important; }

/* ── Modal ─────────────────────────────────────────── */
.ps-modal .modal-content {
    border:none; border-radius:14px;
    box-shadow:0 24px 60px rgba(15,23,42,.18);
}
.ps-modal .modal-header { padding:1.2rem 1.4rem .9rem; border-bottom:1px solid var(--border); }
.ps-modal-icon {
    width:44px; height:44px; border-radius:10px; flex-shrink:0;
    background:var(--dk); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:1rem;
}
.ps-modal-icon.amber { background:linear-gradient(135deg,#d97706,#f59e0b); }
.ps-tab-nav {
    display:flex; gap:2px; padding:0 1.4rem;
    border-bottom:1px solid var(--border);
}
.ps-tab-link {
    font-size:.75rem; font-weight:600; padding:.6rem .85rem;
    border:none; background:transparent; cursor:pointer;
    color:var(--muted); border-bottom:2px solid transparent;
    margin-bottom:-1px; transition:color .14s,border-color .14s;
    display:inline-flex; align-items:center; gap:5px;
}
.ps-tab-link.active { color:var(--dk); border-bottom-color:var(--dk); }
.ps-tab-link .tab-err-dot {
    width:6px; height:6px; border-radius:50%; background:#ef4444;
}
.modal-section-lbl {
    font-size:.62rem; font-weight:700; letter-spacing:.11em;
    text-transform:uppercase; color:var(--muted2); margin:0 0 .75rem;
}
.ps-modal .form-label { font-size:.78rem; font-weight:600; color:var(--dk); margin-bottom:5px; }
.ps-modal .form-control,
.ps-modal .form-select {
    font-size:.84rem; border-color:var(--border); border-radius:8px; color:var(--dk);
}
.ps-modal .form-control:focus,
.ps-modal .form-select:focus {
    border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.1);
}
.ps-modal .modal-footer { padding:.9rem 1.4rem; border-top:1px solid var(--border); }

/* Permission checkboxes */
.perm-group-title { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--muted2); margin-bottom:.5rem; padding-bottom:.4rem; border-bottom:1px solid var(--border); }
.perm-item { display:flex; align-items:center; gap:.5rem; padding:.3rem .5rem; border-radius:8px; cursor:pointer; transition:background .15s; }
.perm-item:hover { background:var(--surface); }
.perm-item label { cursor:pointer; font-size:.82rem; margin:0; color:var(--dk); }
.perm-item input[type=checkbox] { width:15px; height:15px; cursor:pointer; flex-shrink:0; accent-color:var(--accent); }
</style>
@endpush

<div>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#ede9fe;"><i class="fas fa-shield-alt" style="color:#7c3aed;"></i></div>
            <div><div class="ps-kpi-val" id="stat-roles">—</div><div class="ps-kpi-lbl">Total Roles</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;"><i class="fas fa-key" style="color:#059669;"></i></div>
            <div><div class="ps-kpi-val">{{ \Spatie\Permission\Models\Permission::count() }}</div><div class="ps-kpi-lbl">Total Permissions</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef3c7;"><i class="fas fa-users" style="color:#d97706;"></i></div>
            <div><div class="ps-kpi-val">{{ \App\Models\User::count() }}</div><div class="ps-kpi-lbl">Total Users</div></div>
        </div>
    </div>
</div>

<div class="ps-card">
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Roles Management</h4>
            <p class="ps-page-sub">Create and manage roles and their permissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('permissions.index') }}" class="ps-btn ps-btn-ghost"><i class="fas fa-key"></i> Permissions</a>
            <button class="ps-btn ps-btn-primary" id="addRoleBtn" data-bs-toggle="modal" data-bs-target="#roleModal">
                <i class="fas fa-plus"></i> Add Role
            </button>
        </div>
    </div>
    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead><tr>
                    <th style="width:50px;">#</th><th>Role Name</th><th>Permissions</th><th>Users</th><th>Created</th>
                    <th class="text-center">Action</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
</div>

{{-- Modal --}}
<div class="modal fade ps-modal" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon" id="roleModalIcon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);" id="roleModalTitle">Create Role</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);" id="roleModalSubtitle">Set role name and assign permissions</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="ps-tab-nav" id="roleModalTabs">
                <button class="ps-tab-link active" data-tab="rtab-info"><i class="fas fa-tag"></i> Role Info</button>
                <button class="ps-tab-link" data-tab="rtab-perms"><i class="fas fa-key"></i> Permissions</button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="role_id">
                {{-- Info tab --}}
                <div class="modal-tab-pane" id="rtab-info">
                    <p class="modal-section-lbl">Role Details</p>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" id="role_name" class="form-control" placeholder="e.g. admin, editor">
                            <small style="font-size:.72rem;color:var(--muted2);">Lowercase, no spaces</small>
                            <small class="text-danger d-none" id="err_role_name"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Guard</label>
                            <select id="role_guard" class="form-select"><option value="web">web</option><option value="api">api</option></select>
                        </div>
                    </div>
                </div>
                {{-- Perms tab --}}
                <div class="modal-tab-pane d-none" id="rtab-perms">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="modal-section-lbl mb-0">Assign Permissions</p>
                        <div class="d-flex gap-2">
                            <button type="button" class="ps-btn ps-btn-ghost" id="checkAll" style="font-size:.72rem;padding:.3rem .7rem;"><i class="fas fa-check-square"></i> All</button>
                            <button type="button" class="ps-btn ps-btn-ghost" id="uncheckAll" style="font-size:.72rem;padding:.3rem .7rem;"><i class="fas fa-square"></i> None</button>
                        </div>
                    </div>
                    <div id="permissionsContainer" class="row g-3">
                        @foreach ($permissions as $group => $group_permissions)
                            <div class="col-md-6">
                                <div class="rounded-3 p-3" style="border:1px solid var(--border);">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="perm-group-title mb-0"><i class="fas fa-folder me-1" style="color:var(--accent);"></i>{{ $group }}</div>
                                        <button type="button" class="toggle-group ps-btn ps-btn-ghost" data-group="{{ $group }}" style="font-size:.65rem;padding:.2rem .6rem;">Toggle</button>
                                    </div>
                                    @foreach ($group_permissions as $perm)
                                        <div class="perm-item">
                                            <input type="checkbox" class="perm-checkbox form-check-input" id="perm_{{ $perm->id }}" name="permissions[]" value="{{ $perm->id }}" data-group="{{ $group }}">
                                            <label for="perm_{{ $perm->id }}">{{ str_replace('_',' ',$perm->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-danger d-none mt-2" id="err_role_perms"></small>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div style="font-size:.72rem;color:var(--muted2);" id="rolePermCount"></div>
                <div class="d-flex gap-2">
                    <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <button class="ps-btn ps-btn-primary" id="saveRoleBtn">
                        <span id="saveRoleText"><i class="fas fa-save"></i> Save Role</span>
                        <span id="saveRoleSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
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
<script>
$(function () {
    var editingId=null;

    var table = $('#datatable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("roles.getlist") }}', type:'POST', data:function(d){ d._token='{{ csrf_token() }}'; }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false,render:function(d,t,r,m){return m.row+1;}},
            {data:'name',name:'name'},{data:'permissions_count',name:'permissions_count',orderable:false},
            {data:'users_count',name:'users_count',orderable:false},{data:'created_at',name:'created_at',searchable:false},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-center'},
        ],
        order:[[4,'desc']], pageLength:25,
        drawCallback:function(s){ $('#stat-roles').text(s.json?.recordsTotal??'—'); }
    });

    $(document).on('click','#roleModalTabs .ps-tab-link',function(){
        var t=$(this).data('tab'); $('#roleModalTabs .ps-tab-link').removeClass('active'); $(this).addClass('active');
        $('.modal-tab-pane').addClass('d-none'); $('#'+t).removeClass('d-none');
    });

    function updatePermCount(){ var n=$('.perm-checkbox:checked').length; $('#rolePermCount').text(n+' permission(s) selected'); }
    $(document).on('change','.perm-checkbox',updatePermCount);
    $('#checkAll').on('click',function(){ $('.perm-checkbox').prop('checked',true); updatePermCount(); });
    $('#uncheckAll').on('click',function(){ $('.perm-checkbox').prop('checked',false); updatePermCount(); });
    $(document).on('click','.toggle-group',function(){
        var group=$(this).data('group'),boxes=$('.perm-checkbox[data-group="'+group+'"]'),allChecked=boxes.filter(':checked').length===boxes.length;
        boxes.prop('checked',!allChecked); updatePermCount();
    });

    function clearErrors(){ $('#err_role_name,#err_role_perms').addClass('d-none').text(''); $('#role_name').removeClass('is-invalid'); $('#roleModalTabs .tab-err-dot').remove(); }
    function showErrors(errors){
        if(errors.name){ $('#err_role_name').removeClass('d-none').text(errors.name[0]); $('#role_name').addClass('is-invalid');
            var link=$('#roleModalTabs .ps-tab-link[data-tab="rtab-info"]');
            if(!link.find('.tab-err-dot').length) link.append('<span class="tab-err-dot ms-1"></span>');
            link.trigger('click'); }
        if(errors.permissions) $('#err_role_perms').removeClass('d-none').text(errors.permissions[0]);
    }

    function resetModal(){ editingId=null; clearErrors(); $('#role_id').val(''); $('#role_name').val(''); $('#role_guard').val('web'); $('.perm-checkbox').prop('checked',false); updatePermCount(); $('#roleModalTitle').text('Create Role'); $('#roleModalSubtitle').text('Set role name and assign permissions'); $('#roleModalIcon').removeClass('amber'); $('#saveRoleText').html('<i class="fas fa-plus"></i> Create Role'); $('#roleModalTabs .ps-tab-link[data-tab="rtab-info"]').trigger('click'); }

    $('#addRoleBtn').on('click',resetModal);
    $('#roleModal').on('hidden.bs.modal',resetModal);

    $(document).on('click','.edit-role-btn',function(){
        var id=$(this).data('id'); resetModal();
        $.get('{{ url("admin/roles") }}/'+id+'/edit-data',function(res){
            editingId=res.id; $('#role_id').val(res.id); $('#role_name').val(res.name); $('#role_guard').val(res.guard_name);
            $('.perm-checkbox').prop('checked',false); res.permissions.forEach(pid=>$('#perm_'+pid).prop('checked',true)); updatePermCount();
            $('#roleModalTitle').text('Edit Role'); $('#roleModalSubtitle').text('Editing role: '+res.name); $('#roleModalIcon').addClass('amber');
            $('#saveRoleText').html('<i class="fas fa-save"></i> Save Changes'); $('#roleModal').modal('show');
        }).fail(()=>Swal.fire('Error','Could not load role data.','error'));
    });

    $('#saveRoleBtn').on('click',function(){
        clearErrors(); $('#saveRoleText').addClass('d-none'); $('#saveRoleSpinner').removeClass('d-none'); $('#saveRoleBtn').prop('disabled',true);
        var selectedPerms=$('.perm-checkbox:checked').map(function(){return $(this).val();}).get();
        var isEdit=(editingId!==null), url=isEdit?'/admin/roles/'+editingId+'/ajax-update':'{{ route("roles.ajaxStore") }}';
        $.ajax({ url:url, type:'POST', data:{_token:'{{ csrf_token() }}',name:$('#role_name').val(),guard_name:$('#role_guard').val(),permissions:selectedPerms},
            success:function(res){ if(res.success){ $('#roleModal').modal('hide'); table.ajax.reload(null,false); Swal.fire({icon:'success',title:'Success',text:res.message,timer:1800,showConfirmButton:false}); }},
            error:function(xhr){ if(xhr.status===422) showErrors(xhr.responseJSON.errors??{}); else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveRoleText').removeClass('d-none'); $('#saveRoleSpinner').addClass('d-none'); $('#saveRoleBtn').prop('disabled',false); }
        });
    });

    $(document).on('click','.delete-role-btn',function(){
        var id=$(this).data('id'),name=$(this).data('name');
        Swal.fire({title:'Delete Role?',html:'Delete <strong>'+name+'</strong>?<br><small class="text-muted">Roles with assigned users cannot be deleted.</small>',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/roles/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ table.ajax.reload(null,false); Swal.fire({icon:'success',title:'Deleted!',text:res.message,timer:1500,showConfirmButton:false}); }},
                error:function(xhr){ Swal.fire('Cannot Delete',xhr.responseJSON?.message??'Error','error'); }});
        });
    });
});
</script>
@endpush