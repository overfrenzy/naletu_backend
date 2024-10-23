<?php

namespace App\Filament\Resources\QuantityTypeResource\Pages;

use App\Filament\Resources\QuantityTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuantityType extends EditRecord
{
    protected static string $resource = QuantityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
