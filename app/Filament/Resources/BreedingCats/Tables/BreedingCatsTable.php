<?php

namespace App\Filament\Resources\BreedingCats\Tables;

use App\Models\BreedingCat;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BreedingCatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->imageSize(64)
                    ->square(),
                TextColumn::make('name')
                    ->label('Производитель')
                    ->description(fn (BreedingCat $record): ?string => $record->title)
                    ->wrap()
                    ->searchable(['name', 'title']),
                TextColumn::make('sex')
                    ->label('Пол')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'male' => 'Кот',
                        'female' => 'Кошка',
                        default => 'Не указан',
                    }),
                ToggleColumn::make('is_active')
                    ->label('В племенной работе'),
                TextColumn::make('color')
                    ->label('Окрас')
                    ->searchable(),
                TextColumn::make('birthday')
                    ->label('Дата рождения')
                    ->date('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
                ToggleColumn::make('is_visible')
                    ->label('На сайте'),
                TextColumn::make('breeder')
                    ->label('Заводчик')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('owner')
                    ->label('Владелец')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sex')
                    ->label('Пол')
                    ->options([
                        'male' => 'Кот',
                        'female' => 'Кошка',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Племенная работа')
                    ->placeholder('Все производители')
                    ->trueLabel('Активные')
                    ->falseLabel('Не участвуют'),
                TernaryFilter::make('is_visible')
                    ->label('Публикация')
                    ->placeholder('Все производители')
                    ->trueLabel('На сайте')
                    ->falseLabel('Скрытые'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
