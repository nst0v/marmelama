<?php

namespace App\Filament\Resources\ContactRequests\Schemas;

use App\Models\ContactRequest;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Заявка')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([
                                TextEntry::make('status_label')
                                    ->label('Статус')
                                    ->badge()
                                    ->color(fn (ContactRequest $record): string => match ($record->status) {
                                        'new' => 'danger',
                                        'read' => 'info',
                                        'in_progress' => 'warning',
                                        'closed' => 'success',
                                        default => 'gray',
                                    }),
                                TextEntry::make('kitten.display_name')
                                    ->label('Котёнок')
                                    ->placeholder('Общий вопрос'),
                                TextEntry::make('created_at')
                                    ->label('Получена')
                                    ->dateTime('d.m.Y H:i'),
                            ]),
                        TextEntry::make('message')
                            ->label('Сообщение')
                            ->prose()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Контакты')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Имя'),
                                TextEntry::make('phone')
                                    ->label('Телефон')
                                    ->url(fn (?string $state): ?string => filled($state)
                                        ? 'tel:'.preg_replace('/[^\d+]/', '', $state)
                                        : null),
                                TextEntry::make('email')
                                    ->label('Почта')
                                    ->placeholder('Не указана')
                                    ->url(fn (?string $state): ?string => filled($state) ? 'mailto:'.$state : null),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Уведомление по почте')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextEntry::make('mail_status_label')
                                    ->label('Статус отправки')
                                    ->badge()
                                    ->color(fn (ContactRequest $record): string => match ($record->mail_status) {
                                        'sent' => 'success',
                                        'failed' => 'danger',
                                        default => 'warning',
                                    }),
                                TextEntry::make('mail_sent_at')
                                    ->label('Отправлено')
                                    ->dateTime('d.m.Y H:i')
                                    ->placeholder('Не отправлено'),
                            ]),
                        TextEntry::make('mail_error')
                            ->label('Ошибка отправки')
                            ->placeholder('Ошибок нет')
                            ->visible(fn (ContactRequest $record): bool => filled($record->mail_error))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Внутренняя заметка')
                    ->schema([
                        TextEntry::make('internal_notes')
                            ->hiddenLabel()
                            ->placeholder('Заметок пока нет')
                            ->prose(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
