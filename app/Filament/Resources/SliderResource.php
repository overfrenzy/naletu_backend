<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
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

                TextInput::make('slug')
                    ->disabled()
                    ->label('Slug'),

                TextInput::make('description')
                    ->label('Описание'),

                FileUpload::make('image')
                    ->disk('public')
                    ->directory('slider-images')
                    ->nullable()
                    ->label('Картинка'),

                FileUpload::make('image2')
                    ->disk('public')
                    ->directory('slider-images')
                    ->nullable()
                    ->label('Картинка 2'),
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

                Tables\Columns\ImageColumn::make('image2')
                    ->label('Картинка 2'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50),
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
}
