@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('companies.edit_title') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('companies.edit_description') }}</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">{{ __('companies.slug') }}</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $company->slug) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('slug') border-red-300 @enderror"
                        placeholder="{{ __('companies.slug_description') }}">
                    <p class="mt-1 text-sm text-gray-500">{{ __('companies.slug_description') }}</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('common.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('phone') border-red-300 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">{{ __('common.address') }}</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('address') border-red-300 @enderror">{{ old('address', $company->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700">{{ __('companies.language') }} <span class="text-red-500">*</span></label>
                    <select name="language" id="language" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('language') border-red-300 @enderror">
                        <option value="es" {{ old('language', $company->language ?? 'es') === 'es' ? 'selected' : '' }}>{{ __('companies.spanish') }}</option>
                        <option value="en" {{ old('language', $company->language ?? 'es') === 'en' ? 'selected' : '' }}>{{ __('companies.english') }}</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">{{ __('companies.language_description') }}</p>
                    @error('language')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        {{ __('common.active') }}
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('companies.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
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
