<?php
namespace App\Http\Controllers;
use Hash; use App\Models\User; use Illuminate\Http\Request; use Spatie\Permission\Models\Role;
use Carbon\Carbon; use Illuminate\Validation\Rules\Password; use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users.view')  ->only(['index','getlist','show','editData']);
        $this->middleware('can:users.create')->only(['ajaxStore']);
        $this->middleware('can:users.edit')  ->only(['ajaxUpdate']);
        $this->middleware('can:users.delete')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::orderBy('name')->get();
        $stats = ['total'=>User::count(),'active'=>User::where('status','Active')->count(),'inactive'=>User::where('status','Inactive')->count(),'today'=>User::whereDate('created_at',today())->count()];
        return view('users.list', compact('roles','stats'));
    }

    public function getlist(Request $request)
    {
        $query = User::with('roles')->select('users.*');
        $canEdit   = auth()->user()->can('users.edit');
        $canDelete = auth()->user()->can('users.delete');

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->filled('name'))   $query->where('users.name','like','%'.$request->name.'%');
                if ($request->filled('email'))  $query->where('users.email','like','%'.$request->email.'%');
                if ($request->filled('status')) $query->where('users.status',$request->status);
                if ($request->filled('role'))   $query->whereHas('roles',fn($q)=>$q->where('name',$request->role));
                if ($request->filled('start_date') && $request->filled('end_date'))
                    $query->whereBetween('users.created_at',[Carbon::parse($request->start_date)->startOfDay(),Carbon::parse($request->end_date)->endOfDay()]);
            })
            ->order(function ($query) use ($request) {
                $order   = $request->get('order')[0] ?? null;
                $columns = $request->get('columns');
                $col     = $order ? ($columns[$order['column']]['data'] ?? null) : null;
                $allowed = ['name','email','status','created_at'];
                ($col && in_array($col,$allowed)) ? $query->orderBy('users.'.$col,$order['dir']) : $query->latest('users.id');
            })
            ->editColumn('name', function ($row) {
                $initial = strtoupper(substr($row->name,0,1));
                $role    = $row->roles->first()?->name ?? 'User';
                return '<div class="d-flex align-items-center gap-3"><div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width:40px;height:40px;font-size:1rem;">'.$initial.'</div><div><div class="fw-semibold text-sm text-dark">'.e($row->name).'</div><small class="text-muted">'.ucfirst($role).'</small></div></div>';
            })
            ->editColumn('email', fn($row)=>'<a href="mailto:'.e($row->email).'" class="text-sm text-dark fw-medium text-decoration-none">'.e($row->email).'</a>')
            ->addColumn('role', fn($row)=>'<span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">'.ucfirst($row->roles->first()?->name ?? 'User').'</span>')
            ->editColumn('status', function ($row) {
                $active = $row->status==='Active';
                $cls = $active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                return '<span class="badge rounded-pill '.$cls.' px-3 py-2"><span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:currentColor;vertical-align:middle;margin-right:5px;"></span>'.($active?'Active':'Inactive').'</span>';
            })
            ->editColumn('created_at', fn($row)=>'<div class="text-sm text-muted">'.Carbon::parse($row->created_at)->format('d M Y').'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small></div>')
            ->addColumn('action', function ($row) use ($canEdit, $canDelete) {
                $btns = '<div class="d-flex gap-1"><a href="'.route('users.show',$row->id).'" class="btn btn-sm btn-light border" title="View"><i class="fas fa-eye text-info"></i></a>';
                if ($canEdit)   $btns .= '<button class="btn btn-sm btn-light border edit-btn" data-id="'.$row->id.'" title="Edit"><i class="fas fa-pen text-warning"></i></button>';
                if ($canDelete) $btns .= '<button class="btn btn-sm btn-light border delete-btn" data-id="'.$row->id.'" data-name="'.e($row->name).'" title="Delete"><i class="fas fa-trash text-danger"></i></button>';
                return $btns.'</div>';
            })
            ->rawColumns(['name','email','role','status','created_at','action'])
            ->make(true);
    }

    public function editData($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json(['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'phone'=>$user->phone,'status'=>$user->status,'role'=>$user->roles->first()?->name??'','location'=>$user->location,'about_me'=>$user->about_me,'company_name'=>$user->company_name,'whatsapp_number'=>$user->whatsapp_number,'address'=>$user->address,'website'=>$user->website,'latitude'=>$user->latitude,'longitude'=>$user->longitude]);
    }

    public function ajaxStore(Request $request)
    {
        $v = \Validator::make($request->all(),['name'=>'required|string|max:255','email'=>'required|email|unique:users,email','password' => [
    'required', 'confirmed',
    \Illuminate\Validation\Rules\Password::min(8)
        ->letters()->mixedCase()->numbers()->symbols(),
],'role'=>'required|exists:roles,name','status'=>'required|in:Active,Inactive','phone'=>'nullable|string|max:20','whatsapp_number'=>'nullable|string|max:20','location'=>'nullable|string|max:255','company_name'=>'nullable|string|max:255','address'=>'nullable|string|max:500','website'=>'nullable|url|max:255','about_me'=>'nullable|string|max:1000','latitude'=>'nullable|numeric|between:-90,90','longitude'=>'nullable|numeric|between:-180,180']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        $user = User::create(['name'=>$request->name,'email'=>$request->email,'password'=>Hash::make($request->password),'status'=>$request->status,'phone'=>$request->phone,'whatsapp_number'=>$request->whatsapp_number,'location'=>$request->location,'company_name'=>$request->company_name,'address'=>$request->address,'website'=>$request->website,'about_me'=>$request->about_me,'latitude'=>$request->latitude,'longitude'=>$request->longitude]);
        $user->assignRole($request->role);
        return response()->json(['success'=>true,'message'=>'User created.']);
    }

    public function ajaxUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $v = \Validator::make($request->all(),['name'=>'required|string|max:255','email'=>'required|email|unique:users,email,'.$user->id,'password' => [
    'nullable', 'confirmed',
    \Illuminate\Validation\Rules\Password::min(8)
        ->letters()->mixedCase()->numbers()->symbols(),
],'role'=>'required|exists:roles,name','status'=>'required|in:Active,Inactive','phone'=>'nullable|string|max:20','whatsapp_number'=>'nullable|string|max:20','location'=>'nullable|string|max:255','company_name'=>'nullable|string|max:255','address'=>'nullable|string|max:500','website'=>'nullable|url|max:255','about_me'=>'nullable|string|max:1000','latitude'=>'nullable|numeric|between:-90,90','longitude'=>'nullable|numeric|between:-180,180']);
        if ($v->fails()) return response()->json(['success'=>false,'errors'=>$v->errors()],422);
        $data = $request->only(['name','email','status','phone','whatsapp_number','location','company_name','address','website','about_me','latitude','longitude']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data); $user->syncRoles([$request->role]);
        return response()->json(['success'=>true,'message'=>'User updated.']);
    }

    public function show($id) { return view('users.show', ['user'=>User::with('roles')->findOrFail($id),'roles'=>Role::orderBy('name')->get()]); }

    public function destroy($id) { User::findOrFail($id)->delete(); return response()->json(['success'=>true,'message'=>'User deleted.']); }
}