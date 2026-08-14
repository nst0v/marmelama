<?php

namespace App\Filament\Resources\BreedingCats\Schemas;

use App\Models\BreedingCat;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class BreedingCatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Производитель')
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
                                        TextEntry::make('sex')
                                            ->label('Пол')
                                            ->badge()
                                            ->color(fn (?string $state): string => match ($state) {
                                                'male' => 'info',
                                                'female' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                'male' => 'Кот',
                                                'female' => 'Кошка',
                                                default => 'Не указан',
                                            }),
                                        IconEntry::make('is_visible')
                                            ->label('Опубликован на сайте')
                                            ->boolean(),
                                        IconEntry::make('is_active')
                                            ->label('В племенной работе')
                                            ->boolean(),
                                        TextEntry::make('color')
                                            ->label('Окрас')
                                            ->placeholder('Не указан'),
                                        TextEntry::make('birthday')
                                            ->label('Дата рождения')
                                            ->date('d.m.Y')
                                            ->placeholder('Не указана'),
                                        TextEntry::make('title')
                                            ->label('Титулы и награды')
                                            ->placeholder('Не указаны'),
                                        TextEntry::make('litters_count')
                                            ->label('Связанных помётов')
                                            ->state(fn (BreedingCat $record): int => match ($record->sex) {
                                                'male' => $record->fatherLitters()->count(),
                                                'female' => $record->motherLitters()->count(),
                                                default => $record->fatherLitters()->count() + $record->motherLitters()->count(),
                                            })
                                            ->badge(),
                                    ])
                                    ->columnSpan(['lg' => 2]),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Родословная и здоровье')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('father_name')->label('Отец')->placeholder('Не указан'),
                                TextEntry::make('mother_name')->label('Мать')->placeholder('Не указана'),
                                TextEntry::make('breeder')->label('Заводчик')->placeholder('Не указан'),
                                TextEntry::make('owner')->label('Владелец')->placeholder('Не указан'),
                                TextEntry::make('genetic_tests')
                                    ->label('Генетические тесты')
                                    ->prose()
                                    ->placeholder('Не заполнены')
                                    ->columnSpanFull(),
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
