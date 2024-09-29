<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Продукты';

    protected static ?string $pluralLabel = 'Продукты';

    protected static ?string $label = 'Продукт';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Название Продукта'),

                Textarea::make('description')
                    ->required()
                    ->label('Описание'),

                TextInput::make('mrp')
                    ->required()
                    ->numeric()
                    ->label('Максимальная Розничная Цена'),

                TextInput::make('selling_price')
                    ->numeric()
                    ->label('Итоговая Цена'),

                    Select::make('quantity_type_id')
                        ->label('Количество')
                        ->relationship('quantityType', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->createOptionForm(function (Form $form) {
                            return $form->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Название Количества')
                                    ->placeholder('Например: 1L, 500g'),
                            ]);
                        })
                        ->helperText('Выберите или добавьте количество'),

                FileUpload::make('image')
                    ->disk('public')
                    ->directory('product-images')
                    ->nullable()
                    ->label('Картинка'),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Категория')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название Продукта'),

                Tables\Columns\TextColumn::make('mrp')
                    ->label('Максимальная Розничная Цена'),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Итоговая Цена'),

                Tables\Columns\TextColumn::make('quantityType.name')
                    ->label('Количество'),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Картинка'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория'),
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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
