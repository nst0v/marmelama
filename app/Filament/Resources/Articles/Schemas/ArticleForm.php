<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Статья')
                    ->description('Дата, категория, заголовок, ЧПУ, главный заголовок, анонс, текст, картинка и комментарии.')
                    ->schema([
                        Grid::make(2)->schema([
                            DateTimePicker::make('published_at')
                                ->label('Дата добавления'),
                            Select::make('article_category_id')
                                ->label('Категория')
                                ->relationship('category', 'title')
                                ->searchable()
                                ->preload(),
                            TextInput::make('title')
                                ->label('Заголовок')
                                ->required(),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required(),
                            TextInput::make('h1')
                                ->label('Главный заголовок на странице'),
                            Textarea::make('excerpt')
                                ->label('Краткий анонс')
                                ->rows(4)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label('Текст статьи')
                                ->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Изображение')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/articles')
                                ->image(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('allow_comments')
                                ->label('Включить комментарии')
                                ->default(false),
                            Toggle::make('is_visible')
                                ->label('Показывать')
                                ->default(true)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Поисковики')
                    ->schema([
                        Textarea::make('meta_description')
                            ->label('Описание для поисковиков')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('meta_keywords')
                            ->label('Ключевые слова')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
