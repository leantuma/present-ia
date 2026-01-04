@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('employees.title') }}</h1>
        <a href="{{ route('employees.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            {{ __('employees.new_employee') }}
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.email') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('employees.employee_id') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('employees.department') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('employees.position') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('employees.hire_date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->employeeProfile?->employee_id ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->employeeProfile?->department ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->employeeProfile?->position ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->employeeProfile?->hire_date ? $employee->employeeProfile->hire_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('employees.show', $employee) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('common.view') }}</a>
                            <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-900">{{ __('common.edit') }}</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('employees.delete_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ __('employees.no_employees') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
    <div class="mt-4">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
