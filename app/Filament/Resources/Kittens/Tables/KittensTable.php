<?php

namespace App\Filament\Resources\Kittens\Tables;

use App\Models\Kitten;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KittensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('litter'))
            ->defaultSort('updated_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->imageSize(64)
                    ->square(),
                TextColumn::make('name')
                    ->label('Котёнок')
                    ->description(fn (Kitten $record): string => $record->litter?->title
                        ? 'Помёт: '.$record->litter->title
                        : 'Без помёта')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query
                        ->where(fn (Builder $query): Builder => $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('source_litter_letter', 'like', "%{$search}%")
                            ->orWhereHas('litter', fn (Builder $query): Builder => $query
                                ->where('title', 'like', "%{$search}%")
                                ->orWhere('letter', 'like', "%{$search}%"))))
                    ->wrap(),
                TextColumn::make('color')
                    ->label('Окрас')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('born_on')
                    ->label('Дата рождения')
                    ->date('d.m.Y')
                    ->placeholder('—')
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
                    ->money('RUB', locale: 'ru', decimalPlaces: 0)
                    ->placeholder('По запросу')
                    ->sortable(),
                ToggleColumn::make('is_visible')
                    ->label('На сайте'),
                TextColumn::make('sex')
                    ->label('Пол')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'male' => 'Мальчик',
                        'female' => 'Девочка',
                        default => 'Не указан',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'available' => 'Свободен',
                        'reserved' => 'Бронь',
                        'sold' => 'Продан',
                    ]),
                SelectFilter::make('litter_id')
                    ->label('Помёт')
                    ->relationship('litter', 'title', hasEmptyOption: true)
                    ->emptyRelationshipOptionLabel('Без помёта')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sex')
                    ->label('Пол')
                    ->options([
                        'male' => 'Мальчик',
                        'female' => 'Девочка',
                        'unknown' => 'Не указан',
                    ]),
                TernaryFilter::make('is_visible')
                    ->label('Публикация')
                    ->placeholder('Все котята')
                    ->trueLabel('На сайте')
                    ->falseLabel('Черновики'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
