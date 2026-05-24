<?php

namespace App\Filament\Resources\ArticleComments;

use App\Filament\Resources\ArticleComments\Pages\EditArticleComment;
use App\Filament\Resources\ArticleComments\Pages\ListArticleComments;
use App\Filament\Resources\ArticleComments\Schemas\ArticleCommentForm;
use App\Filament\Resources\ArticleComments\Tables\ArticleCommentsTable;
use App\Models\ArticleComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArticleCommentResource extends Resource
{
    protected static ?string $model = ArticleComment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Статьи';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Комментарии статей';

    protected static ?string $modelLabel = 'комментарий статьи';

    protected static ?string $pluralModelLabel = 'комментарии статей';

    public static function form(Schema $schema): Schema
    {
        return ArticleCommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticleCommentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticleComments::route('/'),
            'edit' => EditArticleComment::route('/{record}/edit'),
        ];
    }
}
