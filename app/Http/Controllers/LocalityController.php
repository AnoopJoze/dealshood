<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Carbon\Carbon;

class LocalityController extends Controller
{
    public function index()
    {
        $localities = Locality::orderBy('name')->get();

    return view(
        'localities.list',
        compact('localities')
    );
    }
    public function data(Request $request)
    {
        $query = Locality::with('parent');

        return DataTables::of($query)

            ->addIndexColumn()

            // Search Name
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
            // Custom Columns
            ->addColumn('parent', function ($row) {
                return $row->parent?->name ?? '-';
            })

            ->addColumn('action', function ($row) {
                return '
                    <button data-id="'.$row->id.'" class="btn btn-sm btn-danger delete-btn">Delete</button>
                ';
            })->addColumn('name', function ($row) {
    return '<input type="text"
        class="inline-edit form-control"
        data-id="'.$row->id.'"
        data-field="name"
        value="'.$row->name.'">';
})

->addColumn('parent', function ($row) {

    $options = Locality::where('id', '!=', $row->id)
        ->get()
        ->map(function ($p) use ($row) {
            return '<option value="'.$p->id.'" '.($row->parent_id == $p->id ? 'selected' : '').'>
                        '.$p->name.'
                    </option>';
        })->implode('');

    return '
        <select class="inline-edit form-control"
            data-id="'.$row->id.'"
            data-field="parent_id">

            <option value="">None</option>
            '.$options.'

        </select>
    ';
})

->addColumn('status', function ($row) {
    return '
        <select class="inline-edit form-control"
            data-id="'.$row->id.'"
            data-field="is_active">

            <option value="1" '.($row->is_active ? 'selected' : '').'>Active</option>
            <option value="0" '.(!$row->is_active ? 'selected' : '').'>Inactive</option>

        </select>
    ';
})

            ->rawColumns(['action','parent','name','status','created_at'])

            ->make(true);
    }
    public function inlineUpdate(Request $request)
{
    $request->validate([
        'id' => 'required|exists:localities,id',
        'field' => 'required',
        'value' => 'nullable',
    ]);

    $locality = Locality::findOrFail($request->id);

    switch ($request->field) {

        case 'name':
            $locality->name = $request->value;
            $locality->slug = \Str::slug($request->value);
            break;

        case 'type':
            $locality->type = $request->value;
            break;

        case 'parent_id':
            $locality->parent_id = $request->value ?: null;
            break;

        case 'is_active':
            $locality->is_active = $request->value == 1 ? 1 : 0;
            break;

        default:
            return response()->json(['error' => 'Invalid field'], 422);
    }

    $locality->save();

    return response()->json(['success' => true]);
}
public function ajaxStore(Request $request)
{
    $request->validate([
        'name' => 'required|max:255',
        'type' => 'required|in:country,state,city,area',
        'parent_id' => 'nullable|exists:localities,id',
    ]);

    $locality = Locality::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'type' => $request->type,
        'parent_id' => $request->parent_id,
        'is_active' => 1,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Locality added successfully',
        'data' => $locality
    ]);
}
    public function create()
    {
        $parents = Locality::all();
        return view('admin.localities.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        Locality::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.localities.index')
            ->with('success', 'Locality created');
    }

    public function edit(Locality $locality)
    {
        $parents = Locality::where('id', '!=', $locality->id)->get();
        return view('admin.localities.edit', compact('locality', 'parents'));
    }

    public function update(Request $request, Locality $locality)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $locality->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.localities.index')
            ->with('success', 'Locality updated');
    }

    public function destroy(Locality $locality)
    {
        $locality->delete();

        return back()->with('success', 'Deleted');
    }
}
