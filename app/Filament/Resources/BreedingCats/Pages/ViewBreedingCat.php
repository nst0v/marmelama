<?php

namespace App\Filament\Resources\BreedingCats\Pages;

use App\Filament\Resources\BreedingCats\BreedingCatResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewBreedingCat extends ViewRecord
{
    protected static string $resource = BreedingCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Посмотреть на сайте')
                ->icon(Heroicon::ArrowTopRightOnSquare)
                ->url(fn (): string => route('parents.show', [
                    'sex' => $this->getRecord()->sex === 'male' ? '1' : '0',
                    'slug' => $this->getRecord()->slug,
                ]))
                ->openUrlInNewTab()
                ->visible(fn (): bool => filled($this->getRecord()->slug)
                    && in_array($this->getRecord()->sex, ['male', 'female'], true)
                    && $this->getRecord()->is_visible),
            EditAction::make(),
        ];
    }
}
