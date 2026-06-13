@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<style>
:root {
    --dk: #0f172a; --accent: #6366f1; --surface: #f8fafc;
    --border: #f1f5f9; --muted: #64748b; --muted2: #94a3b8;
    --r: 10px; --sh: 0 2px 16px rgba(15,23,42,.07); --sh-hover: 0 6px 28px rgba(15,23,42,.12);
}
.ps-card { background:#fff; border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); overflow:hidden; }
.ps-card-header { padding:1.1rem 1.4rem .9rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.ps-page-title { font-size:1rem; font-weight:800; color:var(--dk); margin:0; letter-spacing:-.01em; }
.ps-page-sub { font-size:.75rem; color:var(--muted2); margin:2px 0 0; }
.ps-filter-bar { display:flex; gap:.75rem; padding:1rem 1.4rem; border-bottom:1px solid var(--border); background:var(--surface); flex-wrap:wrap; align-items:end; }
.ps-filter-bar .form-select { font-size:.82rem; border-color:var(--border); border-radius:8px; min-width:180px; }
.ps-btn { display:inline-flex; align-items:center; gap:6px; font-size:.77rem; font-weight:600; border-radius:8px; padding:.48rem .9rem; cursor:pointer; border:1.5px solid; transition:all .14s; text-decoration:none; }
.ps-btn-primary { background:var(--dk); color:#fff; border-color:var(--dk); }
.ps-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; box-shadow:0 3px 12px rgba(99,102,241,.3); }
.ps-btn-ghost { background:#fff; color:var(--muted); border-color:var(--border); }
.ps-btn-ghost:hover { background:var(--surface); color:var(--dk); }

#sortableList { padding:1rem 1.4rem 1.4rem; display:flex; flex-direction:column; gap:8px; }
.sort-item {
    display:flex; align-items:center; gap:12px;
    background:#fff; border:1px solid var(--border); border-radius:9px;
    padding:.6rem .9rem; transition:box-shadow .14s, border-color .14s;
}
.sort-item:hover { box-shadow:var(--sh-hover); border-color:var(--accent); }
.sort-item.sortable-ghost { opacity:.35; }
.sort-item.sortable-chosen { box-shadow:var(--sh-hover); }
.sort-handle {
    cursor: grab; color:var(--muted2); font-size:1rem;
    width:24px; text-align:center; flex-shrink:0;
}
.sort-handle:active { cursor: grabbing; }
.sort-thumb {
    width:42px; height:42px; border-radius:8px; object-fit:cover;
    flex-shrink:0; border:1px solid var(--border);
}
.sort-thumb-empty {
    width:42px; height:42px; border-radius:8px; flex-shrink:0;
    background:var(--surface); display:flex; align-items:center; justify-content:center;
    color:var(--muted2); border:1px solid var(--border);
}
.sort-meta { flex:1; min-width:0; }
.sort-title { font-size:.85rem; font-weight:600; color:var(--dk); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sort-sub { font-size:.72rem; color:var(--muted2); margin-top:2px; }
.sort-pos {
    width:30px; height:30px; border-radius:50%; background:var(--surface);
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; color:var(--muted); flex-shrink:0;
}

.save-bar {
    position: sticky; bottom: 0; background:#fff; border-top:1px solid var(--border);
    padding: .9rem 1.4rem; display:flex; justify-content:flex-end; gap:8px;
    box-shadow: 0 -4px 16px rgba(15,23,42,.05);
}
</style>
@endpush

<div class="ps-card">

    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Reorder Posts</h4>
            <p class="ps-page-sub">Drag and drop to set the display order on the frontend</p>
        </div>
        <a href="{{ route('posts.index') }}" class="ps-btn ps-btn-ghost">
            <i class="fas fa-arrow-left"></i> Back to Posts
        </a>
    </div>

    <div class="ps-filter-bar">
        <div>
            <label class="form-label" style="font-size:.65rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted2);">Category</label>
            <select id="filter_category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected':'' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:.65rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted2);">Locality</label>
            <select id="filter_locality" class="form-select form-select-sm">
                <option value="">All Localities</option>
                @foreach ($localities as $loc)
                    <option value="{{ $loc->id }}" {{ request('locality_id') == $loc->id ? 'selected':'' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:.65rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted2);">Status</label>
            <select id="filter_status" class="form-select form-select-sm">
                <option value="published" selected>Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
                <option value="">All</option>
            </select>
        </div>
        <button class="ps-btn ps-btn-primary" id="applyFilters">
            <i class="fas fa-filter"></i> Apply
        </button>
    </div>

    @if ($posts->isEmpty())
        <div style="padding:2.5rem;text-align:center;color:var(--muted2);font-size:.85rem;">
            <i class="fas fa-inbox mb-2" style="font-size:1.6rem;display:block;opacity:.3;"></i>
            No posts found for this filter.
        </div>
    @else
        <div id="sortableList">
            @foreach ($posts as $post)
                @php $thumb = $post->getMedia('posts')->first(); @endphp
                <div class="sort-item" data-id="{{ $post->id }}">
                    <div class="sort-handle"><i class="fas fa-grip-vertical"></i></div>
                    <div class="sort-pos">{{ $loop->iteration }}</div>
                    @if ($thumb)
                        <img src="{{ $thumb->getUrl() }}" class="sort-thumb" alt="">
                    @else
                        <div class="sort-thumb-empty"><i class="fas fa-image"></i></div>
                    @endif
                    <div class="sort-meta">
                        <div class="sort-title">{{ $post->title }}</div>
                        <div class="sort-sub">
                            #{{ $post->id }}
                            @if ($post->category) · {{ $post->category->name }} @endif
                            @if ($post->locality) · {{ $post->locality->name }} @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="save-bar">
            <button class="ps-btn ps-btn-ghost" onclick="window.location.reload()">
                <i class="fas fa-undo"></i> Reset
            </button>
            <button class="ps-btn ps-btn-primary" id="saveOrderBtn">
                <span id="saveOrderText"><i class="fas fa-save"></i> Save Order</span>
                <span id="saveOrderSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm"></span> Saving…
                </span>
            </button>
        </div>
    @endif

</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
const sortableEl = document.getElementById('sortableList');
let sortable;

if (sortableEl) {
    sortable = new Sortable(sortableEl, {
        handle: '.sort-handle',
        animation: 150,
        onEnd: renumber,
    });
}

function renumber() {
    document.querySelectorAll('#sortableList .sort-item').forEach((el, i) => {
        el.querySelector('.sort-pos').textContent = i + 1;
    });
}

$('#saveOrderBtn').on('click', function() {
    const order = $('#sortableList .sort-item').map(function() {
        return $(this).data('id');
    }).get();

    $('#saveOrderText').addClass('d-none');
    $('#saveOrderSpinner').removeClass('d-none');
    $('#saveOrderBtn').prop('disabled', true);

    $.ajax({
        url: '{{ route("posts.saveOrder") }}',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', order: order },
        success: function(res) {
            if (res.success) {
                Swal.fire({ toast:true, position:'top-end', icon:'success',
                    title: res.message, timer: 1500, showConfirmButton:false });
            }
        },
        error: function() {
            Swal.fire('Error', 'Could not save order.', 'error');
        },
        complete: function() {
            $('#saveOrderText').removeClass('d-none');
            $('#saveOrderSpinner').addClass('d-none');
            $('#saveOrderBtn').prop('disabled', false);
        }
    });
});

$('#applyFilters').on('click', function() {
    const params = new URLSearchParams();
    const cat = $('#filter_category').val();
    const loc = $('#filter_locality').val();
    const status = $('#filter_status').val();
    if (cat) params.set('category_id', cat);
    if (loc) params.set('locality_id', loc);
    if (status) params.set('status', status);
    window.location.href = '{{ route("posts.reorder") }}?' + params.toString();
});
</script>
@endpush