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
    | Index – list view
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        return view('users.list', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | DataTables server-side feed
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
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = Carbon::parse($request->start_date)->startOfDay();
                    $end   = Carbon::parse($request->end_date)->endOfDay();
                    $query->whereBetween('users.created_at', [$start, $end]);
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

            ->editColumn('name', function ($row) {
                $initial = strtoupper(substr($row->name, 0, 1));
                $role    = $row->roles->first()?->name ?? 'User';
                return '
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded-circle bg-gradient-primary text-white me-3
                                    d-flex align-items-center justify-content-center fw-bold shadow-sm"
                             style="width:40px;height:40px;font-size:1rem;flex-shrink:0;">
                            ' . $initial . '
                        </div>
                        <div>
                            <h6 class="mb-0 text-sm fw-semibold text-dark">' . e($row->name) . '</h6>
                            <small class="text-muted">' . ucfirst($role) . '</small>
                        </div>
                    </div>';
            })

            ->editColumn('email', function ($row) {
                return '<span class="text-sm text-dark fw-medium">' . e($row->email) . '</span>';
            })

            ->addColumn('role', function ($row) {
                $role = $row->roles->first()?->name ?? 'User';
                return '<span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">'
                    . ucfirst($role) . '</span>';
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 'Active') {
                    return '<span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">Active</span>';
                }
                return '<span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">Inactive</span>';
            })

            ->editColumn('created_at', function ($row) {
                return '<div class="text-sm text-muted fw-medium">'
                    . Carbon::parse($row->created_at)->format('d M Y')
                    . '<br><small class="text-xs">'
                    . Carbon::parse($row->created_at)->diffForHumans()
                    . '</small></div>';
            })

            ->addColumn('action', function ($row) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm shadow-none border mb-0"
                                type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                            <li>
                                <a class="dropdown-item rounded-3 py-2 edit-btn"
                                   href="javascript:void(0)"
                                   data-id="' . $row->id . '">
                                    <i class="fas fa-pen me-2 text-warning"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 view-btn"
                                   href="'.route('users.show',$row->id).'"
                                   data-id="' . $row->id . '">
                                    <i class="fas fa-eye me-2 text-warning"></i> View
                                </a>
                            </li>
                        </ul>
                    </div>';
            })

            ->rawColumns(['name', 'email', 'role', 'status', 'created_at', 'action'])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX – get single user data for edit modal
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
            // Extended fields
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
    | AJAX – store new user
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
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX – update existing user
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
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Show single user profile page
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $user  = User::with('roles')->findOrFail($id);
        $roles = Role::orderBy('name')->get(); // needed for the inline edit modal
        return view('users.show', compact('user', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX – delete user
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}