<?php

namespace App\Filament\Resources\ContactRequests\Schemas;

use App\Models\ContactRequest;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Работа с заявкой')
                    ->description('Контактные данные и текст обращения нельзя случайно изменить здесь.')
                    ->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->options(ContactRequest::STATUSES)
                            ->required()
                            ->native(false),
                        Textarea::make('internal_notes')
                            ->label('Внутренняя заметка')
                            ->helperText('Эта заметка видна только в админке.')
                            ->rows(7)
                            ->maxLength(10000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
