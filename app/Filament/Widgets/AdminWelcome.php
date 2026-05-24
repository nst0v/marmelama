<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class AdminWelcome extends Widget
{
    protected static ?int $sort = -5;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.admin-welcome';

    protected function getViewData(): array
    {
        return [
            'name' => Filament::auth()->user()?->name,
        ];
    }
}
