<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Locality;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostShare;
use App\Models\PostView;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    /* ════════════════════════════════════════════
       HOME
    ════════════════════════════════════════════ */
    public function home(Request $request)
    {
        $categories = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            //->orderByDesc('posts_count')
            ->get();
        $localities = Locality::withPostsTree();

        /* ── AJAX: locality chip was clicked ─────────────────────────── */
        if ($request->ajax() || $request->wantsJson()) {
            $localitySlug = $request->input('filter_locality'); // null = all areas
            $localityIds  = [];

            if ($localitySlug) {
                $localityIds = Locality::descendantIdsForSlug($localitySlug);
            }

            /* Carousels — top-N posts per category filtered by locality */
            $carouselCats = Category::with([
                    'posts' => function ($q) use ($localityIds) {
                        $q->with(['locality', 'subcategory'])
                          ->withCount('likesData')
                          ->withCount('sharesData')
                          ->where('status', 'published')
                          ->when(!empty($localityIds), fn($q) => $q->whereIn('locality_id', $localityIds))
                          ->orderByDesc('views')
                          ->limit(10);
                    },
                ])
                ->whereHas('posts', function ($q) use ($localityIds) {
                    $q->where('status', 'published')
                    ->when(!empty($localityIds), fn($q) => $q->whereIn('locality_id', $localityIds));
                })
                ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
                ->orderByDesc('posts_count')
                ->get();

            /* Latest posts filtered by locality */
            $posts = Post::with(['category', 'subcategory', 'locality'])
                ->withCount(['likesData', 'sharesData'])
                ->where('status', 'published')
                ->when(!empty($localityIds), fn($q) => $q->whereIn('locality_id', $localityIds))
                ->orderBy('sort_order')
                ->latest()
                ->paginate(12);

            /* Build carousel HTML using the same partial as the full page */
            $carouselHtml = '';
            $palette = $this->palette();
            foreach ($carouselCats as $i => $cat) {
                if ($cat->posts->isNotEmpty()) {
                    $carouselHtml .= view('frontend.home-carousel-block', [
                        'cat'     => $cat,
                        'palette' => $palette,
                        'index'   => $i,
                    ])->render();
                }
            }

            return response()->json([
                'carousel_html' => $carouselHtml,
                'posts_html'    => view('frontend.post-cards', compact('posts'))->render(),
                'next_page'     => $posts->nextPageUrl(),
                'total'         => $posts->total(),
            ]);
        }

        /* ── Full page load ──────────────────────────────────────────── */
        $categoryCarousels = Category::with([
                'posts' => function ($q) {
                    $q->with(['locality', 'subcategory'])
                      ->withCount('likesData')
                      ->withCount('sharesData')
                      ->where('status', 'published')
                      ->orderBy('sort_order')
                      ->orderByDesc('views')
                      ->limit(5);
                },
            ])
            ->whereHas('posts', fn($q) => $q->where('status', 'published'))
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->get();

        $posts = Post::with(['category', 'subcategory', 'locality'])
            ->withCount(['likesData', 'sharesData'])
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(5);

        return view('frontend.frontend-app', compact(
            'posts', 'categories', 'categoryCarousels', 'localities'
        ));
    }

    /* ════════════════════════════════════════════
       LISTING
    ════════════════════════════════════════════ */
    public function listing(Request $request)
    {
        $localities = Locality::withPostsTree();
        $categories    = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
                            ->orderByDesc('posts_count')->get();
        $subcategories = collect();

        if ($request->filled('category_id')) {
            $cat           = Category::where('slug', $request->category_id)->first();
            $subcategories = $cat ? $cat->subcategories()
                ->orderBy('sort_order')->get() : collect();
        }

        $query = Post::with(['category', 'subcategory', 'locality'])
            ->withCount(['likesData', 'sharesData'])
            ->where('status', 'published');

        if ($request->filled('category_id')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category_id));
        }
        if ($request->filled('subcategory_id')) {
            $query->whereHas('subcategory', fn($q) => $q->where('slug', $request->subcategory_id));
        }
        if ($request->filled('locality_id')) {
            $localityIds = Locality::descendantIdsForSlug($request->locality_id);
            $query->whereIn('locality_id', $localityIds);
        }
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(fn($q) => $q->where('title', 'like', "%{$kw}%"));
        }

        match ($request->sort) {
            'popular'  => $query->orderByDesc('views'),
            'trending' => $query->orderByRaw('(views + likes_data_count + shares_data_count) DESC'),
            default    => $query->orderBy('sort_order')->latest(),
        };

        $posts = $query->paginate(12)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'      => view('frontend.post-cards', compact('posts'))->render(),
                'next_page' => $posts->nextPageUrl(),
                'total'     => $posts->total(),
            ]);
        }

        return view('frontend.frontend-app-post-listing',
            compact('posts', 'categories', 'localities', 'subcategories'));
    }

    /* ════════════════════════════════════════════
       POST DETAIL
    ════════════════════════════════════════════ */
    public function postDetail(Request $request, $locality, $category, $subcategory, $slug)
    {
        $post = Post::with(['category', 'subcategory', 'locality', 'user',
                            'likesData', 'sharesData', 'viewsData'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Canonical redirect
        if (
            $post->locality?->slug    !== $locality   ||
            $post->category?->slug    !== $category   ||
            $post->subcategory?->slug !== $subcategory
        ) {
            return redirect()->route('post-details', [
                'locality'    => $post->locality?->slug    ?? 'na',
                'category'    => $post->category?->slug    ?? 'na',
                'subcategory' => $post->subcategory?->slug ?? 'na',
                'slug'        => $post->slug,
            ], 301);
        }

        $this->trackView($post);

        $relatedPosts = Post::with(['category', 'subcategory', 'locality'])
            ->withCount(['likesData', 'sharesData'])
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.post-details', compact('post', 'relatedPosts'));
    }

    /* ════════════════════════════════════════════
       TOGGLE LIKE
    ════════════════════════════════════════════ */
    public function toggleLike($id)
    {
        $ip  = request()->ip();
        $sid = session()->getId();

        $existing = PostLike::where('post_id', $id)
            ->where(fn($q) => $q->where('ip_address', $ip)->orWhere('session_id', $sid))
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['liked' => false, 'likes' => PostLike::where('post_id', $id)->count()]);
        }

        PostLike::create(['post_id' => $id, 'ip_address' => $ip, 'session_id' => $sid]);

        return response()->json(['liked' => true, 'likes' => PostLike::where('post_id', $id)->count()]);
    }

    /* ════════════════════════════════════════════
       SHARE
    ════════════════════════════════════════════ */
    public function share($id)
    {
        PostShare::create([
            'post_id'  => $id,
            'user_id'  => auth()->id(),
            'platform' => request('platform', 'web'),
        ]);

        return response()->json(['success' => true, 'shares' => PostShare::where('post_id', $id)->count()]);
    }

    /* ════════════════════════════════════════════
       GET SUBCATEGORIES
    ════════════════════════════════════════════ */
    public function getSubcategories($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        return response()->json(
            Subcategory::where('category_id', $category->id)->select('slug', 'name')
    ->orderBy('sort_order')->get()
        );
    }

    /* ════════════════════════════════════════════
       PRIVATE HELPERS
    ════════════════════════════════════════════ */
    private function trackView(Post $post): void
    {
        $ip  = request()->ip();
        $sid = session()->getId();

        $seen = PostView::where('post_id', $post->id)
            ->where(fn($q) => $q->where('ip_address', $ip)->orWhere('session_id', $sid))
            ->whereDate('created_at', today())
            ->exists();

        if (! $seen) {
            PostView::create([
                'post_id'    => $post->id,
                'ip_address' => $ip,
                'session_id' => $sid,
                'user_id'    => auth()->id(),
            ]);
            $post->increment('views');
        }
    }

    private function palette(): array
    {
        return [
            ['bg'=>'#dbeafe','ic'=>'#1d4ed8','icon'=>'fa-tags'],
            ['bg'=>'#d1fae5','ic'=>'#059669','icon'=>'fa-leaf'],
            ['bg'=>'#fef3c7','ic'=>'#d97706','icon'=>'fa-fire'],
            ['bg'=>'#fce7f3','ic'=>'#db2777','icon'=>'fa-heart'],
            ['bg'=>'#ede9fe','ic'=>'#7c3aed','icon'=>'fa-gem'],
            ['bg'=>'#cffafe','ic'=>'#0891b2','icon'=>'fa-bolt'],
            ['bg'=>'#fef2f2','ic'=>'#dc2626','icon'=>'fa-percent'],
            ['bg'=>'#ecfdf5','ic'=>'#16a34a','icon'=>'fa-star'],
            ['bg'=>'#fff7ed','ic'=>'#ea580c','icon'=>'fa-house'],
            ['bg'=>'#f0f9ff','ic'=>'#0284c7','icon'=>'fa-car'],
            ['bg'=>'#fdf4ff','ic'=>'#a21caf','icon'=>'fa-shirt'],
            ['bg'=>'#f8fafc','ic'=>'#475569','icon'=>'fa-laptop'],
        ];
    }
    public function favourites()
{
    $query = \App\Models\Post::query()
        ->with(['media', 'locality', 'category', 'subcategory', 'likesData', 'viewsData'])
        ->where('status', 'published');

    if (auth()->user()) {
        // Logged-in user — fetch by user_id
        $query->whereHas('likesData', function ($q) {
            $q->where('user_id', auth()->id())->orWhere('session_id', session()->getId());
        });
    } else {
        // Guest — fetch by session id
        $query->whereHas('likesData', function ($q) {
            $q->where('session_id', session()->getId());
        });
    }

    $posts = $query->latest()->paginate(12);
    return view('frontend.favourites', compact('posts'));
}
}
