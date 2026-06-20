@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link href="{{ asset('assets') }}/DataTables/datatables.min.css" rel="stylesheet">
<style>
:root {
    --dk: #0f172a; --accent: #6366f1; --surface: #f8fafc;
    --border: #f1f5f9; --muted: #64748b; --muted2: #94a3b8;
    --r: 10px; --sh: 0 2px 16px rgba(15,23,42,.07);
}
.ps-kpi {
    background: #fff; border: 1px solid var(--border); border-radius: var(--r);
    box-shadow: var(--sh); padding: 1rem 1.2rem;
    display: flex; align-items: center; gap: 14px;
}
.ps-kpi-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; flex-shrink: 0;
}
.ps-kpi-val { font-size: 1.5rem; font-weight: 800; line-height: 1; color: var(--dk); }
.ps-kpi-lbl {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted2); margin-top: 3px;
}
.ps-card {
    background: #fff; border: 1px solid var(--border); border-radius: var(--r);
    box-shadow: var(--sh); overflow: hidden;
}
.ps-card-header {
    padding: 1.1rem 1.4rem .9rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.ps-page-title { font-size: 1rem; font-weight: 800; color: var(--dk); margin: 0; }
.ps-page-sub { font-size: .75rem; color: var(--muted2); margin: 2px 0 0; }
.ps-view-tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--border); padding: 0 1.4rem; }
.ps-view-tab {
    font-size: .77rem; font-weight: 600; padding: .6rem .9rem; border: none;
    background: transparent; cursor: pointer; color: var(--muted);
    border-bottom: 2px solid transparent; margin-bottom: -1px;
    display: inline-flex; align-items: center; gap: 6px;
}
.ps-view-tab.active { color: var(--accent); border-bottom-color: var(--accent); }
.ps-tab-badge { font-size: .62rem; font-weight: 700; padding: 1px 6px; border-radius: 100px; }
#datatable thead th {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--muted2); background: var(--surface); border-bottom: 1px solid var(--border);
    vertical-align: middle; white-space: nowrap; padding: .7rem 1rem;
}
#datatable tbody td {
    vertical-align: middle; white-space: nowrap; font-size: .82rem;
    color: var(--dk); padding: .65rem 1rem; border-bottom: 1px solid var(--border);
}
#datatable tbody tr:hover td { background: var(--surface); }
</style>
@endpush

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#dbeafe;"><i class="fas fa-inbox" style="color:#1d4ed8;"></i></div>
            <div><div class="ps-kpi-val" id="stat-total">—</div><div class="ps-kpi-lbl">Total</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef3c7;"><i class="fas fa-clock" style="color:#d97706;"></i></div>
            <div><div class="ps-kpi-val" style="color:#d97706;" id="stat-pending">—</div><div class="ps-kpi-lbl">Pending</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#d1fae5;"><i class="fas fa-check-circle" style="color:#059669;"></i></div>
            <div><div class="ps-kpi-val" style="color:#059669;" id="stat-approved">—</div><div class="ps-kpi-lbl">Approved</div></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="ps-kpi">
            <div class="ps-kpi-icon" style="background:#fef2f2;"><i class="fas fa-times-circle" style="color:#dc2626;"></i></div>
            <div><div class="ps-kpi-val" style="color:#dc2626;" id="stat-rejected">—</div><div class="ps-kpi-lbl">Rejected</div></div>
        </div>
    </div>
</div>

<div class="ps-card">
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Ad Submissions</h4>
            <p class="ps-page-sub">User-submitted ads pending review</p>
        </div>
    </div>

    <div class="ps-view-tabs">
        <button class="ps-view-tab active" data-status="">All <span class="ps-tab-badge" style="background:#ede9fe;color:#7c3aed;" id="badge-all">—</span></button>
        <button class="ps-view-tab" data-status="pending">Pending <span class="ps-tab-badge" style="background:#fef3c7;color:#d97706;" id="badge-pending">—</span></button>
        <button class="ps-view-tab" data-status="approved">Approved <span class="ps-tab-badge" style="background:#d1fae5;color:#059669;" id="badge-approved">—</span></button>
        <button class="ps-view-tab" data-status="rejected">Rejected <span class="ps-tab-badge" style="background:#fef2f2;color:#dc2626;" id="badge-rejected">—</span></button>
    </div>

    <div style="padding:0 1.4rem 1.4rem;">
        <div class="table-responsive mt-3">
            <table id="datatable" class="table align-middle table-hover mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:110px;">Action</th>
                        <th>Title</th>
                        <th>Submitter</th>
                        <th>Category</th>
                        <th>Locality</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/DataTables/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let table, currentStatus = '';

$('#datatable').DataTable && $(function () {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("ad-submissions.data") }}',
            type: 'POST',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
                d.status = currentStatus;
            }
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'submitter', name: 'submitter' },
            { data: 'category', name: 'category', orderable: false },
            { data: 'locality', name: 'locality', orderable: false },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
        ],
        order: [[6, 'desc']],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        drawCallback: function (settings) {
            const json = settings.json ?? {};
            $('#stat-total').text(json.totalCount ?? '—');
            $('#stat-pending').text(json.pendingCount ?? '—');
            $('#stat-approved').text(json.approvedCount ?? '—');
            $('#stat-rejected').text(json.rejectedCount ?? '—');
            $('#badge-all').text(json.totalCount ?? '—');
            $('#badge-pending').text(json.pendingCount ?? '—');
            $('#badge-approved').text(json.approvedCount ?? '—');
            $('#badge-rejected').text(json.rejectedCount ?? '—');
        },
        language: {
            processing: '<div class="d-flex align-items-center gap-2 justify-content-center py-3"><div class="spinner-border spinner-border-sm" style="color:var(--accent);"></div><span style="font-size:.82rem;color:var(--muted);">Loading…</span></div>',
        }
    });
});

$(document).on('click', '.ps-view-tab', function () {
    $('.ps-view-tab').removeClass('active');
    $(this).addClass('active');
    currentStatus = $(this).data('status');
    table.ajax.reload();
});

$(document).on('click', '.approveBtn', function () {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Approve this ad?',
        text: 'It will be published immediately as a live post.',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#059669', confirmButtonText: 'Approve & Publish',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url: '{{ url("admin/ad-submissions") }}/' + id + '/approve',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: res => {
                if (res.success) {
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false });
                }
            },
            error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Failed to approve.', 'error'),
        });
    });
});

$(document).on('click', '.rejectBtn', function () {
    const id = $(this).data('id'), title = $(this).data('title');
    Swal.fire({
        title: 'Reject this ad?',
        html: '<strong>' + title + '</strong><br><br><textarea id="rejectReason" class="form-control form-control-sm" placeholder="Reason (optional, sent in admin notes)"></textarea>',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc2626', confirmButtonText: 'Reject',
        preConfirm: () => document.getElementById('rejectReason').value,
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url: '{{ url("admin/ad-submissions") }}/' + id + '/reject',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', admin_notes: r.value },
            success: res => {
                if (res.success) {
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                }
            },
        });
    });
});

$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id'), title = $(this).data('title');
    Swal.fire({
        title: 'Delete submission?',
        html: '<strong>' + title + '</strong> will be permanently deleted.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc2626', confirmButtonText: 'Delete',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url: '{{ url("admin/ad-submissions") }}/' + id, type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: res => {
                if (res.success) {
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false });
                }
            },
        });
    });
});
</script>
@endpush
