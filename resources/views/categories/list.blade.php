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

{{-- KPI --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#dbeafe;"><i class="fas fa-tags" style="color:#1d4ed8;"></i></div>
            <div><div class="ps-kpi-val">{{ $stats['total'] }}</div><div class="ps-kpi-lbl">Total Categories</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;"><i class="fas fa-check-circle" style="color:#059669;"></i></div>
            <div><div class="ps-kpi-val" style="color:#059669;">{{ $stats['active'] }}</div><div class="ps-kpi-lbl">Active</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef2f2;"><i class="fas fa-times-circle" style="color:#dc2626;"></i></div>
            <div><div class="ps-kpi-val" style="color:#dc2626;">{{ $stats['inactive'] }}</div><div class="ps-kpi-lbl">Inactive</div></div>
        </div>
    </div>
</div>

{{-- Main --}}
<div class="ps-card">
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Categories Management</h4>
            <p class="ps-page-sub">Click any cell to edit inline — changes save automatically</p>
        </div>
        <div class="d-flex gap-2">
            <button class="ps-btn ps-btn-ghost" id="toggleFilters"><i class="fas fa-sliders-h"></i> Filters</button>
            <button class="ps-btn ps-btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
    </div>

    {{-- Filter panel --}}
    <div class="ps-filter-panel" id="filterPanel">
        <div class="row g-3 align-items-end">
            <div class="col-md-4"><label class="form-label">Search</label>
                <input type="text" id="globalSearch" class="form-control form-control-sm" placeholder="Category name…"></div>
            <div class="col-md-2"><label class="form-label">From</label>
                <input type="date" id="filter_start" class="form-control form-control-sm"></div>
            <div class="col-md-2"><label class="form-label">To</label>
                <input type="date" id="filter_end" class="form-control form-control-sm"></div>
            <div class="col-md-4 d-flex gap-2 align-items-end">
                <button id="applyFilter" class="ps-btn ps-btn-primary"><i class="fas fa-search"></i> Apply</button>
                <button id="clearFilter" class="ps-btn ps-btn-ghost"><i class="fas fa-times"></i> Clear</button>
            </div>
        </div>
    </div>

    {{-- Status pills --}}
    <div style="padding:.75rem 1.4rem; border-bottom:1px solid var(--border);">
        <div class="ps-pills d-flex flex-wrap gap-2">
            <span class="active" data-status=""><i class="fas fa-th"></i> All <strong>{{ $stats['total'] }}</strong></span>
            <span data-status="1" style="background:#d1fae5;color:#059669;border-color:#a7f3d0;">
                <i class="fas fa-check-circle"></i> Active <strong>{{ $stats['active'] }}</strong>
            </span>
            <span data-status="0" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;">
                <i class="fas fa-times-circle"></i> Inactive <strong>{{ $stats['inactive'] }}</strong>
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead><tr>
                    <th style="width:50px;">#</th>
                    <th>Name</th><th>Subcategories</th><th>Status</th><th>Created</th>
                    <th class="text-center" style="width:60px;">Del</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <p style="font-size:.72rem;color:var(--muted2);margin-top:.75rem;">
            <i class="fas fa-info-circle me-1" style="color:var(--accent);"></i>
            Click any <strong>Name</strong> or <strong>Status</strong> cell to edit directly.
        </p>
    </div>
</div>

</div>

{{-- Modal --}}
<div class="modal fade ps-modal" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon"><i class="fas fa-tag"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">Add Category</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">Create a new category</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" id="category_name" class="form-control" placeholder="e.g. Electronics, Fashion…">
                <small class="text-danger d-none" id="err_category_name"></small>
                <div class="mt-3 rounded-3 p-3" style="background:var(--surface);border:1px solid var(--border);">
                    <p class="mb-0" style="font-size:.72rem;color:var(--muted2);">
                        <i class="fas fa-info-circle me-1" style="color:var(--accent);"></i>
                        Slug is auto-generated. Manage subcategories from the Subcategories page.
                    </p>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                <button class="ps-btn ps-btn-primary" id="saveCategory">
                    <span id="saveCategoryText"><i class="fas fa-plus"></i> Create Category</span>
                    <span id="saveCategorySpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
    var activeStatus = '', saveTimeout = null;

    var table = $('#datatable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("categories.data") }}', type:'POST',
               data:function(d){ d._token='{{ csrf_token() }}'; d.status=activeStatus;
                                  d.start_date=$('#filter_start').val(); d.end_date=$('#filter_end').val(); }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false},
            {data:'name',name:'name'},
            {data:'subcategories_count',name:'subcategories_count',orderable:false,searchable:false},
            {data:'status',name:'status',orderable:false,searchable:false},
            {data:'created_at',name:'created_at',searchable:false},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-center'},
        ],
        order:[[1,'asc']], pageLength:25, lengthMenu:[10,25,50,100],
        language:{ processing:'<div class="d-flex align-items-center gap-2 justify-content-center py-3"><div class="spinner-border spinner-border-sm" style="color:var(--accent);"></div><span style="font-size:.82rem;color:var(--muted);">Loading…</span></div>' }
    });

    $(document).on('click','.ps-pills span',function(){
        $('.ps-pills span').removeClass('active').css({background:'',color:'',borderColor:''});
        $(this).addClass('active'); activeStatus=$(this).data('status'); table.ajax.reload();
    });

    $('#toggleFilters').on('click',()=>$('#filterPanel').toggleClass('open'));
    $('#applyFilter').on('click',()=>table.ajax.reload());
    $('#clearFilter').on('click',function(){
        $('#filter_start,#filter_end,#globalSearch').val(''); table.search('').ajax.reload();
    });
    $('#globalSearch').on('keyup',function(){table.search(this.value).draw();});
    $('#filter_start,#filter_end').on('change',()=>table.ajax.reload());

    function inlineSave($el){
        $el.addClass('saving');
        $.ajax({ url:'{{ route("categories.inlineUpdate") }}', type:'POST',
            data:{_token:'{{ csrf_token() }}',id:$el.data('id'),field:$el.data('field'),value:$el.val()},
            success:function(r){ if(r.success){ $el.removeClass('saving').addClass('saved'); setTimeout(()=>$el.removeClass('saved'),1200); }},
            error:function(xhr){ $el.removeClass('saving').addClass('error'); setTimeout(()=>$el.removeClass('error'),2000);
                Swal.fire({toast:true,position:'top-end',icon:'error',title:xhr.responseJSON?.errors?.value?.[0]??'Could not save',timer:2500,showConfirmButton:false}); }
        });
    }
    $(document).on('change','select.inline-edit',function(){inlineSave($(this));});
    $(document).on('keyup','input.inline-edit',function(){ clearTimeout(saveTimeout); var $el=$(this); saveTimeout=setTimeout(()=>inlineSave($el),600); });
    $(document).on('blur','input.inline-edit',function(){ clearTimeout(saveTimeout); inlineSave($(this)); });

    $(document).on('click','.delete-btn',function(){
        var id=$(this).data('id'),name=$(this).data('name'),subs=parseInt($(this).data('subs')||0);
        var html='Delete <strong>'+name+'</strong>?';
        if(subs>0) html+='<br><small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>'+subs+' subcategory/ies will be unlinked.</small>';
        Swal.fire({title:'Delete Category?',html:html,icon:'warning',showCancelButton:true,
            confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/categories/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ table.ajax.reload(null,false);
                    Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:1800,showConfirmButton:false}); }}});
        });
    });

    $('#categoryModal').on('hidden.bs.modal',function(){ $('#category_name').val('').removeClass('is-invalid'); $('#err_category_name').addClass('d-none').text(''); });
    $('#category_name').on('keydown',e=>{ if(e.key==='Enter') $('#saveCategory').trigger('click'); });

    $('#saveCategory').on('click',function(){
        $('#err_category_name').addClass('d-none').text(''); $('#category_name').removeClass('is-invalid');
        $('#saveCategoryText').addClass('d-none'); $('#saveCategorySpinner').removeClass('d-none'); $('#saveCategory').prop('disabled',true);
        $.ajax({ url:'{{ route("categories.ajaxStore") }}', type:'POST',
            data:{_token:'{{ csrf_token() }}',name:$('#category_name').val()},
            success:function(res){ if(res.success){ $('#categoryModal').modal('hide'); table.ajax.reload(null,false);
                Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:2000,showConfirmButton:false}); }},
            error:function(xhr){ var e=xhr.responseJSON?.errors??{};
                if(e.name){ $('#err_category_name').removeClass('d-none').text(e.name[0]); $('#category_name').addClass('is-invalid'); }
                else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveCategoryText').removeClass('d-none'); $('#saveCategorySpinner').addClass('d-none'); $('#saveCategory').prop('disabled',false); }
        });
    });
});
</script>
@endpush
