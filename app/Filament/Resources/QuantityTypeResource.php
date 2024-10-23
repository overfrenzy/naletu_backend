<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuantityTypeResource\Pages;
use App\Filament\Resources\QuantityTypeResource\RelationManagers;
use App\Models\QuantityType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuantityTypeResource extends Resource
{
    protected static ?string $model = QuantityType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Тип Наименования';

    protected static ?string $pluralLabel = 'Типы Наименований';

    protected static ?string $label = 'Тип Наименования';

    protected static ?string $navigationGroup = 'Другое';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Название Наименования'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название Наименования'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuantityTypes::route('/'),
            'create' => Pages\CreateQuantityType::route('/create'),
            'edit' => Pages\EditQuantityType::route('/{record}/edit'),
        ];
    }
}
