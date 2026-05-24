<?php

namespace App\Filament\Resources\Kittens;

use App\Filament\Resources\Kittens\Pages\CreateKitten;
use App\Filament\Resources\Kittens\Pages\EditKitten;
use App\Filament\Resources\Kittens\Pages\ListKittens;
use App\Filament\Resources\Kittens\Pages\ViewKitten;
use App\Filament\Resources\Kittens\Schemas\KittenForm;
use App\Filament\Resources\Kittens\Schemas\KittenInfolist;
use App\Filament\Resources\Kittens\Tables\KittensTable;
use App\Models\Kitten;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class KittenResource extends Resource
{
    protected static ?string $model = Kitten::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|\UnitEnum|null $navigationGroup = 'Питомник';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Котята';

    protected static ?string $modelLabel = 'котенок';

    protected static ?string $pluralModelLabel = 'котята';

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return new HtmlString(file_get_contents(public_path('admin-icons/cat-face.svg')));
    }

    public static function form(Schema $schema): Schema
    {
        return KittenForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KittenInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KittensTable::configure($table);
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
            'index' => ListKittens::route('/'),
            'create' => CreateKitten::route('/create'),
            'view' => ViewKitten::route('/{record}'),
            'edit' => EditKitten::route('/{record}/edit'),
        ];
    }
}
