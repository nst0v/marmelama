<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKitten extends ViewRecord
{
    protected static string $resource = KittenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
