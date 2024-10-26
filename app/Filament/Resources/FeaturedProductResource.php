<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeaturedProductResource\Pages;
use App\Filament\Resources\FeaturedProductResource\RelationManagers;
use App\Models\FeaturedProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeaturedProductResource extends Resource
{
    protected static ?string $model = FeaturedProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Избранные продукты';

    protected static ?string $pluralLabel = 'Избранные продукты';

    protected static ?string $label = 'Избранные продукты';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Выберите продукт')
                    ->options(function () {
                        return \App\Models\Product::all()->pluck('display_name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Название продукта'),
                Tables\Columns\TextColumn::make('product.quantityType.name')
                    ->label('Тип количества')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFeaturedProducts::route('/'),
            'create' => Pages\CreateFeaturedProduct::route('/create'),
            'edit' => Pages\EditFeaturedProduct::route('/{record}/edit'),
        ];
    }
}
