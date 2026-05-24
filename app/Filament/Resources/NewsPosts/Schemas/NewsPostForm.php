<?php

namespace App\Filament\Resources\NewsPosts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Новость')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('published_at')
                                ->label('Дата'),
                            TextInput::make('title')
                                ->label('Заголовок')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_visible')
                                ->label('Показывать')
                                ->default(true)
                                ->required(),
                            Textarea::make('excerpt')
                                ->label('Краткий анонс')
                                ->rows(4)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label('Текст новости')
                                ->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Изображение')
                                ->disk('public')
                                ->visibility('public')
                                ->image()
                                ->directory('media/news'),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
