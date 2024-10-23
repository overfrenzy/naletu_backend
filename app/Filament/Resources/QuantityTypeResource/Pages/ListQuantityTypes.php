<?php

namespace App\Filament\Resources\QuantityTypeResource\Pages;

use App\Filament\Resources\QuantityTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuantityTypes extends ListRecords
{
    protected static string $resource = QuantityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
