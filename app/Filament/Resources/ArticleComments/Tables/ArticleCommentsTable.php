<?php

namespace App\Filament\Resources\ArticleComments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticleCommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('commented_at', 'desc')
            ->columns([
                TextColumn::make('article.title')->label('Статья')->placeholder('-')->searchable(),
                TextColumn::make('author_name')->label('Автор')->searchable(),
                TextColumn::make('body')->label('Комментарий')->limit(60)->searchable(),
                TextColumn::make('commented_at')->label('Дата')->dateTime()->sortable(),
                IconColumn::make('is_visible')->label('Виден')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
