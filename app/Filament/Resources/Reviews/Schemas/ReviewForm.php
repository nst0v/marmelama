<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                Section::make('Отзыв')
                    ->description('Автор, телефон, текст, ответ и видимость на сайте.')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('reviewed_at')
                                ->label('Дата'),
                            TextInput::make('author_name')
                                ->label('Автор')
                                ->required(),
                            TextInput::make('phone')
                                ->label('Телефон')
                                ->tel(),
                            TextInput::make('email')
                                ->label('Почта')
                                ->email(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Textarea::make('body')
                                ->label('Текст отзыва')
                                ->required()
                                ->rows(5)
                                ->columnSpanFull(),
                            Textarea::make('response')
                                ->label('Ответ')
                                ->rows(4)
                                ->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Фото')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/reviews')
                                ->image(),
                            Toggle::make('is_visible')
                                ->label('Показывать')
                                ->default(true)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
