<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;

class LocalityController extends Controller
{
    const TYPES = ['country', 'state', 'city', 'area'];

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $localities = Locality::orderBy('type')->orderBy('name')->get();

        $stats = [
            'total'    => Locality::count(),
            'active'   => Locality::where('is_active', true)->count(),
            'inactive' => Locality::where('is_active', false)->count(),
            'country'  => Locality::where('type', 'country')->count(),
            'state'    => Locality::where('type', 'state')->count(),
            'city'     => Locality::where('type', 'city')->count(),
            'area'     => Locality::where('type', 'area')->count(),
        ];

        return view('localities.list', compact('localities', 'stats'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE FEED
    |--------------------------------------------------------------------------
    */
    public function data(Request $request)
    {
        $query = Locality::with('parent')->select('localities.*');

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        return DataTables::of($query)

            ->addIndexColumn()

            /*── Name (inline editable) ──*/
            ->addColumn('name', function ($row) {
                return '
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill type-badge-' . $row->type . ' px-2 py-1"
                              style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
                            ' . $row->type . '
                        </span>
                        <input type="text"
                               class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold"
                               style="min-width:140px;"
                               data-id="' . $row->id . '"
                               data-field="name"
                               value="' . e($row->name) . '">
                    </div>';
            })

            /*── Parent (inline editable select) ──*/
            ->addColumn('parent', function ($row) {
                $options = Locality::where('id', '!=', $row->id)
                    ->orderBy('type')->orderBy('name')
                    ->get()
                    ->map(function ($p) use ($row) {
                        $sel = $row->parent_id == $p->id ? 'selected' : '';
                        return '<option value="' . $p->id . '" ' . $sel . '>'
                            . ucfirst($p->type) . ' → ' . e($p->name)
                            . '</option>';
                    })->implode('');

                return '
                    <select class="form-select form-select-sm border-0 bg-transparent inline-edit"
                            style="min-width:160px;"
                            data-id="' . $row->id . '"
                            data-field="parent_id">
                        <option value="">— None —</option>
                        ' . $options . '
                    </select>';
            })

            /*── Type (inline editable select) ──*/
            ->addColumn('type', function ($row) {
                $options = '';
                foreach (self::TYPES as $t) {
                    $sel      = $row->type === $t ? 'selected' : '';
                    $options .= '<option value="' . $t . '" ' . $sel . '>' . ucfirst($t) . '</option>';
                }
                return '
                    <select class="form-select form-select-sm border-0 bg-transparent inline-edit"
                            style="min-width:110px;"
                            data-id="' . $row->id . '"
                            data-field="type">
                        ' . $options . '
                    </select>';
            })

            /*── Status (inline editable select) ──*/
            ->addColumn('status', function ($row) {
                return '
                    <select class="form-select form-select-sm border-0 inline-edit status-select
                                   ' . ($row->is_active ? 'text-success' : 'text-danger') . '"
                            style="min-width:100px;font-weight:600;font-size:.78rem;"
                            data-id="' . $row->id . '"
                            data-field="is_active">
                        <option value="1" ' . ($row->is_active ? 'selected' : '') . '>● Active</option>
                        <option value="0" ' . (!$row->is_active ? 'selected' : '') . '>● Inactive</option>
                    </select>';
            })

            /*── Created date ──*/
            ->editColumn('created_at', function ($row) {
                return '<div class="text-sm text-muted">'
                    . Carbon::parse($row->created_at)->format('d M Y')
                    . '<br><small>' . Carbon::parse($row->created_at)->diffForHumans() . '</small></div>';
            })

            /*── Actions ──*/
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-light border delete-btn"
                            data-id="' . $row->id . '"
                            data-name="' . e($row->name) . '"
                            title="Delete">
                        <i class="fas fa-trash text-danger"></i>
                    </button>';
            })

            ->rawColumns(['name', 'parent', 'type', 'status', 'created_at', 'action'])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX STORE
    |--------------------------------------------------------------------------
    */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:country,state,city,area',
            'parent_id' => 'nullable|exists:localities,id',
        ]);

        $locality = Locality::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'type'      => $request->type,
            'parent_id' => $request->parent_id ?: null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Locality created successfully.',
            'data'    => $locality,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | INLINE UPDATE
    |--------------------------------------------------------------------------
    */
    public function inlineUpdate(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:localities,id',
            'field' => 'required|in:name,type,parent_id,is_active',
            'value' => 'nullable',
        ]);

        $locality = Locality::findOrFail($request->id);

        switch ($request->field) {
            case 'name':
                $locality->name = $request->value;
                $locality->slug = Str::slug($request->value);
                break;
            case 'type':
                $locality->type = $request->value;
                break;
            case 'parent_id':
                $locality->parent_id = $request->value ?: null;
                break;
            case 'is_active':
                $locality->is_active = (bool) $request->value;
                break;
        }

        $locality->save();

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Locality $locality)
    {
        // Detach children before deleting
        $locality->children()->update(['parent_id' => null]);
        $locality->update(['is_active'=>0]);

        return response()->json(['success' => true, 'message' => 'Locality deleted successfully.']);
    }
}