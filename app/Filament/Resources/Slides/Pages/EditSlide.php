<?php

namespace App\Filament\Resources\Slides\Pages;

use App\Filament\Resources\Slides\SlideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSlide extends EditRecord
{
    protected static string $resource = SlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Удалить слайд')
                ->modalHeading(fn (): string => 'Удалить слайд «'.($this->getRecord()->title ?: 'Без названия').'»?')
                ->modalDescription('Слайд и его изображение будут удалены без возможности восстановления.')
                ->modalSubmitActionLabel('Удалить навсегда'),
        ];
    }
}
