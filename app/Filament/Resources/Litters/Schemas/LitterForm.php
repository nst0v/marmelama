<?php

namespace App\Filament\Resources\Litters\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LitterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Помет')
                    ->description('Название, литера, родители, статус, приоритет и публикация.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Название')
                                ->required()
                                ->maxLength(255)
                                ->helperText('Короткое смысловое название. Дату, литеру и количество котят сайт покажет отдельно.')
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
                                ->helperText('Заполнится из названия автоматически.'),
                            TextInput::make('letter')
                                ->label('Литера')
                                ->maxLength(20)
                                ->helperText('Например: N. На сайте будет показано «Помёт N».'),
                            DatePicker::make('born_on')
                                ->label('Дата рождения'),
                            Select::make('status')
                                ->label('Статус')
                                ->options([
                                    'planned' => 'Планируется',
                                    'available' => 'Есть свободные',
                                    'reserved' => 'Все в брони',
                                    'archive' => 'Архив',
                                ])
                                ->required()
                                ->default('available')
                                ->helperText('«Есть свободные» показывается, когда в помёте опубликован хотя бы один свободный котёнок.'),
                            Select::make('father_id')
                                ->label('Отец из производителей')
                                ->relationship('father', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('mother_id')
                                ->label('Мать из производителей')
                                ->relationship('mother', 'name')
                                ->searchable()
                                ->preload(),
                            TextInput::make('father_name')
                                ->label('Отец вручную'),
                            TextInput::make('mother_name')
                                ->label('Мать вручную'),
                            Textarea::make('father_description')
                                ->label('Описание отца вручную')
                                ->rows(3)
                                ->columnSpanFull(),
                            FileUpload::make('father_image')
                                ->label('Фото отца вручную')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/litters/parents')
                                ->image(),
                            FileUpload::make('mother_image')
                                ->label('Фото матери вручную')
                                ->disk('public')
                                ->visibility('public')
                                ->directory('media/litters/parents')
                                ->image(),
                            Textarea::make('mother_description')
                                ->label('Описание матери вручную')
                                ->rows(3)
                                ->columnSpanFull(),
                            TextInput::make('sort_order')
                                ->label('Приоритет')
                                ->required()
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_visible')
                                ->label('Показывать на сайте')
                                ->default(true)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('description')
                            ->label('Краткое описание')
                            ->helperText('Не повторяйте дату и количество котят — они выводятся автоматически.')
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
                            ->directory('media/litters')
                            ->columnSpanFull(),
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
