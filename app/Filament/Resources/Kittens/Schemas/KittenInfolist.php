<?php

namespace App\Filament\Resources\Kittens\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class KittenInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Котёнок')
                    ->schema([
                        Grid::make(['default' => 1, 'lg' => 3])
                            ->schema([
                                ImageEntry::make('cover_image')
                                    ->label('Главное фото')
                                    ->disk('public')
                                    ->imageSize(240)
                                    ->square()
                                    ->placeholder('Фото нет'),
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label('Статус')
                                            ->badge()
                                            ->color(fn (?string $state): string => match ($state) {
                                                'available' => 'success',
                                                'reserved' => 'info',
                                                'sold' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                'available' => 'Свободен',
                                                'reserved' => 'Бронь',
                                                'sold' => 'Продан',
                                                default => 'Не указан',
                                            }),
                                        IconEntry::make('is_visible')
                                            ->label('Опубликован на сайте')
                                            ->boolean(),
                                        TextEntry::make('litter.title')
                                            ->label('Помёт')
                                            ->placeholder('Без помёта'),
                                        TextEntry::make('born_on')
                                            ->label('Дата рождения')
                                            ->date('d.m.Y')
                                            ->placeholder('Не указана'),
                                        TextEntry::make('sex')
                                            ->label('Пол')
                                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                'male' => 'Мальчик',
                                                'female' => 'Девочка',
                                                default => 'Не указан',
                                            }),
                                        TextEntry::make('color')
                                            ->label('Окрас')
                                            ->placeholder('Не указан'),
                                        TextEntry::make('price')
                                            ->label('Цена')
                                            ->money('RUB', locale: 'ru', decimalPlaces: 0)
                                            ->placeholder('По запросу'),
                                    ])
                                    ->columnSpan(['lg' => 2]),
                            ]),
                    ])
                    ->columnSpanFull(),
                Tabs::make('Материалы')
                    ->tabs([
                        Tab::make('Описание')
                            ->schema([
                                TextEntry::make('description')
                                    ->label('Кратко')
                                    ->prose()
                                    ->placeholder('Краткое описание не заполнено')
                                    ->columnSpanFull(),
                                TextEntry::make('content')
                                    ->label('Полное описание')
                                    ->html()
                                    ->prose()
                                    ->placeholder('Полное описание не заполнено')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Фотографии')
                            ->schema([
                                ImageEntry::make('images')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->imageHeight(180)
                                    ->wrap()
                                    ->placeholder('Фотографий нет')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
