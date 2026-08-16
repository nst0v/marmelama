<?php

namespace App\Filament\Resources\Litters\Pages;

use App\Filament\Resources\Litters\LitterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLitter extends EditRecord
{
    protected static string $resource = LitterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->label('Удалить помёт')
                ->modalHeading(fn (): string => 'Удалить помёт «'.$this->getRecord()->title.'»?')
                ->modalDescription('Помёт исчезнет с сайта. Его котята останутся в разделе «Котята» без привязки к помёту.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }
}
