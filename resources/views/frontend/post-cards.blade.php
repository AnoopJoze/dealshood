@forelse($posts as $post)

    @include('frontend.post-single-card', ['post' => $post])

@empty

    <div class="col-12 text-center py-5">

        <h4>No Posts Found</h4>

        <p class="text-muted">
            Try changing filters or search keywords.
        </p>

    </div>

@endforelse
