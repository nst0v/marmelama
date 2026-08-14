<?php

namespace App\Filament\Resources\Kittens\Schemas;

use App\Models\Kitten;
use App\Models\Litter;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KittenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Котёнок')
                    ->description('Основная информация, которую увидят посетители сайта.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('litter_id')
                                ->label('Помёт')
                                ->relationship('litter', 'title')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->default(fn ($livewire): ?int => self::relationLitter($livewire)?->getKey())
                                ->disabled(fn ($livewire): bool => self::relationLitter($livewire) !== null)
                                ->dehydrated()
                                ->afterStateUpdated(function (Get $get, Set $set, int|string|null $state): void {
                                    if (($state === null) || filled($get('born_on'))) {
                                        return;
                                    }

                                    $bornOn = Litter::query()->whereKey($state)->value('born_on');

                                    if ($bornOn !== null) {
                                        $set('born_on', $bornOn);
                                    }
                                }),
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
                                    'male' => 'Мальчик',
                                    'female' => 'Девочка',
                                    'unknown' => 'Не указан',
                                ])
                                ->required()
                                ->placeholder('Выберите пол'),
                            Select::make('color')
                                ->label('Окрас')
                                ->options(fn ($record = null): array => BurmeseColors::forSelect($record?->color))
                                ->searchable()
                                ->native(false),
                            DatePicker::make('born_on')
                                ->label('Дата рождения')
                                ->default(fn ($livewire): ?string => self::relationLitter($livewire)?->born_on?->format('Y-m-d')),
                            Select::make('status')
                                ->label('Статус')
                                ->options([
                                    'available' => 'Свободен',
                                    'reserved' => 'Бронь',
                                    'sold' => 'Продан',
                                ])
                                ->required()
                                ->default('available'),
                            TextInput::make('price')
                                ->label('Цена, ₽')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(99999999.99)
                                ->suffix('₽')
                                ->helperText('Оставьте пустым, если цена сообщается по запросу.'),
                            Toggle::make('is_visible')
                                ->label('Опубликован на сайте')
                                ->default(false)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('description')
                            ->label('Коротко о котёнке')
                            ->helperText('Показывается в каталоге. Достаточно 1–2 предложений.')
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Характер и особенности')
                            ->helperText('Подробный текст для страницы котёнка.')
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
                            ->directory('media/kittens')
                            ->helperText('JPG, PNG или WebP, до 10 МБ. Первое фото будет обложкой.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function relationLitter(mixed $livewire): ?Litter
    {
        if (! is_object($livewire) || ! method_exists($livewire, 'getOwnerRecord')) {
            return null;
        }

        $ownerRecord = $livewire->getOwnerRecord();

        return $ownerRecord instanceof Litter ? $ownerRecord : null;
    }

    private static function uniqueSlug(string $name): string
    {
        $base = Str::substr(Str::slug($name), 0, 240);
        $base = $base !== '' ? $base : 'kotenok';
        $slug = $base;
        $suffix = 2;

        while (Kitten::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
