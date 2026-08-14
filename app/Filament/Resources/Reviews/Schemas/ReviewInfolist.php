<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Models\Review;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Отзыв клиента')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 4])
                            ->schema([
                                TextEntry::make('author_name')
                                    ->label('Автор'),
                                TextEntry::make('reviewed_at')
                                    ->label('Дата отзыва')
                                    ->date('d.m.Y')
                                    ->placeholder('Не указана'),
                                TextEntry::make('publication_status')
                                    ->label('Публикация')
                                    ->state(fn (Review $record): string => $record->is_visible ? 'Опубликован' : 'Черновик')
                                    ->badge()
                                    ->color(fn (Review $record): string => $record->is_visible ? 'success' : 'gray'),
                                TextEntry::make('response_status')
                                    ->label('Ответ питомника')
                                    ->state(fn (Review $record): string => filled($record->response) ? 'Ответ добавлен' : 'Без ответа')
                                    ->badge()
                                    ->color(fn (Review $record): string => filled($record->response) ? 'success' : 'gray'),
                            ]),
                        TextEntry::make('body')
                            ->label('Текст отзыва')
                            ->prose()
                            ->columnSpanFull(),
                        ImageEntry::make('image')
                            ->label('Фото к отзыву')
                            ->disk('public')
                            ->imageHeight(220)
                            ->visible(fn (Review $record): bool => filled($record->image)),
                    ])
                    ->columnSpanFull(),
                Section::make('Ответ питомника')
                    ->description('Ответ показывается под отзывом на сайте.')
                    ->schema([
                        TextEntry::make('response')
                            ->hiddenLabel()
                            ->prose()
                            ->placeholder('Ответ ещё не добавлен'),
                    ])
                    ->columnSpanFull(),
                Section::make('Контакты автора')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])
                            ->schema([
                                TextEntry::make('phone')
                                    ->label('Телефон')
                                    ->url(fn (?string $state): ?string => filled($state)
                                        ? 'tel:'.preg_replace('/[^\d+]/', '', $state)
                                        : null),
                                TextEntry::make('email')
                                    ->label('Почта')
                                    ->url(fn (?string $state): ?string => filled($state) ? 'mailto:'.$state : null),
                            ]),
                    ])
                    ->visible(fn (Review $record): bool => filled($record->phone) || filled($record->email))
                    ->columnSpanFull(),
            ]);
    }
}
