<?php

namespace App\Filament\Resources\Kittens\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KittenInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('litter.title')
                    ->label('Помет')
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label('Имя'),
                TextEntry::make('slug')
                    ->label('ЧПУ'),
                TextEntry::make('sex')
                    ->label('Пол')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'male' => 'Мальчик',
                        'female' => 'Девочка',
                        default => 'Не указан',
                    }),
                TextEntry::make('color')
                    ->label('Окрас')
                    ->placeholder('-'),
                TextEntry::make('born_on')
                    ->label('Дата рождения')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'available' => 'success',
                        'sold' => 'warning',
                        'reserved' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'available' => 'Свободен',
                        'reserved' => 'Бронь',
                        'sold' => 'Продан',
                        default => 'Не указан',
                    }),
                TextEntry::make('price')
                    ->label('Цена')
                    ->money()
                    ->placeholder('-'),
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
                ImageEntry::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('image_alt')
                    ->label('Описание фото для поисковиков')
                    ->placeholder('-'),
                TextEntry::make('image_title')
                    ->label('Подсказка фото')
                    ->placeholder('-'),
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
