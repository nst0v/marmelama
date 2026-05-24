<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('title')->label('Вопрос')->searchable(),
                TextColumn::make('author_name')->label('Автор')->searchable(),
                TextColumn::make('phone')->label('Телефон')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('asked_at')->label('Дата')->date()->sortable(),
                IconColumn::make('response')->label('Есть ответ')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
