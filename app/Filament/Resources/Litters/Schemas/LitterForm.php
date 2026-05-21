<?php

namespace App\Filament\Resources\Litters\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LitterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_id')
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('letter'),
                DatePicker::make('born_on'),
                Select::make('father_id')
                    ->relationship('father', 'name'),
                Select::make('mother_id')
                    ->relationship('mother', 'name'),
                TextInput::make('father_name'),
                TextInput::make('mother_name'),
                Select::make('status')
                    ->options([
                        'planned' => 'Планируется',
                        'available' => 'Есть свободные',
                        'reserved' => 'Все в брони',
                        'archive' => 'Архив',
                    ])
                    ->required()
                    ->default('available'),
                Textarea::make('description')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('litters')
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
