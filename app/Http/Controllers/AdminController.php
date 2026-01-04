<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only superadmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        $query = User::where('role', 'admin')
            ->with('company');
        
        // Filter by company if provided
        if (request()->has('company_id') && request()->company_id) {
            $query->where('company_id', request()->company_id);
        }
        
        $admins = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $companies = Company::orderBy('name')->get();
        
        return view('admins.index', compact('admins', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only superadmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        $companies = Company::orderBy('name')->get();
        
        return view('admins.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
            'role' => 'admin',
        ]);
        
        return redirect()->route('admins.index')
            ->with('message', __('admins.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        // Only superadmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        $admin->load('company');
        
        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        // Only superadmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        $companies = Company::orderBy('name')->get();
        
        return view('admins.edit', compact('admin', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, User $admin)
    {
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        $data = $request->validated();
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'company_id' => $data['company_id'],
        ];
        
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }
        
        $admin->update($updateData);
        
        return redirect()->route('admins.index')
            ->with('message', __('admins.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        // Only superadmin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        // Soft delete
        $admin->delete();
        
        return redirect()->route('admins.index')
            ->with('message', __('admins.deleted_successfully'));
    }
}
