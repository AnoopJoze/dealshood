<?php
namespace App\Http\Controllers;

use App\Models\Category; use App\Models\Locality; use App\Models\Post;
use App\Models\Subcategory; use App\Models\User;
use Carbon\Carbon; use DataTables; use Illuminate\Http\Request; use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:posts.view')  ->only(['index','data','show','editData']);
        $this->middleware('can:posts.create')->only(['ajaxStore']);
        $this->middleware('can:posts.edit')  ->only(['update','inlineUpdate']);
        $this->middleware('can:posts.delete')->only(['destroy','restore','forceDelete','bulkTrash','bulkRestore','emptyTrash']);
        $this->middleware('can:media.upload')->only(['mediaUpload']);
        $this->middleware('can:media.delete')->only(['mediaDelete']);
    }

    public function index()
    {
        $categories    = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $localities    = Locality::orderBy('name')->get();
        $users         = User::orderBy('name')->get(['id','name','email']);
        return view('posts.list', compact('categories','subcategories','localities','users'));
    }

    public function data(Request $request)
    {
        $showTrashed = $request->boolean('trashed');
        $query = $showTrashed
                    ? Post::onlyTrashed()->with(['category','subcategory','locality','user','media'])
                    : Post::with(['category','subcategory','locality','user','media']);

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('start_date'))  $query->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))    $query->whereDate('created_at', '<=', $request->end_date);

        $totalCount     = (clone $query)->count();
        $publishedCount = (clone $query)->where('status','published')->count();
        $draftCount     = (clone $query)->where('status','draft')->count();
        $archivedCount  = (clone $query)->where('status','archived')->count();
        $trashedCount   = Post::onlyTrashed()->count();

        $canEdit   = auth()->user()->can('posts.edit');
        $canDelete = auth()->user()->can('posts.delete');

        return DataTables::of($query)
            ->addColumn('title', function ($row) {
                $thumb = $row->getMedia('posts')->first();
                $img = $thumb
                    ? '<img src="'.$thumb->getUrl().'" width="36" height="36" class="rounded me-2" style="object-fit:cover;flex-shrink:0;">'
                    : '<div class="rounded me-2 bg-light d-flex align-items-center justify-content-center" style="width:36px;height:36px;flex-shrink:0;"><i class="fas fa-image text-muted" style="font-size:.7rem;"></i></div>';
                $trashBadge = $row->trashed()
                    ? '<span class="badge bg-danger-subtle text-danger ms-1" style="font-size:.6rem;">Trashed</span>' : '';
                return '<div class="d-flex align-items-center">'.$img
                    .'<div><div class="text-sm fw-semibold text-dark text-truncate" style="max-width:180px;">'.e($row->title)
                    .($row->is_featured ? ' <i class="fas fa-star text-warning ms-1" style="font-size:.65rem;" title="Featured"></i>' : '')
                    .$trashBadge.'</div><small class="text-muted">#'.$row->id.' · '.$row->slug.'</small></div></div>';
            })
            ->addColumn('category', fn($row) =>
                $row->category
                    ? '<span class="badge bg-primary-subtle text-primary rounded-pill px-2">'.e($row->category->name).'</span>'
                    : '<span class="text-muted">—</span>'
            )
            ->addColumn('subcategory', fn($row) =>
                $row->subcategory
                    ? '<span class="badge bg-light text-secondary border rounded-pill px-2">'.e($row->subcategory->name).'</span>'
                    : '<span class="text-muted">—</span>'
            )
            ->addColumn('locality', fn($row) =>
                $row->locality
                    ? '<span class="text-sm"><i class="fas fa-map-marker-alt me-1 text-muted" style="font-size:.65rem;"></i>'.e($row->locality->name).'</span>'
                    : '<span class="text-muted">—</span>'
            )
            ->addColumn('status', function ($row) use ($canEdit) {
                if ($row->trashed()) return '<span class="badge bg-danger-subtle text-danger rounded-pill px-2">Trashed</span>';
                $map = ['published'=>['bg-success-subtle','text-success'],'draft'=>['bg-secondary-subtle','text-secondary'],'archived'=>['bg-warning-subtle','text-warning']];
                [$bg,$tc] = $map[$row->status] ?? ['bg-light','text-muted'];
                if (!$canEdit) {
                    return '<span class="badge '.$bg.' '.$tc.' rounded-pill px-2">'.ucfirst($row->status).'</span>';
                }
                $select = '<select class="form-select form-select-sm inline-status border-0 '.$bg.' '.$tc.' fw-semibold" data-id="'.$row->id.'" style="min-width:115px;border-radius:.5rem;font-size:.75rem;">';
                foreach (['draft'=>'Draft','published'=>'Published','archived'=>'Archived'] as $v=>$l) {
                    $select .= '<option value="'.$v.'"'.($row->status===$v?' selected':'').'>'.$l.'</option>';
                }
                return $select.'</select>';
            })
            ->addColumn('expiry', function ($row) {
                if (!$row->expiry_date) return '<span class="text-muted text-xs">—</span>';
                $date = Carbon::parse($row->expiry_date);
                $expired = now()->gt($date);
                if ($expired) return '<span class="badge bg-danger-subtle text-danger rounded-pill px-2">Expired '.$date->format('d M Y').'</span>';
                $soon = now()->diffInDays($date, false) <= 7;
                return '<span class="text-sm '.($soon?'text-warning fw-semibold':'text-muted').'">'.$date->format('d M Y').'<br><small>'.$date->diffForHumans().'</small></span>';
            })
            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>'
            )
            ->addColumn('action', function ($row) use ($canEdit, $canDelete) {
                $btns = '<div style="display:flex;gap:5px;align-items:center;">';
                $btns .= $this->actionBtn(route('posts.show',$row->id),'a','fa-eye','#6366f1','View','view');
                if ($canEdit)   $btns .= $this->actionBtn(null,'button','fa-pen','#d97706','Edit post','edit','editPost','data-id="'.$row->id.'"');
                if ($canDelete) $btns .= $this->actionBtn(null,'button','fa-trash','#dc2626','Move to trash','delete','deletePost','data-id="'.$row->id.'" data-title="'.e($row->title).'"');
                return $btns.'</div>';
            })
            ->rawColumns(['title','category','subcategory','locality','status','expiry','created_at','action'])
            ->with(compact('totalCount','publishedCount','draftCount','archivedCount','trashedCount'))
            ->make(true);
    }

    
    public function editData(Post $post)
    {
        $images = $post->getMedia('posts')->map(fn($m) => ['id' => $m->id, 'url' => $m->getUrl()]);
        $video  = $post->getMedia('videos')->first();
 
        return response()->json([
            ...$post->toArray(),
            'images'          => $images,
            'video_url_media' => $video?->getUrl(),
            // Explicitly include encrypted contact fields
            'company_name'    => $post->company_name,
            'phone_number'    => $post->phone_number,
            'whatsapp_number' => $post->whatsapp_number,
            'google_map_url'  => $post->google_map_url,
        ]);
    }

    public function ajaxStore(Request $request)
    {
        $validated = $request->validate($this->rules());
        $validated['slug']         = $this->makeSlug($validated['title']);
        $validated['user_id']      = $validated['user_id'] ?? auth()->id();
        $validated['published_at'] = ($validated['status']==='published') ? now() : null;
        $post = Post::create($validated);
        if ($request->hasFile('video')) $post->addMediaFromRequest('video')->toMediaCollection('videos');
        return response()->json(['success'=>true,'data'=>$post]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate($this->rules($post->id));
        if ($validated['status']==='published' && !$post->published_at) $validated['published_at'] = now();
        $post->update($validated);
        if ($request->hasFile('video')) {
            $post->clearMediaCollection('videos');
            $post->addMediaFromRequest('video')->toMediaCollection('videos');
        }
        return response()->json(['success'=>true,'data'=>$post->fresh()]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate(['id'=>'required|exists:posts,id','field'=>'required|in:status,is_featured,is_active','value'=>'required']);
        Post::findOrFail($request->id)->update([$request->field=>$request->value]);
        return response()->json(['success'=>true]);
    }

    public function destroy(Post $post)         { $post->delete(); return response()->json(['success'=>true,'message'=>'Post moved to trash.']); }
    public function restore(int $id)            { Post::onlyTrashed()->findOrFail($id)->restore(); return response()->json(['success'=>true,'message'=>'Post restored.']); }
    public function forceDelete(int $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->clearMediaCollection('posts'); $post->clearMediaCollection('videos'); $post->forceDelete();
        return response()->json(['success'=>true,'message'=>'Post permanently deleted.']);
    }
    public function bulkTrash(Request $request)
    {
        $request->validate(['ids'=>'required|array','ids.*'=>'integer|exists:posts,id']);
        Post::whereIn('id',$request->ids)->delete();
        return response()->json(['success'=>true,'message'=>count($request->ids).' posts moved to trash.']);
    }
    public function bulkRestore(Request $request)
    {
        $request->validate(['ids'=>'required|array','ids.*'=>'integer']);
        Post::onlyTrashed()->whereIn('id',$request->ids)->restore();
        return response()->json(['success'=>true,'message'=>count($request->ids).' posts restored.']);
    }
    public function emptyTrash()
    {
        Post::onlyTrashed()->get()->each(function($post){ $post->clearMediaCollection('posts'); $post->clearMediaCollection('videos'); $post->forceDelete(); });
        return response()->json(['success'=>true,'message'=>'Trash emptied.']);
    }
    public function mediaUpload(Request $request)
    {
        $request->validate(['file'=>'required|image|max:5120','post_id'=>'required|exists:posts,id']);
        $media = Post::findOrFail($request->post_id)->addMediaFromRequest('file')->toMediaCollection('posts');
        return response()->json(['success'=>true,'id'=>$media->id,'url'=>$media->getUrl()]);
    }
    public function mediaDelete($id) { Media::findOrFail($id)->delete(); return response()->json(['success'=>true]); }

    public function show(Post $post)
    {
        $post->load(['category','subcategory','locality','user','likesData','sharesData','viewsData','media']);
        $post->increment('views');
        return view('posts.show', compact('post') + [
            'categories' => Category::orderBy('name')->get(),
            'localities' => Locality::orderBy('name')->get(),
            'users'      => User::orderBy('name')->get(['id','name','email']),
        ]);
    }

    /* ── helpers ─────────────────────────────────── */
    private function actionBtn($href, $tag, $icon, $hoverColor, $title, $type, $class='', $extra='')
    {
        $base = 'width:30px;height:30px;border-radius:7px;border:1px solid #f1f5f9;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;color:#94a3b8;transition:all .14s;';
        $over = "this.style.borderColor='{$hoverColor}';this.style.color='{$hoverColor}';this.style.background='".($hoverColor==='#dc2626'?'#fef2f2':'#f8fafc')."';";
        $out  = "this.style.borderColor='#f1f5f9';this.style.color='#94a3b8';this.style.background='#fff';";
        if ($tag==='a') {
            return '<a href="'.$href.'" title="'.$title.'" style="'.$base.'text-decoration:none;cursor:pointer;" onmouseover="'.$over.'" onmouseout="'.$out.'"><i class="fas '.$icon.'"></i></a>';
        }
        return '<button class="'.$class.'" '.$extra.' title="'.$title.'" style="'.$base.'cursor:pointer;" onmouseover="'.$over.'" onmouseout="'.$out.'"><i class="fas '.$icon.'"></i></button>';
    }

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
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
            // Location
            'country'          => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'city'             => 'nullable|string|max:100',
            'location'         => 'nullable|string|max:255',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'google_map_url'   => 'nullable|string|max:500',
            // Contact
            'company_name'     => 'nullable|string|max:255',
            'phone_number'     => 'nullable|string|max:20',
            'whatsapp_number'  => 'nullable|string|max:20',
            // Media / SEO
            'video_url'        => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords'         => 'nullable|string|max:500',
        ];
    }
 
// Patch 2 — editData(): add the three contact fields to the returned array
// Replace the existing editData method with:
 

    private function makeSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = Post::withTrashed()->where('slug','like',"{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}