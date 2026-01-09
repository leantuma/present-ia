@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('companies.settings_title') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('companies.settings_description') }}</p>
    </div>

    @if(session('message'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
        {{ session('message') }}
    </div>
    @endif

    <!-- Company Information Form -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('companies.company_information') }}</h2>
        
        <form action="{{ route('company.settings.update') }}" method="POST">
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
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('common.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('companies.phone') }}</label>
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
                    @error('language')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                    {{ __('common.update') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Fixed QR Code Section -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('companies.fixed_qr_title') }}</h2>
        <p class="text-sm text-gray-600 mb-4">{{ __('companies.fixed_qr_description') }}</p>

        @if($fixedQR)
            <div class="mb-6">
                <div class="flex flex-col items-center space-y-4">
                    <!-- QR Code Display -->
                    <div class="bg-white p-4 border-2 border-gray-200 rounded-lg">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($fixedQR['qr_data']) }}" 
                             alt="QR Code" 
                             class="mx-auto">
                    </div>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-4">{{ __('companies.fixed_qr_instructions') }}</p>
                        
                        <div class="flex justify-center space-x-3">
                            <form action="{{ route('company.settings.generate-qr') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    {{ __('companies.regenerate_qr') }}
                                </button>
                            </form>
                            
                            <a href="{{ route('company.settings.download-qr') }}" 
                               class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                                {{ __('companies.download_qr') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 mb-4">{{ __('companies.no_fixed_qr_token') }}</p>
                <form action="{{ route('company.settings.generate-qr') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                        {{ __('companies.generate_qr') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

