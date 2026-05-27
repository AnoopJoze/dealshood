<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('permissions.list');
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE FEED
    |--------------------------------------------------------------------------
    */
    public function getList(Request $request)
    {
        $query = Permission::withCount('roles');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('group')) {
            $query->where('name', 'like', $request->group . '_%');
        }

        return DataTables::of($query)

            ->editColumn('name', function ($row) {
                $parts  = explode('_', $row->name);
                $module = array_shift($parts);
                $action = implode(' ', $parts);
                return '
                    <div>
                        <span class="badge bg-light text-secondary border rounded-pill me-1 text-xs">
                            ' . e(ucfirst($module)) . '
                        </span>
                        <span class="fw-semibold text-sm text-dark">' . e(ucwords($action)) . '</span>
                        <br><small class="text-muted font-monospace">' . e($row->name) . '</small>
                    </div>';
            })

            ->addColumn('group', function ($row) {
                $module = ucfirst(explode('_', $row->name)[0]);
                $colors = ['bg-primary-subtle text-primary', 'bg-success-subtle text-success',
                           'bg-warning-subtle text-warning', 'bg-info-subtle text-info',
                           'bg-danger-subtle text-danger'];
                $color  = $colors[crc32($module) % count($colors)];
                return '<span class="badge ' . $color . ' rounded-pill px-3 py-2">' . $module . '</span>';
            })

            ->addColumn('roles_count', function ($row) {
                if ($row->roles_count === 0) {
                    return '<span class="badge bg-light text-muted border rounded-pill px-3 py-2">Unassigned</span>';
                }
                return '<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            <i class="fas fa-shield-alt me-1" style="font-size:.6rem;"></i>
                            ' . $row->roles_count . ' role(s)
                        </span>';
            })

            ->editColumn('created_at', function ($row) {
                return '<div class="text-sm text-muted">'
                    . Carbon::parse($row->created_at)->format('d M Y')
                    . '<br><small>' . Carbon::parse($row->created_at)->diffForHumans() . '</small></div>';
            })

            ->addColumn('action', function ($row) {
                return '
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light border edit-perm-btn" data-id="' . $row->id . '"
                                data-name="' . e($row->name) . '" data-guard="' . e($row->guard_name) . '" title="Edit">
                            <i class="fas fa-pen text-warning"></i>
                        </button>
                        <button class="btn btn-sm btn-light border delete-perm-btn"
                                data-id="' . $row->id . '" data-name="' . e($row->name) . '" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                    </div>';
            })

            ->rawColumns(['name', 'group', 'roles_count', 'created_at', 'action'])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX STORE
    |--------------------------------------------------------------------------
    */
    public function ajaxStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'       => 'required|string|max:150|unique:permissions,name',
            'guard_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $names = array_map('trim', explode(',', $request->name));

        foreach ($names as $name) {
            if ($name) {
                Permission::firstOrCreate([
                    'name'       => strtolower($name),
                    'guard_name' => $request->guard_name,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Permission(s) created successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX UPDATE
    |--------------------------------------------------------------------------
    */
    public function ajaxUpdate(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'name'       => 'required|string|max:150|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $permission->update([
            'name'       => strtolower(trim($request->name)),
            'guard_name' => $request->guard_name,
        ]);

        return response()->json(['success' => true, 'message' => 'Permission updated successfully.']);
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['success' => true, 'message' => 'Permission deleted successfully.']);
    }
}
