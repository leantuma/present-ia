@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('profile.title') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('profile.description') }}</p>
    </div>

    <!-- User Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('profile.user_information') }}</h2>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.email') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('profile.role') }}</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($user->role) }}
                        </span>
                    </dd>
                </div>
                @if($user->company)
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('companies.title') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->company->name }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('profile.change_password') }}</h2>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">{{ __('profile.current_password') }} <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('current_password') border-red-300 @enderror">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('profile.new_password') }} <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required minlength="8"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password') border-red-300 @enderror">
                        <p class="mt-1 text-sm text-gray-500">{{ __('profile.password_min') }}</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('profile.confirm_password') }} <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('password_confirmation') border-red-300 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('common.cancel') }}
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                        {{ __('profile.update_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



