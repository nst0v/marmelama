<?php

namespace App\Filament\Resources\ContentPages;

use App\Filament\Resources\ContentPages\Pages\CreateContentPage;
use App\Filament\Resources\ContentPages\Pages\EditContentPage;
use App\Filament\Resources\ContentPages\Pages\ListContentPages;
use App\Filament\Resources\ContentPages\Pages\ViewContentPage;
use App\Filament\Resources\ContentPages\Schemas\ContentPageForm;
use App\Filament\Resources\ContentPages\Schemas\ContentPageInfolist;
use App\Filament\Resources\ContentPages\Tables\ContentPagesTable;
use App\Models\ContentPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentPageResource extends Resource
{
    protected static ?string $model = ContentPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Контент';

    protected static ?string $navigationLabel = 'Страницы';

    protected static ?string $modelLabel = 'страница';

    protected static ?string $pluralModelLabel = 'страницы';

    public static function form(Schema $schema): Schema
    {
        return ContentPageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContentPageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentPagesTable::configure($table);
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
            'index' => ListContentPages::route('/'),
            'create' => CreateContentPage::route('/create'),
            'view' => ViewContentPage::route('/{record}'),
            'edit' => EditContentPage::route('/{record}/edit'),
        ];
    }
}
