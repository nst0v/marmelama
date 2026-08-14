<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Litters\LitterResource;
use App\Filament\Resources\Reviews\ReviewResource;
use App\Filament\Resources\Slides\SlideResource;
use Filament\Widgets\Widget;

class AdminQuickActions extends Widget
{
    protected static ?int $sort = -4;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.admin-quick-actions';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Добавить котёнка',
                    'description' => 'Имя, статус, цена и фото',
                    'url' => KittenResource::getUrl('create'),
                    'icon' => '/admin-icons/cat-face.svg',
                    'theme' => 'cat',
                ],
                [
                    'label' => 'Создать помёт',
                    'description' => 'Дата, родители и котята',
                    'url' => LitterResource::getUrl('create'),
                    'icon' => 'heroicon-o-calendar-days',
                    'theme' => 'gallery',
                ],
                [
                    'label' => 'Добавить слайд',
                    'description' => 'Изображение на главной',
                    'url' => SlideResource::getUrl('create'),
                    'icon' => 'heroicon-o-photo',
                    'theme' => 'files',
                ],
                [
                    'label' => 'Отзывы',
                    'description' => 'Ответить и скрыть',
                    'url' => ReviewResource::getUrl(),
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                    'theme' => 'reviews',
                ],
            ],
        ];
    }
}
