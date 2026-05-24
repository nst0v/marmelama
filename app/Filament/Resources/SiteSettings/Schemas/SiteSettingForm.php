<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Настройка сайта')
                    ->description('Почта, телефон, адрес, сообщения, заказ звонка и награды.')
                    ->schema([
                        TextInput::make('label')
                            ->label('Название'),
                        TextInput::make('key')
                            ->label('Ключ')
                            ->required(),
                        Textarea::make('value')
                            ->label('Значение')
                            ->rows(4)
                            ->columnSpanFull(),
                        Select::make('group')
                            ->label('Группа')
                            ->options(fn ($record = null): array => self::withCurrent([
                                'main' => 'Основные',
                                'contacts' => 'Контакты',
                                'content' => 'Контент',
                                'seo' => 'Поисковая оптимизация',
                                'social' => 'Соцсети',
                            ], $record?->group))
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->default('main'),
                        Select::make('type')
                            ->label('Тип')
                            ->options(fn ($record = null): array => self::withCurrent([
                                'text' => 'Короткий текст',
                                'textarea' => 'Большой текст',
                                'email' => 'Почта',
                                'boolean' => 'Да / нет',
                                'url' => 'Ссылка',
                                'image' => 'Изображение',
                            ], $record?->type))
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->default('text'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function withCurrent(array $options, ?string $currentValue): array
    {
        $currentValue = trim((string) $currentValue);

        if ($currentValue !== '' && ! array_key_exists($currentValue, $options)) {
            $options[$currentValue] = "Текущее значение: {$currentValue}";
        }

        return $options;
    }
}
