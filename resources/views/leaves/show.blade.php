@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('leaves.show_title') }}</h1>
        <div class="flex flex-wrap gap-2 items-center">
            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
            <a href="{{ route('leaves.edit', $leave) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-100 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('common.edit') }}
            </a>
            @endif
            @if($leave->isPending() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor()))
            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-md hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('leaves.approve') }}
                </button>
            </form>
            <button type="button" onclick="showRejectModal()" 
                    class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-md hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ __('leaves.reject') }}
            </button>
            @endif
            <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('leaves.delete_confirm') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('common.delete') }}
                </button>
            </form>
            <a href="{{ route('leaves.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('common.back') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ $leave->user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $leave->getTypeLabel() }}</p>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.name') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->user->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.type') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->getTypeLabel() }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.start_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->start_date->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.end_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->end_date->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.days') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->getDaysCount() }} {{ __('leaves.days') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('common.status') }}</dt>
                    <dd class="mt-1">
                        @if($leave->status === 'approved')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('leaves.approved') }}</span>
                        @elseif($leave->status === 'rejected')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('leaves.rejected') }}</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ __('leaves.pending') }}</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.requested_by') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->requestedBy->name }}</dd>
                </div>

                @if($leave->approvedBy)
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ $leave->isApproved() ? __('leaves.approved_by') : __('leaves.rejected_by') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->approvedBy->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ $leave->isApproved() ? __('leaves.approval_date') : __('leaves.rejection_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->approved_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif

                @if($leave->rejection_reason)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.rejection_reason') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->rejection_reason }}</dd>
                </div>
                @endif

                @if($leave->reason)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.reason') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->reason }}</dd>
                </div>
                @endif

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.request_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->created_at->format('d/m/Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('leaves.last_update') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

@if($leave->isPending() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor()))
<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('leaves.reject_modal_title') }}</h3>
            <form action="{{ route('leaves.reject', $leave) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">{{ __('leaves.reject_reason_required') }} <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        {{ __('leaves.reject') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endif
@endsection
