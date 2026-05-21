<?php

namespace App\Filament\Resources\Kittens\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KittenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_id')
                    ->numeric(),
                Select::make('litter_id')
                    ->relationship('litter', 'title'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('sex')
                    ->options([
                        'male' => 'Мальчик',
                        'female' => 'Девочка',
                        'unknown' => 'Не указан',
                    ])
                    ->required()
                    ->default('unknown'),
                TextInput::make('color'),
                DatePicker::make('born_on'),
                Select::make('status')
                    ->options([
                        'available' => 'Свободен',
                        'reserved' => 'Бронь',
                        'sold' => 'Продан',
                    ])
                    ->required()
                    ->default('available'),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('₽'),
                Textarea::make('description')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('kittens')
                    ->columnSpanFull(),
                TextInput::make('image_alt'),
                TextInput::make('image_title'),
                TextInput::make('meta_title'),
                Textarea::make('meta_description')
                    ->columnSpanFull(),
                Textarea::make('meta_keywords')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->required(),
            ]);
    }
}
