<?php

namespace App\Filament\Resources\ContentPages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('ЧПУ')
                    ->searchable(),
                TextColumn::make('h1')
                    ->label('Главный заголовок')
                    ->searchable(),
                TextColumn::make('meta_title')
                    ->label('Заголовок для поисковиков')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_system')
                    ->label('Служебная')
                    ->boolean(),
                IconColumn::make('is_visible')
                    ->label('Видна')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Приоритет')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
