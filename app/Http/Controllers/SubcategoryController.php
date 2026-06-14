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
    public function __construct()
    {
        $this->middleware('can:subcategories.view')  ->only(['index','data','getByCategory']);
        $this->middleware('can:subcategories.create')->only(['ajaxStore']);
        $this->middleware('can:subcategories.edit')  ->only(['inlineUpdate']);
        $this->middleware('can:subcategories.delete')->only(['destroy']);
    }

    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $stats = ['total'=>Subcategory::count(),'active'=>Subcategory::where('is_active',true)->count(),'inactive'=>Subcategory::where('is_active',false)->count()];
        return view('subcategories.list', compact('categories','stats'));
    }

    public function data(Request $request)
    {
        $query = Subcategory::with('category');
        if ($request->filled('category_id')) $query->where('category_id',$request->category_id);
        if ($request->filled('status'))      $query->where('is_active',$request->status);
        if ($request->filled('start_date') && $request->filled('end_date'))
            $query->whereBetween('created_at',[Carbon::parse($request->start_date)->startOfDay(),Carbon::parse($request->end_date)->endOfDay()]);

        $allCategories = Category::orderBy('name')->get();
        $canEdit   = auth()->user()->can('subcategories.edit');
        $canDelete = auth()->user()->can('subcategories.delete');

        return DataTables::of($query)
            ->addIndexColumn()
            ->filterColumn('name', fn($q,$k)=>$q->where('subcategories.name','like',"%{$k}%"))
            ->filterColumn('category', fn($q,$k)=>$q->whereHas('category',fn($qq)=>$qq->where('name','like',"%{$k}%")))
            ->addColumn('name', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="fw-semibold text-sm">'.e($row->name).'</span>';
                return '<input type="text" class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold" style="min-width:160px;" data-id="'.$row->id.'" data-field="name" value="'.e($row->name).'">';
            })
            ->addColumn('category', function ($row) use ($allCategories, $canEdit) {
                if (!$canEdit) return '<span class="badge bg-primary-subtle text-primary rounded-pill px-2">'.e($row->category?->name ?? '—').'</span>';
                $opts = $allCategories->map(fn($c)=>'<option value="'.$c->id.'" '.($row->category_id==$c->id?'selected':'').'>'.e($c->name).'</option>')->implode('');
                return '<select class="form-select form-select-sm border-0 bg-transparent inline-edit" style="min-width:160px;" data-id="'.$row->id.'" data-field="category_id"><option value="">— None —</option>'.$opts.'</select>';
            })
            ->addColumn('status', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="badge '.($row->is_active?'text-success':'text-danger').'">'.($row->is_active?'● Active':'● Inactive').'</span>';
                return '<select class="form-select form-select-sm border-0 inline-edit status-select '.($row->is_active?'text-success':'text-danger').'" style="min-width:105px;font-weight:600;font-size:.78rem;" data-id="'.$row->id.'" data-field="is_active">
                    <option value="1" '.($row->is_active?'selected':'').'>● Active</option>
                    <option value="0" '.(!$row->is_active?'selected':'').'>● Inactive</option></select>';
            })
            ->editColumn('created_at', fn($row)=>'<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>')
            ->addColumn('action', function ($row) use ($canDelete) {
                if (!$canDelete) return '<span class="text-muted text-xs">—</span>';
                return '<button class="btn btn-sm btn-light border delete-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'"><i class="fas fa-trash text-danger"></i></button>';
            })
            ->rawColumns(['name','category','status','created_at','action'])
            ->make(true);
    }

    public function ajaxStore(Request $request)
    {
        $request->validate(['category_id'=>'required|exists:categories,id','name'=>'required|string|max:255']);
        $sub = Subcategory::create(['category_id'=>$request->category_id,'name'=>$request->name,'slug'=>Str::slug($request->name),'is_active'=>true]);
        return response()->json(['success'=>true,'message'=>'Subcategory created.','data'=>$sub->load('category')]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate(['id'=>'required|exists:subcategories,id','field'=>'required|in:name,category_id,is_active','value'=>'nullable']);
        $sub = Subcategory::findOrFail($request->id);
        match ($request->field) {
            'name'        => [$sub->name=$request->value, $sub->slug=Str::slug($request->value)],
            'category_id' => $sub->category_id=$request->value?:null,
            'is_active'   => $sub->is_active=(bool)$request->value,
        };
        $sub->save();
        return response()->json(['success'=>true]);
    }

    public function destroy(Subcategory $subcategory) { $subcategory->delete(); return response()->json(['success'=>true,'message'=>'Subcategory deleted.']); }

    public function getByCategory($id)
    {
        return Subcategory::where('category_id',$id)->where('is_active',true)
    ->orderBy('sort_order')->orderBy('name')->select('id','name')->get();
    }
    public function reorder(Request $request)
{
    $query = Subcategory::with('category')
                        ->whereNull('deleted_at'); // remove if no SoftDeletes

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $subcategories = $query->orderBy('sort_order')
                           ->orderBy('name')
                           ->get();

    return view('subcategories.reorder', [
        'subcategories' => $subcategories,
        'categories'    => Category::orderBy('name')->get(),
    ]);
}

public function saveOrder(Request $request)
{
    $request->validate([
        'order'   => 'required|array',
        'order.*' => 'integer|exists:subcategories,id',
    ]);

    foreach ($request->order as $index => $id) {
        Subcategory::where('id', $id)->update(['sort_order' => $index]);
    }

    return response()->json(['success' => true, 'message' => 'Subcategory order saved.']);
}
}