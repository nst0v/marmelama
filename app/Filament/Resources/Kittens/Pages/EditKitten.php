<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditKitten extends EditRecord
{
    protected static string $resource = KittenResource::class;

    public static bool $formActionsAreSticky = true;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Карточка котёнка'),
            DeleteAction::make()
                ->label('Удалить котёнка')
                ->modalHeading(fn (): string => 'Удалить котёнка «'.$this->getRecord()->display_name.'»?')
                ->modalDescription('Котёнок будет полностью удалён из базы и исчезнет с сайта. Это действие нельзя отменить.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Сохранить изменения')
            ->icon(Heroicon::Check);
    }
}
