<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'xl' => 3])
                    ->schema([
                        Section::make('Отзыв и ответ')
                            ->description('Основной текст, который увидят посетители сайта.')
                            ->schema([
                                Textarea::make('body')
                                    ->label('Отзыв клиента')
                                    ->required()
                                    ->helperText('Абзацы и длинный текст поддерживаются.')
                                    ->rows(9)
                                    ->columnSpanFull(),
                                Textarea::make('response')
                                    ->label('Ответ питомника')
                                    ->helperText('Оставьте пустым, если ответ пока не готов.')
                                    ->rows(7)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(['xl' => 2]),

                        Grid::make(1)
                            ->schema([
                                Section::make('Автор')
                                    ->schema([
                                        TextInput::make('author_name')
                                            ->label('Имя')
                                            ->required()
                                            ->maxLength(255),
                                        DatePicker::make('reviewed_at')
                                            ->label('Дата отзыва'),
                                        TextInput::make('phone')
                                            ->label('Телефон')
                                            ->tel()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Электронная почта')
                                            ->email()
                                            ->maxLength(255),
                                    ]),
                                Section::make('Публикация и фото')
                                    ->schema([
                                        Toggle::make('is_visible')
                                            ->label('Опубликован на сайте')
                                            ->default(false)
                                            ->required(),
                                        FileUpload::make('image')
                                            ->label('Фото к отзыву')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->directory('media/reviews')
                                            ->image()
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                            ->maxSize(10240)
                                            ->helperText('JPG, PNG или WebP, до 10 МБ.'),
                                    ]),
                            ])
                            ->columnSpan(['xl' => 1]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
