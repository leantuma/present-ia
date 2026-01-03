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
            <p><span class="font-medium">Check In:</span> {{ $todayAttendance->check_in_at->format('H:i:s') }}</p>
            @endif
            @if($todayAttendance->check_out_at)
            <p><span class="font-medium">Check Out:</span> {{ $todayAttendance->check_out_at->format('H:i:s') }}</p>
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

