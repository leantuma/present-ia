<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\Alert;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Http\Requests\RejectLeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Leave::class);
        
        $user = auth()->user();
        $companyId = $user->company_id;
        
        $query = Leave::where('company_id', $companyId)
            ->with(['user', 'requestedBy', 'approvedBy']);
        
        // Employees can only see their own leaves
        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        }
        
        // Filters
        if (request()->has('status') && request()->status !== '') {
            $query->where('status', request()->status);
        }
        
        if (request()->has('type') && request()->type !== '') {
            $query->where('type', request()->type);
        }
        
        if (request()->has('start_date') && request()->start_date) {
            $query->where('start_date', '>=', request()->start_date);
        }
        
        if (request()->has('end_date') && request()->end_date) {
            $query->where('end_date', '<=', request()->end_date);
        }
        
        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Leave::class);
        
        $user = auth()->user();
        $employees = [];
        
        // If admin/supervisor, get list of employees
        if ($user->isAdmin() || $user->isSupervisor()) {
            $employees = User::where('company_id', $user->company_id)
                ->where('role', 'employee')
                ->orderBy('name')
                ->get();
        }
        
        return view('leaves.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;
        
        // Determine user_id: admin/supervisor can select, employee is themselves
        $userId = $request->input('user_id', $user->id);
        
        // If admin/supervisor creates directly, auto-approve
        $status = ($user->isAdmin() || $user->isSupervisor()) ? 'approved' : 'pending';
        $approvedBy = ($status === 'approved') ? $user->id : null;
        $approvedAt = ($status === 'approved') ? now() : null;
        
        $leave = Leave::create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $status,
            'reason' => $request->reason,
            'requested_by' => $user->id,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
        ]);
        
        // Create alert for admin/supervisor when leave is pending
        if ($status === 'pending') {
            $employee = User::find($userId);
            $leaveTypeLabel = $leave->getTypeLabel();
            
            Alert::create([
                'company_id' => $companyId,
                'user_id' => null, // Visible for all admin/supervisor
                'leave_id' => $leave->id,
                'type' => 'leave_request',
                'title' => __('leaves.new_leave_request_alert_title'),
                'message' => __('leaves.new_leave_request_alert_message', [
                    'employee' => $employee->name,
                    'type' => $leaveTypeLabel,
                    'start_date' => $leave->start_date->format('d/m/Y'),
                    'end_date' => $leave->end_date->format('d/m/Y'),
                ]),
                'severity' => 'medium',
                'metadata' => [
                    'leave_id' => $leave->id,
                    'employee_id' => $employee->id,
                ],
            ]);
        }
        
        $message = $status === 'approved' 
            ? __('leaves.created_approved_successfully')
            : __('leaves.created_successfully');
        
        return redirect()->route('leaves.index')
            ->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $this->authorize('view', $leave);
        
        $leave->load(['user', 'requestedBy', 'approvedBy', 'company']);
        
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        $this->authorize('update', $leave);
        
        $user = auth()->user();
        $employees = [];
        
        // If admin/supervisor, get list of employees
        if ($user->isAdmin() || $user->isSupervisor()) {
            $employees = User::where('company_id', $user->company_id)
                ->where('role', 'employee')
                ->orderBy('name')
                ->get();
        }
        
        return view('leaves.edit', compact('leave', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        $updateData = [
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ];
        
        // Admin/supervisor can change the employee
        if ($request->has('user_id')) {
            $updateData['user_id'] = $request->user_id;
        }
        
        $leave->update($updateData);
        
        return redirect()->route('leaves.index')
            ->with('message', __('leaves.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $this->authorize('delete', $leave);
        
        $leave->delete();
        
        return redirect()->route('leaves.index')
            ->with('message', __('leaves.deleted_successfully'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Leave $leave)
    {
        $user = auth()->user();
        
        // Ensure leave is loaded with company_id
        $leave->load('company');
        
        // Verify authorization
        if (!$user->isAdmin() && !$user->isSupervisor()) {
            abort(403, __('common.access_denied'));
        }
        
        if ($user->company_id !== $leave->company_id) {
            abort(403, __('common.access_denied'));
        }
        
        if (!$leave->isPending()) {
            abort(400, __('leaves.leave_not_pending'));
        }
        
        $leave->approve($user);
        
        return redirect()->route('leaves.index')
            ->with('message', __('leaves.approved_successfully'));
    }

    /**
     * Reject a leave request.
     */
    public function reject(RejectLeaveRequest $request, Leave $leave)
    {
        $user = auth()->user();
        $leave->reject($user, $request->rejection_reason);
        
        return redirect()->route('leaves.show', $leave)
            ->with('message', __('leaves.rejected_successfully'));
    }
}
