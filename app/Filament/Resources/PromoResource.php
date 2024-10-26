<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Filament\Resources\PromoResource\RelationManagers;
use App\Models\Promo;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Промокоды';

    protected static ?string $pluralLabel = 'Промокоды';

    protected static ?string $label = 'Промокоды';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Название промокода'),

                TextInput::make('discount')
                    ->nullable()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->label('Скидка в %'),

                Select::make('product_id')
                    ->label('Бесплатный продукт')
                    ->options(function () {
                        return \App\Models\Product::all()->pluck('display_name', 'id');
                    })
                    ->nullable()
                    ->searchable()
                    ->preload(),

                TextInput::make('cart_total')
                    ->numeric()
                    ->nullable()
                    ->label('Скидка только от ... рублей'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название Промокода'),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Скидка в %'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Бесплатный продукт'),

                Tables\Columns\TextColumn::make('cart_total')
                    ->label('Скидка только от ... рублей'),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
