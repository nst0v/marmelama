<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Загрузка файлов</x-slot>
        <x-slot name="description">Загрузка файлов для вставки ссылок на страницы сайта.</x-slot>

        <form wire:submit="upload" class="space-y-4">
            <label class="block">
                <span class="text-sm font-medium text-gray-950 dark:text-white">Выберите один или несколько файлов</span>
                <input
                    type="file"
                    wire:model="uploads"
                    multiple
                    class="mt-2 block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-950 file:me-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium dark:border-white/10 dark:bg-white/5 dark:text-white dark:file:bg-white/10"
                >
            </label>

            @error('uploads.*')
                <p class="text-sm text-danger-600">{{ $message }}</p>
            @enderror

            <x-filament::button type="submit" wire:loading.attr="disabled">
                Загрузить
            </x-filament::button>
        </form>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Загруженные файлы</x-slot>
        <x-slot name="description">Адрес для вставки на сайт выглядит как /storage/media/files/имя-файла.</x-slot>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($this->getFiles() as $file)
                <article class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="mb-3 flex h-32 items-center justify-center overflow-hidden rounded-lg bg-gray-50 dark:bg-white/5">
                        @if($file['is_image'])
                            <img src="{{ $file['url'] }}" alt="" class="h-full w-full object-cover">
                        @else
                            <span class="text-sm font-medium text-gray-500">Файл</span>
                        @endif
                    </div>

                    <h3 class="truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $file['name'] }}</h3>
                    <input
                        readonly
                        value="/storage/{{ $file['path'] }}"
                        class="mt-2 w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-700 dark:border-white/10 dark:bg-white/5 dark:text-gray-200"
                    >

                    <div class="mt-3 flex items-center justify-between gap-3">
                        <a href="{{ $file['url'] }}" target="_blank" class="text-sm font-medium text-primary-600 hover:underline">
                            Открыть
                        </a>
                        <x-filament::button
                            color="danger"
                            size="sm"
                            wire:click="deleteFile('{{ $file['path'] }}')"
                            wire:confirm="Удалить файл?"
                        >
                            Удалить
                        </x-filament::button>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-500 dark:border-white/10">
                    Пока нет загруженных файлов.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>
