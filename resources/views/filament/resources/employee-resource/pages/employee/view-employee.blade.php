<x-filament-panels::page>

    {{ $this->infolist }}

    <div class="flex justify-end">
        {{ $this->createBenefitAction }}
    </div>

    {{ $this->table }}

    <x-filament-actions::modals />
</x-filament-panels::page>
