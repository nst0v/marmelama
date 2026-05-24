<?php

namespace App\Filament\Resources\Kittens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KittensTable
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
                TextColumn::make('litter.title')
                    ->label('Помет')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('ЧПУ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('sex')
                    ->label('Пол')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'male' => 'Мальчик',
                        'female' => 'Девочка',
                        default => 'Не указан',
                    })
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Окрас')
                    ->searchable(),
                TextColumn::make('born_on')
                    ->label('Дата рождения')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'available' => 'success',
                        'sold' => 'warning',
                        'reserved' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'available' => 'Свободен',
                        'reserved' => 'Бронь',
                        'sold' => 'Продан',
                        default => 'Не указан',
                    })
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Цена')
                    ->money()
                    ->sortable(),
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
