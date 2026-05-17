<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Carbon\Carbon;

class SubcategoryController extends Controller
{
    public function index()
    {
       $categories = Category::orderBy('name')->get();

    return view(
        'subcategories.list',
        compact('categories')
    );
    }
    public function data(Request $request)
    {
        $query = Subcategory::with('category');

        return DataTables::of($query)

            ->addIndexColumn()

            ->filterColumn('name', function ($query, $keyword) {
                $query->where('subcategories.name', 'like', "%{$keyword}%");
            })

            ->filterColumn('category', function ($query, $keyword) {
                $query->whereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

        ->editColumn('created_at', function ($row) {

            return '
                <div class="text-sm text-muted fw-medium">
                    ' . Carbon::parse($row->created_at)->format('d M Y') . '
                    <br>

                    <small class="text-xs">
                        ' . Carbon::parse($row->created_at)->diffForHumans() . '
                    </small>
                </div>
            ';
        })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('action', function ($row) {
                return '
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete-btn">Delete</button>
                ';
            })
            ->addColumn('name', function ($row) {
    return '<input type="text"
                class="inline-edit form-control"
                data-id="'.$row->id.'"
                data-field="name"
                value="'.$row->name.'">';
})

->addColumn('category', function ($row) {
    return '<select class="inline-edit form-control"
                data-id="'.$row->id.'"
                data-field="category_id">
            '.\App\Models\Category::all()->map(function($c) use ($row) {
                return '<option value="'.$c->id.'" '.($row->category_id == $c->id ? 'selected' : '').'>
                    '.$c->name.'
                </option>';
            })->implode('').'
        </select>';
})

->addColumn('status', function ($row) {
    return '
        <select class="inline-edit form-control" data-id="'.$row->id.'" data-field="is_active">
            <option value="1" '.($row->is_active ? 'selected' : '').'>Active</option>
            <option value="0" '.(!$row->is_active ? 'selected' : '').'>Inactive</option>
        </select>
    ';
})

            ->rawColumns(['status', 'action','name','category','created_at'])
            ->make(true);
    }
public function inlineUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:subcategories,id',
        'field' => 'required',
        'value' => 'required'
    ]);

    $sub = Subcategory::findOrFail($request->id);

    if (!in_array($request->field, ['name', 'category_id', 'is_active'])) {
        return response()->json(['error' => 'Invalid field'], 422);
    }

    if ($request->field === 'name') {
        $sub->name = $request->value;
        $sub->slug = \Str::slug($request->value);
    }

    if ($request->field === 'category_id') {
        $sub->category_id = $request->value;
    }

    if ($request->field === 'is_active') {
        $sub->is_active = $request->value == 1 ? 1 : 0;
    }

    $sub->save();

    return response()->json(['success' => true]);
}
    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

public function ajaxStore(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
    ]);

    $subcategory = Subcategory::create([
        'category_id' => $request->category_id,
        'name' => $request->name,
        'slug' => \Str::slug($request->name),
        'is_active' => 1,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Subcategory created successfully',
        'data' => $subcategory
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
        ]);

        Subcategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.subcategories.index');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $subcategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.subcategories.index');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return back();
    }
    public function getByCategory($id)
{
    return Subcategory::where('category_id', $id)
        ->select('id', 'name')
        ->get();
}
}
