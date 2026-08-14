<?php

namespace App\Filament\Resources\Litters\Schemas;

use App\Models\Litter;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Throwable;

class LitterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Помёт')
                    ->description('Дата рождения, родители, статус и публикация помёта.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('letter')
                                ->label('Литера')
                                ->required()
                                ->maxLength(20)
                                ->helperText('Например: N. На сайте будет показано «Помёт N».'),
                            DatePicker::make('born_on')
                                ->label('Дата рождения'),
                            TextInput::make('title')
                                ->label('Короткое название')
                                ->maxLength(255)
                                ->helperText('Необязательно. Если оставить пустым, название составится из литеры и даты.')
                                ->dehydrateStateUsing(fn (?string $state, Get $get): string => filled($state)
                                    ? trim($state)
                                    : self::generatedTitle($get('letter'), $get('born_on')))
                                ->columnSpanFull(),
                            Hidden::make('slug')
                                ->dehydrateStateUsing(function (?string $state, Get $get): string {
                                    if (filled($state)) {
                                        return $state;
                                    }

                                    $title = filled($get('title'))
                                        ? (string) $get('title')
                                        : self::generatedTitle($get('letter'), $get('born_on'));

                                    return self::uniqueSlug($title);
                                }),
                            Select::make('father_id')
                                ->label('Отец')
                                ->relationship(
                                    name: 'father',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('sex', 'male'),
                                )
                                ->searchable()
                                ->preload()
                                ->live(),
                            Select::make('mother_id')
                                ->label('Мать')
                                ->relationship(
                                    name: 'mother',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query): Builder => $query->where('sex', 'female'),
                                )
                                ->searchable()
                                ->preload()
                                ->live(),
                            TextInput::make('father_name')
                                ->label('Отец, если его нет в списке')
                                ->maxLength(255)
                                ->visible(fn (Get $get): bool => blank($get('father_id'))),
                            TextInput::make('mother_name')
                                ->label('Мать, если её нет в списке')
                                ->maxLength(255)
                                ->visible(fn (Get $get): bool => blank($get('mother_id'))),
                            Select::make('status')
                                ->label('Статус')
                                ->options([
                                    'planned' => 'Планируется',
                                    'available' => 'Есть свободные котята',
                                    'reserved' => 'Все котята в брони',
                                    'archive' => 'Архив',
                                ])
                                ->required()
                                ->default('planned'),
                            Toggle::make('is_visible')
                                ->label('Опубликован на сайте')
                                ->default(false)
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('description')
                            ->label('Коротко о помёте')
                            ->helperText('Показывается в списке помётов. Не повторяйте дату и количество котят.')
                            ->rows(4)
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Подробное описание')
                            ->helperText('Дополнительный текст для страницы помёта.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function generatedTitle(mixed $letter, mixed $bornOn): string
    {
        $letter = trim((string) $letter);
        $title = $letter !== '' ? "Помёт {$letter}" : 'Новый помёт';

        if (blank($bornOn)) {
            return $title;
        }

        try {
            return $title.' — '.CarbonImmutable::parse($bornOn)->format('d.m.Y');
        } catch (Throwable) {
            return $title;
        }
    }

    private static function uniqueSlug(string $title): string
    {
        $base = Str::substr(Str::slug($title), 0, 240);
        $base = $base !== '' ? $base : 'pomet';
        $slug = $base;
        $suffix = 2;

        while (Litter::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
