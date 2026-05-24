<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use App\Models\GalleryImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Фото галереи')
                    ->description('Категория, название, описание картинки, изображение и приоритет.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('gallery_category_id')
                                ->label('Категория')
                                ->relationship('galleryCategory', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('category')
                                ->label('Категория текстом')
                                ->options(fn ($record = null): array => self::legacyCategoryOptions($record?->category))
                                ->searchable()
                                ->native(false)
                                ->helperText('Лучше выбирать категорию выше, это поле оставлено для редких ручных случаев.'),
                            TextInput::make('title')
                                ->label('Название'),
                            TextInput::make('alt')
                                ->label('Описание картинки для поисковиков')
                                ->columnSpanFull(),
                            FileUpload::make('image_path')
                                ->label('Изображение')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/gallery')
                                ->image()
                                ->required()
                                ->columnSpanFull(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_visible')
                                ->label('Показывать')
                                ->default(true)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function legacyCategoryOptions(?string $currentValue): array
    {
        $options = GalleryImage::query()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category', 'category')
            ->all();

        $options = [
            'Галерея' => 'Галерея',
            'slider' => 'Слайдер',
        ] + $options;

        $currentValue = trim((string) $currentValue);

        if ($currentValue !== '' && ! array_key_exists($currentValue, $options)) {
            $options[$currentValue] = "Текущее значение: {$currentValue}";
        }

        return $options;
    }
}
