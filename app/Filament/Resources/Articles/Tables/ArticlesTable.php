<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')->label('Фото')->disk('public')->square(),
                TextColumn::make('title')->label('Заголовок')->searchable(),
                TextColumn::make('category.title')->label('Категория')->placeholder('-')->searchable(),
                TextColumn::make('published_at')->label('Дата')->dateTime()->sortable(),
                TextColumn::make('sort_order')->label('Приоритет')->numeric()->sortable(),
                IconColumn::make('allow_comments')->label('Комментарии')->boolean(),
                IconColumn::make('is_visible')->label('Видна')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
