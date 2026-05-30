<?php

namespace App\Filament\Admin\Resources\BookPromotions\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class BookPromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Información de Promoción')
                    ->schema([
                        Forms\Components\Select::make('publication_id')
                            ->relationship('publication', 'external_identifier')
                            ->label('Publicación')
                            ->required(),

                        Forms\Components\Select::make('marketplace_id')
                            ->relationship('marketplace', 'name')
                            ->label('Marketplace')
                            ->nullable(),

                        Forms\Components\Select::make('kdp_select_period_id')
                            ->relationship('kdpSelectPeriod', 'id')
                            ->label('Período KDP Select')
                            ->nullable(),

                        Forms\Components\Select::make('promotion_type')
                            ->label('Tipo de Promoción')
                            ->options([
                                'free' => 'Gratis',
                                'kindle_countdown' => 'Kindle Countdown',
                                'price_promo' => 'Precio Promocional',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('promotion_name')
                            ->label('Nombre de Promoción')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Fechas y Precios')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Fecha Inicio')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Fecha Fin')
                            ->required(),

                        Forms\Components\TextInput::make('normal_price')
                            ->label('Precio Normal')
                            ->numeric()
                            ->step('0.01'),

                        Forms\Components\TextInput::make('promotional_price')
                            ->label('Precio Promocional')
                            ->numeric()
                            ->step('0.01'),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'planned' => 'Planeada',
                                'active' => 'Activa',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada',
                            ])
                            ->default('planned')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detalles')
                    ->schema([
                        Forms\Components\Textarea::make('objective')
                            ->label('Objetivo')
                            ->rows(3),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2),
                    ]),
            ]);
    }
}
