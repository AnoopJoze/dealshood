<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Spatie\Permission\Models\Role; use Spatie\Permission\Models\Permission;
use App\Models\User; use Carbon\Carbon; use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles.view')  ->only(['index','getList','show','editData']);
        $this->middleware('can:roles.create')->only(['ajaxStore']);
        $this->middleware('can:roles.edit')  ->only(['ajaxUpdate','syncPermissions']);
        $this->middleware('can:roles.delete')->only(['destroy']);
    }

    public function index()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p)=>ucfirst(explode('.',$p->name)[0]));
        return view('roles.list', compact('permissions'));
    }

    public function getList(Request $request)
    {
        $query = Role::withCount(['permissions','users']);
        if ($request->filled('name')) $query->where('name','like','%'.$request->name.'%');
        $canEdit   = auth()->user()->can('roles.edit');
        $canDelete = auth()->user()->can('roles.delete');

        return DataTables::of($query)
            ->editColumn('name', function ($row) {
                $colors = ['bg-primary','bg-success','bg-warning','bg-info','bg-danger','bg-secondary'];
                $color  = $colors[$row->id%count($colors)];
                return '<div class="d-flex align-items-center gap-3"><div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm '.$color.'" style="width:38px;height:38px;font-size:.9rem;flex-shrink:0;">'.strtoupper(substr($row->name,0,1)).'</div><div><div class="fw-semibold text-sm text-dark">'.e(ucfirst($row->name)).'</div><small class="text-muted">Guard: '.e($row->guard_name).'</small></div></div>';
            })
            ->addColumn('permissions_count', fn($row)=>'<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"><i class="fas fa-key me-1" style="font-size:.6rem;"></i>'.$row->permissions_count.' permissions</span>')
            ->addColumn('users_count', fn($row)=>'<span class="badge bg-success-subtle text-success rounded-pill px-3 py-2"><i class="fas fa-users me-1" style="font-size:.6rem;"></i>'.$row->users_count.' users</span>')
            ->editColumn('created_at', fn($row)=>'<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>')
            ->addColumn('action', function ($row) use ($canEdit,$canDelete) {
                $btns = '<div class="d-flex gap-1"><a href="'.route('roles.show',$row->id).'" class="btn btn-sm btn-light border" title="View & Permissions"><i class="fas fa-eye text-info"></i></a>';
                if ($canEdit)   $btns .= '<button class="btn btn-sm btn-light border edit-role-btn" data-id="'.$row->id.'" title="Edit"><i class="fas fa-pen text-warning"></i></button>';
                if ($canDelete) $btns .= '<button class="btn btn-sm btn-light border delete-role-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'" title="Delete"><i class="fas fa-trash text-danger"></i></button>';
                return $btns.'</div>';
            })
            ->rawColumns(['name','permissions_count','users_count','created_at','action'])
            ->make(true);
    }

    public function editData($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json(['id'=>$role->id,'name'=>$role->name,'guard_name'=>$role->guard_name,'permissions'=>$role->permissions->pluck('id')->toArray()]);
    }

    public function ajaxStore(Request $request)
    {
        $v = \Validator::make($request->all(),['name'=>'required|string|max:100|unique:roles,name','guard_name'=>'required|string|max:100','permissions'=>'nullable|array','permissions.*'=>'exists:permissions,id']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        $role = Role::create(['name'=>strtolower(trim($request->name)),'guard_name'=>$request->guard_name]);
        if ($request->filled('permissions')) $role->syncPermissions(Permission::whereIn('id',$request->permissions)->get());
        return response()->json(['success'=>true,'message'=>'Role created.']);
    }

    public function ajaxUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $v = \Validator::make($request->all(),['name'=>'required|string|max:100|unique:roles,name,'.$role->id,'guard_name'=>'required|string|max:100','permissions'=>'nullable|array','permissions.*'=>'exists:permissions,id']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        $role->update(['name'=>strtolower(trim($request->name)),'guard_name'=>$request->guard_name]);
        $role->syncPermissions($request->filled('permissions') ? Permission::whereIn('id',$request->permissions)->get() : collect());
        return response()->json(['success'=>true,'message'=>'Role updated.']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if ($role->users()->count() > 0) return response()->json(['success'=>false,'message'=>'Cannot delete: '.$role->users()->count().' user(s) assigned.'],422);
        $role->delete();
        return response()->json(['success'=>true,'message'=>'Role deleted.']);
    }

    public function show($id)
    {
        $role = Role::with(['permissions','users'])->findOrFail($id);
        $allPermissions = Permission::orderBy('name')->get()->groupBy(fn($p)=>ucfirst(explode('.',$p->name)[0]));
        return view('roles.show', ['role'=>$role,'allPermissions'=>$allPermissions,'assignedIds'=>$role->permissions->pluck('id')->toArray()]);
    }

    public function syncPermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate(['permissions'=>'nullable|array','permissions.*'=>'exists:permissions,id']);
        $perms = !empty($request->permissions) ? Permission::whereIn('id',$request->permissions)->get() : collect();
        $role->syncPermissions($perms);
        return response()->json(['success'=>true,'message'=>'Permissions updated.','count'=>$perms->count()]);
    }
}