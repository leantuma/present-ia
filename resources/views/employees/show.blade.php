@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('employees.show_title') }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('employees.edit', $employee) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                {{ __('common.edit') }}
            </a>
            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('employees.delete_confirm') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    {{ __('common.delete') }}
                </button>
            </form>
            <a href="{{ route('employees.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                {{ __('common.back') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ $employee->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $employee->email }}</p>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.email') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->email }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.employee_id') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->employeeProfile?->employee_id ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.department') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->employeeProfile?->department ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.position') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->employeeProfile?->position ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.hire_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $employee->employeeProfile?->hire_date ? $employee->employeeProfile->hire_date->format('d/m/Y') : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.role') }}</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ __('employees.title') }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.registration_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->created_at->format('d/m/Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('employees.last_update') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
