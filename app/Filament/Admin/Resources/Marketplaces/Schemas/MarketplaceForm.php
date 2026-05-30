<?php

namespace App\Filament\Admin\Resources\Marketplaces\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class MarketplaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Información de Marketplace')
                    ->schema([
                        Forms\Components\Select::make('platform_id')
                            ->relationship('platform', 'name')
                            ->label('Plataforma')
                            ->required(),

                        Forms\Components\TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('currency')
                            ->label('Moneda')
                            ->maxLength(3)
                            ->default('USD'),
                    ])
                    ->columns(2),
            ]);
    }
}
