<?php

namespace App\Filament\Resources\ContentPages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContentPageInfolist
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
                    ->label('Название'),
                TextEntry::make('slug')
                    ->label('ЧПУ'),
                TextEntry::make('h1')
                    ->label('Главный заголовок на странице')
                    ->placeholder('-'),
                TextEntry::make('content')
                    ->label('Текст страницы')
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
                IconEntry::make('is_system')
                    ->label('Служебная страница')
                    ->boolean(),
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
