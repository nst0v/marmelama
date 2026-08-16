<?php

namespace App\Filament\Resources\ContactRequests\Tables;

use App\Models\ContactRequest;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ContactRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('status_label')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (ContactRequest $record): string => match ($record->status) {
                        'new' => 'danger',
                        'read' => 'info',
                        'in_progress' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('name')
                    ->label('Клиент')
                    ->description(fn (ContactRequest $record): string => Str::limit(
                        trim((string) preg_replace('/\s+/u', ' ', $record->message)),
                        100,
                    ))
                    ->searchable(['name', 'phone', 'email', 'message'])
                    ->wrap(),
                TextColumn::make('kitten.display_name')
                    ->label('Котёнок')
                    ->placeholder('Общий вопрос')
                    ->wrap(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Почта')
                    ->placeholder('Не указана')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('mail_status_label')
                    ->label('Письмо')
                    ->badge()
                    ->color(fn (ContactRequest $record): string => match ($record->mail_status) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Получена')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус заявки')
                    ->options(ContactRequest::STATUSES),
                SelectFilter::make('mail_status')
                    ->label('Отправка письма')
                    ->options(ContactRequest::MAIL_STATUSES),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->label('Статус'),
            ]);
    }
}
