<?php

namespace App\Filament\Resources\ArticleComments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Комментарий статьи')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('article_id')
                                ->label('Статья')
                                ->relationship('article', 'title')
                                ->searchable()
                                ->preload(),
                            TextInput::make('author_name')
                                ->label('Автор'),
                            DateTimePicker::make('commented_at')
                                ->label('Дата'),
                            Textarea::make('body')
                                ->label('Текст комментария')
                                ->required()
                                ->rows(6)
                                ->columnSpanFull(),
                            Toggle::make('is_visible')
                                ->label('Опубликован')
                                ->default(false),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
