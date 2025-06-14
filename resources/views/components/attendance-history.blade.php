@props(['history'])
<div class="flex justify-between items-center p-3 bg-gray-100 rounded-lg mb-2 border border-gray-200">
    <div class="flex items-center gap-3">
        <img src="https://api.dicebear.com/9.x/lorelei/svg?glasses=variant01,variant02,variant03&seed=Ahmad Rivaldi"
            alt="Profile Picture" class="w-10 h-10 rounded-full border border-gray-200 bg-gray-100 object-cover">
        <div>
            <p class="text-sm font-medium text-gray-700">{{ $history->employee->name }}</p>
            <p class="text-xs text-gray-500">{{ $history->employee->formatted_position }}</p>
        </div>
    </div>
    <div class="text-right">
        <p class="text-xs text-gray-500 mb-1">{{ $history->created_at }}</p>
        @if ($history->isOnTime())
            <span
                class="bg-green-100 text-green-800 py-1 px-2 rounded-full border-green-500 border font-medium text-xs inline-flex items-center gap-1">
                <x-heroicon-o-star class="w-4 h-4 inline m-0" />
                On Time
            </span>
        @elseif($history->isEarly())
            <span
                class="bg-yellow-100 text-yellow-800 py-1 px-2 rounded-full border-yellow-500 border font-medium text-xs inline-flex items-center gap-1">
                <x-heroicon-o-check-badge class="w-4 h-4 inline m-0" />
                Early
            </span>
        @elseif($history->isLate())
            <span
                class="bg-red-100 text-red-800 py-1 px-2 rounded-full border-red-500 border font-medium text-xs inline-flex items-center gap-1">
                <x-heroicon-o-exclamation-circle class="w-4 h-4 inline m-0" />
                {{ $history->late_in_human }} Late
            </span>
        @endif
    </div>
</div>
