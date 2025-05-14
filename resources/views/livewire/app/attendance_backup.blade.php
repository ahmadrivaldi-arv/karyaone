{{-- <div
    class="grid grid-cols-1 lg:grid-cols-12 min-h-screen bg-gradient-to-br from-yellow-100 to-yellow-300 px-5 py-10 gap-6">

    <!-- PRESENSI CARD -->
    <div class="col-span-1 lg:col-span-8 bg-white shadow-xl w-full  p-8 rounded flex">
        <div class="flex flex-col items-center w-full justify-center max-h-lg">
            <!-- Icon/Illustration -->
            @if ($scanned)
                <img src="https://api.dicebear.com/9.x/adventurer/svg?seed=Adrian" alt="Profile Picture"
                    class="w-28 h-28 rounded-full border-4 border-blue-500 shadow mb-4">
            @else
                <img src="https://ui-avatars.com/api/?name=Ahmad Rivaldi&background=fff" alt="Profile Picture"
                    class="w-28 h-28 object-cover rounded-full border-2 border-blue-500 shadow mb-4 mx-auto">
            @endif

            <!-- Message -->
            <h1 class="text-2xl font-bold text-blue-700 mb-1">Selamat Datang!</h1>
            <p class="text-gray-600 text-lg mb-6 text-center">
                Silakan tempelkan kartu RFID anda untuk melakukan presensi.
            </p>


            @if ($scanned)
                <!-- User Info -->
                <div class="text-center mb-6 transition-all duration-700 ease-in-out transform hover:scale-105">
                    <h2 class="text-3xl font-bold text-gray-800">Ahmad Rivaldi</h2>
                    <p class="text-gray-500 text-lg">IT / Developer</p>
                    <p class="text-gray-500 text-lg">RFID: {{ $rfid }}</p>
                </div>

                <!-- Attendance Status -->
                <div
                    class="w-full space-y-3 text-sm text-gray-700 transition-opacity duration-700 ease-in-out opacity-100">
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <div>
                            <span class="font-medium text-xl">Status Presensi:</span>
                            <div class="sm:hidden mt-1 ">
                                <span
                                    class="bg-green-100 text-green-800 px-3 py-1 rounded font-medium text-xl">Hadir</span>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-medium text-xl">Hadir</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <div>
                            <span class="font-medium text-xl">Keterlambatan:</span>
                            <div class="sm:hidden mt-1">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded font-medium text-xl">Tidak</span>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded font-medium text-xl">Tidak</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <div>
                            <span class="font-medium text-xl">Jam Presensi:</span>
                            <div class="sm:hidden mt-1">
                                <span class="font-semibold text-gray-900 text-xl">{{ now()->format('H:i:s A') }}</span>
                            </div>
                        </div>
                        <div class="hidden sm:block">
                            <span class="font-semibold text-gray-900 text-xl">{{ now()->format('H:i:s A') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="h-[300px] items-center flex justify-center border-gray-500 border-2 border-dashed animate-pulse w-full ">
                    Silahkan tempelkan kartu RFID anda
                </div>
            @endif
        </div>
    </div>

    <!-- RIWAYAT PRESENSI CARD -->
    <div class="col-span-1 lg:col-span-4 bg-white shadow-xl w-full  p-6 rounded">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-300">Today Histories</h2>

        <div class="space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-300 pb-2">
                <img src="https://ui-avatars.com/api/?name=Ahmad Rivaldi&background=eee" alt="Avatar"
                    class="w-10 h-10 rounded-full border">
                <div class="flex-1">
                    <p class="font-medium text-gray-800 text-sm">Ahmad Rivaldi</p>
                    <p class="text-sm text-gray-500">IT / Web Developer</p>
                    <p class="text-sm text-gray-400 mt-1">Jam: {{ now() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 border-b border-gray-300 pb-2">
                <img src="https://ui-avatars.com/api/?name=Roni Abdul Hamid&background=eee" alt="Avatar"
                    class="w-10 h-10 rounded-full border">
                <div class="flex-1">
                    <p class="font-medium text-gray-800 text-sm">Roni Abdul Hamid</p>
                    <p class="text-sm text-gray-500">IT / Web Development</p>
                    <p class="text-sm text-gray-400 mt-1">Jam: -:-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RFID SCAN HANDLER -->
    <div x-data="{ rfid: '', lastScan: '', timeout: null }" x-init="window.addEventListener('keypress', (e) => {
        if (timeout) clearTimeout(timeout);
        if (e.key !== 'Enter') {
            rfid += e.key;
        }

        timeout = setTimeout(() => {
            lastScan = rfid;
            $wire.scan(rfid);
            new Audio('/scanned.mp3').play();
            rfid = '';
        }, 300);
    });" class="hidden">
        <input type="text" x-model="lastScan" class="hidden">
    </div>
</div>

@section('page-scripts')

@endsection --}}
