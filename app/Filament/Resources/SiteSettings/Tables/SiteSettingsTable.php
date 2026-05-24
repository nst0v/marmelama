<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->label('Название')
                    ->searchable(),
                TextColumn::make('key')
                    ->label('Ключ')
                    ->searchable(),
                TextColumn::make('value')
                    ->label('Значение')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('group')
                    ->label('Группа')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'main' => 'Основные',
                        'contacts' => 'Контакты',
                        'content' => 'Контент',
                        'seo' => 'Поисковая оптимизация',
                        'social' => 'Соцсети',
                        default => $state ?? '-',
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'text' => 'Короткий текст',
                        'textarea' => 'Большой текст',
                        'email' => 'Почта',
                        'boolean' => 'Да / нет',
                        'url' => 'Ссылка',
                        'image' => 'Изображение',
                        default => $state ?? '-',
                    })
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
