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

    protected static ?string $navigationLabel = 'Promo Codes';

    protected static ?string $pluralLabel = 'Promo Codes';

    protected static ?string $label = 'Promo Code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Promo Code Name'),

                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->label('Discount Percentage'),

                Select::make('product_id')
                    ->label('Product (Optional)')
                    ->relationship('product', 'name')
                    ->nullable()
                    ->searchable()
                    ->preload(),

                TextInput::make('cart_total')
                    ->numeric()
                    ->nullable()
                    ->label('Cart Total Threshold (Optional)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Promo Code Name'),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Discount (%)'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product (Optional)'),

                Tables\Columns\TextColumn::make('cart_total')
                    ->label('Cart Total Threshold (Optional)'),
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
