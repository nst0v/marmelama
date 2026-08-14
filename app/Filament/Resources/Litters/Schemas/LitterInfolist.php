<?php

namespace App\Filament\Resources\Litters\Schemas;

use App\Models\Litter;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class LitterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Помёт')
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
                                                'planned' => 'gray',
                                                'available' => 'success',
                                                'reserved' => 'info',
                                                'archive' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                                'planned' => 'Планируется',
                                                'available' => 'Есть свободные',
                                                'reserved' => 'Все в брони',
                                                'archive' => 'Архив',
                                                default => 'Не указан',
                                            }),
                                        IconEntry::make('is_visible')
                                            ->label('Опубликован на сайте')
                                            ->boolean(),
                                        TextEntry::make('letter')
                                            ->label('Литера')
                                            ->placeholder('Не указана'),
                                        TextEntry::make('born_on')
                                            ->label('Дата рождения')
                                            ->date('d.m.Y')
                                            ->placeholder('Не указана'),
                                        TextEntry::make('kittens_count')
                                            ->label('Котят в помёте')
                                            ->counts('kittens')
                                            ->badge(),
                                    ])
                                    ->columnSpan(['lg' => 2]),
                            ]),
                    ])
                    ->columnSpanFull(),
                Grid::make(['default' => 1, 'lg' => 2])
                    ->schema([
                        Section::make('Отец')
                            ->schema([
                                ImageEntry::make('father.cover_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->imageSize(120)
                                    ->square()
                                    ->placeholder('Фото нет'),
                                TextEntry::make('father_display')
                                    ->label('Имя')
                                    ->state(fn (Litter $record): ?string => $record->father?->name ?: $record->father_name)
                                    ->placeholder('Не указан'),
                            ]),
                        Section::make('Мать')
                            ->schema([
                                ImageEntry::make('mother.cover_image')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->imageSize(120)
                                    ->square()
                                    ->placeholder('Фото нет'),
                                TextEntry::make('mother_display')
                                    ->label('Имя')
                                    ->state(fn (Litter $record): ?string => $record->mother?->name ?: $record->mother_name)
                                    ->placeholder('Не указана'),
                            ]),
                    ]),
                Section::make('Котята из помёта')
                    ->schema([
                        TextEntry::make('kittens.name')
                            ->hiddenLabel()
                            ->bulleted()
                            ->listWithLineBreaks()
                            ->placeholder('Котята еще не добавлены'),
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
