<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ads.view')  ->only(['index', 'data']);
        $this->middleware('can:ads.create')->only(['store']);
        $this->middleware('can:ads.edit')  ->only(['update', 'inlineUpdate']);
        $this->middleware('can:ads.delete')->only(['destroy']);
    }

    public function index()
    {
        $stats = [
            'total'    => Ad::count(),
            'active'   => Ad::where('is_active', true)->count(),
            'inactive' => Ad::where('is_active', false)->count(),
        ];
        return view('ads.list', compact('stats'));
    }

    public function data(Request $request)
    {
        $query = Ad::query();
        if ($request->filled('status')) $query->where('is_active', $request->status);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $canEdit   = auth()->user()->can('ads.edit');
        $canDelete = auth()->user()->can('ads.delete');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', fn($row) =>
                '<img src="'.$row->image_url.'" width="60" height="40" class="rounded" style="object-fit:cover;">'
            )
            ->addColumn('title', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="fw-semibold text-sm">'.e($row->title).'</span>';
                return '<input type="text" class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold" style="min-width:160px;" data-id="'.$row->id.'" data-field="title" value="'.e($row->title).'">';
            })
            ->addColumn('link_url', function ($row) use ($canEdit) {
                if (!$canEdit) return $row->link_url ? '<a href="'.e($row->link_url).'" target="_blank" class="text-sm">'.e(Str::limit($row->link_url, 40)).'</a>' : '<span class="text-muted">—</span>';
                return '<input type="text" class="form-control form-control-sm border-0 bg-transparent inline-edit" style="min-width:180px;" data-id="'.$row->id.'" data-field="link_url" value="'.e($row->link_url).'" placeholder="https://…">';
            })
            ->addColumn('sort_order', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="text-sm">'.$row->sort_order.'</span>';
                return '<input type="number" class="form-control form-control-sm border-0 bg-transparent inline-edit" style="min-width:70px;" data-id="'.$row->id.'" data-field="sort_order" value="'.$row->sort_order.'">';
            })
            ->addColumn('status', function ($row) use ($canEdit) {
                if (!$canEdit) {
                    $c = $row->is_active ? 'text-success' : 'text-danger';
                    return '<span class="badge rounded-pill '.$c.'" style="font-size:.75rem;">'.($row->is_active ? '● Active' : '● Inactive').'</span>';
                }
                return '<select class="form-select form-select-sm border-0 inline-edit status-select '.($row->is_active ? 'text-success' : 'text-danger').'" style="min-width:105px;font-weight:600;font-size:.78rem;" data-id="'.$row->id.'" data-field="is_active">
                    <option value="1" '.($row->is_active ? 'selected' : '').'>● Active</option>
                    <option value="0" '.(!$row->is_active ? 'selected' : '').'>● Inactive</option></select>';
            })
            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>'
            )
            ->addColumn('action', function ($row) use ($canEdit, $canDelete) {
                $btns = '<div style="display:flex;gap:5px;align-items:center;">';
                if ($canEdit) $btns .= '<button class="btn btn-sm btn-light border edit-btn" data-id="'.$row->id.'" data-title="'.e($row->title).'" data-link="'.e($row->link_url).'" data-image="'.e($row->image_url).'" title="Replace image"><i class="fas fa-image text-primary"></i></button>';
                if ($canDelete) $btns .= '<button class="btn btn-sm btn-light border delete-btn" data-id="'.$row->id.'" data-title="'.e($row->title).'" title="Delete"><i class="fas fa-trash text-danger"></i></button>';
                return $btns.'</div>';
            })
            ->rawColumns(['image', 'title', 'link_url', 'sort_order', 'status', 'created_at', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
            'link_url' => 'nullable|url|max:500',
        ]);

        $path = $request->file('image')->store('ads', 'public');

        $ad = Ad::create([
            'title'      => $request->title,
            'image'      => $path,
            'link_url'   => $request->link_url,
            'is_active'  => true,
            'sort_order' => (int) Ad::max('sort_order') + 1,
        ]);

        return response()->json(['success' => true, 'message' => 'Ad created.', 'data' => $ad]);
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'link_url' => 'nullable|url|max:500',
        ]);

        $ad->title    = $request->title;
        $ad->link_url = $request->link_url;

        if ($request->hasFile('image')) {
            if ($ad->image && Storage::disk('public')->exists($ad->image)) {
                Storage::disk('public')->delete($ad->image);
            }
            $ad->image = $request->file('image')->store('ads', 'public');
        }

        $ad->save();

        return response()->json(['success' => true, 'message' => 'Ad updated.', 'data' => $ad]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:ads,id',
            'field' => 'required|in:title,link_url,sort_order,is_active',
            'value' => 'nullable',
        ]);

        $ad = Ad::findOrFail($request->id);

        switch ($request->field) {
            case 'title':
                $request->validate(['value' => 'required|string|max:255']);
                $ad->title = $request->value;
                break;
            case 'link_url':
                $request->validate(['value' => 'nullable|url|max:500']);
                $ad->link_url = $request->value ?: null;
                break;
            case 'sort_order':
                $ad->sort_order = (int) $request->value;
                break;
            case 'is_active':
                $ad->is_active = (bool) $request->value;
                break;
        }
        $ad->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image && Storage::disk('public')->exists($ad->image)) {
            Storage::disk('public')->delete($ad->image);
        }
        $ad->delete();

        return response()->json(['success' => true, 'message' => 'Ad deleted.']);
    }
}
