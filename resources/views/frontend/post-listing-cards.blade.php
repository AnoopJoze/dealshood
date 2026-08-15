@forelse($posts as $post)
    @include('frontend.post-single-card', ['post' => $post])
@empty
    <div class="dh-empty">
        <div class="dh-empty-icon">🔍</div>
        <p class="dh-empty-title">No Deals Found</p>
        <p class="dh-empty-text">Try a different locality, category, or keyword.</p>
    </div>
@endforelse
