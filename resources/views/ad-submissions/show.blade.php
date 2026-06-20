@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
<style>
:root {
    --dk: #0f172a; --accent: #6366f1; --surface: #f8fafc;
    --border: #f1f5f9; --muted: #64748b; --muted2: #94a3b8;
    --r: 10px; --sh: 0 2px 16px rgba(15,23,42,.07);
}
.ps-actbar {
    display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
    background: #fff; border: 1px solid var(--border); border-radius: var(--r);
    padding: .7rem 1rem; margin-bottom: 1.5rem; box-shadow: var(--sh);
}
.ps-actbar .spacer { flex: 1 1 0; }
.ps-status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 700; padding: 5px 13px; border-radius: 100px;
}
.ps-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .76rem; font-weight: 600; border-radius: 8px;
    padding: .45rem .9rem; cursor: pointer; border: 1.5px solid; text-decoration: none;
}
.ps-btn-success { background: linear-gradient(135deg,#059669,#10b981); color: #fff; border-color: transparent; }
.ps-btn-danger { background: #fff; color: #dc2626; border-color: #fecaca; }
.ps-btn-danger:hover { background: #fef2f2; }
.ps-btn-ghost { background: #fff; color: var(--muted); border-color: var(--border); }
.ps-btn-ghost:hover { background: var(--surface); color: var(--dk); }
.ps-card {
    background: #fff; border: 1px solid var(--border); border-radius: var(--r);
    margin-bottom: 1.1rem; box-shadow: var(--sh); overflow: hidden;
}
.ps-card-hd { padding: .85rem 1.2rem; border-bottom: 1px solid var(--border); }
.ps-card-title {
    font-size: .63rem; font-weight: 700; letter-spacing: .11em; text-transform: uppercase;
    color: var(--muted2); margin: 0; display: flex; align-items: center; gap: 7px;
}
.ps-card-title i { color: var(--accent); font-size: .72rem; }
.ps-card-body { padding: 1.1rem 1.2rem; }
.ps-meta { list-style: none; padding: 0; margin: 0; }
.ps-meta li {
    display: flex; align-items: flex-start; gap: .7rem;
    padding: .55rem 0; border-bottom: 1px solid var(--border); font-size: .83rem;
}
.ps-meta li:last-child { border-bottom: none; }
.ps-meta .ml {
    width: 100px; flex-shrink: 0; font-size: .68rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase; color: var(--muted2); padding-top: .15rem;
}
.ps-meta .mv { color: var(--dk); font-weight: 500; word-break: break-word; }
.ps-meta .mv.empty { color: var(--muted2); font-style: italic; font-weight: 400; }
.ps-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 10px; }
.ps-gallery a {
    display: block; aspect-ratio: 1; border-radius: 8px; overflow: hidden;
    border: 2px solid var(--border); transition: border-color .16s, transform .16s;
}
.ps-gallery a:hover { border-color: var(--accent); transform: scale(1.03); }
.ps-gallery img { width: 100%; height: 100%; object-fit: cover; }
.ps-body { font-size: .9rem; line-height: 1.85; color: #374151; white-space: pre-wrap; }
.notes-box {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px;
    padding: 12px 16px; font-size: .85rem; color: #92400e;
}
</style>
@endpush

@php [$sbg, $stc] = $submission->status_badge; @endphp

<div class="ps-actbar">
    <span class="ps-status-pill {{ $sbg }} {{ $stc }}">
        <span style="width:7px;height:7px;border-radius:50%;background:currentColor;"></span>
        {{ ucfirst($submission->status) }}
    </span>
    <div class="spacer"></div>
    @if ($submission->status === 'pending')
        <button class="ps-btn ps-btn-success" id="approveBtn" data-id="{{ $submission->id }}">
            <i class="fas fa-check"></i> Approve & Publish
        </button>
        <button class="ps-btn ps-btn-danger" id="rejectBtn" data-id="{{ $submission->id }}" data-title="{{ $submission->title }}">
            <i class="fas fa-times"></i> Reject
        </button>
    @endif
    <a href="{{ route('ad-submissions.index') }}" class="ps-btn ps-btn-ghost">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="row g-4">

    <div class="col-lg-8">

        {{-- Images --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-images"></i> Submitted Images</p>
            </div>
            <div class="ps-card-body">
                @if ($submission->getMedia('images')->count())
                    <div class="ps-gallery">
                        @foreach ($submission->getMedia('images') as $m)
                            <a href="{{ $m->getUrl() }}" data-fancybox="submission-gallery">
                                <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mb-0" style="font-size:.82rem;color:var(--muted2);">
                        <i class="fas fa-image me-2" style="opacity:.3;"></i>No images submitted.
                    </p>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-align-left"></i> Description</p>
            </div>
            <div class="ps-card-body">
                @if ($submission->description)
                    <div class="ps-body">{{ $submission->description }}</div>
                @else
                    <p class="mb-0" style="font-size:.82rem;color:var(--muted2);">No description provided.</p>
                @endif
            </div>
        </div>

        @if ($submission->admin_notes)
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-sticky-note"></i> Admin Notes</p>
            </div>
            <div class="ps-card-body">
                <div class="notes-box">{{ $submission->admin_notes }}</div>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4">

        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-info-circle"></i> Ad Details</p></div>
            <div class="ps-card-body" style="padding-top:.6rem;padding-bottom:.6rem;">
                <ul class="ps-meta">
                    <li><span class="ml">Title</span><span class="mv">{{ $submission->title }}</span></li>
                    <li><span class="ml">Category</span><span class="mv {{ !$submission->category ? 'empty':'' }}">{{ $submission->category?->name ?? 'Not set' }}</span></li>
                    <li><span class="ml">Locality</span><span class="mv {{ !$submission->locality ? 'empty':'' }}">{{ $submission->locality?->name ?? 'Not set' }}</span></li>
                    <li><span class="ml">Location</span><span class="mv {{ !$submission->location ? 'empty':'' }}">{{ $submission->location ?? 'Not set' }}</span></li>
                    <li><span class="ml">Offer</span><span class="mv {{ !$submission->offer_percentage ? 'empty':'' }}">{{ $submission->offer_percentage ? $submission->offer_percentage.'% OFF' : 'None' }}</span></li>
                    <li><span class="ml">Expiry</span><span class="mv {{ !$submission->expiry_date ? 'empty':'' }}">{{ $submission->expiry_date?->format('d M Y') ?? 'No expiry' }}</span></li>
                    <li><span class="ml">Submitted</span><span class="mv" style="color:var(--muted);">{{ $submission->created_at->format('d M Y, H:i') }}</span></li>
                </ul>
            </div>
        </div>

        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-user"></i> Submitter</p></div>
            <div class="ps-card-body" style="padding-top:.6rem;padding-bottom:.6rem;">
                <ul class="ps-meta">
                    <li><span class="ml">Name</span><span class="mv">{{ $submission->name }}</span></li>
                    <li><span class="ml">Email</span><span class="mv"><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></span></li>
                    <li><span class="ml">Phone</span><span class="mv {{ !$submission->phone ? 'empty':'' }}">{{ $submission->phone ?? 'Not provided' }}</span></li>
                    <li><span class="ml">WhatsApp</span><span class="mv {{ !$submission->whatsapp ? 'empty':'' }}">{{ $submission->whatsapp ?? 'Not provided' }}</span></li>
                    <li><span class="ml">Company</span><span class="mv {{ !$submission->company_name ? 'empty':'' }}">{{ $submission->company_name ?? 'Not provided' }}</span></li>
                </ul>
            </div>
        </div>

        @if ($submission->status === 'approved')
        <div class="ps-card">
            <div class="ps-card-body text-center">
                <i class="fas fa-check-circle" style="font-size:1.5rem;color:#059669;margin-bottom:8px;"></i>
                <p class="mb-0" style="font-size:.82rem;color:var(--muted);">This submission was approved and published as a live post.</p>
            </div>
        </div>
        @endif

    </div>

</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
Fancybox.bind('[data-fancybox="submission-gallery"]', { Toolbar: { display: ['close'] } });

$('#approveBtn').on('click', function () {
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
                    Swal.fire({ icon: 'success', title: res.message, timer: 1800, showConfirmButton: false })
                        .then(() => window.location.reload());
                }
            },
            error: xhr => Swal.fire('Error', xhr.responseJSON?.message || 'Failed to approve.', 'error'),
        });
    });
});

$('#rejectBtn').on('click', function () {
    const id = $(this).data('id'), title = $(this).data('title');
    Swal.fire({
        title: 'Reject this ad?',
        html: '<strong>' + title + '</strong><br><br><textarea id="rejectReason" class="form-control form-control-sm" placeholder="Reason (optional)"></textarea>',
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
                    Swal.fire({ icon: 'success', title: res.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.reload());
                }
            },
        });
    });
});
</script>
@endpush