<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Locality;
use App\Models\Post;
use App\Models\Subcategory;   // ← matches the model's belongsTo(Subcategory::class)
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // INDEX  –  render the posts list blade
    // ──────────────────────────────────────────────────────────────────────────
    public function index()
    {
        $categories    = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $localities    = Locality::orderBy('name')->get();
        $users         = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('posts.list', compact('categories', 'subcategories', 'localities', 'users'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DATA  –  server-side DataTables JSON
    // ──────────────────────────────────────────────────────────────────────────
    public function data(Request $request)
    {
        $query = Post::with(['category', 'subcategory', 'locality', 'user']);

        // ── Filters ───────────────────────────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ── Stats (before pagination) ─────────────────────────────────────────
        $publishedCount = (clone $query)->where('status', 'published')->count();
        $draftCount     = (clone $query)->where('status', 'draft')->count();

        return DataTables::of($query)

            // Title + featured star
            ->addColumn('title', fn($row) =>
                '<strong>' . e($row->title) . '</strong>'
                . ($row->is_featured
                    ? ' <span class="badge bg-warning text-dark ms-1"><i class="fas fa-star"></i></span>'
                    : '')
            )

            // Relations
            ->addColumn('category',    fn($row) => e($row->category?->name    ?? '—'))
            ->addColumn('subcategory', fn($row) => e($row->subcategory?->name ?? '—'))
            ->addColumn('locality',    fn($row) => e($row->locality?->name    ?? '—'))

            // User (name + email)
            ->addColumn('user', fn($row) =>
                $row->user
                    ? '<span class="text-sm">' . e($row->user->name)
                      . '<br><small class="text-muted">' . e($row->user->email) . '</small></span>'
                    : '—'
            )

            // Images via Spatie Media Library
            ->addColumn('images', function ($row) {
                $media = $row->getMedia('posts');

                if ($media->isEmpty()) {
                    return '<span class="text-muted text-xs">—</span>';
                }

                $html = '<div class="d-flex flex-wrap gap-1">';
                foreach ($media->take(4) as $m) {
                    $html .= '<a href="' . $m->getUrl() . '" data-fancybox="gallery-' . $row->id . '">'
                           . '<img src="' . $m->getUrl() . '" width="38" height="38"'
                           . ' class="rounded" style="object-fit:cover">'
                           . '</a>';
                }
                if ($media->count() > 4) {
                    $html .= '<span class="badge bg-secondary">+' . ($media->count() - 4) . '</span>';
                }

                return $html . '</div>';
            })

            // Inline status dropdown  ← model field: status
            ->addColumn('status', function ($row) {
                $options = [
                    'draft'     => 'Draft',
                    'published' => 'Published',
                    'archived'  => 'Archived',
                ];
                $select = '<select class="form-select form-select-sm inline-status"
                                   data-id="' . $row->id . '"
                                   style="min-width:120px">';
                foreach ($options as $val => $label) {
                    $sel     = $row->status === $val ? 'selected' : '';
                    $select .= "<option value=\"{$val}\" {$sel}>{$label}</option>";
                }
                return $select . '</select>';
            })

            // Expiry  ← model field: expiry_date  (NOT expires_at)
            ->addColumn('expires_at', function ($row) {
                if (!$row->expiry_date) {
                    return '<span class="text-muted">—</span>';
                }
                $date    = Carbon::parse($row->expiry_date);
                $expired = now()->gt($date);

                return $expired
                    ? '<span class="badge bg-danger">Expired ' . $date->format('d M Y') . '</span>'
                    : '<span class="text-sm">' . $date->format('d M Y') . '</span>';
            })

            // Created date
            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted fw-medium">'
                . Carbon::parse($row->created_at)->format('d M Y')
                . '<br><small class="text-xs">'
                . Carbon::parse($row->created_at)->diffForHumans()
                . '</small></div>'
            )

            // Action buttons
            ->addColumn('action', fn($row) =>
                '<div class="d-flex gap-1">'
                . '<a href="' . route('posts.show', $row->id) . '"
                    class="btn btn-sm btn-outline-secondary" title="View">
                    <i class="fas fa-eye"></i>View
                </a>'
                . '<button class="btn btn-sm btn-outline-primary editPost" data-id="' . $row->id . '" title="Edit">'
                . '<i class="fas fa-pen"></i>Edit</button>'
                . '</div>'
            )

            ->rawColumns(['title', 'user', 'images', 'status', 'expires_at', 'created_at', 'action'])
            ->with(['publishedCount' => $publishedCount, 'draftCount' => $draftCount])
            ->make(true);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EDIT DATA  –  returns post JSON for the edit modal (GET)
    // ──────────────────────────────────────────────────────────────────────────
    public function editData(Post $post)
    {
        $images = $post->getMedia('posts')->map(fn($m) => [
            'id'   => $m->id,
            'url'  => $m->getUrl(),
            'name' => $m->name,
        ]);

        return response()->json([
            ...$post->toArray(),
            'images' => $images,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // AJAX STORE  –  create a new post (POST ajax-store)
    // ──────────────────────────────────────────────────────────────────────────
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'status'         => 'required|in:draft,published,archived',
            'description'    => 'nullable|string',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'locality_id'    => 'nullable|exists:localities,id',
            'user_id'        => 'nullable|exists:users,id',
            'expiry_date'    => 'nullable|date',
            'google_map_url' => 'nullable|string|max:500',  // blade field: google_map_url
           // 'video_url'      => 'nullable|string|max:500',
            'is_featured'    => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
        ]);

        $validated['slug']         = $this->makeSlug($validated['title']);
        $validated['user_id']      = $validated['user_id'] ?? auth()->id();
        $validated['published_at'] = ($validated['status'] === 'published') ? now() : null;

        $post = Post::create($validated);

        // Video file upload
        if ($request->hasFile('video')) {
            $post->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return response()->json(['success' => true, 'data' => $post]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE  –  save edits (PUT /admin/posts/{post}  →  posts.update)
    // ──────────────────────────────────────────────────────────────────────────
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'status'         => 'required|in:draft,published,archived',
            'description'    => 'nullable|string',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'locality_id'    => 'nullable|exists:localities,id',
            'user_id'        => 'nullable|exists:users,id',
            'expiry_date'    => 'nullable|date',
            'google_map_url' => 'nullable|string|max:500',
            //'video_url'      => 'nullable|string|max:500',
            'is_featured'    => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
        ]);

        // Set published_at only the first time it transitions to published
        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        // Replace video file if a new one was uploaded
        if ($request->hasFile('video')) {
            $post->clearMediaCollection('videos');
            $post->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return response()->json(['success' => true, 'data' => $post->fresh()]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INLINE UPDATE  –  quick field edit from the table dropdown
    // ──────────────────────────────────────────────────────────────────────────
    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:posts,id',
            'field' => 'required|in:status,is_featured,is_active',  // whitelist only
            'value' => 'required',
        ]);

        $post = Post::findOrFail($request->id);
        $post->update([$request->field => $request->value]);

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DESTROY  –  soft-delete (DELETE /admin/posts/{post}  →  posts.destroy)
    // ──────────────────────────────────────────────────────────────────────────
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // MEDIA UPLOAD  –  Dropzone single-file upload
    // ──────────────────────────────────────────────────────────────────────────
    public function mediaUpload(Request $request)
    {
        $request->validate([
            'file'    => 'required|image|max:5120',
            'post_id' => 'required|exists:posts,id',
        ]);

        $post  = Post::findOrFail($request->post_id);
        $media = $post->addMediaFromRequest('file')->toMediaCollection('posts');

        return response()->json([
            'success' => true,
            'id'      => $media->id,
            'url'     => $media->getUrl(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // MEDIA DELETE  –  remove one Spatie media item
    // ──────────────────────────────────────────────────────────────────────────
    public function mediaDelete($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────
    private function makeSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = Post::withTrashed()->where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // ── Legacy form-based methods (kept for backward compatibility) ───────────

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $localities = Locality::orderBy('name')->get();
        $users      = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.posts.create', compact('categories', 'localities', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required', 'status' => 'required']);
        Post::create(array_merge($request->all(), [
            'slug'    => Str::slug($request->title),
            'user_id' => auth()->id(),
        ]));
        return redirect()->route('posts.index')->with('success', 'Post created');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $localities = Locality::orderBy('name')->get();
        $users      = User::orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.posts.edit', compact('post', 'categories', 'localities', 'users'));
    }
    // ──────────────────────────────────────────────────────────────────────────────
// Add this method to PostController  (replaces the empty resource show())
// ──────────────────────────────────────────────────────────────────────────────
 
public function show(Post $post)
{
    // Eager-load everything the show page needs
    $post->load([
        'category',
        'subcategory',
        'locality',
        'user',
        'likesData',
        'sharesData',
        'viewsData',
    ]);
 
    // Increment view counter (simple, non-unique)
    $post->increment('views');
 
    return view('posts.show', compact('post'));
}
}