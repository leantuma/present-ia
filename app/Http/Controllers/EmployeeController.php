<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeProfile;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $user = auth()->user();
        
        // Show employees, admins and supervisors (not superadmin)
        $employees = User::where('company_id', $user->company_id)
            ->whereIn('role', ['employee', 'admin', 'supervisor'])
            ->with('employeeProfile')
            ->orderBy('role')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $user = auth()->user();
        
        DB::transaction(function () use ($request, $user) {
            // Create the user (employee)
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $user->company_id,
                'role' => 'employee',
            ]);

            // Create the employee profile
            EmployeeProfile::create([
                'user_id' => $employee->id,
                'company_id' => $user->company_id,
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'position' => $request->position,
                'hire_date' => $request->hire_date,
            ]);
        });
        
        return redirect()->route('employees.index')
            ->with('message', __('employees.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        $this->authorize('view', $employee);
        
        $employee->load('employeeProfile');
        
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $this->authorize('update', $employee);
        
        $employee->load('employeeProfile');
        
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $user = auth()->user();
        
        // Only Admin can change roles
        if ($request->filled('role') && $request->role !== $employee->role) {
            if (!$user->isAdmin()) {
                abort(403, 'Solo los administradores pueden cambiar roles.');
            }
            
            // Validate role change permission
            if (!$user->can('changeRole', $employee)) {
                abort(403, 'No tienes permiso para cambiar el rol de este usuario.');
            }
        }
        
        DB::transaction(function () use ($request, $employee, $user) {
            // Update the user
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            
            // Only Admin can change roles
            if ($request->filled('role') && $user->isAdmin() && $user->can('changeRole', $employee)) {
                $updateData['role'] = $request->role;
            }
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            $employee->update($updateData);

            // Update or create the employee profile (maintain it even if role changes)
            $profileData = [
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'position' => $request->position,
                'hire_date' => $request->hire_date,
            ];
            
            if ($employee->employeeProfile) {
                $employee->employeeProfile->update($profileData);
            } else {
                EmployeeProfile::create(array_merge($profileData, [
                    'user_id' => $employee->id,
                    'company_id' => $employee->company_id,
                ]));
            }
        });
        
        return redirect()->route('employees.index')
            ->with('message', __('employees.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        $this->authorize('delete', $employee);
        
        // Soft delete the employee (cascade will handle employee profile)
        $employee->delete();
        
        return redirect()->route('employees.index')
            ->with('message', __('employees.deleted_successfully'));
    }
}
