<?php

namespace App\Filament\Resources\ArticleCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticleCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'desc')
            ->columns([
                TextColumn::make('title')->label('Название')->searchable(),
                TextColumn::make('parent.title')->label('Родитель')->placeholder('-')->searchable(),
                TextColumn::make('slug')->label('ЧПУ')->searchable(),
                TextColumn::make('sort_order')->label('Приоритет')->numeric()->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
