<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Spatie\Permission\Models\Permission; use Spatie\Permission\Models\Role;
use Carbon\Carbon; use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:permissions.view')  ->only(['index','getList']);
        $this->middleware('can:permissions.create')->only(['ajaxStore']);
        $this->middleware('can:permissions.edit')  ->only(['ajaxUpdate']);
        $this->middleware('can:permissions.delete')->only(['destroy']);
    }

    public function index() { return view('permissions.list'); }

    public function getList(Request $request)
    {
        $query = Permission::withCount('roles');
        if ($request->filled('name'))  $query->where('name','like','%'.$request->name.'%');
        if ($request->filled('group')) $query->where('name','like',$request->group.'.%');

        $canEdit   = auth()->user()->can('permissions.edit');
        $canDelete = auth()->user()->can('permissions.delete');

        return DataTables::of($query)
            ->editColumn('name', function ($row) {
                // dot notation: posts.create → "posts" + "create"
                [$module, $action] = array_pad(explode('.',$row->name,2),2,'');
                return '<div><span class="badge bg-light text-secondary border rounded-pill me-1 text-xs">'.e(ucfirst($module)).'</span><span class="fw-semibold text-sm text-dark">'.e(ucwords($action)).'</span><br><small class="text-muted font-monospace">'.e($row->name).'</small></div>';
            })
            ->addColumn('group', function ($row) {
                $module = ucfirst(explode('.',$row->name)[0]);
                $colors = ['bg-primary-subtle text-primary','bg-success-subtle text-success','bg-warning-subtle text-warning','bg-info-subtle text-info','bg-danger-subtle text-danger'];
                return '<span class="badge '.($colors[crc32($module)%count($colors)]).' rounded-pill px-3 py-2">'.$module.'</span>';
            })
            ->addColumn('roles_count', fn($row) =>
                $row->roles_count===0
                    ? '<span class="badge bg-light text-muted border rounded-pill px-3 py-2">Unassigned</span>'
                    : '<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"><i class="fas fa-shield-alt me-1" style="font-size:.6rem;"></i>'.$row->roles_count.' role(s)</span>'
            )
            ->editColumn('created_at', fn($row)=>'<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>')
            ->addColumn('action', function ($row) use ($canEdit,$canDelete) {
                if (!$canEdit && !$canDelete) return '<span class="text-muted text-xs">—</span>';
                $btns = '<div class="d-flex gap-1">';
                if ($canEdit)   $btns .= '<button class="btn btn-sm btn-light border edit-perm-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'" data-guard="'.e($row->guard_name).'"><i class="fas fa-pen text-warning"></i></button>';
                if ($canDelete) $btns .= '<button class="btn btn-sm btn-light border delete-perm-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'"><i class="fas fa-trash text-danger"></i></button>';
                return $btns.'</div>';
            })
            ->rawColumns(['name','group','roles_count','created_at','action'])
            ->make(true);
    }

    public function ajaxStore(Request $request)
    {
        $v = \Validator::make($request->all(),['name'=>'required|string|max:150|unique:permissions,name','guard_name'=>'required|string|max:100']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        foreach (array_filter(array_map('trim',explode(',',$request->name))) as $name) {
            Permission::firstOrCreate(['name'=>strtolower($name),'guard_name'=>$request->guard_name]);
        }
        return response()->json(['success'=>true,'message'=>'Permission(s) created.']);
    }

    public function ajaxUpdate(Request $request, $id)
    {
        $perm = Permission::findOrFail($id);
        $v = \Validator::make($request->all(),['name'=>'required|string|max:150|unique:permissions,name,'.$perm->id,'guard_name'=>'required|string|max:100']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        $perm->update(['name'=>strtolower(trim($request->name)),'guard_name'=>$request->guard_name]);
        return response()->json(['success'=>true,'message'=>'Permission updated.']);
    }

    public function destroy($id) { Permission::findOrFail($id)->delete(); return response()->json(['success'=>true,'message'=>'Permission deleted.']); }
}