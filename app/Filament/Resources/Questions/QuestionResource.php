<?php

namespace App\Filament\Resources\Questions;

use App\Filament\Resources\Questions\Pages\EditQuestion;
use App\Filament\Resources\Questions\Pages\ListQuestions;
use App\Filament\Resources\Questions\Schemas\QuestionForm;
use App\Filament\Resources\Questions\Tables\QuestionsTable;
use App\Models\Question;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QuestionMarkCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Обратная связь';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Вопрос-ответ';

    protected static ?string $modelLabel = 'вопрос';

    protected static ?string $pluralModelLabel = 'вопросы';

    public static function form(Schema $schema): Schema
    {
        return QuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestions::route('/'),
            'edit' => EditQuestion::route('/{record}/edit'),
        ];
    }
}
