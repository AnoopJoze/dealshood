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
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#dbeafe;"><i class="fas fa-users" style="color:#1d4ed8;"></i></div>
            <div><div class="ps-kpi-val">{{ $stats['total'] }}</div><div class="ps-kpi-lbl">Total Users</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;"><i class="fas fa-check-circle" style="color:#059669;"></i></div>
            <div><div class="ps-kpi-val" style="color:#059669;">{{ $stats['active'] }}</div><div class="ps-kpi-lbl">Active</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef2f2;"><i class="fas fa-times-circle" style="color:#dc2626;"></i></div>
            <div><div class="ps-kpi-val" style="color:#dc2626;">{{ $stats['inactive'] }}</div><div class="ps-kpi-lbl">Inactive</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef3c7;"><i class="fas fa-user-plus" style="color:#d97706;"></i></div>
            <div><div class="ps-kpi-val" style="color:#d97706;">{{ $stats['today'] }}</div><div class="ps-kpi-lbl">Joined Today</div></div>
        </div>
    </div>
</div>

<div class="ps-card">
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Users Management</h4>
            <p class="ps-page-sub">Manage all registered users</p>
        </div>
        <div class="d-flex gap-2">
            <button class="ps-btn ps-btn-ghost" id="toggleFilters"><i class="fas fa-sliders-h"></i> Filters</button>
            <button class="ps-btn ps-btn-primary" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#userModal">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>
    </div>

    {{-- Filter panel --}}
    <div class="ps-filter-panel" id="filterPanel">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label">Name</label>
                <input type="text" id="filter_name" class="form-control form-control-sm" placeholder="Search name…"></div>
            <div class="col-md-3"><label class="form-label">Email</label>
                <input type="text" id="filter_email" class="form-control form-control-sm" placeholder="Search email…"></div>
            <div class="col-md-2"><label class="form-label">Role</label>
                <select id="filter_role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select></div>
            <div class="col-md-2"><label class="form-label">From</label>
                <input type="date" id="filter_start" class="form-control form-control-sm"></div>
            <div class="col-md-2"><label class="form-label">To</label>
                <input type="date" id="filter_end" class="form-control form-control-sm"></div>
            <div class="col-12 d-flex gap-2">
                <button id="applyFilter" class="ps-btn ps-btn-primary"><i class="fas fa-search"></i> Apply</button>
                <button id="clearFilter" class="ps-btn ps-btn-ghost"><i class="fas fa-times"></i> Clear</button>
            </div>
        </div>
    </div>

    {{-- Status pills --}}
    <div style="padding:.75rem 1.4rem; border-bottom:1px solid var(--border);">
        <div class="ps-pills d-flex flex-wrap gap-2" id="statusPills">
            <span class="active" data-status=""><i class="fas fa-th-list"></i> All <strong>{{ $stats['total'] }}</strong></span>
            <span data-status="Active" style="background:#d1fae5;color:#059669;border-color:#a7f3d0;"><i class="fas fa-check-circle"></i> Active <strong>{{ $stats['active'] }}</strong></span>
            <span data-status="Inactive" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;"><i class="fas fa-times-circle"></i> Inactive <strong>{{ $stats['inactive'] }}</strong></span>
        </div>
    </div>

    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead><tr>
                    <th style="width:50px;">#</th><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th>
                    <th class="text-center" style="width:110px;">Action</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

</div>

{{-- Modal --}}
<div class="modal fade ps-modal" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                         id="modalAvatar"
                         style="width:44px;height:44px;font-size:1.1rem;flex-shrink:0;background:var(--dk);color:#fff;">
                        <i class="fas fa-user-plus" id="modalAvatarIcon"></i>
                        <span id="modalAvatarLetter" class="d-none"></span>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);" id="userModalLabel">Add New User</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);" id="modalSubtitle">Fill in the details to create a new user</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="ps-tab-nav" id="userModalTabs">
                <button class="ps-tab-link active" data-tab="tab-account"><i class="fas fa-user"></i> Account</button>
                <button class="ps-tab-link" data-tab="tab-business"><i class="fas fa-building"></i> Business</button>
                <button class="ps-tab-link" data-tab="tab-address"><i class="fas fa-map-marker-alt"></i> Address</button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="user_id">

                {{-- Account --}}
                <div class="modal-tab-pane" id="tab-account">
                    <p class="modal-section-lbl">Account Information</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="user_name" class="form-control" placeholder="Enter full name">
                            <small class="text-danger d-none" id="err_name"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="user_email" class="form-control" placeholder="Enter email">
                            <small class="text-danger d-none" id="err_email"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger" id="pw_required_star">*</span><span class="fw-normal d-none" style="color:var(--muted2);" id="pw_optional_hint"> (blank = keep current)</span></label>
                            <div class="input-group">
                                <input type="password" id="user_password" class="form-control" placeholder="Min. 8 characters">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password"><i class="fas fa-eye"></i></button>
                            </div>
                            <small class="text-danger d-none" id="err_password"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password <span class="text-danger" id="pwc_required_star">*</span></label>
                            <div class="input-group">
                                <input type="password" id="user_password_confirmation" class="form-control" placeholder="Repeat password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password_confirmation"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="user_role" class="form-select">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="err_role"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="user_status" class="form-select">
                                <option value="Active">Active</option><option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone" style="font-size:.75rem;color:var(--muted2);"></i></span>
                                <input type="text" id="user_phone" class="form-control border-start-0" placeholder="+971 xx xxx xxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fab fa-whatsapp text-success" style="font-size:.75rem;"></i></span>
                                <input type="text" id="user_whatsapp_number" class="form-control border-start-0" placeholder="+971 xx xxx xxxx">
                            </div>
                        </div>
                    </div>
                    <hr style="border-color:var(--border);margin:1rem 0;">
                    <p class="modal-section-lbl">About</p>
                    <textarea id="user_about_me" class="form-control" rows="3" placeholder="Short bio…"></textarea>
                </div>

                {{-- Business --}}
                <div class="modal-tab-pane d-none" id="tab-business">
                    <p class="modal-section-lbl">Business Information</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-building" style="font-size:.75rem;color:var(--muted2);"></i></span>
                                <input type="text" id="user_company_name" class="form-control border-start-0" placeholder="Company name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-globe" style="font-size:.75rem;color:var(--muted2);"></i></span>
                                <input type="url" id="user_website" class="form-control border-start-0" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="modal-tab-pane d-none" id="tab-address">
                    <p class="modal-section-lbl">Address</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">City / Region</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-city" style="font-size:.75rem;color:var(--muted2);"></i></span>
                                <input type="text" id="user_location" class="form-control border-start-0" placeholder="City or region">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Full Address</label>
                            <textarea id="user_address" class="form-control" rows="2" placeholder="Street, building, area…"></textarea>
                        </div>
                    </div>
                    <hr style="border-color:var(--border);margin:1rem 0;">
                    <p class="modal-section-lbl">GPS Coordinates <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--muted);">(optional)</span></p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LAT</span>
                                <input type="number" id="user_latitude" class="form-control border-start-0" placeholder="25.2048" step="any" min="-90" max="90">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LNG</span>
                                <input type="number" id="user_longitude" class="form-control border-start-0" placeholder="55.2708" step="any" min="-180" max="180">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                <button class="ps-btn ps-btn-primary" id="saveUserBtn">
                    <span id="saveBtnText"><i class="fas fa-save"></i> Save User</span>
                    <span id="saveBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
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
    var editingId=null, activeStatus='';

    var table = $('#datatable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("users.getlist") }}', type:'POST',
               data:function(d){ d._token='{{ csrf_token() }}'; d.name=$('#filter_name').val(); d.email=$('#filter_email').val();
                                  d.status=activeStatus||''; d.role=$('#filter_role').val(); d.start_date=$('#filter_start').val(); d.end_date=$('#filter_end').val(); }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false,render:function(d,t,r,m){return m.row+1;}},
            {data:'name',name:'name'},{data:'email',name:'email'},{data:'role',name:'role',orderable:false},
            {data:'status',name:'status',orderable:false},{data:'created_at',name:'created_at',searchable:false},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-center'},
        ],
        order:[[5,'desc']], pageLength:25, lengthMenu:[10,25,50,100]
    });

    $(document).on('click','#statusPills span',function(){
        $('#statusPills span').removeClass('active').css({background:'',color:'',borderColor:''});
        $(this).addClass('active'); activeStatus=$(this).data('status'); table.ajax.reload();
    });

    $('#toggleFilters').on('click',()=>$('#filterPanel').toggleClass('open'));
    $('#applyFilter').on('click',function(){ activeStatus=''; $('#statusPills span').removeClass('active'); $('#statusPills span:first').addClass('active'); table.ajax.reload(); });
    $('#clearFilter').on('click',function(){ activeStatus=''; $('#filter_name,#filter_email,#filter_start,#filter_end').val(''); $('#filter_role').val(''); $('#statusPills span').removeClass('active'); $('#statusPills span:first').addClass('active'); table.ajax.reload(); });
    $('#filterPanel input,#filterPanel select').on('keyup change',function(e){ if(e.key==='Enter'||e.type==='change') table.ajax.reload(); });

    // Tab switching
    $(document).on('click','#userModalTabs .ps-tab-link',function(){
        var target=$(this).data('tab');
        $('#userModalTabs .ps-tab-link').removeClass('active');
        $(this).addClass('active'); $('.modal-tab-pane').addClass('d-none'); $('#'+target).removeClass('d-none');
    });
    function switchToTab(id){ $('#userModalTabs .ps-tab-link[data-tab="'+id+'"]').trigger('click'); }

    var fieldTabMap={ name:'tab-account',email:'tab-account',password:'tab-account',role:'tab-account',status:'tab-account',phone:'tab-account',whatsapp_number:'tab-account',about_me:'tab-account',company_name:'tab-business',website:'tab-business',location:'tab-address',address:'tab-address',latitude:'tab-address',longitude:'tab-address' };

    function clearErrors(){ $('.text-danger[id^="err_"]').addClass('d-none').text(''); $('.form-control,.form-select').removeClass('is-invalid'); $('#userModalTabs .tab-err-dot').remove(); }
    function showErrors(errors){
        var firstTab=null,tabsWithErrors={};
        $.each(errors,function(field,messages){
            var errEl=$('#err_'+field),inputEl=$('#user_'+field);
            if(errEl.length) errEl.removeClass('d-none').text(messages[0]);
            if(inputEl.length) inputEl.addClass('is-invalid');
            var tab=fieldTabMap[field]||'tab-account'; tabsWithErrors[tab]=true; if(!firstTab) firstTab=tab;
        });
        $.each(tabsWithErrors,function(tab){ var link=$('#userModalTabs .ps-tab-link[data-tab="'+tab+'"]'); if(!link.find('.tab-err-dot').length) link.append('<span class="tab-err-dot ms-1"></span>'); });
        if(firstTab) switchToTab(firstTab);
    }

    function resetModal(){ clearErrors(); $('#user_name,#user_email,#user_phone,#user_whatsapp_number,#user_company_name,#user_website,#user_location,#user_latitude,#user_longitude').val(''); $('#user_about_me,#user_address').val(''); $('#user_password,#user_password_confirmation').val(''); $('#user_role').val(''); $('#user_status').val('Active'); $('#user_id').val(''); switchToTab('tab-account'); }

    function setCreateMode(){ editingId=null; $('#userModalLabel').text('Add New User'); $('#modalSubtitle').text('Fill in the details to create a new user'); $('#modalAvatar').css({background:'var(--dk)'}); $('#modalAvatarIcon').removeClass('d-none'); $('#modalAvatarLetter').addClass('d-none').text(''); $('#pw_required_star,#pwc_required_star').removeClass('d-none'); $('#pw_optional_hint').addClass('d-none'); $('#saveBtnText').html('<i class="fas fa-user-plus"></i> Create User'); }

    function setEditMode(user){ editingId=user.id; $('#user_id').val(user.id); $('#user_name').val(user.name); $('#user_email').val(user.email); $('#user_phone').val(user.phone??''); $('#user_whatsapp_number').val(user.whatsapp_number??''); $('#user_about_me').val(user.about_me??''); $('#user_status').val(user.status); $('#user_role').val(user.role); $('#user_company_name').val(user.company_name??''); $('#user_website').val(user.website??''); $('#user_location').val(user.location??''); $('#user_address').val(user.address??''); $('#user_latitude').val(user.latitude??''); $('#user_longitude').val(user.longitude??''); $('#userModalLabel').text('Edit User'); $('#modalSubtitle').text('Editing '+user.name+' — leave password blank to keep current'); $('#modalAvatar').css({background:'linear-gradient(135deg,#d97706,#f59e0b)'}); $('#modalAvatarIcon').addClass('d-none'); $('#modalAvatarLetter').removeClass('d-none').text(user.name.charAt(0).toUpperCase()); $('#pw_required_star,#pwc_required_star').addClass('d-none'); $('#pw_optional_hint').removeClass('d-none'); $('#saveBtnText').html('<i class="fas fa-save"></i> Save Changes'); }

    $('#addUserBtn').on('click',function(){ resetModal(); setCreateMode(); });
    $('#userModal').on('hidden.bs.modal',function(){ resetModal(); setCreateMode(); });

    $(document).on('click','.edit-btn',function(){
        var id=$(this).data('id'); resetModal();
        $.get('/admin/users/'+id+'/edit-data',function(user){ setEditMode(user); $('#userModal').modal('show'); }).fail(()=>Swal.fire('Error','Could not load user data.','error'));
    });

    $('#saveUserBtn').on('click',function(){
        clearErrors(); $('#saveBtnText').addClass('d-none'); $('#saveBtnSpinner').removeClass('d-none'); $('#saveUserBtn').prop('disabled',true);
        var isEdit=(editingId!==null), url=isEdit?'/admin/users/'+editingId+'/ajax-update':'{{ route("users.ajaxStore") }}';
        $.ajax({ url:url, type:'POST', data:{ _token:'{{ csrf_token() }}', name:$('#user_name').val(), email:$('#user_email').val(), password:$('#user_password').val(), password_confirmation:$('#user_password_confirmation').val(), role:$('#user_role').val(), status:$('#user_status').val(), phone:$('#user_phone').val(), whatsapp_number:$('#user_whatsapp_number').val(), about_me:$('#user_about_me').val(), company_name:$('#user_company_name').val(), website:$('#user_website').val(), location:$('#user_location').val(), address:$('#user_address').val(), latitude:$('#user_latitude').val(), longitude:$('#user_longitude').val() },
            success:function(res){ if(res.success){ $('#userModal').modal('hide'); table.ajax.reload(null,false); Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:2000,showConfirmButton:false}); }},
            error:function(xhr){ if(xhr.status===422) showErrors(xhr.responseJSON.errors??{}); else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveBtnText').removeClass('d-none'); $('#saveBtnSpinner').addClass('d-none'); $('#saveUserBtn').prop('disabled',false); }
        });
    });

    $(document).on('click','.delete-btn',function(){
        var id=$(this).data('id'),name=$(this).data('name');
        Swal.fire({title:'Delete User?',html:'Delete <strong>'+name+'</strong>?',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/users/'+id,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ table.ajax.reload(null,false); Swal.fire({toast:true,position:'top-end',icon:'success',title:res.message,timer:1800,showConfirmButton:false}); }}});
        });
    });

    $(document).on('click','.toggle-pw',function(){
        var target=$('#'+$(this).data('target')); target.attr('type',target.attr('type')==='password'?'text':'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
});
</script>
@endpush