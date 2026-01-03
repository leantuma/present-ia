<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <!-- Today's Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Total Employees</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $todayStats['total_employees'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Checked In</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $todayStats['checked_in'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Late</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $todayStats['late'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">Absent</h3>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $todayStats['absent'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Alerts -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Alerts</h2>
            @if(count($recentAlerts) > 0)
            <div class="space-y-3">
                @foreach($recentAlerts as $alert)
                <div class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100" wire:click="markAlertAsRead({{ $alert->id }})">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $alert->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $alert->message }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs rounded
                                @if($alert->severity === 'high') bg-red-100 text-red-800
                                @elseif($alert->severity === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($alert->severity) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">No alerts</p>
            @endif
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Attendances</h2>
            @if(count($recentAttendances) > 0)
            <div class="space-y-3">
                @foreach($recentAttendances as $attendance)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $attendance->user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $attendance->date->format('M d, Y') }}</p>
                            @if($attendance->check_in_at)
                            <p class="text-xs text-gray-500">In: {{ $attendance->check_in_at->format('H:i') }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs rounded
                            @if($attendance->status === 'late') bg-yellow-100 text-yellow-800
                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">No recent attendances</p>
            @endif
        </div>
    </div>

    <!-- Weekly Summary (for admins/supervisors) -->
    @if(isset($weeklySummary) && !empty($weeklySummary))
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Weekly Summary</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Check Ins</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $weeklySummary['overview']['total_check_ins'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Late</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $weeklySummary['overview']['total_late'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Absent</h3>
                <p class="text-2xl font-bold text-red-600">{{ $weeklySummary['overview']['total_absent'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Hours Worked</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $weeklySummary['overview']['total_hours_worked'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

