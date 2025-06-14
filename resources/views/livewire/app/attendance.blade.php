<div class="bg-gray-100 min-h-screen flex justify-center items-center">
    @livewire('notifications')
    <div class="grid md:grid-cols-1 lg:grid-cols-12 mx-2 sm:mx-5 gap-4 w-full mt-5">
        @if (session()->has('success'))
        <div class="lg:col-span-12 col-span-12 mb-1">
            <div class="bg-green-100 border-t-4 border-green-500 rounded-lg text-green-900 px-4 py-3 shadow-md"
                role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg></div>
                    <div>
                        <p class="font-bold">You have successfully checked in</p>
                        <p class="text-sm">
                            {!! session('success') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="lg:col-span-8 col-span-12">
            <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6 h-full flex flex-col justify-center">
                <!-- BEGIN TOP--->
                <div class="flex flex-col items-center mb-6">
                    <img src="https://api.dicebear.com/9.x/lorelei/svg?glasses=variant01,variant02,variant03&seed=Ahmad Rivaldi"
                        alt="Profile Picture"
                        class="w-20 h-20 rounded-full border border-gray-200 bg-gray-100 mb-4 object-cover">

                    <p class="text-gray-500 text-center text-sm">
                        Please scan your card for attendance. Ensure the card is placed properly on the RFID reader
                    </p>
                </div>
                <!-- END TOP--->
                <div class="p-3 bg-gray-100 border border-gray-200 rounded-lg h-100 flex items-center justify-center">
                    @if (!$attendance)
                        <div class="flex flex-col items-center justify-center my-12">
                            <div class="relative w-20 h-20 mb-4 text-center">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <img src="/contactless_card.png" alt="Contactless Card" class="w-25 h-25">
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 italic">Put your card into RFID reader</p>
                        </div>
                    @endif

                    @if ($attendance)
                        <div class="flex flex-col gap-6 p-3 w-full">
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Name</span>
                                <span class="font-medium">{{ $attendance->employee->name }}</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Department / Position</span>
                                <span class="font-medium">{{ $attendance->employee->formatted_position }}</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Attendance Time</span>
                                <span class="font-medium">{{ $attendance->created_at }}</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="font-bold">Attendance Status</span>
                                <span class="font-medium">
                                    @if ($attendance->isOnTime())
                                        <span
                                            class="bg-green-100 text-green-800 py-1 px-2 rounded-full border-green-500 border font-medium text-xs inline-flex items-center gap-1">
                                            <x-heroicon-o-star class="w-4 h-4 inline m-0" />
                                            On Time
                                        </span>
                                    @endif
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
                        x-on:click=""
                        class="cursor-pointer bg-gray-100 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 text-xs sm:text-sm">
                        <x-heroicon-o-paper-airplane class="w-4 h-4 inline" />
                        Submit Attendance
                    </button>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12 flex items-center justify-center w-full h-[50rem]">
            <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6 h-full w-full overflow-hidden">
                <h2 class="text-lg font-semibold text-gray-700">Attendance Histories</h2>
                <small class="text-gray-500">
                    Showing last 5 data
                </small>
                <hr class="border-gray-200 mb-4 mt-2">
                <div class="flex flex-col overflow-y-auto max-h-[50rem]">
                    @foreach ($histories as $history)
                        <x-attendance-history :history="$history" />
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
