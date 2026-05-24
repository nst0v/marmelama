<?php

namespace App\Filament\Resources\Litters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LittersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('born_on', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('ЧПУ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('letter')
                    ->label('Литера')
                    ->searchable(),
                TextColumn::make('born_on')
                    ->label('Дата')
                    ->date()
                    ->sortable(),
                TextColumn::make('father.name')
                    ->label('Отец')
                    ->searchable(),
                TextColumn::make('mother.name')
                    ->label('Мать')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Приоритет')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label('Виден')
                    ->boolean(),
                TextColumn::make('old_id')
                    ->label('Внутренний номер')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Обновлен')
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
