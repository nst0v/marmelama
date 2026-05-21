<?php

namespace App\Filament\Resources\BreedingCats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BreedingCatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('sex')
                    ->options([
                        'male' => 'Кот',
                        'female' => 'Кошка',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('title'),
                TextInput::make('color'),
                DatePicker::make('birthday'),
                TextInput::make('father_name'),
                TextInput::make('mother_name'),
                Textarea::make('genetic_tests')
                    ->columnSpanFull(),
                TextInput::make('breeder'),
                TextInput::make('owner'),
                Textarea::make('description')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('parents')
                    ->columnSpanFull(),
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
