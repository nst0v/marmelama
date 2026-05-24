<?php

namespace App\Filament\Resources\BreedingCats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BreedingCatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->square(),
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('ЧПУ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('sex')
                    ->label('Пол')
                    ->formatStateUsing(fn (?string $state): string => $state === 'male' ? 'Кот' : 'Кошка')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                TextColumn::make('title')
                    ->label('Титулы')
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Окрас')
                    ->searchable(),
                TextColumn::make('birthday')
                    ->label('Дата рождения')
                    ->date()
                    ->sortable(),
                TextColumn::make('breeder')
                    ->label('Заводчик')
                    ->searchable(),
                TextColumn::make('owner')
                    ->label('Владелец')
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
