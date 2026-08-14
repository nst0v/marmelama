<?php

namespace App\Filament\Resources\Litters\RelationManagers;

use App\Filament\Resources\Kittens\Schemas\KittenForm;
use App\Filament\Resources\Kittens\Schemas\KittenInfolist;
use App\Filament\Resources\Kittens\Tables\KittensTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class KittensRelationManager extends RelationManager
{
    protected static string $relationship = 'kittens';

    protected static ?string $title = 'Котята помёта';

    public function form(Schema $schema): Schema
    {
        return KittenForm::configure($schema);
    }

    public function infolist(Schema $schema): Schema
    {
        return KittenInfolist::configure($schema);
    }

    public function table(Table $table): Table
    {
        return KittensTable::configure($table)
            ->recordTitleAttribute('name')
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->label('Добавить котёнка'),
            ]);
    }
}
