<?php

namespace App\Filament\Resources\Litters\Tables;

use App\Models\Litter;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LittersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['father', 'mother']))
            ->defaultSort('born_on', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Фото')
                    ->disk('public')
                    ->imageSize(60)
                    ->square(),
                TextColumn::make('title')
                    ->label('Помёт')
                    ->description(fn (Litter $record): string => filled($record->letter)
                        ? 'Литера '.$record->letter
                        : 'Литера не указана')
                    ->searchable(['title', 'letter', 'father_name', 'mother_name'])
                    ->wrap(),
                TextColumn::make('born_on')
                    ->label('Дата рождения')
                    ->date('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('parents')
                    ->label('Родители')
                    ->state(fn (Litter $record): array => [
                        'Отец: '.($record->father?->name ?: $record->father_name ?: 'не указан'),
                        'Мать: '.($record->mother?->name ?: $record->mother_name ?: 'не указана'),
                    ])
                    ->listWithLineBreaks()
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query
                        ->where(fn (Builder $query): Builder => $query
                            ->where('father_name', 'like', "%{$search}%")
                            ->orWhere('mother_name', 'like', "%{$search}%")
                            ->orWhereHas('father', fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('mother', fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%")))),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'planned' => 'gray',
                        'available' => 'success',
                        'reserved' => 'info',
                        'archive' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'planned' => 'Планируется',
                        'available' => 'Есть свободные',
                        'reserved' => 'Все в брони',
                        'archive' => 'Архив',
                        default => 'Не указан',
                    }),
                TextColumn::make('kittens_count')
                    ->label('Котят')
                    ->counts('kittens')
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('is_visible')
                    ->label('На сайте'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'planned' => 'Планируется',
                        'available' => 'Есть свободные',
                        'reserved' => 'Все в брони',
                        'archive' => 'Архив',
                    ]),
                SelectFilter::make('father_id')
                    ->label('Отец')
                    ->relationship('father', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('mother_id')
                    ->label('Мать')
                    ->relationship('mother', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('has_kittens')
                    ->label('Котята')
                    ->placeholder('Все помёты')
                    ->trueLabel('Есть котята')
                    ->falseLabel('Без котят')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->has('kittens'),
                        false: fn (Builder $query): Builder => $query->doesntHave('kittens'),
                    ),
                TernaryFilter::make('is_visible')
                    ->label('Публикация')
                    ->placeholder('Все помёты')
                    ->trueLabel('На сайте')
                    ->falseLabel('Черновики'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Изменить')
                    ->button(),
                ViewAction::make()
                    ->label('Смотреть'),
                DeleteAction::make()
                    ->label('Удалить')
                    ->modalHeading(fn (Litter $record): string => 'Удалить помёт «'.$record->title.'»?')
                    ->modalDescription('Помёт исчезнет с сайта. Его котята останутся в разделе «Котята» без привязки к помёту.')
                    ->modalSubmitActionLabel('Удалить навсегда'),
            ]);
    }
}
