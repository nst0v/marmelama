<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use App\Models\SiteSetting;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Контакт на сайте')
                    ->description('Измените только значение. Технические ключи и типы управляются приложением.')
                    ->schema([
                        TextInput::make('value')
                            ->label(fn (?SiteSetting $record): string => match ($record?->key) {
                                'phone' => 'Телефон',
                                'admin_email' => 'Электронная почта',
                                'max_url' => 'Ссылка на MAX',
                                default => $record?->label ?: 'Значение',
                            })
                            ->helperText(fn (?SiteSetting $record): ?string => match ($record?->key) {
                                'phone' => 'Показывается в шапке, контактах и подвале.',
                                'admin_email' => 'Показывается на сайте и принимает сообщения из формы контактов.',
                                'max_url' => 'Полная ссылка на профиль или чат MAX. Пустое значение использует резервную ссылку.',
                                default => null,
                            })
                            ->required(fn (?SiteSetting $record): bool => in_array($record?->key, ['phone', 'admin_email'], true))
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
