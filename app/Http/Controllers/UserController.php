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
        //$this->middleware('permission:passport_tracking_list|passport_tracking_scan');
    }

    public function index(Request $request)
    {
        if ($request->type == 'staff' || $request->type == 'technician') {
            $title = 'Paasport Tracking';
            $type = $request->type;
            return view('passport_tracking.index', compact('title', 'type'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function getlist(Request $request)
    {
        $query = Employee::query()
            ->select(
                'employee_master.id',
                'employee_master.slug',
                'employee_master.emp_code',
                'employee_master.emp_name',
                'employee_master.created_at',
                'passport_tracking.date_of_birth',
                'passport_tracking.status',
                'passport_tracking.passport_number',
                'passport_tracking.passport_issue_date',
                'passport_tracking.passport_expiry_date',
                'passport_tracking.last_locker_number',
                'passport_tracking.last_batch_bundle_number',
                'passport_tracking.last_action_date',
                'employee_master.date_of_joining',
                'employee_master.category'
            )
            ->leftJoin('passport_tracking', 'passport_tracking.employee_id', '=', 'employee_master.id')
            ->where('employee_master.ps_emp_status', 'Active');

        if ($request->filled('type')) {
            $query->where('employee_master.category', $request->type);
        }
        //dd($query->toSql(), $query->getBindings());
        return DataTables::of($query)

            ->filter(function ($query) use ($request) {

                // if ($request->has('columns') && !$request->filled('start_date') && !$request->filled('end_date')) {
                //     foreach ($request->get('columns') as $col) {
                //         $name = $col['data'];
                //         $value = $col['search']['value'] ?? null;

                //         // Only apply if value and column exist in selected fields
                //         if ($value && in_array($name, [
                //             'employee_master.emp_code',
                //             'employee_master.emp_name',
                //             'passport_tracking.passport_number',
                //             'passport_tracking.status',
                //             'passport_tracking.last_locker_number',
                //             'passport_tracking.last_batch_bundle_number'
                //         ])) {
                //             $query->where($name, 'like', "%{$value}%");
                //         }
                //     }
                // }

                // ✅ Status
                if ($request->filled('status')) {
                    $query->where('passport_tracking.status', 'like', "%{$request->status}%");
                }

                // ✅ Date range filter (created_at)
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $start = $request->start_date;
                    $end = $request->end_date;
                    $query->whereBetween('employee_master.created_at', [$start, $end]);
                }

                if ($request->filled('action_start_date') && $request->filled('action_end_date')) {
                    $start = Carbon::parse($request->action_start_date)->startOfDay();
                    $end = Carbon::parse($request->action_end_date)->endOfDay();
                    $query->whereBetween('passport_tracking.last_action_date', [$start, $end]);
                }

                if ($request->filled('date_of_birth_start_date') && $request->filled('date_of_birth_end_date')) {
                    $start = Carbon::parse($request->date_of_birth_start_date)->startOfDay();
                    $end = Carbon::parse($request->date_of_birth_end_date)->endOfDay();
                    $query->whereBetween('passport_tracking.date_of_birth', [$start, $end]);
                }
                if ($request->filled('passport_issue_date_start_date') && $request->filled('passport_issue_date_end_date')) {
                    $start = Carbon::parse($request->passport_issue_date_start_date)->startOfDay();
                    $end = Carbon::parse($request->passport_issue_date_end_date)->endOfDay();
                    $query->whereBetween('passport_tracking.passport_issue_date', [$start, $end]);
                }
                if ($request->filled('passport_expiry_date_start_date') && $request->filled('passport_expiry_date_end_date')) {
                    $start = Carbon::parse($request->passport_expiry_date_start_date)->startOfDay();
                    $end = Carbon::parse($request->passport_expiry_date_end_date)->endOfDay();
                    $query->whereBetween('passport_tracking.passport_expiry_date', [$start, $end]);
                }
            })
            ->order(function ($query) use ($request) {
                if ($order = $request->get('order')[0] ?? null) {
                    $columns = $request->get('columns');
                    $colName = $columns[$order['column']]['data'];
                    $dir = $order['dir'];
                    $query->orderBy($colName, $dir);
                }
            })

            // ✅ Format date columns safely
            ->editColumn('date_of_birth', fn($row) => $row->date_of_birth ? Carbon::parse($row->date_of_birth)->format('d-M-Y') : '')
            ->editColumn('created_at', fn($row) => $row->created_at ? Carbon::parse($row->created_at)->format('d-M-Y') : '')
            ->editColumn('passport_issue_date', fn($row) => $row->passport_issue_date ? Carbon::parse($row->passport_issue_date)->format('d-M-Y') : '')
            ->editColumn('passport_expiry_date', fn($row) => $row->passport_expiry_date ? Carbon::parse($row->passport_expiry_date)->format('d-M-Y') : '')

            // ✅ Badge styling
            ->editColumn('status', function ($row) {
                if (empty($row->status)) {
                    return '<span class="mb-1 badge rounded-pill text-bg-danger">Not Uploaded</span>';
                }
                return '<span class="mb-1 badge rounded-pill text-bg-success">' . e($row->status) . '</span>';
            })

            // ✅ Action buttons
            ->addColumn('action', function ($row) {
                $buttons = [];
                if(auth()->user()->can('passport_tracking_scan')){
                // Scan Passport
                $buttons[] = "<a href='javascript:void(0);' title='Scan Passport' class='btn btn-primary btn-sm scan_passport'
                data-item='{$row->id}' data-name='" . e($row->emp_name) . "'
                data-bs-toggle='modal' data-bs-target='#update-scaneid-modal'>
                <i class='ti ti-pencil'></i>&nbsp;Scan Passport</a>";

                // Conditional buttons
                if (in_array($row->status, ['Passport Uploaded', 'Employee'])) {
                    $buttons[] = "<a href='javascript:void(0);' title='Update Returned' class='btn btn-primary btn-sm update_returned'
                    data-item='{$row->id}' data-name='" . e($row->emp_name) . "'
                    data-bs-toggle='modal' data-bs-target='#update-returned-modal'>
                    <i class='ti ti-pencil'></i>&nbsp;Update Returned</a>";
                } elseif ($row->status === 'PR Team') {
                    $buttons[] = "<a href='javascript:void(0);' title='Update Given Back' class='btn btn-primary btn-sm update_givenback'
                    data-item='{$row->id}' data-name='" . e($row->emp_name) . "'
                    data-bs-toggle='modal' data-bs-target='#update-givenback-modal'>
                    <i class='ti ti-pencil'></i>&nbsp;Update Given Back</a>";
                }
                }

                // View button
                $buttons[] = "<a href='" . route('passport_tracking.show', $row->slug) . "' class='btn btn-sm btn-info'>
                <i class='ti ti-eye'></i>&nbsp;View</a>";

                return '<div class="btn-group" role="group">' . implode('&nbsp;', $buttons) . '</div>';
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function show($param)
    {
        $title = 'Paasport Tracking Details';
        $data = Employee::where('slug', $param)->first();
        return view('passport_tracking.show', compact('data', 'title'));
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
