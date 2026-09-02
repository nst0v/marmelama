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
                Section::make('Согласие на обработку персональных данных')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextEntry::make('privacy_consented_at')
                                    ->label('Получено')
                                    ->dateTime('d.m.Y H:i')
                                    ->placeholder('Не зафиксировано'),
                                TextEntry::make('privacy_consent_version')
                                    ->label('Редакция согласия')
                                    ->placeholder('Создано до учёта редакций'),
                            ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Реклама и источник')
                    ->description('Показывает, откуда посетитель пришёл перед отправкой заявки.')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([
                                TextEntry::make('source_label')
                                    ->label('Источник')
                                    ->badge()
                                    ->color(fn (ContactRequest $record): string => $record->source_label === 'Яндекс Директ'
                                        ? 'warning'
                                        : 'gray'),
                                TextEntry::make('utm_campaign')
                                    ->label('Кампания')
                                    ->placeholder('Не указана'),
                                TextEntry::make('utm_term')
                                    ->label('Ключевая фраза')
                                    ->placeholder('Не указана'),
                                TextEntry::make('utm_medium')
                                    ->label('Тип рекламы')
                                    ->placeholder('Не указан'),
                                TextEntry::make('utm_content')
                                    ->label('Объявление')
                                    ->placeholder('Не указано'),
                                TextEntry::make('yclid')
                                    ->label('Идентификатор клика yclid')
                                    ->placeholder('Не указан')
                                    ->copyable(),
                            ]),
                        TextEntry::make('landing_url')
                            ->label('Страница входа')
                            ->placeholder('Не определена')
                            ->url(fn (?string $state): ?string => filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
                        TextEntry::make('referrer_url')
                            ->label('Предыдущая страница')
                            ->placeholder('Не определена')
                            ->url(fn (?string $state): ?string => filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                            ->openUrlInNewTab()
                            ->columnSpanFull(),
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
