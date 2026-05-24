<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Вопрос посетителя')
                    ->description('Вопрос появляется на сайте после ответа менеджера.')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('asked_at')
                                ->label('Дата'),
                            TextInput::make('author_name')
                                ->label('Имя'),
                            TextInput::make('phone')
                                ->label('Телефон'),
                            TextInput::make('title')
                                ->label('Заголовок')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('body')
                                ->label('Вопрос')
                                ->rows(6)
                                ->columnSpanFull(),
                            Textarea::make('response')
                                ->label('Ответ менеджера')
                                ->rows(6)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
