<?php

namespace App\Filament\Resources\Litters\Pages;

use App\Filament\Resources\Litters\LitterResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewLitter extends ViewRecord
{
    protected static string $resource = LitterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Посмотреть на сайте')
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->url(fn (): string => route('litters.show', ['slug' => $this->getRecord()->slug]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->slug) && $this->getRecord()->is_visible),
            EditAction::make(),
        ];
    }
}
