<?php

namespace App\Filament\Resources\BreedingCats\Pages;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBreedingCat extends CreateRecord
{
    protected static string $resource = BreedingCatResource::class;

    protected static bool $canCreateAnother = false;
}
