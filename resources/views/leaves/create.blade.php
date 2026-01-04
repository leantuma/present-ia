@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('leaves.request_leave') }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ __('leaves.request_description') }}</p>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('leaves.store') }}" method="POST" id="leaveForm">
            @csrf

            <div class="space-y-6">
                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('user_id') border-red-300 @enderror">
                        <option value="">{{ __('leaves.select_type') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">{{ __('leaves.type') }} <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('type') border-red-300 @enderror">
                        <option value="">{{ __('leaves.select_type') }}</option>
                        <option value="vacation" {{ old('type') === 'vacation' ? 'selected' : '' }}>{{ __('leaves.vacation') }}</option>
                        <option value="sick" {{ old('type') === 'sick' ? 'selected' : '' }}>{{ __('leaves.sick') }}</option>
                        <option value="maternity" {{ old('type') === 'maternity' ? 'selected' : '' }}>{{ __('leaves.maternity') }}</option>
                        <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>{{ __('leaves.other') }}</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('leaves.start_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('start_date') border-red-300 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('leaves.end_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('end_date') border-red-300 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="days_count" class="block text-sm font-medium text-gray-700">{{ __('leaves.days') }}</label>
                    <input type="text" id="days_count" readonly
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50"
                        value="0 días">
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700">{{ __('leaves.reason') }}</label>
                    <textarea name="reason" id="reason" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary @error('reason') border-red-300 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('leaves.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    {{ __('common.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-indigo-700">
                    {{ __('common.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const daysCount = document.getElementById('days_count');
    
    function calculateDays() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            daysCount.value = diffDays + ' días';
        } else {
            daysCount.value = '0 días';
        }
    }
    
    startDate.addEventListener('change', calculateDays);
    endDate.addEventListener('change', calculateDays);
});
</script>
@endsection
