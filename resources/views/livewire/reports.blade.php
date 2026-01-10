<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('reports.title') }}</h1>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('reports.start_date') }}</label>
                <input type="date" wire:model="startDate" class="block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('reports.end_date') }}</label>
                <input type="date" wire:model="endDate" class="block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('reports.employee') }}</label>
                <select wire:model="selectedUserId" class="block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">{{ __('reports.all_employees') }}</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex items-end">
                <button wire:click="exportExcel" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    {{ __('reports.export_excel') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.total_days') }}</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $summary['total_days'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.present') }}</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $summary['present'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.late') }}</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $summary['late'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.absent') }}</h3>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $summary['absent'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.total_hours') }}</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $summary['total_hours'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('reports.avg_late_min') }}</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $summary['average_minutes_late'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('reports.date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('reports.employee') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('reports.check_in') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('reports.check_out') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('reports.hours') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.status') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                <tr>
                    @php
                        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        $mes = $meses[$attendance->date->month - 1];
                        $fechaFormateada = $mes . ' ' . $attendance->date->format('d, Y');
                    @endphp
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $fechaFormateada }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @php
                            $timezoneService = app(\App\Services\TimezoneService::class);
                            $company = auth()->user()->company;
                        @endphp
                        {{ $timezoneService->formatForCompany($company, $attendance->check_in_at, 'H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $timezoneService->formatForCompany($company, $attendance->check_out_at, 'H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $attendance->total_minutes_worked ? round($attendance->total_minutes_worked / 60, 2) : '-' }}h
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded
                            @if($attendance->status === 'late') bg-yellow-100 text-yellow-800
                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                            @else bg-green-100 text-green-800
                            @endif">
                            @if($attendance->status === 'late')
                                {{ __('reports.late') }}
                            @elseif($attendance->status === 'absent')
                                {{ __('reports.absent') }}
                            @else
                                {{ __('reports.present') }}
                            @endif
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">{{ __('reports.no_records') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

