@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('employees.edit_title') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('employees.edit_description') }}</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('common.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if(auth()->user()->isAdmin() && auth()->user()->id !== $employee->id)
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol <span class="text-red-500">*</span></label>
                    <select name="role" id="role" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('role') border-red-300 @enderror">
                        <option value="employee" {{ old('role', $employee->role) === 'employee' ? 'selected' : '' }}>Empleado</option>
                        <option value="supervisor" {{ old('role', $employee->role) === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="admin" {{ old('role', $employee->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Solo los administradores pueden cambiar roles.</p>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <div class="mt-1">
                        @if($employee->role === 'admin')
                            <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-md bg-purple-100 text-purple-800">Admin</span>
                        @elseif($employee->role === 'supervisor')
                            <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-md bg-blue-100 text-blue-800">Supervisor</span>
                        @else
                            <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-md bg-gray-100 text-gray-800">Empleado</span>
                        @endif
                    </div>
                    @if(auth()->user()->id === $employee->id)
                        <p class="mt-1 text-sm text-gray-500">No puedes cambiar tu propio rol.</p>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Solo los administradores pueden cambiar roles.</p>
                    @endif
                </div>
                @endif

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('employees.password') }}</label>
                    <input type="password" name="password" id="password" minlength="8"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password') border-red-300 @enderror"
                        placeholder="{{ __('employees.password_optional') }}">
                    <p class="mt-1 text-sm text-gray-500">{{ __('employees.password_optional') }}</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">{{ __('employees.employee_id') }}</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->employeeProfile?->employee_id) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('employee_id') border-red-300 @enderror"
                        placeholder="ID único del empleado en la empresa">
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">{{ __('employees.department') }}</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $employee->employeeProfile?->department) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('department') border-red-300 @enderror">
                    @error('department')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">{{ __('employees.position') }}</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $employee->employeeProfile?->position) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('position') border-red-300 @enderror">
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hire_date" class="block text-sm font-medium text-gray-700">{{ __('employees.hire_date') }}</label>
                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $employee->employeeProfile?->hire_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('hire_date') border-red-300 @enderror">
                    @error('hire_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    {{ __('common.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                    {{ __('common.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
