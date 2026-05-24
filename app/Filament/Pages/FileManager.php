<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class FileManager extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::FolderOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Файлы';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Файлы';

    protected static ?string $title = 'Файлы';

    protected string $view = 'filament.pages.file-manager';

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $uploads = [];

    public function upload(): void
    {
        $this->validate([
            'uploads' => ['required', 'array'],
            'uploads.*' => ['file', 'max:20480'],
        ]);

        foreach ($this->uploads as $upload) {
            if (! $upload instanceof TemporaryUploadedFile) {
                continue;
            }

            $upload->storeAs('media/files', $this->safeFilename($upload->getClientOriginalName()), 'public');
        }

        $this->uploads = [];

        Notification::make()
            ->title('Файлы загружены')
            ->success()
            ->send();
    }

    public function deleteFile(string $path): void
    {
        if (! str_starts_with($path, 'media/files/')) {
            abort(403);
        }

        Storage::disk('public')->delete($path);

        Notification::make()
            ->title('Файл удален')
            ->success()
            ->send();
    }

    public function getFiles(): array
    {
        return collect(Storage::disk('public')->files('media/files'))
            ->map(fn (string $path): array => [
                'path' => $path,
                'name' => basename($path),
                'url' => Storage::disk('public')->url($path),
                'size' => Storage::disk('public')->size($path),
                'updated_at' => Storage::disk('public')->lastModified($path),
                'is_image' => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true),
            ])
            ->sortByDesc('updated_at')
            ->values()
            ->all();
    }

    private function safeFilename(string $originalName): string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $slug = Str::slug($name) ?: 'file';

        return now()->format('His').'-'.$slug.($extension !== '' ? ".{$extension}" : '');
    }
}
