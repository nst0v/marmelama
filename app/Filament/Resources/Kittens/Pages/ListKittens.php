<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKittens extends ListRecords
{
    protected static string $resource = KittenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
