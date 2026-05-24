<?php

namespace App\Filament\Resources\ArticleCategories\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Категория статей')
                    ->description('Родитель, название, ЧПУ, описание, главный заголовок и приоритет.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('parent_id')
                                ->label('Родительская категория')
                                ->relationship('parent', 'title')
                                ->searchable()
                                ->preload(),
                            TextInput::make('title')
                                ->label('Название')
                                ->required(),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required(),
                            Select::make('description_position')
                                ->label('Расположение описания')
                                ->options([
                                    'top' => 'Над статьями',
                                    'bottom' => 'Под статьями',
                                ])
                                ->default('top')
                                ->required(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            RichEditor::make('description')
                                ->label('Описание категории')
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->columnSpanFull(),
                Section::make('Поисковики')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Заголовок для поисковиков')
                            ->columnSpanFull(),
                        TextInput::make('seo_h1')
                            ->label('Главный заголовок на странице')
                            ->columnSpanFull(),
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
