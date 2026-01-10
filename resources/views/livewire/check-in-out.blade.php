<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Check In / Check Out</h2>

    @if($errorMessage)
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
        {{ $errorMessage }}
    </div>
    @endif

    @if($successMessage)
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
        {{ $successMessage }}
    </div>
    @endif

    @if($todayAttendance)
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <h3 class="font-semibold text-gray-700 mb-2">Today's Attendance</h3>
        <div class="space-y-2 text-sm">
            @if($todayAttendance->check_in_at)
            @php
                $timezoneService = app(\App\Services\TimezoneService::class);
                $company = auth()->user()->company;
            @endphp
            <p><span class="font-medium">Check In:</span> {{ $timezoneService->formatForCompany($company, $todayAttendance->check_in_at, 'H:i:s') }}</p>
            @endif
            @if($todayAttendance->check_out_at)
            <p><span class="font-medium">Check Out:</span> {{ $timezoneService->formatForCompany($company, $todayAttendance->check_out_at, 'H:i:s') }}</p>
            @endif
            <p><span class="font-medium">Status:</span> 
                <span class="px-2 py-1 rounded text-xs 
                    @if($todayAttendance->status === 'late') bg-yellow-100 text-yellow-800
                    @elseif($todayAttendance->status === 'absent') bg-red-100 text-red-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($todayAttendance->status) }}
                </span>
            </p>
        </div>
    </div>
    @endif

    <!-- Weekly Calendar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Calendario Semanal</h3>
            <div class="flex items-center space-x-2">
                <button wire:click="previousWeek" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                    ← Anterior
                </button>
                <button wire:click="goToCurrentWeek" class="px-3 py-1 text-sm bg-primary text-white rounded hover:bg-indigo-700">
                    Semana Actual
                </button>
                <button wire:click="nextWeek" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                    Siguiente →
                </button>
            </div>
        </div>
        
        @if(isset($weeklyCalendar['week_start']) && isset($weeklyCalendar['week_end']))
        <p class="text-sm text-gray-600 mb-4">
            Semana: {{ $weeklyCalendar['week_start']->format('d M') }} - {{ $weeklyCalendar['week_end']->format('d M Y') }}
        </p>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Entrada</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Salida</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($weeklyCalendar['days']))
                        @php
                            $dayNames = [
                                'monday' => 'Lunes',
                                'tuesday' => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday' => 'Jueves',
                                'friday' => 'Viernes',
                                'saturday' => 'Sábado',
                                'sunday' => 'Domingo'
                            ];
                        @endphp
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            @php
                                $dayData = $weeklyCalendar['days'][$day] ?? null;
                            @endphp
                            @if($dayData)
                            <tr class="{{ $dayData['date']->isToday() ? 'bg-blue-50' : '' }}">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $dayNames[$day] }}<br>
                                    <span class="text-xs text-gray-500">{{ $dayData['date']->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    @if($dayData['check_in'])
                                        <span class="font-semibold text-green-700">{{ $dayData['check_in'] }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    @if($dayData['check_out'])
                                        <span class="font-semibold text-red-700">{{ $dayData['check_out'] }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($dayData['status'] === 'present')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            Presente
                                        </span>
                                    @elseif($dayData['status'] === 'late')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            Tarde
                                        </span>
                                    @elseif($dayData['status'] === 'absent')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                            Ausente
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                            Sin registro
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                No hay datos disponibles
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
            <div class="text-sm text-gray-600">
                <p>Lat: <span id="lat-display">{{ $latitude ?? 'Getting...' }}</span></p>
                <p>Lng: <span id="lng-display">{{ $longitude ?? 'Getting...' }}</span></p>
            </div>
            <button type="button" onclick="getLocation()" class="mt-2 text-sm text-primary hover:text-indigo-700">
                Refresh Location
            </button>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Photo (Optional)</label>
            <input type="file" wire:model="photo" accept="image/*" capture="environment" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-indigo-700">
        </div>

        <div class="flex space-x-4">
            @if($canCheckIn)
            <button wire:click="checkIn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                Check In
            </button>
            @endif

            @if($canCheckOut)
            <button wire:click="checkOut" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg">
                Check Out
            </button>
            @endif
        </div>
    </div>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.setLocation(position.coords.latitude, position.coords.longitude);
                    document.getElementById('lat-display').textContent = position.coords.latitude.toFixed(6);
                    document.getElementById('lng-display').textContent = position.coords.longitude.toFixed(6);
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    // Get location on page load
    window.addEventListener('load', function() {
        getLocation();
    });

    // Listen for Livewire location request
    Livewire.on('request-location', () => {
        getLocation();
    });
</script>

