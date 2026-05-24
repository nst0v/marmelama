<?php

namespace App\Filament\Resources\NewsPosts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NewsPostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Заголовок'),
                TextEntry::make('slug')
                    ->label('ЧПУ'),
                TextEntry::make('excerpt')
                    ->label('Краткий анонс')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('Текст новости')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->placeholder('-'),
                TextEntry::make('published_at')
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
                    ->label('Создана')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
