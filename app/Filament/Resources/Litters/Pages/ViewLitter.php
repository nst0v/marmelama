<?php

namespace App\Filament\Resources\Litters\Pages;

use App\Filament\Resources\Litters\LitterResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewLitter extends ViewRecord
{
    protected static string $resource = LitterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Изменить данные')
                ->icon(Heroicon::PencilSquare)
                ->button(),
            Action::make('preview')
                ->label('На сайте')
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->url(fn (): string => route('litters.show', ['slug' => $this->getRecord()->slug]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->slug) && $this->getRecord()->is_visible),
            DeleteAction::make()
                ->label('Удалить')
                ->modalHeading(fn (): string => 'Удалить помёт «'.$this->getRecord()->title.'»?')
                ->modalDescription('Помёт исчезнет с сайта. Его котята останутся в разделе «Котята» без привязки к помёту.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }
}
