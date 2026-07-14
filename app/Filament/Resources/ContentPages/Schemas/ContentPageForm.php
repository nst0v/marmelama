<?php

namespace App\Filament\Resources\ContentPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ContentPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Страница')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Название')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                    if (blank($get('slug'))) {
                                        $set('slug', Str::slug($state ?? ''));
                                    }
                                }),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText('Заполнится из названия автоматически; при необходимости можно изменить.'),
                            TextInput::make('h1')
                                ->label('Главный заголовок на странице')
                                ->maxLength(255)
                                ->helperText('Необязательно. Если поле пустое, на странице будет показано название.'),
                            RichEditor::make('content')
                                ->label('Текст страницы')
                                ->helperText('Главный заголовок уже выводится сайтом — не повторяйте его в тексте. Заголовки разделов можно добавлять обычным способом.')
                                ->columnSpanFull(),
                            Toggle::make('is_system')
                                ->label('Служебная')
                                ->required(),
                            Toggle::make('is_visible')
                                ->label('Показывать')
                                ->required(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Поисковики')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Заголовок для поисковиков')
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
