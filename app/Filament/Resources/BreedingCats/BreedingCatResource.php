<?php

namespace App\Filament\Resources\BreedingCats;

use App\Filament\Resources\BreedingCats\Pages\CreateBreedingCat;
use App\Filament\Resources\BreedingCats\Pages\EditBreedingCat;
use App\Filament\Resources\BreedingCats\Pages\ListBreedingCats;
use App\Filament\Resources\BreedingCats\Pages\ViewBreedingCat;
use App\Filament\Resources\BreedingCats\Schemas\BreedingCatForm;
use App\Filament\Resources\BreedingCats\Schemas\BreedingCatInfolist;
use App\Filament\Resources\BreedingCats\Tables\BreedingCatsTable;
use App\Models\BreedingCat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BreedingCatResource extends Resource
{
    protected static ?string $model = BreedingCat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Питомник';

    protected static ?string $navigationLabel = 'Производители';

    protected static ?string $modelLabel = 'производитель';

    protected static ?string $pluralModelLabel = 'производители';

    public static function form(Schema $schema): Schema
    {
        return BreedingCatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BreedingCatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BreedingCatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBreedingCats::route('/'),
            'create' => CreateBreedingCat::route('/create'),
            'view' => ViewBreedingCat::route('/{record}'),
            'edit' => EditBreedingCat::route('/{record}/edit'),
        ];
    }
}
