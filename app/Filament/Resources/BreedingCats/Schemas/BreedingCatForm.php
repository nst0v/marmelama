<?php

namespace App\Filament\Resources\BreedingCats\Schemas;

use App\Models\BreedingCat;
use App\Support\BurmeseColors;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BreedingCatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Производитель')
                    ->description('Имя, пол, активность, окрас, дата рождения, родители, заводчик и владелец.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Имя')
                                ->required()
                                ->maxLength(255),
                            Hidden::make('slug')
                                ->dehydrateStateUsing(fn (?string $state, Get $get): string => filled($state)
                                    ? $state
                                    : self::uniqueSlug((string) $get('name'))),
                            Select::make('sex')
                                ->label('Пол')
                                ->options([
                                    'male' => 'Кот',
                                    'female' => 'Кошка',
                                ])
                                ->required(),
                            Toggle::make('is_active')
                                ->label('В племенной работе')
                                ->default(true)
                                ->required(),
                            Toggle::make('is_visible')
                                ->label('Показывать на сайте')
                                ->default(false)
                                ->required(),
                            TextInput::make('title')
                                ->label('Титулы / награды'),
                            Select::make('color')
                                ->label('Окрас')
                                ->options(fn ($record = null): array => BurmeseColors::forSelect($record?->color))
                                ->searchable()
                                ->native(false),
                            DatePicker::make('birthday')
                                ->label('Дата рождения'),
                            TextInput::make('father_name')
                                ->label('Отец'),
                            TextInput::make('mother_name')
                                ->label('Мать'),
                            TextInput::make('breeder')
                                ->label('Заводчик'),
                            TextInput::make('owner')
                                ->label('Владелец'),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('genetic_tests')
                            ->label('Генетические тесты')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Краткое описание')
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Полное описание')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Фотографии')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Фото')
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxFiles(12)
                            ->maxSize(10240)
                            ->directory('media/parents')
                            ->helperText('JPG, PNG или WebP, до 10 МБ. Первое фото будет обложкой.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function uniqueSlug(string $name): string
    {
        $base = Str::substr(Str::slug($name), 0, 240);
        $base = $base !== '' ? $base : 'proizvoditel';
        $slug = $base;
        $suffix = 2;

        while (BreedingCat::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
