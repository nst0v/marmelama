<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Слайд на главной')
                    ->description('Загрузите изображение и решите, показывать ли его на сайте.')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Изображение')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('media/slides')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(10240)
                            ->required()
                            ->helperText('JPG, PNG или WebP, до 10 МБ.')
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->label('Название для администратора')
                            ->maxLength(200)
                            ->helperText('Поможет отличить этот слайд от остальных.'),
                        TextInput::make('alt')
                            ->label('Что изображено')
                            ->maxLength(200)
                            ->helperText('Короткое описание изображения для доступности сайта.'),
                        Toggle::make('is_visible')
                            ->label('Опубликован на сайте')
                            ->default(false)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
