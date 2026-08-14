<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Контакт')
                    ->formatStateUsing(fn (?string $state, $record): string => match ($record->key) {
                        'phone' => 'Телефон',
                        'admin_email' => 'Электронная почта',
                        'max_url' => 'MAX',
                        default => $state ?: 'Настройка',
                    }),
                TextColumn::make('value')
                    ->label('Значение')
                    ->placeholder('Используется резервное значение')
                    ->limit(70),
                TextColumn::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('label')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
