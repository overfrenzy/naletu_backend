<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
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

                RichEditor::make('description')
                    ->label('Описание')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                    ]),

                FileUpload::make('image')
                    ->required()
                    ->disk('public')
                    ->directory('slider-images')
                    ->label('Картинка'),

                FileUpload::make('image2')
                    ->disk('public')
                    ->directory('slider-images')
                    ->nullable()
                    ->label('Картинка с описанием'),

                Checkbox::make('clickable')
                    ->label('Кликабельно')
                    ->default(false),
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
                    ->label('Картинка с описанием'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Описание')
                    ->limit(50),

                Tables\Columns\BooleanColumn::make('clickable')
                    ->label('Кликабельно'),
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
