<?php

namespace App\Http\Controllers;

use App\Exports\ActiveProcessReportExport;
use App\Models\ActiveCustomerProcess;
use App\Models\ActiveCustomerProcessStep;
use App\Models\CustomerProcess;
use App\Models\CustomerProcessStep;
use App\Models\Customers;
use App\Models\EmployeeDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Excel;

class ActiveCustomerProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users_ids = getUsersReportingToAuth();
        $customer_ids = EmployeeDetail::whereIn('user_id', $users_ids)->pluck('customer_id')->toArray();
        $customers = Customers::where('active', 'Y');
        if (!auth()->user()->hasRole('superadmin') && !auth()->user()->hasRole('Admin')) {
            $customers->whereIn('id', $customer_ids);
        }
        $customers = $customers->select('id', 'name')->get();
        if ($request->ajax()) {
            $completedCustomerIds = ActiveCustomerProcess::select('customer_id')
                ->groupBy('customer_id')
                ->havingRaw('SUM(CASE WHEN status != ? THEN 1 ELSE 0 END) = 0', ['completed'])
                ->pluck('customer_id')
                ->toArray();

            $query = ActiveCustomerProcess::with([
                'customer:id,name,mobile,creation_date',
                'process:id,process_name',
                'assignedBy:id,name'
            ]);

            if (!auth()->user()->hasRole('superadmin') && !auth()->user()->hasRole('Admin')) {
                $query->whereIn('customer_id', $customer_ids);
            }

            if (auth()->user()->hasRole('Service')) {
                $query->whereHas('process', function ($q) {
                    $q->where('process_type', 'Service');
                });
            }

            if ($request->customer_id && !empty($request->customer_id)) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->start_date && !empty($request->start_date)) {
                $query->whereHas('customer', function ($query) use ($request) {
                    $query->where('creation_date', '>=', $request->start_date);
                });
            }

            if ($request->end_date && !empty($request->end_date)) {
                $query->whereHas('customer', function ($query) use ($request) {
                    $query->where('creation_date', '<=', $request->end_date);
                });
            }

            if ($request->status == 'closed') {
                // All steps completed
                $query->whereHas('steps') // ensure steps exist
                    ->whereDoesntHave('steps', function ($q) {
                        $q->where('status', '!=', 'completed');
                    });
            }

            if ($request->status == 'active') {
                // At least one step pending
                $query->whereHas('steps', function ($q) {
                    $q->where('status', '!=', 'completed');
                });
            }

            $query = $query->select('active_customer_processes.*')->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    // $view = '<a href="' . route('active_customer_process.show', $row->id) . '" class="btn btn-sm btn-info">View</a>';
                    if (auth()->user()->can(['active_process_delete'])) {
                        $delete = '<button class="btn btn-sm btn-danger delete" data-id="' . $row->id . '" title="Delete Active Process"><i class="material-icons">delete</i></button>';
                        return $delete;
                    }
                })

                ->editColumn('customer.creation_date', function ($row) {
                    $date = $row->customer->creation_date ?? null;

                    if (!empty($date) && strtotime($date)) {
                        return date('d M Y', strtotime($date));
                    }

                    return '';
                })

                ->addColumn('steps', function ($row) {
                    return '<button class="btn btn-sm btn-info steps" data-id="' . $row->id . '" title="Steps"><i class="material-icons">list</i></button>';
                })
                ->rawColumns(['action', 'steps'])
                ->make(true);
        }
        return view('active_customer_process.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('active_process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = Customers::where('active', 'Y')->select('id', 'name')->get();
        $processes = CustomerProcess::with('steps')->get();
        $active_process = new ActiveCustomerProcess();
        return view('active_customer_process.create', compact('customers', 'processes'))->with('active_process', $active_process);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('active_process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'process_id' => 'required|array',
            'process_id.*' => 'required|exists:customer_processes,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->process_id as $process_id) {
                $activeProcess = ActiveCustomerProcess::updateOrCreate([
                    'customer_id' => $request->customer_id,
                    'process_id' => $process_id,
                ], [
                    'assigned_by' => auth()->id(),
                    'status' => 'pending',
                ]);
                $processSteps = CustomerProcessStep::where('customer_process_id', $process_id)->get();
                foreach ($processSteps as $step) {
                    ActiveCustomerProcessStep::updateOrCreate([
                        'active_customer_process_id' => $activeProcess->id,
                        'customer_process_step_id' => $step->id,
                    ], [
                        'status' => 'pending',
                    ]);
                }
            }
        });
        return redirect()->route('active_customer_process.index')->with('success', 'Process assigned successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActiveCustomerProcess  $activeCustomerProcess
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $all_processes = ActiveCustomerProcess::with('customer', 'process', 'steps')->where('customer_id', $id)->get();
        return view('active_customer_process.show', compact('all_processes'));
    }

    public function public_active_processes(Request $request)
    {
        $id = decrypt($request->id);
        $all_processes = ActiveCustomerProcess::with('customer', 'process', 'steps')->where('customer_id', $id)->get();
        return view('active_customer_process.show', compact('all_processes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActiveCustomerProcess  $activeCustomerProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(ActiveCustomerProcess $activeCustomerProcess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActiveCustomerProcess  $activeCustomerProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActiveCustomerProcess $activeCustomerProcess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActiveCustomerProcess  $activeCustomerProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActiveCustomerProcess $activeCustomerProcess)
    {
        abort_if(Gate::denies('active_process_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden, You do not have permission to delete this process!');
        if ($activeCustomerProcess->delete()) {
            return response()->json(['status'  => 'success', 'message' => 'Active Process deleted successfully!']);
        }
        return response()->json(['status'  => 'error', 'message' => 'Error in Process Delete!']);
    }

    public function getActiveProcessSteps(ActiveCustomerProcess $activeCustomerProcess)
    {
        return response()->json(['status' => 'success', 'steps' => $activeCustomerProcess->steps->load('step', 'completedByUser')]);
    }

    public function completeProcessStep(ActiveCustomerProcessStep $step, Request $request)
    {
        $request->validate([
            'remarks' => 'required|string|max:255',
            'status' => 'required|in:pending,completed',
        ]);

        $step->update([
            'status' => $request->status,
            'completed_at' => $request->status == 'completed' ? $request->completed_at : null,
            'remark' => $request->remarks,
        ]);

        $process = $step->activeProcess; // assuming you have a relation in step model

        $allCompleted = $process->steps()->where('status', '!=', 'completed')->count() === 0;

        if ($allCompleted) {
            $process->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        } else {
            $process->update([
                'status' => 'pending',
                'completed_at' => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Step marked as completed successfully.',
        ]);
    }

    public function activeProcessReport(Request $request)
    {
        $customers = $this->reportCustomers();
        $processes = CustomerProcess::select('id', 'process_name')->get();

        $records = $this->activeProcessReportQuery($request)->paginate(50)->withQueryString();

        // Listing shows progress summary only; step-wise columns are in the Excel download
        $rows = $records->getCollection()->map(function ($row) {
            $steps = $row->steps->sortBy(function ($step) {
                return optional($step->step)->sort_order;
            })->values();
            $completedSteps = $steps->where('status', 'completed');
            $lastCompleted = $completedSteps->sortBy('completed_at')->last();
            $nextPending = $steps->firstWhere('status', '!=', 'completed');
            $total = $steps->count();

            return (object) [
                'name' => $row->customer->name ?? '',
                'mobile' => $row->customer->mobile ?? '',
                'created_date' => !empty($row->customer->creation_date) && strtotime($row->customer->creation_date) ? date('d M Y', strtotime($row->customer->creation_date)) : '-',
                'process' => $row->process->process_name ?? '',
                'completed' => $completedSteps->count(),
                'total' => $total,
                'percent' => $total > 0 ? round($completedSteps->count() * 100 / $total) : 0,
                'closed' => $total > 0 && $completedSteps->count() == $total,
                'last_completed' => $lastCompleted ? (optional($lastCompleted->step)->value ?? '') : null,
                'last_completed_at' => $lastCompleted && $lastCompleted->completed_at ? $lastCompleted->completed_at->format('d M Y') : '',
                'next_step' => $nextPending ? (optional($nextPending->step)->value ?? '') : null,
            ];
        });

        return view('reports.active_process_report', compact('customers', 'processes', 'records', 'rows'));
    }

    public function activeProcessReportDownload(Request $request)
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');
        $records = $this->activeProcessReportQuery($request)->get();
        [$headings, $rows] = $this->activeProcessReportData($request, $records);

        $filters = [
            'Process' => !empty($request->process_id) ? (CustomerProcess::find($request->process_id)->process_name ?? '-') : 'All',
            'Customer' => !empty($request->customer_id) ? (Customers::find($request->customer_id)->name ?? '-') : 'All',
            'Status' => ucfirst($request->input('status', 'active')),
            'Created Date' => ($request->start_date ? date('d-m-Y', strtotime($request->start_date)) : 'Start') . ' to ' . ($request->end_date ? date('d-m-Y', strtotime($request->end_date)) : 'Today'),
            'Total Records' => count($rows),
            'Generated On' => date('d-m-Y h:i A'),
        ];

        return Excel::download(new ActiveProcessReportExport($headings, $rows, $filters), 'active_process_report_' . date('d_m_Y') . '.xlsx');
    }

    private function reportCustomers()
    {
        $customers = Customers::where('active', 'Y');
        if (!auth()->user()->hasRole('superadmin') && !auth()->user()->hasRole('Admin')) {
            $customers->whereIn('id', $this->reportCustomerIds());
        }
        return $customers->select('id', 'name')->get();
    }

    private function reportCustomerIds()
    {
        $users_ids = getUsersReportingToAuth();
        return EmployeeDetail::whereIn('user_id', $users_ids)->pluck('customer_id')->toArray();
    }

    private function activeProcessReportQuery(Request $request)
    {
        $query = ActiveCustomerProcess::with([
            'customer:id,name,mobile,creation_date',
            'process:id,process_name',
            'steps.step',
        ]);

        if (!auth()->user()->hasRole('superadmin') && !auth()->user()->hasRole('Admin')) {
            $query->whereIn('customer_id', $this->reportCustomerIds());
        }

        if (auth()->user()->hasRole('Service')) {
            $query->whereHas('process', function ($q) {
                $q->where('process_type', 'Service');
            });
        }

        if (!empty($request->process_id)) {
            $query->where('process_id', $request->process_id);
        }

        if (!empty($request->customer_id)) {
            $query->where('customer_id', $request->customer_id);
        }

        if (!empty($request->start_date)) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('creation_date', '>=', $request->start_date);
            });
        }

        if (!empty($request->end_date)) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('creation_date', '<=', $request->end_date);
            });
        }

        // Default to active (at least one step pending)
        $status = $request->input('status', 'active');
        if ($status == 'closed') {
            $query->whereHas('steps')
                ->whereDoesntHave('steps', function ($q) {
                    $q->where('status', '!=', 'completed');
                });
        } elseif ($status == 'active') {
            $query->whereHas('steps', function ($q) {
                $q->where('status', '!=', 'completed');
            });
        }

        return $query->select('active_customer_processes.*')->latest();
    }

    /**
     * Build headings + rows: one column per process step holding its completed date (or Pending).
     */
    private function activeProcessReportData(Request $request, $records, $startIndex = 1)
    {
        $stepRows = $records->map(function ($row) {
            return $row->steps->sortBy(function ($step) {
                return optional($step->step)->sort_order;
            })->values();
        });

        // Single process (selected or all rows share one) -> real step names as headings, otherwise generic Step 1..N
        $processIds = !empty($request->process_id) ? [$request->process_id] : $records->pluck('process_id')->unique()->values()->toArray();
        if (count($processIds) == 1) {
            $stepHeadings = CustomerProcessStep::where('customer_process_id', $processIds[0])->pluck('value')->toArray();
        } else {
            $maxSteps = $stepRows->map->count()->max() ?? 0;
            $stepHeadings = [];
            for ($i = 1; $i <= $maxSteps; $i++) {
                $stepHeadings[] = 'Step ' . $i;
            }
        }

        $headings = array_merge(['Sr. No.', 'Client Name', 'Mobile Number', 'Customer Created Date', 'Process', 'Status', 'Progress'], $stepHeadings);

        $rows = [];
        foreach ($records->values() as $index => $row) {
            $steps = $stepRows[$index];
            $completed = $steps->where('status', 'completed')->count();
            $line = [
                $startIndex + $index,
                $row->customer->name ?? '',
                $row->customer->mobile ?? '',
                !empty($row->customer->creation_date) && strtotime($row->customer->creation_date) ? date('d-m-Y', strtotime($row->customer->creation_date)) : '',
                $row->process->process_name ?? '',
                $steps->count() > 0 && $completed == $steps->count() ? 'Closed' : 'Active',
                $completed . ' / ' . $steps->count(),
            ];
            foreach (array_keys($stepHeadings) as $i) {
                $step = $steps[$i] ?? null;
                if (!$step) {
                    $line[] = '-';
                } elseif ($step->status == 'completed') {
                    $line[] = $step->completed_at ? $step->completed_at->format('d-m-Y') : 'Completed';
                } else {
                    $line[] = 'Pending';
                }
            }
            $rows[] = $line;
        }

        return [$headings, $rows];
    }

    public function updateRemark(Request $request, ActiveCustomerProcessStep $step)
    {
        $request->validate([
            'remark' => 'required|string|max:255',
        ]);

        $step->remark = $request->remark;
        $step->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Remark updated successfully.',
            'remark' => $step->remark,
        ]);
    }
}
