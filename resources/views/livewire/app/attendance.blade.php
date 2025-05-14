<div class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="grid md:grid-cols-1 lg:grid-cols-12 mx-2 sm:mx-5 gap-4 w-full mt-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6 h-full flex flex-col justify-center">
                <!-- BEGIN TOP--->
                <div class="flex flex-col items-center mb-6">
                    <img src="https://api.dicebear.com/9.x/lorelei/svg?glasses=variant01,variant02,variant03&seed=Ahmad Rivaldi"
                        alt="Profile Picture"
                        class="w-30 h-30 rounded-full border border-gray-200 bg-gray-100 mb-4 object-cover">

                    <p class="text-gray-500 text-center text-sm">
                        Please scan your card for attendance. Ensure the card is placed properly on the RFID reader
                    </p>
                </div>
                <!-- END TOP--->
                <div class="p-3 bg-gray-100 border border-gray-200 rounded-lg h-100 flex items-center justify-center">
                    @if (!$scanned)
                        <div class="flex flex-col items-center justify-center my-12">
                            <div class="relative w-20 h-20 mb-4 text-center">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <img src="/contactless_card.png" alt="Contactless Card" class="w-25 h-25">
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 italic">Put your card into RFID reader</p>
                        </div>
                    @endif

                    @if ($scanned)
                        <div class="flex flex-col gap-6 p-3 w-full">
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Name</span>
                                <span class="font-medium">Ahmad Rivaldi</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Department / Position</span>
                                <span class="font-medium">IT / Web Developer</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Attendance Time</span>
                                <span class="font-medium">{{ now() }}</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Attendance Status</span>
                                <span class="font-medium">
                                    <span
                                        class="bg-green-100 text-green-800 py-1 px-2 rounded-full border-green-500 border font-medium text-xs inline-flex items-center gap-1">
                                        <x-heroicon-o-star class="w-4 h-4 inline m-0" />
                                        On Time
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Score</span>
                                <span class="font-medium">
                                    <span
                                        class="bg-green-100 text-green-800 py-1 flex gap-1 px-3 rounded-full border-green-500 border font-medium text-xs">
                                        <x-heroicon-o-check-badge class="w-4 h-4 inline m-0" />
                                        Good
                                    </span>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-2 flex gap-2 inline-flex" x-data="{ manualMode: false }">
                    <input id="manualId" x-bind:disabled="!manualMode" type="number"
                        class="disabled:bg-gray-100 disabled:cursor-not-allowed border max-w-[15rem] border-gray-200 px-4 py-2 rounded-lg text-xs sm:text-sm w-full"
                        placeholder="Enter your ID">
                    <button x-show="!manualMode" @click="manualMode = true"
                        class="cursor-pointer bg-gray-100 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 text-xs sm:text-sm">
                        <x-heroicon-o-pencil-square class="w-4 h-4 inline" />
                        Manual Attendance
                    </button>
                    <button @click="manualMode = false" x-show="manualMode"
                        class="cursor-pointer border border-gray-100 px-4 py-2 rounded-lg hover:bg-gray-50 text-xs sm:text-sm">
                        <x-heroicon-o-x-circle class="w-4 h-4 inline" />
                        Cancel
                    </button>
                    <button x-show="manualMode"
                        class="cursor-pointer bg-gray-100 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 text-xs sm:text-sm">
                        <x-heroicon-o-paper-airplane class="w-4 h-4 inline" />
                        Submit Attendance
                    </button>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12 flex items-center justify-center col-span-2 w-full h-[50rem]">
            <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6 h-full w-full overflow-hidden">
                <h2 class="text-lg font-semibold text-gray-700">Attendance Histories</h2>
                <small class="text-gray-500">
                    Showing last 5 data
                </small>
                <hr class="border-gray-200 mb-4 mt-2">
                <div class="flex flex-col overflow-y-auto max-h-[50rem]">
                    @foreach ($histories as $history)
                        <div
                            class="flex justify-between items-center p-3 bg-gray-100 rounded-lg mb-2 border border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://api.dicebear.com/9.x/lorelei/svg?glasses=variant01,variant02,variant03&seed=Ahmad Rivaldi"
                                    alt="Profile Picture"
                                    class="w-10 h-10 rounded-full border border-gray-200 bg-gray-100 object-cover">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $history['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $history['department'] }} /
                                        {{ $history['position'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $history['attendance_time'] }}</p>
                                @if ($history['status'] === 'on_time')
                                    <span
                                        class="bg-green-100 text-green-800 py-1 px-2 rounded-full border-green-500 border font-medium text-xs inline-flex items-center gap-1">
                                        <x-heroicon-o-star class="w-4 h-4 inline m-0" />
                                        On Time
                                    </span>
                                @elseif($history['status'] === 'early')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 py-1 px-2 rounded-full border-yellow-500 border font-medium text-xs inline-flex items-center gap-1">
                                        <x-heroicon-o-check-badge class="w-4 h-4 inline m-0" />
                                        {{ $history['status_label'] ?? 'Early' }}
                                    </span>
                                @elseif($history['status'] === 'late')
                                    <span
                                        class="bg-red-100 text-red-800 py-1 px-2 rounded-full border-red-500 border font-medium text-xs inline-flex items-center gap-1">
                                        <x-heroicon-o-exclamation-circle class="w-4 h-4 inline m-0" />
                                        {{ $history['status_label'] ?? 'Late' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ rfid: '', lastScan: '', timeout: null }" x-init="window.addEventListener('keypress', (e) => {
        const manualInput = document.getElementById('manualId');
        if (manualInput && manualInput === document.activeElement) {
            return; // Do nothing if the manual input is focused
        }
        if (timeout) clearTimeout(timeout);
        if (e.key !== 'Enter') {
            rfid += e.key;
        }

        timeout = setTimeout(() => {
            lastScan = rfid;
            $wire.scan(rfid);
            {{-- new Audio('/scanned.mp3').play(); --}}
            rfid = '';
        }, 300);
    });" class="hidden">
        <input type="text" x-model="lastScan" class="hidden">
    </div>
</div>
