<?php

namespace App\Filament\Resources\Litters\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LitterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->placeholder('-'),
                ImageEntry::make('cover_image')
                    ->label('Главное фото')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Название'),
                TextEntry::make('slug')
                    ->label('ЧПУ'),
                TextEntry::make('letter')
                    ->label('Литера')
                    ->placeholder('-'),
                TextEntry::make('born_on')
                    ->label('Дата рождения')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('father.name')
                    ->label('Отец из производителей')
                    ->placeholder('-'),
                TextEntry::make('mother.name')
                    ->label('Мать из производителей')
                    ->placeholder('-'),
                TextEntry::make('father_name')
                    ->label('Отец вручную')
                    ->placeholder('-'),
                TextEntry::make('mother_name')
                    ->label('Мать вручную')
                    ->placeholder('-'),
                TextEntry::make('father_description')
                    ->label('Описание отца вручную')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('father_image')
                    ->label('Фото отца вручную')
                    ->disk('public')
                    ->placeholder('-'),
                ImageEntry::make('mother_image')
                    ->label('Фото матери вручную')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('mother_description')
                    ->label('Описание матери вручную')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'planned' => 'Планируется',
                        'available' => 'Есть свободные',
                        'reserved' => 'Все в брони',
                        'archive' => 'Архив',
                        default => 'Не указан',
                    }),
                TextEntry::make('description')
                    ->label('Краткое описание')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('Полное описание')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('images')
                    ->label('Все фото (пути)')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meta_title')
                    ->label('Заголовок для поисковиков')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->label('Описание для поисковиков')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meta_keywords')
                    ->label('Ключевые слова')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort_order')
                    ->label('Приоритет')
                    ->numeric(),
                IconEntry::make('is_visible')
                    ->label('Показывать на сайте')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Создан')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Обновлен')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
