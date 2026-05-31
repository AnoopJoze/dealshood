<?php
namespace App\Http\Controllers;
use App\Models\Locality; use Illuminate\Http\Request; use Illuminate\Support\Str; use Carbon\Carbon; use DataTables;

class LocalityController extends Controller
{
    const TYPES = ['country','state','city','area'];

    public function __construct()
    {
        $this->middleware('can:localities.view')  ->only(['index','data']);
        $this->middleware('can:localities.create')->only(['ajaxStore']);
        $this->middleware('can:localities.edit')  ->only(['inlineUpdate']);
        $this->middleware('can:localities.delete')->only(['destroy']);
    }

    public function index()
    {
        $localities = Locality::orderBy('type')->orderBy('name')->get();
        $stats = ['total'=>Locality::count(),'active'=>Locality::where('is_active',true)->count(),'inactive'=>Locality::where('is_active',false)->count(),'country'=>Locality::where('type','country')->count(),'state'=>Locality::where('type','state')->count(),'city'=>Locality::where('type','city')->count(),'area'=>Locality::where('type','area')->count()];
        return view('localities.list', compact('localities','stats'));
    }

    public function data(Request $request)
    {
        $query = Locality::with('parent')->select('localities.*');
        if ($request->filled('type'))   $query->where('type',$request->type);
        if ($request->filled('status')) $query->where('is_active',$request->status);
        if ($request->filled('start_date') && $request->filled('end_date'))
            $query->whereBetween('created_at',[Carbon::parse($request->start_date)->startOfDay(),Carbon::parse($request->end_date)->endOfDay()]);

        $canEdit   = auth()->user()->can('localities.edit');
        $canDelete = auth()->user()->can('localities.delete');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) use ($canEdit) {
                $badge = '<span class="badge rounded-pill type-badge-'.$row->type.' px-2 py-1" style="font-size:.62rem;font-weight:700;text-transform:uppercase;">'.$row->type.'</span>';
                if (!$canEdit) return '<div class="d-flex align-items-center gap-2">'.$badge.'<span class="fw-semibold">'.e($row->name).'</span></div>';
                return '<div class="d-flex align-items-center gap-2">'.$badge.'<input type="text" class="form-control form-control-sm border-0 bg-transparent inline-edit fw-semibold" style="min-width:140px;" data-id="'.$row->id.'" data-field="name" value="'.e($row->name).'"></div>';
            })
            ->addColumn('parent', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="text-sm text-muted">'.e($row->parent?->name ?? '—').'</span>';
                $opts = Locality::where('id','!=',$row->id)->orderBy('type')->orderBy('name')->get()
                    ->map(fn($p)=>'<option value="'.$p->id.'" '.($row->parent_id==$p->id?'selected':'').'>'.ucfirst($p->type).' → '.e($p->name).'</option>')->implode('');
                return '<select class="form-select form-select-sm border-0 bg-transparent inline-edit" style="min-width:160px;" data-id="'.$row->id.'" data-field="parent_id"><option value="">— None —</option>'.$opts.'</select>';
            })
            ->addColumn('type', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="badge bg-light text-secondary border rounded-pill">'.ucfirst($row->type).'</span>';
                $opts = collect(self::TYPES)->map(fn($t)=>'<option value="'.$t.'" '.($row->type===$t?'selected':'').'>'.ucfirst($t).'</option>')->implode('');
                return '<select class="form-select form-select-sm border-0 bg-transparent inline-edit" style="min-width:110px;" data-id="'.$row->id.'" data-field="type">'.$opts.'</select>';
            })
            ->addColumn('status', function ($row) use ($canEdit) {
                if (!$canEdit) return '<span class="badge '.($row->is_active?'text-success':'text-danger').'">'.($row->is_active?'● Active':'● Inactive').'</span>';
                return '<select class="form-select form-select-sm border-0 inline-edit status-select '.($row->is_active?'text-success':'text-danger').'" style="min-width:100px;font-weight:600;font-size:.78rem;" data-id="'.$row->id.'" data-field="is_active">
                    <option value="1" '.($row->is_active?'selected':'').'>● Active</option>
                    <option value="0" '.(!$row->is_active?'selected':'').'>● Inactive</option></select>';
            })
            ->editColumn('created_at', fn($row)=>'<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>')
            ->addColumn('action', function ($row) use ($canDelete) {
                if (!$canDelete) return '<span class="text-muted text-xs">—</span>';
                return '<button class="btn btn-sm btn-light border delete-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'"><i class="fas fa-trash text-danger"></i></button>';
            })
            ->rawColumns(['name','parent','type','status','created_at','action'])
            ->make(true);
    }

    public function ajaxStore(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255','type'=>'required|in:country,state,city,area','parent_id'=>'nullable|exists:localities,id']);
        $loc = Locality::create(['name'=>$request->name,'slug'=>Str::slug($request->name),'type'=>$request->type,'parent_id'=>$request->parent_id?:null,'is_active'=>true]);
        return response()->json(['success'=>true,'message'=>'Locality created.','data'=>$loc]);
    }

    public function inlineUpdate(Request $request)
    {
        $request->validate(['id'=>'required|exists:localities,id','field'=>'required|in:name,type,parent_id,is_active','value'=>'nullable']);
        $loc = Locality::findOrFail($request->id);
        match ($request->field) {
            'name'      => [$loc->name=$request->value, $loc->slug=Str::slug($request->value)],
            'type'      => $loc->type=$request->value,
            'parent_id' => $loc->parent_id=$request->value?:null,
            'is_active' => $loc->is_active=(bool)$request->value,
        };
        $loc->save();
        return response()->json(['success'=>true]);
    }

    public function destroy(Locality $locality)
    {
        $locality->children()->update(['parent_id'=>null]);
        $locality->is_active=0; $locality->save();
        return response()->json(['success'=>true,'message'=>'Locality deleted.']);
    }
}