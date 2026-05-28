<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostView;
use App\Models\PostShare;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Locality;

class FrontEndController extends Controller
{
    public function home(Request $request)
    {
        /*
        |--------------------------------------------------------------
        | withCount rules — no aliases (always safe)
        | withCount('likesData')  → $post->likes_data_count
        | withCount('sharesData') → $post->shares_data_count
        | views                   → real DB column
        |--------------------------------------------------------------
        */
 
        // ── Category tiles (all categories with deal count) ────────────────
        $categories = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->get();
 
        // ── Category carousels — ALL categories that have published posts ──
        // Each carousel shows top 10 most-viewed posts for that category.
        $categoryCarousels = Category::with([
                'posts' => function ($q) {
                    $q->with('locality')
                      ->withCount('likesData')
                      ->where('status', 'published')
                      ->orderByDesc('views')
                      ->limit(10);
                },
            ])
            ->whereHas('posts', fn($q) => $q->where('status', 'published'))
            ->withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')
            ->get();   // ← no limit — every category gets a carousel
 
        // ── Latest deals grid (bottom of page) ────────────────────────────
        $posts = Post::with(['category', 'subcategory', 'locality'])
            ->withCount(['likesData', 'sharesData'])
            ->where('status', 'published')
            ->latest()
            ->paginate(12);
 
        return view('frontend.frontend-app', compact(
            'posts',
            'categories',
            'categoryCarousels'
        ));
    }
 
    // Listing page keeps filters for the dedicated browse page
    public function listing(Request $request)
    {
        $localities    = Locality::where('parent_id', 3)->get();
        $categories    = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('posts_count')->get();
 
        if ($request->filled('category_id')) {
            $cat           = Category::where('slug', $request->category_id)->first();
            $subcategories = $cat ? $cat->subcategories()->get() : collect();
        } else {
            $subcategories = collect();
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
            $query->whereHas('locality', fn($q) => $q->where('slug', $request->locality_id));
        }
        if ($request->filled('keyword')) {
            $query->where(fn($q) =>
                $q->where('title',       'like', "%{$request->keyword}%")
                  ->orWhere('description', 'like', "%{$request->keyword}%")
            );
        }
 
        match ($request->sort) {
            'popular'  => $query->orderByDesc('views'),
            'trending' => $query->orderByRaw('(views + likes_data_count + shares_data_count) DESC'),
            default    => $query->latest(),
        };
 
        $posts = $query->paginate(12)->withQueryString();
 
        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'html'      => view('frontend.post-cards', compact('posts'))->render(),
                'next_page' => $posts->nextPageUrl(),
            ]);
        }
 
        return view('frontend.frontend-app-post-listing', compact('posts', 'categories', 'localities', 'subcategories'));
    }
    public function homes()
    {
        $liked = PostLike::where('post_id', $post->id)
        ->where('ip_address', request()->ip())
        ->exists();
        $existing = PostLike::where('post_id', $id)
    ->where(function ($q) use ($ip) {

        $q->where('ip_address', $ip)
          ->orWhere('session_id', session()->getId());

    })
    ->first();
        return view('frontend.frontend-app');
    }
    public function postDetail(Request $request, $locality, $category, $subcategory, $slug)
    {
    $localities = Locality::where('parent_id', 3)->get();
    if($request->category_id){
    $category = Category::where('slug', $request->category_id)->first();
    $subcategories = SubCategory::where('category_id',$category->id)->get();
    }else{
    $subcategories = SubCategory::all();
    }
    $categories = Category::all();
        $post = Post::with([
                'category',
                'subcategory',
                'locality',
                'user'
            ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // SEO URL VALIDATION (canonical redirect)
        if (
            $post->locality?->slug !== $locality ||
            $post->category?->slug !== $category ||
            $post->subcategory?->slug !== $subcategory
        ) {
            return redirect()->route('post-details', [
                'locality' => $post->locality?->slug,
                'category' => $post->category?->slug,
                'subcategory' => $post->subcategory?->slug,
                'slug' => $post->slug,
            ], 301);
        }

        /*
        |--------------------------------------------------------------------------
        | VIEWS (unique per IP per day)
        |--------------------------------------------------------------------------
        */

        //$this->trackView($post);

        /*
        |--------------------------------------------------------------------------
        | RELATED POSTS
        |--------------------------------------------------------------------------
        */

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.post-details', compact(
            'post',
            'relatedPosts',
        'localities',
        'categories',
        'subcategories'
        ));
    }

// public function like($id)
// {
//     $post = Post::findOrFail($id);

//     $post->increment('likes');

//     return response()->json([
//         'success' => true,
//         'likes' => $post->likes
//     ]);
// }
// public function share($id)
// {
//     $post = Post::findOrFail($id);

//     $post->increment('shares');

//     return response()->json([
//         'success' => true
//     ]);
// }

public function toggleLike($id)
{
    $post = Post::findOrFail($id);

    $ip = request()->ip();

    $existing = PostLike::where('post_id', $id)
        ->where('ip_address', $ip)
        ->first();

    // UNLIKE
    if ($existing) {

        $existing->delete();

        return response()->json([
            'liked' => false,
            'likes' => $post->likes
        ]);
    }

    // LIKE
    PostLike::create([
        'post_id' => $id,
        'ip_address' => $ip,
        'session_id' => session()->getId(),
    ]);

    return response()->json([
        'liked' => true,
        'likes' => $post->likesData->count()
    ]);
}

public function share($id)
{
    $post = Post::findOrFail($id);

    PostShare::create([
        'post_id' => $id,
        'user_id' => auth()->id(),
        'platform' => request('platform')
    ]);

    return response()->json(['success' => true]);
}
public function getSubcategories($categoryId)
{
    $category = Category::where('slug', $categoryId)->first();
    $subcategories = SubCategory::where('category_id', $category->id)
        ->select('slug', 'name')
        ->get();

    return response()->json($subcategories);
}
}
