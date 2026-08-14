<?php

namespace App\Filament\Resources\BreedingCats\Pages;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBreedingCat extends EditRecord
{
    protected static string $resource = BreedingCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }
}
