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

</style>
@endpush

<div>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;"><i class="fas fa-key" style="color:#059669;"></i></div>
            <div><div class="ps-kpi-val" id="stat-total-perms">—</div><div class="ps-kpi-lbl">Total Permissions</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#ede9fe;"><i class="fas fa-folder" style="color:#7c3aed;"></i></div>
            <div>
                <div class="ps-kpi-val">{{ \Spatie\Permission\Models\Permission::get()->groupBy(fn($p) => explode('_', $p->name)[0])->count() }}</div>
                <div class="ps-kpi-lbl">Groups</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef3c7;"><i class="fas fa-shield-alt" style="color:#d97706;"></i></div>
            <div><div class="ps-kpi-val">{{ \Spatie\Permission\Models\Role::count() }}</div><div class="ps-kpi-lbl">Roles</div></div>
        </div>
    </div>
</div>

<div class="ps-card">
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Permissions Management</h4>
            <p class="ps-page-sub">Create and manage all system permissions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('roles.index') }}" class="ps-btn ps-btn-ghost"><i class="fas fa-shield-alt"></i> Roles</a>
            <button class="ps-btn ps-btn-primary" id="addPermBtn" data-bs-toggle="modal" data-bs-target="#permModal">
                <i class="fas fa-plus"></i> Add Permission
            </button>
        </div>
    </div>

    {{-- Search + group filter --}}
    <div style="padding:.75rem 1.4rem; border-bottom:1px solid var(--border);">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" id="globalSearch" class="form-control"
                       style="border-color:var(--border);border-radius:8px;font-size:.82rem;"
                       placeholder="Search permissions…">
            </div>
            <div class="col-md-8">
                <div class="ps-pills d-flex flex-wrap gap-2" id="groupFilter">
                    <span class="active" data-group="">All</span>
                    @php
                        $groups = \Spatie\Permission\Models\Permission::get()
                            ->groupBy(fn($p) => ucfirst(explode('_', $p->name)[0]))->keys();
                    @endphp
                    @foreach ($groups as $g)
                        <span data-group="{{ strtolower($g) }}">{{ $g }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead><tr>
                    <th style="width:50px;">#</th><th>Permission</th><th>Group</th>
                    <th>Used In Roles</th><th>Created</th><th class="text-center">Action</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
</div>

{{-- Modal --}}
<div class="modal fade ps-modal" id="permModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon" id="permModalIcon"><i class="fas fa-key"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);" id="permModalTitle">Add Permission</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);" id="permModalSubtitle">Create a new permission</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="perm_id">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Permission Name <span class="text-danger">*</span></label>
                        <input type="text" id="perm_name" class="form-control" placeholder="e.g. posts.create">
                        <small style="font-size:.72rem;color:var(--muted2);">Format: <code>resource.action</code> — separate multiple with commas</small>
                        <small class="text-danger d-none" id="err_perm_name"></small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Guard</label>
                        <select id="perm_guard" class="form-select"><option value="web">web</option><option value="api">api</option></select>
                    </div>
                    <div class="col-12" id="bulkHint">
                        <div class="rounded-3 p-3" style="background:var(--surface);border:1px solid var(--border);">
                            <p class="mb-1" style="font-size:.72rem;font-weight:700;color:var(--muted2);">
                                <i class="fas fa-bolt me-1" style="color:#d97706;"></i> Bulk Create
                            </p>
                            <p class="mb-0" style="font-size:.72rem;color:var(--muted2);">
                                Separate with commas: <code>posts.view, posts.create, posts.edit</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                <button class="ps-btn ps-btn-primary" id="savePermBtn">
                    <span id="savePermBtnText"><i class="fas fa-plus"></i> Create</span>
                    <span id="savePermSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
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
    var editingId=null, activeGroup='';

    var table = $('#datatable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("permissions.getlist") }}', type:'POST', data:function(d){ d._token='{{ csrf_token() }}'; d.group=activeGroup; }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false,render:function(d,t,r,m){return m.row+1;}},
            {data:'name',name:'name'},{data:'group',name:'group',orderable:false,searchable:false},
            {data:'roles_count',name:'roles_count',orderable:false,searchable:false},
            {data:'created_at',name:'created_at',searchable:false},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-center'},
        ],
        order:[[1,'asc']], pageLength:25, lengthMenu:[10,25,50,100],
        drawCallback:function(s){ $('#stat-total-perms').text(s.json?.recordsTotal??'—'); }
    });

    $('#globalSearch').on('keyup',function(){table.search(this.value).draw();});
    $(document).on('click','#groupFilter span',function(){
        $('#groupFilter span').removeClass('active'); $(this).addClass('active'); activeGroup=$(this).data('group'); table.ajax.reload();
    });

    function resetModal(){ editingId=null; $('#perm_id').val(''); $('#perm_name').val('').removeClass('is-invalid'); $('#perm_guard').val('web'); $('#err_perm_name').addClass('d-none').text(''); $('#permModalTitle').text('Add Permission'); $('#permModalSubtitle').text('Create a new permission'); $('#permModalIcon').removeClass('amber'); $('#savePermBtnText').html('<i class="fas fa-plus"></i> Create'); $('#bulkHint').show(); }

    $('#addPermBtn').on('click',resetModal);
    $('#permModal').on('hidden.bs.modal',resetModal);

    $(document).on('click','.edit-perm-btn',function(){
        resetModal(); editingId=$(this).data('id');
        $('#perm_id').val(editingId); $('#perm_name').val($(this).data('name')); $('#perm_guard').val($(this).data('guard'));
        $('#permModalTitle').text('Edit Permission'); $('#permModalSubtitle').text('Update permission name or guard');
        $('#permModalIcon').addClass('amber'); $('#savePermBtnText').html('<i class="fas fa-save"></i> Save Changes'); $('#bulkHint').hide();
        $('#permModal').modal('show');
    });

    $('#savePermBtn').on('click',function(){
        $('#err_perm_name').addClass('d-none').text(''); $('#perm_name').removeClass('is-invalid');
        $('#savePermBtnText').addClass('d-none'); $('#savePermSpinner').removeClass('d-none'); $('#savePermBtn').prop('disabled',true);
        var isEdit=(editingId!==null), url=isEdit?'/admin/permissions/'+editingId+'/ajax-update':'{{ route("permissions.ajaxStore") }}';
        $.ajax({ url:url, type:'POST', data:{_token:'{{ csrf_token() }}',name:$('#perm_name').val(),guard_name:$('#perm_guard').val()},
            success:function(res){ if(res.success){ $('#permModal').modal('hide'); table.ajax.reload(null,false); Swal.fire({icon:'success',title:'Success',text:res.message,timer:1800,showConfirmButton:false}); }},
            error:function(xhr){ var e=xhr.responseJSON?.errors??{}; if(e.name){ $('#err_perm_name').removeClass('d-none').text(e.name[0]); $('#perm_name').addClass('is-invalid'); } else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#savePermBtnText').removeClass('d-none'); $('#savePermSpinner').addClass('d-none'); $('#savePermBtn').prop('disabled',false); }
        });
    });

    $(document).on('click','.delete-perm-btn',function(){
        var id=$(this).data('id'),name=$(this).data('name');
        Swal.fire({title:'Delete Permission?',html:'Delete <strong>'+name+'</strong>?<br><small class="text-muted">It will be removed from all roles.</small>',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/permissions/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ table.ajax.reload(null,false); Swal.fire({icon:'success',title:'Deleted!',text:res.message,timer:1500,showConfirmButton:false}); }},
                error:function(){ Swal.fire('Error','Could not delete.','error'); }});
        });
    });
});
</script>
@endpush