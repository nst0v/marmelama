<?php

namespace App\Filament\Resources\BreedingCats\Schemas;

use App\Support\BurmeseColors;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BreedingCatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Производитель')
                    ->description('Имя, пол, активность, окрас, дата рождения, родители, заводчик и владелец.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Имя')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('ЧПУ')
                                ->required()
                                ->maxLength(255),
                            Select::make('sex')
                                ->label('Пол')
                                ->options([
                                    'male' => 'Кот',
                                    'female' => 'Кошка',
                                ])
                                ->required(),
                            Toggle::make('is_active')
                                ->label('В племенной работе')
                                ->default(true)
                                ->required(),
                            Toggle::make('is_visible')
                                ->label('Показывать на сайте')
                                ->default(true)
                                ->required(),
                            TextInput::make('title')
                                ->label('Титулы / награды'),
                            Select::make('color')
                                ->label('Окрас')
                                ->options(fn ($record = null): array => BurmeseColors::forSelect($record?->color))
                                ->searchable()
                                ->native(false)
                                ->helperText('Официальные окрасы: CFA для американской бурмы, FIFe для европейского типа. Текущее значение сохраняется отдельным пунктом, если оно не совпадает со стандартом.'),
                            DatePicker::make('birthday')
                                ->label('Дата рождения'),
                            TextInput::make('father_name')
                                ->label('Отец'),
                            TextInput::make('mother_name')
                                ->label('Мать'),
                            TextInput::make('breeder')
                                ->label('Заводчик'),
                            TextInput::make('owner')
                                ->label('Владелец'),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('genetic_tests')
                            ->label('Генетические тесты')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Краткое описание')
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Полное описание')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Фотографии')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Фото')
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('media/parents')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Поисковики')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('image_alt')
                                ->label('Описание фото для поисковиков'),
                            TextInput::make('image_title')
                                ->label('Подсказка фото'),
                        ]),
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
