<?php

namespace App\Filament\Resources\BreedingCats\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BreedingCatInfolist
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
                TextEntry::make('name')
                    ->label('Имя'),
                TextEntry::make('slug')
                    ->label('ЧПУ'),
                TextEntry::make('sex')
                    ->label('Пол')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'male' => 'Кот',
                        'female' => 'Кошка',
                        default => 'Не указан',
                    }),
                IconEntry::make('is_active')
                    ->label('В племенной работе')
                    ->boolean(),
                TextEntry::make('title')
                    ->label('Титулы / награды')
                    ->placeholder('-'),
                TextEntry::make('color')
                    ->label('Окрас')
                    ->placeholder('-'),
                TextEntry::make('birthday')
                    ->label('Дата рождения')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('father_name')
                    ->label('Отец')
                    ->placeholder('-'),
                TextEntry::make('mother_name')
                    ->label('Мать')
                    ->placeholder('-'),
                TextEntry::make('genetic_tests')
                    ->label('Генетические тесты')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('breeder')
                    ->label('Заводчик')
                    ->placeholder('-'),
                TextEntry::make('owner')
                    ->label('Владелец')
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
