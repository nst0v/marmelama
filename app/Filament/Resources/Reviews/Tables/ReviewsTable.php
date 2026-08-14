<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('reviewed_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->imageSize(56)
                    ->square(),
                TextColumn::make('author_name')
                    ->label('Отзыв')
                    ->description(fn (Review $record): string => Str::limit(
                        trim((string) preg_replace('/\s+/u', ' ', strip_tags($record->body))),
                        120,
                    ))
                    ->searchable(['author_name', 'body', 'phone', 'email'])
                    ->wrap(),
                TextColumn::make('reviewed_at')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable(),
                ToggleColumn::make('is_visible')
                    ->label('Опубликован'),
                IconColumn::make('has_response')
                    ->label('Есть ответ')
                    ->state(fn (Review $record): bool => filled($record->response))
                    ->boolean(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('Почта')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')
                    ->label('Публикация')
                    ->placeholder('Все отзывы')
                    ->trueLabel('Опубликованы')
                    ->falseLabel('Не опубликованы'),
                TernaryFilter::make('has_response')
                    ->label('Ответ питомника')
                    ->placeholder('Все отзывы')
                    ->trueLabel('Есть ответ')
                    ->falseLabel('Без ответа')
                    ->queries(
                        true: fn (Builder $query): Builder => $query
                            ->whereNotNull('response')
                            ->where('response', '!=', ''),
                        false: fn (Builder $query): Builder => $query
                            ->where(fn (Builder $query): Builder => $query
                                ->whereNull('response')
                                ->orWhere('response', '')),
                    ),
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
