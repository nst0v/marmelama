<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Filament\Resources\Kittens\KittenResource;
use App\Filament\Resources\Litters\LitterResource;
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
                    'label' => 'Котята',
                    'description' => 'Просмотр и редактирование',
                    'url' => KittenResource::getUrl(),
                    'icon' => '/admin-icons/cat-face.svg',
                    'theme' => 'cat',
                ],
                [
                    'label' => 'Помёты',
                    'description' => 'Список помётов и котят',
                    'url' => LitterResource::getUrl(),
                    'icon' => 'heroicon-o-calendar-days',
                    'theme' => 'gallery',
                ],
                [
                    'label' => 'Производители',
                    'description' => 'Коты и кошки питомника',
                    'url' => BreedingCatResource::getUrl(),
                    'icon' => 'heroicon-o-identification',
                    'theme' => 'breeding',
                ],
                [
                    'label' => 'Заявки',
                    'description' => 'Обращения с сайта',
                    'url' => ContactRequestResource::getUrl(),
                    'icon' => 'heroicon-o-inbox-arrow-down',
                    'theme' => 'reviews',
                ],
                [
                    'label' => 'Слайды',
                    'description' => 'Изображения на главной',
                    'url' => SlideResource::getUrl(),
                    'icon' => 'heroicon-o-photo',
                    'theme' => 'files',
                ],
            ],
        ];
    }
}
