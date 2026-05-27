<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Locality;
use App\Models\Post;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $categories    = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $localities    = Locality::orderBy('name')->get();
        $users         = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('posts.list', compact('categories', 'subcategories', 'localities', 'users'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE FEED
    |--------------------------------------------------------------------------
    */
    public function data(Request $request)
    {
        $query = Post::with(['category', 'subcategory', 'locality', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $totalCount     = (clone $query)->count();
        $publishedCount = (clone $query)->where('status', 'published')->count();
        $draftCount     = (clone $query)->where('status', 'draft')->count();
        $archivedCount  = (clone $query)->where('status', 'archived')->count();

        return DataTables::of($query)

            ->addColumn('title', function ($row) {
                $thumb = $row->getMedia('posts')->first();
                $img   = $thumb
                    ? '<img src="' . $thumb->getUrl() . '" width="36" height="36"
                           class="rounded me-2" style="object-fit:cover;flex-shrink:0;">'
                    : '<div class="rounded me-2 bg-light d-flex align-items-center justify-content-center"
                            style="width:36px;height:36px;flex-shrink:0;">
                            <i class="fas fa-image text-muted" style="font-size:.7rem;"></i>
                         </div>';

                return '<div class="d-flex align-items-center">'
                    . $img
                    . '<div>'
                    . '<div class="text-sm fw-semibold text-dark text-truncate" style="max-width:180px;">'
                    . e($row->title)
                    . ($row->is_featured ? ' <i class="fas fa-star text-warning ms-1" style="font-size:.65rem;" title="Featured"></i>' : '')
                    . '</div>'
                    . '<small class="text-muted">#' . $row->id . ' · ' . $row->slug . '</small>'
                    . '</div></div>';
            })

            ->addColumn('category', fn($row) =>
                $row->category
                    ? '<span class="badge bg-primary-subtle text-primary rounded-pill px-2">'
                      . e($row->category->name) . '</span>'
                    : '<span class="text-muted">—</span>'
            )

            ->addColumn('subcategory', fn($row) =>
                $row->subcategory
                    ? '<span class="badge bg-light text-secondary border rounded-pill px-2">'
                      . e($row->subcategory->name) . '</span>'
                    : '<span class="text-muted">—</span>'
            )

            ->addColumn('locality', fn($row) =>
                $row->locality
                    ? '<span class="text-sm"><i class="fas fa-map-marker-alt me-1 text-muted" style="font-size:.65rem;"></i>'
                      . e($row->locality->name) . '</span>'
                    : '<span class="text-muted">—</span>'
            )

            ->addColumn('user', function ($row) {
                if (!$row->user) return '<span class="text-muted">—</span>';
                $initial = strtoupper(substr($row->user->name, 0, 1));
                return '<div class="d-flex align-items-center gap-2">'
                    . '<div class="rounded-circle bg-gradient-secondary text-white d-flex align-items-center
                               justify-content-center fw-bold" style="width:28px;height:28px;font-size:.75rem;flex-shrink:0;">'
                    . $initial . '</div>'
                    . '<div><div class="text-sm fw-medium">' . e($row->user->name) . '</div>'
                    . '<small class="text-muted">' . e($row->user->email) . '</small></div>'
                    . '</div>';
            })

            ->addColumn('images', function ($row) {
                $media = $row->getMedia('posts');
                if ($media->isEmpty()) return '<span class="text-muted text-xs">—</span>';
                $html = '<div class="d-flex gap-1 flex-wrap">';
                foreach ($media->take(3) as $m) {
                    $html .= '<a href="' . $m->getUrl() . '" data-fancybox="gallery-' . $row->id . '">'
                           . '<img src="' . $m->getUrl() . '" width="32" height="32"
                                  class="rounded" style="object-fit:cover;border:1px solid #dee2e6;">'
                           . '</a>';
                }
                if ($media->count() > 3) {
                    $html .= '<span class="badge bg-secondary rounded-pill align-self-center">+'
                           . ($media->count() - 3) . '</span>';
                }
                return $html . '</div>';
            })

            ->addColumn('status', function ($row) {
                $map = [
                    'published' => ['bg-success-subtle', 'text-success'],
                    'draft'     => ['bg-secondary-subtle', 'text-secondary'],
                    'archived'  => ['bg-warning-subtle', 'text-warning'],
                ];
                [$bg, $tc] = $map[$row->status] ?? ['bg-light', 'text-muted'];
                $select = '<select class="form-select form-select-sm inline-status border-0 ' . $bg . ' ' . $tc . ' fw-semibold"
                                   data-id="' . $row->id . '"
                                   style="min-width:115px;border-radius:.5rem;font-size:.75rem;">';
                foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $v => $l) {
                    $select .= '<option value="' . $v . '"' . ($row->status === $v ? ' selected' : '') . '>' . $l . '</option>';
                }
                return $select . '</select>';
            })

            ->addColumn('expiry', function ($row) {
                if (!$row->expiry_date) return '<span class="text-muted text-xs">—</span>';
                $date    = Carbon::parse($row->expiry_date);
                $expired = now()->gt($date);
                if ($expired) {
                    return '<span class="badge bg-danger-subtle text-danger rounded-pill px-2">
                                <i class="fas fa-exclamation-circle me-1" style="font-size:.6rem;"></i>
                                Expired ' . $date->format('d M Y') . '
                            </span>';
                }
                $soon = now()->diffInDays($date, false) <= 7;
                return '<span class="text-sm ' . ($soon ? 'text-warning fw-semibold' : 'text-muted') . '">'
                     . $date->format('d M Y')
                     . '<br><small>' . $date->diffForHumans() . '</small></span>';
            })

            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted">'
                . Carbon::parse($row->created_at)->format('d M Y')
                . '<br><small>' . Carbon::parse($row->created_at)->diffForHumans() . '</small></div>'
            )

            ->addColumn('action', fn($row) =>
                '<div class="d-flex gap-1">'
                . '<a href="' . route('posts.show', $row->id) . '"
                    class="btn btn-sm btn-light border" title="View">
                    <i class="fas fa-eye text-info"></i>
                   </a>'
                . '<button class="btn btn-sm btn-light border editPost" data-id="' . $row->id . '" title="Edit">
                    <i class="fas fa-pen text-warning"></i>
                   </button>'
                . '<button class="btn btn-sm btn-light border deletePost" data-id="' . $row->id . '"
                           data-title="' . e($row->title) . '" title="Delete">
                    <i class="fas fa-trash text-danger"></i>
                   </button>'
                . '</div>'
            )

            ->rawColumns(['title', 'category', 'subcategory', 'locality', 'user', 'images', 'status', 'expiry', 'created_at', 'action'])
            ->with([
                'totalCount'     => $totalCount,
                'publishedCount' => $publishedCount,
                'draftCount'     => $draftCount,
                'archivedCount'  => $archivedCount,
            ])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT DATA – JSON for modal
    |--------------------------------------------------------------------------
    */
    public function editData(Post $post)
    {
        $images = $post->getMedia('posts')->map(fn($m) => [
            'id'   => $m->id,
            'url'  => $m->getUrl(),
            'name' => $m->name,
        ]);

        $video = $post->getMedia('videos')->first();

        return response()->json([
            ...$post->toArray(),
            'images'    => $images,
            'video_url_media' => $video?->getUrl(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX STORE
    |--------------------------------------------------------------------------
    */
    public function ajaxStore(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['slug']         = $this->makeSlug($validated['title']);
        $validated['user_id']      = $validated['user_id'] ?? auth()->id();
        $validated['published_at'] = ($validated['status'] === 'published') ? now() : null;

        $post = Post::create($validated);

        if ($request->hasFile('video')) {
            $post->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return response()->json(['success' => true, 'data' => $post]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate($this->rules($post->id));

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if ($request->hasFile('video')) {
            $post->clearMediaCollection('videos');
            $post->addMediaFromRequest('video')->toMediaCollection('videos');
        }

        return response()->json(['success' => true, 'data' => $post->fresh()]);
    }

    /*
    |--------------------------------------------------------------------------
    | INLINE UPDATE
    |--------------------------------------------------------------------------
    */
    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:posts,id',
            'field' => 'required|in:status,is_featured,is_active',
            'value' => 'required',
        ]);

        Post::findOrFail($request->id)->update([$request->field => $request->value]);

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA UPLOAD
    |--------------------------------------------------------------------------
    */
    public function mediaUpload(Request $request)
    {
        $request->validate([
            'file'    => 'required|image|max:5120',
            'post_id' => 'required|exists:posts,id',
        ]);

        $media = Post::findOrFail($request->post_id)
            ->addMediaFromRequest('file')
            ->toMediaCollection('posts');

        return response()->json(['success' => true, 'id' => $media->id, 'url' => $media->getUrl()]);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA DELETE
    |--------------------------------------------------------------------------
    */
    public function mediaDelete($id)
    {
        Media::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Post $post)
    {
        $post->load(['category', 'subcategory', 'locality', 'user', 'likesData', 'sharesData', 'viewsData']);
        $post->increment('views');

        $categories = Category::orderBy('name')->get();
        $localities  = Locality::orderBy('name')->get();
        $users       = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('posts.show', compact('post', 'categories', 'localities', 'users'));
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'title'            => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'status'           => 'required|in:draft,published,archived',
            'description'      => 'nullable|string',
            'subcategory_id'   => 'nullable|exists:subcategories,id',
            'locality_id'      => 'nullable|exists:localities,id',
            'user_id'          => 'nullable|exists:users,id',
            'expiry_date'      => 'nullable|date',
            'google_map_url'   => 'nullable|string|max:500',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            // Location
            'country'          => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:100',
            'location'         => 'nullable|string|max:255',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            // Media
            'video_url'        => 'nullable|string|max:500',
            // SEO
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords'         => 'nullable|string|max:500',
        ];
    }

    private function makeSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = Post::withTrashed()->where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}