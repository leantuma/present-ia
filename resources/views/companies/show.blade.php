@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('companies.show_title') }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('companies.edit', $company) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                {{ __('common.edit') }}
            </a>
            <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('companies.delete_confirm') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    {{ __('common.delete') }}
                </button>
            </form>
            <a href="{{ route('companies.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                {{ __('common.back') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ $company->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">ID: {{ $company->id }}</p>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('companies.slug') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->slug }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.status') }}</dt>
                    <dd class="mt-1">
                        @if($company->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('common.active') }}</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('common.inactive') }}</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.email') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->email ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.phone') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->phone ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('companies.language') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $company->language === 'es' ? __('companies.spanish') : __('companies.english') }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.address') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->address ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('companies.creation_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->created_at->format('d/m/Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('companies.last_update') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $company->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Administrators Section -->
    <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('admins.title') }}</h2>
            <a href="{{ route('companies.admins.create', $company) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                {{ __('admins.new_admin') }}
            </a>
        </div>

        <div class="px-6 py-4">
            @php
                $admins = \App\Models\User::where('company_id', $company->id)
                    ->where('role', 'admin')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($admins->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admins.registration_date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($admins as $admin)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $admin->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $admin->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $admin->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admins.edit', $admin) }}" class="text-blue-600 hover:text-blue-900">{{ __('common.edit') }}</a>
                                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admins.delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-gray-500 text-center py-4">{{ __('admins.no_admins') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
