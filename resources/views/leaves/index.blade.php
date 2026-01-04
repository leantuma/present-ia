@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('leaves.title') }}</h1>
        <a href="{{ route('leaves.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            {{ __('leaves.new_leave') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
        <form method="GET" action="{{ route('leaves.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('common.status') }}</label>
                <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">{{ __('leaves.all_statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('leaves.pending') }}</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('leaves.approved') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('leaves.rejected') }}</option>
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('leaves.type') }}</label>
                <select name="type" id="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    <option value="">{{ __('leaves.all_types') }}</option>
                    <option value="vacation" {{ request('type') === 'vacation' ? 'selected' : '' }}>{{ __('leaves.vacation') }}</option>
                    <option value="sick" {{ request('type') === 'sick' ? 'selected' : '' }}>{{ __('leaves.sick') }}</option>
                    <option value="maternity" {{ request('type') === 'maternity' ? 'selected' : '' }}>{{ __('leaves.maternity') }}</option>
                    <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>{{ __('leaves.other') }}</option>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('leaves.from') }}</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    {{ __('common.filter') }}
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.name') }}</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leaves.type') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leaves.start_date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leaves.end_date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('leaves.days') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leaves as $leave)
                <tr>
                    @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $leave->user->name }}</td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->getTypeLabel() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->start_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->end_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->getDaysCount() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($leave->status === 'approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('leaves.approved') }}</span>
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('leaves.rejected') }}</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('leaves.pending') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('leaves.show', $leave) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('common.view') }}</a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                            <a href="{{ route('leaves.edit', $leave) }}" class="text-blue-600 hover:text-blue-900">{{ __('common.edit') }}</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() || auth()->user()->isSupervisor() ? '7' : '6' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ __('leaves.no_leaves') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leaves->hasPages())
    <div class="mt-4">
        {{ $leaves->links() }}
    </div>
    @endif
</div>
@endsection
