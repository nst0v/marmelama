<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\FileManager;
use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Reviews\ReviewResource;
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
                    'label' => 'Котята',
                    'description' => 'Статусы, цены, фото',
                    'url' => KittenResource::getUrl(),
                    'icon' => '/admin-icons/cat-face.svg',
                    'theme' => 'cat',
                ],
                [
                    'label' => 'Галерея',
                    'description' => 'Добавить фото',
                    'url' => GalleryImageResource::getUrl('create'),
                    'icon' => 'heroicon-o-photo',
                    'theme' => 'gallery',
                ],
                [
                    'label' => 'Файлы',
                    'description' => 'Загрузки и ссылки',
                    'url' => FileManager::getUrl(),
                    'icon' => 'heroicon-o-folder-open',
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
