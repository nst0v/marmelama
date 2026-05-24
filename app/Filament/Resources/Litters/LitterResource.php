<?php

namespace App\Filament\Resources\Litters;

use App\Filament\Resources\Litters\Pages\CreateLitter;
use App\Filament\Resources\Litters\Pages\EditLitter;
use App\Filament\Resources\Litters\Pages\ListLitters;
use App\Filament\Resources\Litters\Pages\ViewLitter;
use App\Filament\Resources\Litters\Schemas\LitterForm;
use App\Filament\Resources\Litters\Schemas\LitterInfolist;
use App\Filament\Resources\Litters\Tables\LittersTable;
use App\Models\Litter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LitterResource extends Resource
{
    protected static ?string $model = Litter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static string|\UnitEnum|null $navigationGroup = 'Питомник';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Пометы';

    protected static ?string $modelLabel = 'помет';

    protected static ?string $pluralModelLabel = 'пометы';

    public static function form(Schema $schema): Schema
    {
        return LitterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LitterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LittersTable::configure($table);
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
            'index' => ListLitters::route('/'),
            'create' => CreateLitter::route('/create'),
            'view' => ViewLitter::route('/{record}'),
            'edit' => EditLitter::route('/{record}/edit'),
        ];
    }
}
