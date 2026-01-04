<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Company::class);
        
        $companies = Company::orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Company::class);
        
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Company::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Set default values
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        Company::create($data);
        
        return redirect()->route('companies.index')
            ->with('message', __('companies.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);
        
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Ensure slug is unique (excluding current company)
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Company::where('slug', $data['slug'])->where('id', '!=', $company->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Set default values
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        $company->update($data);
        
        return redirect()->route('companies.index')
            ->with('message', __('companies.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        
        $company->delete();
        
        return redirect()->route('companies.index')
            ->with('message', __('companies.deleted_successfully'));
    }

    /**
     * List admins for a company
     */
    public function admins(Company $company)
    {
        $this->authorize('view', $company);
        
        $admins = User::where('company_id', $company->id)
            ->where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('companies.admins', compact('company', 'admins'));
    }

    /**
     * Show form to create admin for a company
     */
    public function createAdmin(Company $company)
    {
        $this->authorize('view', $company);
        
        return view('companies.create-admin', compact('company'));
    }

    /**
     * Store admin for a company
     */
    public function storeAdmin(StoreAdminRequest $request, Company $company)
    {
        $this->authorize('view', $company);
        
        $data = $request->validated();
        
        // Ensure company_id matches the route company
        $data['company_id'] = $company->id;
        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
            'role' => 'admin',
        ]);
        
        return redirect()->route('companies.show', $company)
            ->with('message', __('admins.created_successfully'));
    }
}
