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

.ps-btn {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.77rem; font-weight:600; border-radius:8px;
    padding:.48rem .9rem; cursor:pointer; border:1.5px solid;
    transition:all .14s; text-decoration:none; white-space:nowrap;
}
.ps-btn-primary { background:var(--dk); color:#fff; border-color:var(--dk); }
.ps-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; box-shadow:0 3px 12px rgba(99,102,241,.3); }
.ps-btn-ghost { background:#fff; color:var(--muted); border-color:var(--border); }
.ps-btn-ghost:hover { background:var(--surface); color:var(--dk); }

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

.inline-edit { transition:box-shadow .15s,background .15s; border-radius:8px !important; }
.inline-edit:focus { background:#fff !important; box-shadow:0 0 0 3px rgba(99,102,241,.12) !important; border-color:var(--accent) !important; }
.inline-edit.saving { opacity:.5; pointer-events:none; }
.inline-edit.saved  { background:#f0fdf4 !important; transition:background .3s; }
.inline-edit.error  { background:#fef2f2 !important; }

.ps-modal .modal-content { border:none; border-radius:14px; box-shadow:0 24px 60px rgba(15,23,42,.18); }
.ps-modal .modal-header { padding:1.2rem 1.4rem .9rem; border-bottom:1px solid var(--border); }
.ps-modal-icon {
    width:44px; height:44px; border-radius:10px; flex-shrink:0;
    background:var(--dk); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:1rem;
}
.ps-modal .form-label { font-size:.78rem; font-weight:600; color:var(--dk); margin-bottom:5px; }
.ps-modal .form-control,
.ps-modal .form-select {
    font-size:.84rem; border-color:var(--border); border-radius:8px; color:var(--dk);
}
.ps-modal .form-control:focus,
.ps-modal .form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.ps-modal .modal-footer { padding:.9rem 1.4rem; border-top:1px solid var(--border); }
.ad-preview {
    width:100%; height:140px; border-radius:10px; object-fit:cover;
    background:var(--surface); border:1px solid var(--border);
}
</style>
@endpush

<div>

{{-- KPI --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#ede9fe;"><i class="fas fa-ad" style="color:#7c3aed;"></i></div>
            <div><div class="ps-kpi-val">{{ $stats['total'] }}</div><div class="ps-kpi-lbl">Total Ads</div></div>
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
            <h4 class="ps-page-title">Ads Management</h4>
            <p class="ps-page-sub">Banner ads shown on the listing page. Click Title / Link / Order / Status to edit inline.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="ps-btn ps-btn-ghost" id="toggleFilters"><i class="fas fa-sliders-h"></i> Filters</button>
            <button class="ps-btn ps-btn-primary" data-bs-toggle="modal" data-bs-target="#adModal">
                <i class="fas fa-plus"></i> Add Ad
            </button>
        </div>
    </div>

    {{-- Filter panel --}}
    <div class="ps-filter-panel" id="filterPanel">
        <div class="row g-3 align-items-end">
            <div class="col-md-4"><label class="form-label">Search</label>
                <input type="text" id="globalSearch" class="form-control form-control-sm" placeholder="Ad title…"></div>
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
                    <th style="width:80px;">Image</th>
                    <th>Title</th><th>Link URL</th><th style="width:80px;">Order</th><th>Status</th><th>Created</th>
                    <th class="text-center" style="width:80px;">Actions</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
        <p style="font-size:.72rem;color:var(--muted2);margin-top:.75rem;">
            <i class="fas fa-info-circle me-1" style="color:var(--accent);"></i>
            Use the image icon to replace an ad's picture. Ads with the lowest order number show first.
        </p>
    </div>
</div>

</div>

{{-- Add Modal --}}
<div class="modal fade ps-modal" id="adModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="adForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ps-modal-icon"><i class="fas fa-ad"></i></div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">Add Ad</h5>
                            <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">Create a new banner ad</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="ad_title" class="form-control" placeholder="e.g. Diwali Sale Banner">
                    <small class="text-danger d-none" id="err_ad_title"></small>

                    <label class="form-label mt-3">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="ad_image" class="form-control" accept="image/png,image/jpeg,image/webp">
                    <small class="text-danger d-none" id="err_ad_image"></small>
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;">PNG, JPG or WEBP. Max 5MB.</small>

                    <label class="form-label mt-3">Link URL</label>
                    <input type="text" name="link_url" id="ad_link" class="form-control" placeholder="https://example.com (optional)">
                    <small class="text-danger d-none" id="err_ad_link"></small>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <button type="submit" class="ps-btn ps-btn-primary" id="saveAd">
                        <span id="saveAdText"><i class="fas fa-plus"></i> Create Ad</span>
                        <span id="saveAdSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade ps-modal" id="adEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="adEditForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ps-modal-icon"><i class="fas fa-pen"></i></div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">Edit Ad</h5>
                            <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">Update details or replace the image</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <img src="" id="edit_ad_preview" class="ad-preview mb-3">
                    <input type="hidden" id="edit_ad_id">

                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="edit_ad_title" class="form-control">
                    <small class="text-danger d-none" id="err_edit_ad_title"></small>

                    <label class="form-label mt-3">Replace Image</label>
                    <input type="file" name="image" id="edit_ad_image" class="form-control" accept="image/png,image/jpeg,image/webp">
                    <small class="text-danger d-none" id="err_edit_ad_image"></small>
                    <small class="text-muted d-block mt-1" style="font-size:.7rem;">Leave empty to keep the current image.</small>

                    <label class="form-label mt-3">Link URL</label>
                    <input type="text" name="link_url" id="edit_ad_link" class="form-control" placeholder="https://example.com (optional)">
                    <small class="text-danger d-none" id="err_edit_ad_link"></small>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <button type="submit" class="ps-btn ps-btn-primary" id="saveAdEdit">
                        <span id="saveAdEditText"><i class="fas fa-save"></i> Save Changes</span>
                        <span id="saveAdEditSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
                    </button>
                </div>
            </form>
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
        ajax:{ url:'{{ route("ads.data") }}', type:'POST',
               data:function(d){ d._token='{{ csrf_token() }}'; d.status=activeStatus;
                                  d.start_date=$('#filter_start').val(); d.end_date=$('#filter_end').val(); }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false},
            {data:'image',name:'image',orderable:false,searchable:false},
            {data:'title',name:'title'},
            {data:'link_url',name:'link_url',orderable:false,searchable:false},
            {data:'sort_order',name:'sort_order',orderable:false,searchable:false},
            {data:'status',name:'status',orderable:false,searchable:false},
            {data:'created_at',name:'created_at',searchable:false},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-center'},
        ],
        order:[[4,'asc']], pageLength:25, lengthMenu:[10,25,50,100],
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
        $.ajax({ url:'{{ route("ads.inlineUpdate") }}', type:'POST',
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
        var id=$(this).data('id'),title=$(this).data('title');
        Swal.fire({title:'Delete Ad?',html:'Delete <strong>'+title+'</strong>? This cannot be undone.',icon:'warning',showCancelButton:true,
            confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/ads/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ table.ajax.reload(null,false);
                    Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:1800,showConfirmButton:false}); }}});
        });
    });

    $('#adModal').on('hidden.bs.modal',function(){
        $('#adForm')[0].reset();
        $('#adForm .is-invalid').removeClass('is-invalid');
        $('#adForm small.text-danger').addClass('d-none').text('');
    });

    $('#adForm').on('submit',function(e){
        e.preventDefault();
        $('#adForm small.text-danger').addClass('d-none').text('');
        $('#adForm .is-invalid').removeClass('is-invalid');
        $('#saveAdText').addClass('d-none'); $('#saveAdSpinner').removeClass('d-none'); $('#saveAd').prop('disabled',true);

        var fd = new FormData(this);
        fd.append('_token','{{ csrf_token() }}');

        $.ajax({ url:'{{ route("ads.store") }}', type:'POST', data:fd, processData:false, contentType:false,
            success:function(res){ if(res.success){ $('#adModal').modal('hide'); table.ajax.reload(null,false);
                Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:2000,showConfirmButton:false}); }},
            error:function(xhr){ var errs=xhr.responseJSON?.errors??{};
                Object.keys(errs).forEach(function(field){
                    $('#ad_'+field).addClass('is-invalid');
                    $('#err_ad_'+field).removeClass('d-none').text(errs[field][0]);
                });
                if(!Object.keys(errs).length) Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveAdText').removeClass('d-none'); $('#saveAdSpinner').addClass('d-none'); $('#saveAd').prop('disabled',false); }
        });
    });

    $(document).on('click','.edit-btn',function(){
        $('#edit_ad_id').val($(this).data('id'));
        $('#edit_ad_title').val($(this).data('title'));
        $('#edit_ad_link').val($(this).data('link'));
        $('#edit_ad_preview').attr('src',$(this).data('image'));
        $('#edit_ad_image').val('');
        $('#adEditForm small.text-danger').addClass('d-none').text('');
        $('#adEditForm .is-invalid').removeClass('is-invalid');
        new bootstrap.Modal('#adEditModal').show();
    });

    $('#adEditForm').on('submit',function(e){
        e.preventDefault();
        $('#adEditForm small.text-danger').addClass('d-none').text('');
        $('#adEditForm .is-invalid').removeClass('is-invalid');
        $('#saveAdEditText').addClass('d-none'); $('#saveAdEditSpinner').removeClass('d-none'); $('#saveAdEdit').prop('disabled',true);

        var fd = new FormData(this);
        fd.append('_token','{{ csrf_token() }}');
        fd.append('_method','PUT');
        var id = $('#edit_ad_id').val();

        $.ajax({ url:'/admin/ads/'+id, type:'POST', data:fd, processData:false, contentType:false,
            success:function(res){ if(res.success){ $('#adEditModal').modal('hide'); table.ajax.reload(null,false);
                Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:2000,showConfirmButton:false}); }},
            error:function(xhr){ var errs=xhr.responseJSON?.errors??{};
                Object.keys(errs).forEach(function(field){
                    $('#edit_ad_'+field).addClass('is-invalid');
                    $('#err_edit_ad_'+field).removeClass('d-none').text(errs[field][0]);
                });
                if(!Object.keys(errs).length) Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveAdEditText').removeClass('d-none'); $('#saveAdEditSpinner').addClass('d-none'); $('#saveAdEdit').prop('disabled',false); }
        });
    });
});
</script>
@endpush
