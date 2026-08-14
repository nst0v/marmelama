<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewKitten extends ViewRecord
{
    protected static string $resource = KittenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Посмотреть на сайте')
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->url(fn (): string => route('kittens.show', ['slug' => $this->getRecord()->slug]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->slug) && $this->getRecord()->is_visible),
            EditAction::make(),
        ];
    }
}
