<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewKitten extends ViewRecord
{
    protected static string $resource = KittenResource::class;

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
                ->url(fn (): string => route('kittens.show', ['slug' => $this->getRecord()->slug]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->slug) && $this->getRecord()->is_visible),
            DeleteAction::make()
                ->label('Удалить')
                ->modalHeading(fn (): string => 'Удалить котёнка «'.$this->getRecord()->display_name.'»?')
                ->modalDescription('Котёнок будет полностью удалён из базы и исчезнет с сайта. Это действие нельзя отменить.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }
}
