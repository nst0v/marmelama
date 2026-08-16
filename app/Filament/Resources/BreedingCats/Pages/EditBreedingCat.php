<?php

namespace App\Filament\Resources\BreedingCats\Pages;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBreedingCat extends EditRecord
{
    protected static string $resource = BreedingCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->label('Удалить производителя')
                ->modalHeading(fn (): string => 'Удалить производителя «'.$this->getRecord()->name.'»?')
                ->modalDescription('Производитель исчезнет с сайта. Связанные помёты и котята останутся в базе.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }
}
