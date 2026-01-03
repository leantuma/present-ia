<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Schedules</h1>
        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
        <button wire:click="openCreateModal" class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
            Create Schedule
        </button>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($schedules as $schedule)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $schedule->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($schedule->type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @php
                            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            $selectedDays = array_map(function($d) use ($days) { return $days[$d-1] ?? ''; }, $schedule->days_of_week ?? []);
                        @endphp
                        {{ implode(', ', $selectedDays) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                        <button wire:click="toggleActive({{ $schedule->id }})" class="text-primary hover:text-indigo-700 mr-3">
                            {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button wire:click="openEditModal({{ $schedule->id }})" class="text-primary hover:text-indigo-700 mr-3">Edit</button>
                        <button wire:click="deleteSchedule({{ $schedule->id }})" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="$set('showModal', false)">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $editingSchedule ? 'Edit' : 'Create' }} Schedule</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="formData.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" wire:model="formData.start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" wire:model="formData.end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tolerance (minutes)</label>
                    <input type="number" wire:model="formData.tolerance_minutes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex space-x-4">
                    <button wire:click="saveSchedule" class="flex-1 bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                        Save
                    </button>
                    <button wire:click="$set('showModal', false)" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

