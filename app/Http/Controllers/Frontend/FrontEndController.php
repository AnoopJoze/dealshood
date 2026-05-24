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
        $localities = Locality::where('parent_id',3)->get();
        $categories = Category::all();
        $subcategories = SubCategory::all();
        $query = Post::with([
            'category',
            'subcategory',
            'locality'
        ])
        ->withCount(['likesData as likes','viewsData as views','sharesData as shares'])
        ->where('status', 'published');

        /*
        |--------------------------------------------------------------------------
        | FILTERS (SEO FRIENDLY READY)
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('subcategory')) {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        if ($request->filled('locality')) {
            $query->whereHas('locality', function ($q) use ($request) {
                $q->where('slug', $request->locality);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING (TRENDING / NEW / POPULAR)
        |--------------------------------------------------------------------------
        */

        if ($request->sort === 'old') {
            $query->orderBy('created_at', 'asc');
        }

        if ($request->sort === 'popular') {
            $query->orderBy('views', 'desc');
        }

        if ($request->sort === 'trending') {
            $query->orderByRaw('(views + likes + shares) DESC');
        }

        if (!$request->sort) {
            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $posts = $query->take(6)->get();

        return view('frontend.frontend-app', compact('posts','localities',
        'categories',
        'subcategories'));
    }
    public function listing(Request $request)
    {
    $localities = Locality::where('parent_id', 3)->get();
    if($request->category_id){
    $category = Category::where('slug', $request->category_id)->first();
    $subcategories = SubCategory::where('category_id',$category->id)->get();
    }else{
    $subcategories = SubCategory::all();
    }
    $categories = Category::all();

    $query = Post::with(['category','subcategory','locality'])
        ->withCount(['likesData as likes','viewsData as views','sharesData as shares'])
        ->where('status', 'published');

    if ($request->filled('category_id')) {
    $query->whereHas('category', fn($q) =>
        $q->where('slug', $request->category_id));
}

if ($request->filled('subcategory_id')) {
    $query->whereHas('subcategory', fn($q) =>
        $q->where('slug', $request->subcategory_id));
}

if ($request->filled('locality_id')) {
    $query->whereHas('locality', fn($q) =>
        $q->where('slug', $request->locality_id));
}

    if ($request->filled('keyword')) {
        $query->where(function ($q) use ($request) {
            $q->where('title','like',"%{$request->keyword}%")
              ->orWhere('description','like',"%{$request->keyword}%");
        });
    }

    // SORT
    if ($request->sort === 'old') {
        $query->orderBy('created_at','asc');
    } elseif ($request->sort === 'popular') {
        $query->orderBy('views','desc');
    } elseif ($request->sort === 'trending') {
        $query->orderByRaw('(views + likes + shares) DESC');
    } else {
        $query->latest();
    }

    $posts = $query->paginate(12)->withQueryString();

    // 🔥 AJAX RESPONSE (IMPORTANT)
   if ($request->ajax()) {

    $html = view('frontend.post-cards', compact('posts'))->render();

    return response()->json([
        'success' => true,
        'html' => $html,
        'next_page' => $posts->nextPageUrl(),
    ]);
}

    return view('frontend.frontend-app-post-listing', compact(
        'posts',
        'localities',
        'categories',
        'subcategories'
    ));
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
    public function postDetail($locality, $category, $subcategory, $slug)
    {
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
            return redirect()->route('post.show', [
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
            'relatedPosts'
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
