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
</div>
@endsection
