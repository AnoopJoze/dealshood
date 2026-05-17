<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Locality;
use DataTables;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends Controller
{
    // LIST
    public function index()
    {
       $categories = Category::orderBy('name')->get();
       $subcategories = SubCategory::orderBy('name')->get();
       $localities = Locality::orderBy('name')->get();
        return view('posts.list',
        compact('categories','subcategories','localities'));
    }
public function data(Request $request)
{
    $query = Post::with(['category', 'subcategory', 'locality']);

    // FILTERS
    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->locality_id) {
        $query->where('locality_id', $request->locality_id);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->from && $request->to) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    return DataTables::of($query)

        ->addColumn('title', function ($row) {
            return '<strong>'.$row->title.'</strong>';
        })

        ->addColumn('category', fn($row) => $row->category?->name ?? '-')

        ->addColumn('locality', fn($row) => $row->locality?->name ?? '-')

        // STATUS BADGE
        ->addColumn('status', function ($row) {
            return $row->status == 'published'
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';
        })

        // EXPIRY WARNING
        ->addColumn('expires_at', function ($row) {

            if (!$row->expires_at) return '-';

            $expired = now()->gt($row->expires_at);

            return $expired
                ? '<span class="text-danger">Expired</span>'
                : $row->expires_at->format('d M Y');
        })

        // MAP
        ->addColumn('map', function ($row) {
            if (!$row->latitude) return '-';

            return '<a target="_blank"
                    href="https://www.google.com/maps?q='.$row->latitude.','.$row->longitude.'"
                    class="btn btn-sm btn-info">
                    View Map
                </a>';
        })

        // ACTIONS
        ->addColumn('action', function ($row) {

            return '
                <button class="btn btn-sm btn-primary editPost" data-id="'.$row->id.'">
                    Edit
                </button>

                <button class="btn btn-sm btn-danger deletePost" data-id="'.$row->id.'">
                    Delete
                </button>
            ';
        })
->addColumn('images', function ($row) {

    $media = $row->getMedia('posts');

    if ($media->isEmpty()) return '-';

    $html = '';

    foreach ($media as $m) {
        $html .= '<img src="'.$m->getUrl().'"
                    width="40"
                    class="rounded me-1">';
    }

    return $html;
})
        ->rawColumns(['title', 'status', 'expires_at', 'map', 'action','images'])
        ->make(true);
}
public function uploadImage(Request $request)
{
    if ($request->hasFile('file')) {

        $file = $request->file('file');

        $name = time().'_'.$file->getClientOriginalName();

        $file->move(public_path('uploads/posts'), $name);

        return response()->json([
            'file_name' => $name
        ]);
    }
}
public function mediaUpload(Request $request)
{
    $post = Post::find($request->post_id);

    if (!$post) {
        return response()->json(['error' => 'Post not found'], 404);
    }

    $media = $post
        ->addMedia($request->file('file'))
        ->toMediaCollection('posts');

    return response()->json([
        'id' => $media->id,
        'url' => $media->getUrl()
    ]);
}
public function ajaxStore(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'description' => 'nullable',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'nullable|exists:subcategories,id',
        'locality_id' => 'nullable|exists:localities,id',
        'expiry_date' => 'nullable|date',
        'map_location' => 'nullable|string',
    ]);

    $post = Post::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'description' => $request->description,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'locality_id' => $request->locality_id,
        'expiry_date' => $request->expiry_date,
        'map_location' => $request->map_location,
        'is_active' => 1,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Post created successfully',
        'data' => $post
    ]);
}
    // CREATE FORM
    public function create()
    {
        return view('admin.posts.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $data = $request->all();

        // slug auto-generate
        $data['slug'] = Str::slug($request->title);

        // default user (optional)
        $data['user_id'] = auth()->id();

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created');
    }

    // EDIT
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    // UPDATE
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $data = $request->all();

        // update slug if title changes
        $data['slug'] = Str::slug($request->title);

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated');
    }

    // DELETE
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['success' => true]);
    }

    public function mediaDelete($id)
{
    $media = Media::findOrFail($id);
    $media->delete();

    return response()->json(['success' => true]);
}
}
