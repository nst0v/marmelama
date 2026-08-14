<?php

namespace App\Filament\Resources\Slides\Tables;

use App\Models\Slide;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'desc')
            ->reorderable('sort_order', direction: 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Изображение')
                    ->disk('public')
                    ->imageWidth(180)
                    ->imageHeight(100),
                TextColumn::make('title')
                    ->label('Слайд')
                    ->description(fn (Slide $record): string => filled($record->alt)
                        ? $record->alt
                        : 'Описание изображения не заполнено')
                    ->searchable(['title', 'alt', 'caption'])
                    ->wrap(),
                ToggleColumn::make('is_visible')
                    ->label('На сайте'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
