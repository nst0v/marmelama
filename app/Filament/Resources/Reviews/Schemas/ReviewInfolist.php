<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('author_name')
                    ->label('Автор'),
                TextEntry::make('phone')
                    ->label('Телефон')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Почта')
                    ->placeholder('-'),
                TextEntry::make('body')
                    ->label('Текст отзыва')
                    ->columnSpanFull(),
                TextEntry::make('response')
                    ->label('Ответ')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('reviewed_at')
                    ->label('Дата')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('is_visible')
                    ->label('Показывать на сайте')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->label('Приоритет')
                    ->numeric(),
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
