<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total'    => User::count(),
            'active'   => User::where('status', 'Active')->count(),
            'inactive' => User::where('status', 'Inactive')->count(),
            'today'    => User::whereDate('created_at', today())->count(),
        ];

        return view('users.list', compact('roles', 'stats'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE FEED
    |--------------------------------------------------------------------------
    */
    public function getlist(Request $request)
    {
        $query = User::with('roles')->select('users.*');

        return DataTables::of($query)

            ->filter(function ($query) use ($request) {
                if ($request->filled('name')) {
                    $query->where('users.name', 'like', '%' . $request->name . '%');
                }
                if ($request->filled('email')) {
                    $query->where('users.email', 'like', '%' . $request->email . '%');
                }
                if ($request->filled('status')) {
                    $query->where('users.status', $request->status);
                }
                if ($request->filled('role')) {
                    $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
                }
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween('users.created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay(),
                    ]);
                }
            })

            ->order(function ($query) use ($request) {
                if ($order = $request->get('order')[0] ?? null) {
                    $columns = $request->get('columns');
                    $column  = $columns[$order['column']]['data'];
                    $dir     = $order['dir'];
                    $allowed = ['name', 'email', 'status', 'created_at'];
                    if (in_array($column, $allowed)) {
                        $query->orderBy('users.' . $column, $dir);
                    }
                } else {
                    $query->latest('users.id');
                }
            })

            /*── Name + role ──*/
            ->editColumn('name', function ($row) {
                $initial = strtoupper(substr($row->name, 0, 1));
                $role    = $row->roles->first()?->name ?? 'User';
                return '
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center
                                    justify-content-center fw-bold shadow-sm flex-shrink-0"
                             style="width:40px;height:40px;font-size:1rem;">
                            ' . $initial . '
                        </div>
                        <div>
                            <div class="fw-semibold text-sm text-dark">' . e($row->name) . '</div>
                            <small class="text-muted">' . ucfirst($role) . '</small>
                        </div>
                    </div>';
            })

            /*── Email ──*/
            ->editColumn('email', function ($row) {
                return '<a href="mailto:' . e($row->email) . '"
                            class="text-sm text-dark fw-medium text-decoration-none">'
                    . e($row->email) . '</a>';
            })

            /*── Role badge ──*/
            ->addColumn('role', function ($row) {
                $role = $row->roles->first()?->name ?? 'User';
                return '<span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">'
                    . ucfirst($role) . '</span>';
            })

            /*── Status (inline toggle) ──*/
            ->editColumn('status', function ($row) {
                if ($row->status === 'Active') {
                    return '<span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">'
                         . '<span style="display:inline-block;width:6px;height:6px;border-radius:50%;'
                         . 'background:currentColor;vertical-align:middle;margin-right:5px;"></span>'
                         . 'Active</span>';
                }
                return '<span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">'
                     . '<span style="display:inline-block;width:6px;height:6px;border-radius:50%;'
                     . 'background:currentColor;vertical-align:middle;margin-right:5px;"></span>'
                     . 'Inactive</span>';
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
                    <div class="d-flex gap-1">
                        <a href="' . route('users.show', $row->id) . '"
                           class="btn btn-sm btn-light border" title="View">
                            <i class="fas fa-eye text-info"></i>
                        </a>
                        <button class="btn btn-sm btn-light border edit-btn"
                                data-id="' . $row->id . '" title="Edit">
                            <i class="fas fa-pen text-warning"></i>
                        </button>
                        <button class="btn btn-sm btn-light border delete-btn"
                                data-id="' . $row->id . '"
                                data-name="' . e($row->name) . '" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                    </div>';
            })

            ->rawColumns(['name', 'email', 'role', 'status', 'created_at', 'action'])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT DATA – JSON for modal
    |--------------------------------------------------------------------------
    */
    public function editData($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response()->json([
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'phone'           => $user->phone,
            'status'          => $user->status,
            'role'            => $user->roles->first()?->name ?? '',
            'location'        => $user->location,
            'about_me'        => $user->about_me,
            'company_name'    => $user->company_name,
            'whatsapp_number' => $user->whatsapp_number,
            'address'         => $user->address,
            'website'         => $user->website,
            'latitude'        => $user->latitude,
            'longitude'       => $user->longitude,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX STORE
    |--------------------------------------------------------------------------
    */
    public function ajaxStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => ['required', 'confirmed', Password::min(8)],
            'role'            => 'required|exists:roles,name',
            'status'          => 'required|in:Active,Inactive',
            'phone'           => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'location'        => 'nullable|string|max:255',
            'company_name'    => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:500',
            'website'         => 'nullable|url|max:255',
            'about_me'        => 'nullable|string|max:1000',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'status'          => $request->status,
            'phone'           => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'location'        => $request->location,
            'company_name'    => $request->company_name,
            'address'         => $request->address,
            'website'         => $request->website,
            'about_me'        => $request->about_me,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
        ]);

        $user->assignRole($request->role);

        return response()->json(['success' => true, 'message' => 'User created successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX UPDATE
    |--------------------------------------------------------------------------
    */
    public function ajaxUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => ['nullable', 'confirmed', Password::min(8)],
            'role'            => 'required|exists:roles,name',
            'status'          => 'required|in:Active,Inactive',
            'phone'           => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'location'        => 'nullable|string|max:255',
            'company_name'    => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:500',
            'website'         => 'nullable|url|max:255',
            'about_me'        => 'nullable|string|max:1000',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = [
            'name'            => $request->name,
            'email'           => $request->email,
            'status'          => $request->status,
            'phone'           => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,
            'location'        => $request->location,
            'company_name'    => $request->company_name,
            'address'         => $request->address,
            'website'         => $request->website,
            'about_me'        => $request->about_me,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $user  = User::with('roles')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        return view('users.show', compact('user', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}