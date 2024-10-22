<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Слайдеры';

    protected static ?string $pluralLabel = 'Слайдеры';

    protected static ?string $label = 'Слайдер';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Название Слайдера'),

                TextInput::make('special')
                    ->label('Ссылка на Специальное'),

                FileUpload::make('image')
                    ->disk('public')
                    ->directory('slider-images')
                    ->nullable()
                    ->label('Картинка'),

                Select::make('type')
                    ->options([
                        'home' => 'Главная',
                        'banner' => 'Баннер',
                    ])
                    ->required()
                    ->label('Тип Слайдера'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название Слайдера'),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Картинка'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип Слайдера'),
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
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
