<?php

namespace App\Http\Controllers;

use App\Models\AdSubmission;
use App\Models\Category;
use App\Models\Locality;
use App\Models\Post;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:posts.view')   ->only(['index', 'data', 'show']);
        $this->middleware('can:posts.create') ->only(['approve']);
        $this->middleware('can:posts.delete') ->only(['destroy', 'reject']);
    }

    public function index()
    {
        return view('ad-submissions.list');
    }

    public function data(Request $request)
    {
        $query = AdSubmission::with(['category', 'subcategory', 'locality', 'media']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalCount    = (clone $query)->count();
        $pendingCount  = (clone $query)->where('status', 'pending')->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();

        return DataTables::of($query)
            ->addColumn('title', function ($row) {
                $thumb = $row->getFirstMediaUrl('images');
                $img = $thumb
                    ? '<img src="'.$thumb.'" width="36" height="36" class="rounded me-2" style="object-fit:cover;flex-shrink:0;">'
                    : '<div class="rounded me-2 bg-light d-flex align-items-center justify-content-center" style="width:36px;height:36px;flex-shrink:0;"><i class="fas fa-image text-muted" style="font-size:.7rem;"></i></div>';
                return '<div class="d-flex align-items-center">'.$img
                    .'<div><div class="text-sm fw-semibold text-dark text-truncate" style="max-width:200px;">'.e($row->title)
                    .'</div><small class="text-muted">#'.$row->id.' · '.$row->getMedia('images')->count().' image(s)</small></div></div>';
            })
            ->addColumn('submitter', fn($row) =>
                '<div><div class="text-sm fw-semibold">'.e($row->name).'</div>'
                .'<small class="text-muted">'.e($row->email).'</small></div>'
            )
            ->addColumn('category', function ($row) {
                if (!$row->category) return '<span class="text-muted">—</span>';
                $html = '<span class="badge bg-primary-subtle text-primary rounded-pill px-2">'.e($row->category->name).'</span>';
                $sub  = $row->subcategory ? e($row->subcategory->name) : ($row->custom_subcategory ? e($row->custom_subcategory).' <em class="text-muted">(custom)</em>' : null);
                if ($sub) $html .= '<br><small class="text-muted">'.$sub.'</small>';
                return $html;
            })
            ->addColumn('locality', function ($row) {
                if ($row->locality) return '<span class="text-sm">'.e($row->locality->name).'</span>';
                if ($row->custom_locality) return '<span class="text-sm">'.e($row->custom_locality).' <em class="text-muted">(custom)</em></span>';
                return '<span class="text-muted">—</span>';
            })
            ->addColumn('status', function ($row) {
                [$bg, $tc] = $row->status_badge;
                return '<span class="badge '.$bg.' '.$tc.' rounded-pill px-2">'.ucfirst($row->status).'</span>';
            })
            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>'
            )
            ->addColumn('action', function ($row) {
                $btns = '<div style="display:flex;gap:5px;align-items:center;">';
                $btns .= $this->actionBtn(route('ad-submissions.show', $row->id), 'a', 'fa-eye', '#6366f1', 'View', 'view');
                if ($row->status === 'pending') {
                    $btns .= '<button class="approveBtn" data-id="'.$row->id.'" title="Approve" style="width:30px;height:30px;border-radius:7px;border:1px solid #f1f5f9;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;color:#94a3b8;cursor:pointer;" onmouseover="this.style.borderColor=\'#059669\';this.style.color=\'#059669\';this.style.background=\'#f0fdf4\';" onmouseout="this.style.borderColor=\'#f1f5f9\';this.style.color=\'#94a3b8\';this.style.background=\'#fff\';"><i class="fas fa-check"></i></button>';
                    $btns .= '<button class="rejectBtn" data-id="'.$row->id.'" data-title="'.e($row->title).'" title="Reject" style="width:30px;height:30px;border-radius:7px;border:1px solid #f1f5f9;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;color:#94a3b8;cursor:pointer;" onmouseover="this.style.borderColor=\'#dc2626\';this.style.color=\'#dc2626\';this.style.background=\'#fef2f2\';" onmouseout="this.style.borderColor=\'#f1f5f9\';this.style.color=\'#94a3b8\';this.style.background=\'#fff\';"><i class="fas fa-times"></i></button>';
                }
                $btns .= '<button class="deleteBtn" data-id="'.$row->id.'" data-title="'.e($row->title).'" title="Delete" style="width:30px;height:30px;border-radius:7px;border:1px solid #f1f5f9;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;color:#94a3b8;cursor:pointer;" onmouseover="this.style.borderColor=\'#dc2626\';this.style.color=\'#dc2626\';this.style.background=\'#fef2f2\';" onmouseout="this.style.borderColor=\'#f1f5f9\';this.style.color=\'#94a3b8\';this.style.background=\'#fff\';"><i class="fas fa-trash"></i></button>';
                return $btns.'</div>';
            })
            ->rawColumns(['title', 'submitter', 'category', 'locality', 'status', 'created_at', 'action'])
            ->with(compact('totalCount', 'pendingCount', 'approvedCount', 'rejectedCount'))
            ->make(true);
    }

    public function show(AdSubmission $submission)
    {
        $submission->load(['category', 'subcategory', 'locality', 'media']);
        return view('ad-submissions.show', compact('submission'));
    }

    public function approve(Request $request, AdSubmission $submission)
    {
        if ($submission->status === 'approved') {
            return response()->json(['success' => false, 'message' => 'Already approved.'], 422);
        }

        $slug  = Str::slug($submission->title);
        $count = Post::withTrashed()->where('slug', 'like', "{$slug}%")->count();
        $slug  = $count ? "{$slug}-{$count}" : $slug;

        $post = Post::create([
            'title'            => $submission->title,
            'slug'             => $slug,
            'description'      => $submission->description,
            'category_id'      => $submission->category_id,
            'subcategory_id'   => $submission->subcategory_id,
            'locality_id'      => $submission->locality_id,
            'user_id'          => auth()->id(),
            'status'           => 'published',
            'published_at'     => now(),
            'is_featured'      => false,
            'is_active'        => true,
            'company_name'     => $submission->company_name,
            'phone_number'     => $submission->phone,
            'whatsapp_number'  => $submission->whatsapp,
            'location'         => $submission->location,
            'offer_percentage' => $submission->offer_percentage,
            'expiry_date'      => $submission->expiry_date,
        ]);

        // Copy images from submission to the new post
        foreach ($submission->getMedia('images') as $media) {
            $media->copy($post, 'posts');
        }

        $submission->update([
            'status'       => 'approved',
            'admin_notes'  => $request->input('admin_notes', $submission->admin_notes),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Ad approved and published!',
            'post_url' => route('posts.show', $post->id),
        ]);
    }

    public function reject(Request $request, AdSubmission $submission)
    {
        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        $submission->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Ad submission rejected.']);
    }

    public function destroy(AdSubmission $submission)
    {
        $submission->clearMediaCollection('images');
        $submission->delete();
        return response()->json(['success' => true, 'message' => 'Submission deleted.']);
    }

    private function actionBtn($href, $tag, $icon, $hoverColor, $title, $type)
    {
        $base = 'width:30px;height:30px;border-radius:7px;border:1px solid #f1f5f9;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;color:#94a3b8;transition:all .14s;';
        $over = "this.style.borderColor='{$hoverColor}';this.style.color='{$hoverColor}';this.style.background='#f8fafc';";
        $out  = "this.style.borderColor='#f1f5f9';this.style.color='#94a3b8';this.style.background='#fff';";
        return '<a href="'.$href.'" title="'.$title.'" style="'.$base.'text-decoration:none;cursor:pointer;" onmouseover="'.$over.'" onmouseout="'.$out.'"><i class="fas '.$icon.'"></i></a>';
    }
}