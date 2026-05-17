<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Log;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\ExportPassportTrackingJob;

class UserController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:user_list|user_scan');
    }

    public function index(Request $request)
    {
    return view('users.list');
    }

    public function getlist(Request $request)
{
    $query = User::with('roles')->select('users.*');

    return DataTables::of($query)

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */
        ->filter(function ($query) use ($request) {

            // Search Name
            if ($request->filled('name')) {
                $query->where('users.name', 'like', '%' . $request->name . '%');
            }

            // Search Email
            if ($request->filled('email')) {
                $query->where('users.email', 'like', '%' . $request->email . '%');
            }

            // Search Status
            if ($request->filled('status')) {
                $query->where('users.status', $request->status);
            }

            // Date Range Filter
            if ($request->filled('start_date') && $request->filled('end_date')) {

                $start = Carbon::parse($request->start_date)
                    ->startOfDay();

                $end = Carbon::parse($request->end_date)
                    ->endOfDay();

                $query->whereBetween('users.created_at', [$start, $end]);
            }
        })

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        ->order(function ($query) use ($request) {

            if ($order = $request->get('order')[0] ?? null) {

                $columns = $request->get('columns');

                $column = $columns[$order['column']]['data'];

                $dir = $order['dir'];

                // Prevent sorting issue for custom columns
                $allowed = [
                    'name',
                    'email',
                    'status',
                    'created_at'
                ];

                if (in_array($column, $allowed)) {
                    $query->orderBy('users.' . $column, $dir);
                }
            } else {

                // Default sorting
                $query->latest('users.id');
            }
        })

        /*
        |--------------------------------------------------------------------------
        | User Column
        |--------------------------------------------------------------------------
        */
        ->editColumn('name', function ($row) {

            $initial = strtoupper(substr($row->name, 0, 1));

            $role = $row->roles->first()?->name ?? 'User';

            return '
                <div class="d-flex align-items-center">

                    <div class="avatar avatar-md rounded-circle bg-gradient-primary text-white me-3 d-flex align-items-center justify-content-center fw-bold shadow-sm">
                        ' . $initial . '
                    </div>

                    <div>
                        <h6 class="mb-0 text-sm fw-semibold text-dark">
                            ' . e($row->name) . '
                        </h6>

                        <small class="text-muted">
                            ' . ucfirst($role) . '
                        </small>
                    </div>

                </div>
            ';
        })

        /*
        |--------------------------------------------------------------------------
        | Email Column
        |--------------------------------------------------------------------------
        */
        ->editColumn('email', function ($row) {

            return '
                <div>
                    <span class="text-sm text-dark fw-medium">
                        ' . e($row->email) . '
                    </span>
                </div>
            ';
        })

        /*
        |--------------------------------------------------------------------------
        | Status Column
        |--------------------------------------------------------------------------
        */
        ->editColumn('status', function ($row) {

            if ($row->status == 'Active') {

                return '
                    <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                        Active
                    </span>
                ';
            }

            return '
                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">
                    Inactive
                </span>
            ';
        })

        /*
        |--------------------------------------------------------------------------
        | Created Date
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Action Column
        |--------------------------------------------------------------------------
        */
        ->addColumn('action', function ($row) {

            return '
                <div class="dropdown">

                    <button class="btn btn-light btn-sm shadow-none border mb-0"
                            type="button"
                            data-bs-toggle="dropdown">

                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">

                        <li>
                            <a class="dropdown-item rounded-3 py-2"
                               href="' . route('users.show', $row->id) . '">

                                <i class="fas fa-eye me-2 text-info"></i>
                                View
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2"
                               href="' . route('users.edit', $row->id) . '">

                                <i class="fas fa-pen me-2 text-warning"></i>
                                Edit
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item rounded-3 py-2 text-danger delete-btn"
                               href="javascript:void(0)"
                               data-id="' . $row->id . '">

                                <i class="fas fa-trash me-2"></i>
                                Delete
                            </a>
                        </li>

                    </ul>

                </div>
            ';
        })

        /*
        |--------------------------------------------------------------------------
        | Raw HTML Columns
        |--------------------------------------------------------------------------
        */
        ->rawColumns([
            'name',
            'email',
            'status',
            'created_at',
            'action'
        ])

        ->make(true);
    }

    public function show($param)
    {
        $title = 'Paasport Tracking Details';
        $data = Employee::where('slug', $param)->first();
        return view('users.show', compact('data', 'title'));
    }
    public function save_returned(Request $request)
    {
        $result = Employee::where('id', $request->employee_id)->first();
        try {
            $check = PassportTracking::where('employee_id', $result->id)->first();
            if ($check) {
                $data = [
                    'status' => 'PR Team',
                    'last_locker_number' => $request->locker_number,
                    'last_batch_bundle_number' => $request->batch_bundle_number,
                    'last_action_date' => $request->returned_date,
                    'last_comments' => $request->comments,
                    'updated_by' => auth()->user()->id,
                ];
                $data = $check->update($data);
            }
            $arr = array('msg' => 'Something goes to wrong. Please try again later', 'status' => false);
            if ($data) {
                $arr = array('msg' => 'Successfully Updated', 'status' => true);
            }
        } catch (\Exception $ex) {
            $arr = array('msg' => $ex->getMessage(), 'status' => false);
            return Response()->json($arr);
        } catch (\Error $ex) {
            $arr = array('msg' => $ex->getMessage(), 'status' => false);
            return Response()->json($arr);
        }
        return Response()->json($arr);
    }
    public function save_givenback(Request $request)
    {
        $result = Employee::where('id', $request->employee_id)->first();
        try {
            $check = PassportTracking::where('employee_id', $result->id)->first();
            if ($check) {
                $data = [
                    'status' => 'Employee',
                    'last_action_date' => $request->givenback_date,
                    'last_comments' => $request->comments,
                    'updated_by' => auth()->user()->id,
                ];
                $data = $check->update($data);
            }
            $arr = array('msg' => 'Something goes to wrong. Please try again later', 'status' => false);
            if ($data) {
                $arr = array('msg' => 'Successfully Updated', 'status' => true);
            }
        } catch (\Exception $ex) {
            $arr = array('msg' => $ex->getMessage(), 'status' => false);
            return Response()->json($arr);
        } catch (\Error $ex) {
            $arr = array('msg' => $ex->getMessage(), 'status' => false);
            return Response()->json($arr);
        }
        return Response()->json($arr);
    }

}
