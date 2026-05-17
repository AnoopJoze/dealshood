<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.list');
    }

    public function data(Request $request)
    {
        $query = Category::query();

        return DataTables::of($query)

            ->addIndexColumn()

            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
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
                class="inline-edit form-control "
                data-id="'.$row->id.'"
                data-field="name"
                value="'.$row->name.'">';
})

->addColumn('status', function ($row) {
    return '
        <select class="inline-edit form-control " data-id="'.$row->id.'" data-field="is_active">
            <option value="1" '.($row->is_active ? 'selected' : '').'>Active</option>
            <option value="0" '.(!$row->is_active ? 'selected' : '').'>Inactive</option>
        </select>
    ';
})

    ->rawColumns(['name', 'status', 'action','created_at'])
            ->make(true);
    }
    public function create()
    {
        return view('categories.create');
    }
    public function inlineUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:categories,id',
        'field' => 'required',
        'value' => 'required'
    ]);

    $category = Category::findOrFail($request->id);

    $field = $request->field;

    if (!in_array($field, ['name', 'is_active'])) {
        return response()->json(['error' => 'Invalid field'], 422);
    }

    if ($field === 'is_active') {
        $category->$field = $request->value == 1 ? 1 : 0;
    } else {
        $category->$field = $request->value;
        $category->slug = \Str::slug($request->value);
    }

    $category->save();

    return response()->json(['success' => true]);
}
public function ajaxStore(Request $request)
{
    $request->validate([
        'name' => 'required|unique:categories,name',
    ]);

    $category = Category::create([
        'name' => $request->name,
        'slug' => \Str::slug($request->name),
        'is_active' => 1,
    ]);

    return response()->json([
        'success' => true,
        'category' => $category
    ]);
}
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }
}
