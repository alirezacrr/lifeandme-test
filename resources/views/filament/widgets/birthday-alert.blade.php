<x-filament-widgets::widget>
    @if($isTodayBirthday)
        <x-filament::section>
            <div
                class=" p-2 rounded-xl shadow-sm flex justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎂</span>
                    <div>
                        <div class="font-bold text-base mb-1">تولدت مبارک {{ Auth::user()->name }} جان! 🥳</div>
                        <div class="text-sm mt-1">
                            امروز روز خاص توئه، برات یه عالمه شادی و موفقیت آرزو داریم ❤️
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>
    @elseif($notification)
        <x-filament::section>
            <div
                class="p-2 rounded-xl shadow-sm flex justify-between items-start gap-4">
                <div class="flex items-start gap-3">
                    <div>
                        <div class="font-bold text-base mb-4">{{ $notification->title }}</div>
                        <div class="text-sm mt-1 leading-relaxed">{{ $notification->description }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('filament.notifications.dismiss', $notification->id) }}">
                    @csrf
                    <button type="submit" title="بستن"
                            class="text-red-600 hover:text-red-800 transition-colors">
                        ❌
                    </button>
                </form>
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
