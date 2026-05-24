<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Слайд на главной')
                    ->description('Слайдер главной страницы: название, ссылка, подпись, описание картинки и приоритет.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Название слайда')
                                ->maxLength(200)
                                ->columnSpan(1),
                            Select::make('placement')
                                ->label('Страница')
                                ->options(fn ($record = null): array => self::withCurrent([
                                    'home' => 'Главная страница',
                                ], $record?->placement))
                                ->searchable()
                                ->native(false)
                                ->default('home')
                                ->helperText('Значение home означает главную страницу.')
                                ->columnSpan(1),
                            TextInput::make('url')
                                ->label('Ссылка')
                                ->maxLength(200)
                                ->columnSpanFull(),
                            Textarea::make('caption')
                                ->label('Блок описания')
                                ->rows(4)
                                ->columnSpanFull(),
                            TextInput::make('alt')
                                ->label('Описание картинки для поисковиков')
                                ->maxLength(200)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Изображение и публикация')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('media/slides')
                            ->image()
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_visible')
                                ->label('Показывать на сайте')
                                ->default(true)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function withCurrent(array $options, ?string $currentValue): array
    {
        $currentValue = trim((string) $currentValue);

        if ($currentValue !== '' && ! array_key_exists($currentValue, $options)) {
            $options[$currentValue] = "Текущее значение: {$currentValue}";
        }

        return $options;
    }
}
