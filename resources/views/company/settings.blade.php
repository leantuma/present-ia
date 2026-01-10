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
        
        @if(auth()->user()->isSupervisor())
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded">
            <p class="text-sm">Estás viendo esta información en modo de solo lectura. Solo los administradores pueden modificar la configuración de la empresa.</p>
        </div>
        @endif
        
        <form action="{{ route('company.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('name') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) readonly @endif>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('common.email') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('email') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) readonly @endif>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('companies.phone') }}</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('phone') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) readonly @endif>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">{{ __('common.address') }}</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('address') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) readonly @endif>{{ old('address', $company->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700">{{ __('companies.language') }} <span class="text-red-500">*</span></label>
                    <select name="language" id="language" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('language') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) disabled @endif>
                        <option value="es" {{ old('language', $company->language ?? 'es') === 'es' ? 'selected' : '' }}>{{ __('companies.spanish') }}</option>
                        <option value="en" {{ old('language', $company->language ?? 'es') === 'en' ? 'selected' : '' }}>{{ __('companies.english') }}</option>
                    </select>
                    @error('language')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700">Zona Horaria <span class="text-red-500">*</span></label>
                    <select name="timezone" id="timezone" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('timezone') border-red-300 @enderror"
                        @if(auth()->user()->isSupervisor()) disabled @endif>
                        <optgroup label="Argentina">
                            <option value="America/Argentina/Buenos_Aires" {{ old('timezone', $company->timezone ?? 'America/Argentina/Buenos_Aires') === 'America/Argentina/Buenos_Aires' ? 'selected' : '' }}>Buenos Aires (GMT-3)</option>
                            <option value="America/Argentina/Cordoba" {{ old('timezone', $company->timezone ?? '') === 'America/Argentina/Cordoba' ? 'selected' : '' }}>Córdoba (GMT-3)</option>
                            <option value="America/Argentina/Mendoza" {{ old('timezone', $company->timezone ?? '') === 'America/Argentina/Mendoza' ? 'selected' : '' }}>Mendoza (GMT-3)</option>
                        </optgroup>
                        <optgroup label="Chile">
                            <option value="America/Santiago" {{ old('timezone', $company->timezone ?? '') === 'America/Santiago' ? 'selected' : '' }}>Santiago (GMT-3)</option>
                        </optgroup>
                        <optgroup label="Uruguay">
                            <option value="America/Montevideo" {{ old('timezone', $company->timezone ?? '') === 'America/Montevideo' ? 'selected' : '' }}>Montevideo (GMT-3)</option>
                        </optgroup>
                        <optgroup label="Paraguay">
                            <option value="America/Asuncion" {{ old('timezone', $company->timezone ?? '') === 'America/Asuncion' ? 'selected' : '' }}>Asunción (GMT-4)</option>
                        </optgroup>
                        <optgroup label="Perú">
                            <option value="America/Lima" {{ old('timezone', $company->timezone ?? '') === 'America/Lima' ? 'selected' : '' }}>Lima (GMT-5)</option>
                        </optgroup>
                        <optgroup label="Colombia">
                            <option value="America/Bogota" {{ old('timezone', $company->timezone ?? '') === 'America/Bogota' ? 'selected' : '' }}>Bogotá (GMT-5)</option>
                        </optgroup>
                        <optgroup label="México">
                            <option value="America/Mexico_City" {{ old('timezone', $company->timezone ?? '') === 'America/Mexico_City' ? 'selected' : '' }}>Ciudad de México (GMT-6)</option>
                        </optgroup>
                        <optgroup label="Brasil">
                            <option value="America/Sao_Paulo" {{ old('timezone', $company->timezone ?? '') === 'America/Sao_Paulo' ? 'selected' : '' }}>São Paulo (GMT-3)</option>
                        </optgroup>
                        <optgroup label="España">
                            <option value="Europe/Madrid" {{ old('timezone', $company->timezone ?? '') === 'Europe/Madrid' ? 'selected' : '' }}>Madrid (GMT+1)</option>
                        </optgroup>
                        <optgroup label="UTC">
                            <option value="UTC" {{ old('timezone', $company->timezone ?? '') === 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                        </optgroup>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Selecciona la zona horaria de tu empresa. Todos los horarios se mostrarán ajustados a esta zona horaria.</p>
                    @error('timezone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                @if(auth()->user()->isAdmin())
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                    {{ __('common.update') }}
                </button>
                @endif
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
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('company.settings.generate-qr') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    {{ __('companies.regenerate_qr') }}
                                </button>
                            </form>
                            @endif
                            
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
                @if(auth()->user()->isAdmin())
                <form action="{{ route('company.settings.generate-qr') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                        {{ __('companies.generate_qr') }}
                    </button>
                </form>
                @else
                <p class="text-sm text-gray-500">Solo los administradores pueden generar códigos QR.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

