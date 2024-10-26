<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoardingHouseResource\Pages;
use App\Filament\Resources\BoardingHouseResource\RelationManagers;
use App\Models\BoardingHouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BoardingHouseResource extends Resource
{
    protected static ?string $model = BoardingHouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-left-start-on-rectangle';

    protected static ?string $navigationGroup = 'Boarding House Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Umum')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nama')
                                    ->reactive()
                                    ->debounce(500)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('address')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->prefix('Rp. '),
                                Forms\Components\Select::make('city_id')
                                    ->relationship('city', 'name')
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->label('Thumbnail')
                                    ->required()
                                    ->directory('boarding_house'),
                                Forms\Components\RichEditor::make('description')
                                    ->required(),
                        ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Bonus Ngekos')
                            ->schema([
                                Forms\Components\Repeater::make('bonuses')
                                    ->relationship('bonuses')
                                    ->label('Bonus')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('description')
                                            ->required(),
                                        Forms\Components\FileUpload::make('image') 
                                            ->image()
                                            ->required()
                                            ->directory('bonuses'),
                                    ])->columns(2),                                    
                            ]),

                        Forms\Components\Tabs\Tab::make('Kamar')
                            ->schema([
                                Forms\Components\Repeater::make('rooms')
                                    ->relationship('rooms')
                                    ->label('Kamar')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_available')
                                            ->required(),
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('room_type')
                                            ->required(),
                                        Forms\Components\TextInput::make('square_feet') 
                                            ->numeric()
                                            ->required(),
                                        Forms\Components\TextInput::make('price_per_month')
                                            ->required()
                                            ->numeric()
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->prefix('Rp. '),
                                        Forms\Components\TextInput::make('capacity')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\Repeater::make('images')
                                            ->relationship('images')
                                            ->label('Gambar')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->image()
                                                    ->directory('rooms')
                                                    ->required(),
                                            ]),
                                        
                                    ])->columns(2),   
                            ])
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->square()
                    ->label('Thumbnail'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->numeric()
                    ->prefix('Rp. '),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBoardingHouses::route('/'),
            'create' => Pages\CreateBoardingHouse::route('/create'),
            'edit' => Pages\EditBoardingHouse::route('/{record}/edit'),
        ];
    }
}
