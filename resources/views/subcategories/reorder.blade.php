@extends('layouts.user_type.auth')

@section('content')

@push('css')
<style>
:root {
    --dk: #0f172a; --accent: #6366f1; --surface: #f8fafc;
    --border: #f1f5f9; --muted: #64748b; --muted2: #94a3b8;
    --r: 10px; --sh: 0 2px 16px rgba(15,23,42,.07);
    --sh-hover: 0 6px 28px rgba(15,23,42,.12);
}

.ps-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: var(--r); box-shadow: var(--sh); overflow: hidden;
}
.ps-card-header {
    padding: 1.1rem 1.4rem .9rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.ps-page-title { font-size: 1rem; font-weight: 800; color: var(--dk); margin: 0; }
.ps-page-sub   { font-size: .75rem; color: var(--muted2); margin: 2px 0 0; }

.ps-filter-bar {
    display: flex; gap: .75rem; padding: 1rem 1.4rem;
    border-bottom: 1px solid var(--border);
    background: var(--surface); flex-wrap: wrap; align-items: flex-end;
}
.ps-filter-bar .form-select {
    font-size: .82rem; border-color: var(--border);
    border-radius: 8px; min-width: 220px;
}
.ps-filter-bar .form-label {
    font-size: .65rem; font-weight: 700; letter-spacing: .09em;
    text-transform: uppercase; color: var(--muted2); margin-bottom: 5px;
    display: block;
}

.ps-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .77rem; font-weight: 600; border-radius: 8px;
    padding: .48rem .9rem; cursor: pointer; border: 1.5px solid;
    transition: all .14s; text-decoration: none;
}
.ps-btn-primary {
    background: var(--dk); color: #fff; border-color: var(--dk);
}
.ps-btn-primary:hover {
    background: var(--accent); border-color: var(--accent); color: #fff;
    box-shadow: 0 3px 12px rgba(99,102,241,.3);
}
.ps-btn-ghost { background: #fff; color: var(--muted); border-color: var(--border); }
.ps-btn-ghost:hover { background: var(--surface); color: var(--dk); }

/* ── Sortable list ── */
#sortableList {
    padding: 1rem 1.4rem 0;
    display: flex; flex-direction: column; gap: 6px;
}
.sort-item {
    display: flex; align-items: center; gap: 12px;
    background: #fff; border: 1px solid var(--border);
    border-radius: 9px; padding: .55rem .9rem;
    transition: box-shadow .14s, border-color .14s;
    cursor: default;
}
.sort-item:hover       { box-shadow: var(--sh-hover); border-color: var(--accent); }
.sort-item.sortable-ghost  { opacity: .3; }
.sort-item.sortable-chosen { box-shadow: var(--sh-hover); }

.sort-handle {
    cursor: grab; color: var(--muted2); font-size: .95rem;
    width: 22px; text-align: center; flex-shrink: 0;
}
.sort-handle:active { cursor: grabbing; }

.sort-pos {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; color: var(--muted);
    flex-shrink: 0;
}
.sort-pos.changed {
    background: #ede9fe; border-color: var(--accent); color: var(--accent);
}

.sort-meta { flex: 1; min-width: 0; }
.sort-name { font-size: .85rem; font-weight: 600; color: var(--dk); }
.sort-sub  { font-size: .72rem; color: var(--muted2); margin-top: 1px; }

.sort-cat-badge {
    font-size: .68rem; font-weight: 600; padding: 3px 10px;
    border-radius: 100px; background: #ede9fe; color: #7c3aed;
    flex-shrink: 0; white-space: nowrap;
}

/* ── Save bar ── */
.save-bar {
    position: sticky; bottom: 0;
    background: #fff; border-top: 1px solid var(--border);
    padding: .9rem 1.4rem;
    display: flex; justify-content: space-between; align-items: center;
    box-shadow: 0 -4px 16px rgba(15,23,42,.05);
    margin-top: 1rem;
}
.save-bar-info {
    font-size: .75rem; color: var(--muted2);
    display: flex; align-items: center; gap: 6px;
}

/* Empty state */
.empty-state {
    padding: 3rem; text-align: center;
    color: var(--muted2); font-size: .85rem;
}
.empty-state i {
    font-size: 2rem; display: block;
    margin-bottom: .75rem; opacity: .25;
}
</style>
@endpush

<div class="ps-card">

    {{-- Header --}}
    <div class="ps-card-header">
        <div>
            <h4 class="ps-page-title">Reorder Subcategories</h4>
            <p class="ps-page-sub">Drag and drop to set display order on the frontend</p>
        </div>
        <a href="{{ route('subcategories.index') }}" class="ps-btn ps-btn-ghost">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Filter bar --}}
    <div class="ps-filter-bar">
        <div>
            <label class="form-label">Filter by Category</label>
            <select id="filter_category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="ps-btn ps-btn-primary" id="applyFilter" style="align-self:flex-end;">
            <i class="fas fa-filter"></i> Apply
        </button>
        @if(request('category_id'))
            <a href="{{ route('subcategories.reorder') }}"
               class="ps-btn ps-btn-ghost" style="align-self:flex-end;">
                <i class="fas fa-times"></i> Clear
            </a>
        @endif
    </div>

    {{-- List --}}
    @if ($subcategories->isEmpty())
        <div class="empty-state">
            <i class="fas fa-layer-group"></i>
            No subcategories found.
            @if(request('category_id')) Try clearing the category filter. @endif
        </div>
    @else
        <div id="sortableList">
            @foreach ($subcategories as $sub)
                <div class="sort-item" data-id="{{ $sub->id }}">
                    <div class="sort-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    <div class="sort-pos" id="pos-{{ $sub->id }}">
                        {{ $loop->iteration }}
                    </div>
                    <div class="sort-meta">
                        <div class="sort-name">{{ $sub->name }}</div>
                        <div class="sort-sub">#{{ $sub->id }} · slug: {{ $sub->slug }}</div>
                    </div>
                    @if ($sub->category)
                        <span class="sort-cat-badge">{{ $sub->category->name }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="save-bar">
            <div class="save-bar-info">
                <i class="fas fa-info-circle"></i>
                <span id="movedCount">0</span> item(s) moved — click Save to apply
            </div>
            <div class="d-flex gap-2">
                <button class="ps-btn ps-btn-ghost" onclick="window.location.reload()">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button class="ps-btn ps-btn-primary" id="saveOrderBtn">
                    <span id="saveOrderText">
                        <i class="fas fa-save"></i> Save Order
                    </span>
                    <span id="saveOrderSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm"></span> Saving…
                    </span>
                </button>
            </div>
        </div>
    @endif

</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>

<script>
const sortableEl = document.getElementById('sortableList');
let movedCount   = 0;

if (sortableEl) {
    new Sortable(sortableEl, {
        handle:    '.sort-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function () {
            movedCount++;
            renumber();
            document.getElementById('movedCount').textContent = movedCount;
        },
    });
}

function renumber() {
    document.querySelectorAll('#sortableList .sort-item').forEach((el, i) => {
        const pos = el.querySelector('.sort-pos');
        pos.textContent = i + 1;
        pos.classList.add('changed');
    });
}

$('#applyFilter').on('click', function () {
    const cat = $('#filter_category').val();
    const params = new URLSearchParams();
    if (cat) params.set('category_id', cat);
    window.location.href = '{{ route("subcategories.reorder") }}?' + params.toString();
});

$('#saveOrderBtn').on('click', function () {
    const order = $('#sortableList .sort-item').map(function () {
        return $(this).data('id');
    }).get();

    $('#saveOrderText').addClass('d-none');
    $('#saveOrderSpinner').removeClass('d-none');
    $('#saveOrderBtn').prop('disabled', true);

    $.ajax({
        url:  '{{ route("subcategories.saveOrder") }}',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', order: order },
        success: function (res) {
            if (res.success) {
                movedCount = 0;
                document.getElementById('movedCount').textContent = 0;
                // Remove the .changed highlight after saving
                document.querySelectorAll('.sort-pos').forEach(el => el.classList.remove('changed'));
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: res.message, timer: 1800, showConfirmButton: false,
                });
            }
        },
        error: function () {
            Swal.fire('Error', 'Could not save order.', 'error');
        },
        complete: function () {
            $('#saveOrderText').removeClass('d-none');
            $('#saveOrderSpinner').addClass('d-none');
            $('#saveOrderBtn').prop('disabled', false);
        }
    });
});
</script>
@endpush