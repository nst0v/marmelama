<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GalleryImageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('category')
                    ->label('Категория текстом')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Название')
                    ->placeholder('-'),
                TextEntry::make('alt')
                    ->label('Описание картинки для поисковиков')
                    ->placeholder('-'),
                ImageEntry::make('image_path')
                    ->label('Фото')
                    ->disk('public'),
                TextEntry::make('sort_order')
                    ->label('Приоритет')
                    ->numeric(),
                IconEntry::make('is_visible')
                    ->label('Показывать на сайте')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
