<?php

namespace App\Filament\Resources\GalleryCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Категория')
                    ->description('Родитель, название, ЧПУ, главный заголовок, описание и позиция описания.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('parent_id')
                                ->label('Родительская категория')
                                ->relationship('parent', 'name')
                                ->searchable()
                                ->preload(),
                            TextInput::make('name')
                                ->label('Название категории')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('h1')
                                ->label('Главный заголовок на странице')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            RichEditor::make('description')
                                ->label('Описание категории')
                                ->columnSpanFull(),
                            Select::make('description_position')
                                ->label('Расположение описания')
                                ->options([
                                    'top' => 'Над фотографиями',
                                    'bottom' => 'Под фотографиями',
                                ])
                                ->default('top')
                                ->required(),
                            FileUpload::make('image')
                                ->label('Изображение категории')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/gallery-categories')
                                ->image(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Поисковики и публикация')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Заголовок для поисковиков')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('meta_description')
                            ->label('Описание для поисковиков')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('meta_keywords')
                            ->label('Ключевые слова')
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
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
