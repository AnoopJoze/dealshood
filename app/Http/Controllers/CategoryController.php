<?php
namespace App\Http\Controllers;
use App\Models\Category; use Illuminate\Http\Request; use Illuminate\Support\Str; use Carbon\Carbon; use DataTables;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:categories.view')  ->only(['index','data']);
        $this->middleware('can:categories.create')->only(['ajaxStore']);
        $this->middleware('can:categories.edit')  ->only(['inlineUpdate']);
        $this->middleware('can:categories.delete')->only(['destroy']);
    }

    public function index()
    {
        $stats = ['total'=>Category::count(),'active'=>Category::where('is_active',true)->count(),'inactive'=>Category::where('is_active',false)->count()];
        return view('categories.list', compact('stats'));
    }

    public function data(Request $request)
    {
        $query = Category::withCount('subcategories');
        if ($request->filled('status')) $query->where('is_active',$request->status);
        if ($request->filled('start_date') && $request->filled('end_date'))
            $query->whereBetween('created_at',[Carbon::parse($request->start_date)->startOfDay(),Carbon::parse($request->end_date)->endOfDay()]);

        $canEdit   = auth()->user()->can('categories.edit');
        $canDelete = auth()->user()->can('categories.delete');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="fw-semibold text-sm">'.e($row->name).'</span>';
                return '<input type="text" class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold" style="min-width:160px;" data-id="'.$row->id.'" data-field="name" value="'.e($row->name).'">';
            })
            ->addColumn('subcategories_count', fn($row) =>
                '<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"><i class="fas fa-sitemap me-1" style="font-size:.6rem;"></i>'.$row->subcategories_count.'</span>'
            )
            ->addColumn('status', function ($row) use ($canEdit) {
                if (!$canEdit) {
                    $c = $row->is_active ? 'text-success' : 'text-danger';
                    return '<span class="badge rounded-pill '.$c.'" style="font-size:.75rem;">'.($row->is_active?'● Active':'● Inactive').'</span>';
                }
                return '<select class="form-select form-select-sm border-0 inline-edit status-select '.($row->is_active?'text-success':'text-danger').'" style="min-width:105px;font-weight:600;font-size:.78rem;" data-id="'.$row->id.'" data-field="is_active">
                    <option value="1" '.($row->is_active?'selected':'').'>● Active</option>
                    <option value="0" '.(!$row->is_active?'selected':'').'>● Inactive</option></select>';
            })
            ->editColumn('created_at', fn($row) =>
                '<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>'
            )
            ->addColumn('action', function ($row) use ($canDelete) {
                if (!$canDelete) return '<span class="text-muted text-xs">—</span>';
                return '<button class="btn btn-sm btn-light border delete-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'" data-subs="'.$row->subcategories_count.'" title="Delete"><i class="fas fa-trash text-danger"></i></button>';
            })
            ->rawColumns(['name','subcategories_count','status','created_at','action'])
            ->make(true);
    }

    public function ajaxStore(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255|unique:categories,name']);
        $category = Category::create(['name'=>$request->name,'slug'=>Str::slug($request->name),'is_active'=>true]);
        return response()->json(['success'=>true,'message'=>'Category created.','data'=>$category]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate(['id'=>'required|exists:categories,id','field'=>'required|in:name,is_active','value'=>'nullable']);
        $category = Category::findOrFail($request->id);
        if ($request->field==='name') { $request->validate(['value'=>'required|string|max:255']); $category->name=$request->value; $category->slug=Str::slug($request->value); }
        else { $category->is_active=(bool)$request->value; }
        $category->save();
        return response()->json(['success'=>true]);
    }

    public function destroy(Category $category)
    {
        $category->subcategories()->update(['category_id'=>null]);
        $category->delete();
        return response()->json(['success'=>true,'message'=>'Category deleted.']);
    }
}