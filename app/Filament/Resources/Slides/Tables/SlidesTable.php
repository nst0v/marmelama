<?php

namespace App\Filament\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('placement')
                    ->label('Страница')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'home' => 'Главная',
                        default => $state ?? '-',
                    }),
                TextColumn::make('url')
                    ->label('Ссылка')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Приоритет')
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('is_visible')
                    ->label('Показывать'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
