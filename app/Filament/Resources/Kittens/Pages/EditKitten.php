<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKitten extends EditRecord
{
    protected static string $resource = KittenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
