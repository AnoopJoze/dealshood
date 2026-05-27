<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;

class SubCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        $stats = [
            'total'    => Subcategory::count(),
            'active'   => Subcategory::where('is_active', true)->count(),
            'inactive' => Subcategory::where('is_active', false)->count(),
        ];

        return view('subcategories.list', compact('categories', 'stats'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE FEED
    |--------------------------------------------------------------------------
    */
    public function data(Request $request)
    {
        $query = Subcategory::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
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

        // Pre-load all categories once for the inline category select
        $allCategories = Category::orderBy('name')->get();

        return DataTables::of($query)

            ->addIndexColumn()

            ->filterColumn('name', function ($q, $keyword) {
                $q->where('subcategories.name', 'like', "%{$keyword}%");
            })

            ->filterColumn('category', function ($q, $keyword) {
                $q->whereHas('category', fn($qq) => $qq->where('name', 'like', "%{$keyword}%"));
            })

            /*── Name (inline editable) ──*/
            ->addColumn('name', function ($row) {
                return '
                    <input type="text"
                           class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold"
                           style="min-width:160px;"
                           data-id="' . $row->id . '"
                           data-field="name"
                           value="' . e($row->name) . '">';
            })

            /*── Category (inline editable select) ──*/
            ->addColumn('category', function ($row) use ($allCategories) {
                $options = $allCategories->map(function ($c) use ($row) {
                    $sel = $row->category_id == $c->id ? 'selected' : '';
                    return '<option value="' . $c->id . '" ' . $sel . '>' . e($c->name) . '</option>';
                })->implode('');

                return '
                    <select class="form-select form-select-sm border-0 bg-transparent inline-edit"
                            style="min-width:160px;"
                            data-id="' . $row->id . '"
                            data-field="category_id">
                        <option value="">— None —</option>
                        ' . $options . '
                    </select>';
            })

            /*── Status (inline editable) ──*/
            ->addColumn('status', function ($row) {
                return '
                    <select class="form-select form-select-sm border-0 inline-edit status-select
                                   ' . ($row->is_active ? 'text-success' : 'text-danger') . '"
                            style="min-width:105px;font-weight:600;font-size:.78rem;"
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

            ->rawColumns(['name', 'category', 'status', 'created_at', 'action'])
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
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
        ]);

        $subcategory = Subcategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'is_active'   => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subcategory created successfully.',
            'data'    => $subcategory->load('category'),
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
            'id'    => 'required|exists:subcategories,id',
            'field' => 'required|in:name,category_id,is_active',
            'value' => 'nullable',
        ]);

        $sub = Subcategory::findOrFail($request->id);

        switch ($request->field) {
            case 'name':
                $request->validate(['value' => 'required|string|max:255']);
                $sub->name = $request->value;
                $sub->slug = Str::slug($request->value);
                break;
            case 'category_id':
                $request->validate(['value' => 'nullable|exists:categories,id']);
                $sub->category_id = $request->value ?: null;
                break;
            case 'is_active':
                $sub->is_active = (bool) $request->value;
                break;
        }

        $sub->save();

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return response()->json(['success' => true, 'message' => 'Subcategory deleted successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY CATEGORY  (cascade for post/user modals)
    |--------------------------------------------------------------------------
    */
    public function getByCategory($id)
    {
        return Subcategory::where('category_id', $id)
            ->where('is_active', true)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();
    }
}