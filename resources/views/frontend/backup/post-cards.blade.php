@forelse($posts as $post)
    @include('frontend.post-single-card', ['post' => $post])
@empty
    <div class="dh-empty" style="grid-column:1/-1;">
        <div class="dh-empty-icon">🔍</div>
        <p class="dh-empty-title">No Deals Found</p>
        <p class="dh-empty-text">Try adjusting your filters or search keywords.</p>
    </div>
@endforelse