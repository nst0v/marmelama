<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKitten extends CreateRecord
{
    protected static string $resource = KittenResource::class;
}
