<?php

namespace App\Filament\Admin\Resources\SourceUsages\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class SourceUsageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Uso de Fuente')
                    ->schema([
                        Forms\Components\Select::make('source_id')
                            ->relationship('source', 'title')
                            ->label('Fuente')
                            ->required(),

                        Forms\Components\Select::make('work_id')
                            ->relationship('work', 'title_public')
                            ->label('Obra')
                            ->required(),

                        Forms\Components\Select::make('manuscript_version_id')
                            ->relationship('manuscriptVersion', 'version_number')
                            ->label('Versión Manuscrito')
                            ->nullable(),

                        Forms\Components\Select::make('chapter_id')
                            ->relationship('chapter', 'title')
                            ->label('Capítulo')
                            ->nullable(),

                        Forms\Components\TextInput::make('usage_type')
                            ->label('Tipo de Uso')
                            ->maxLength(100),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Fragmento')
                    ->schema([
                        Forms\Components\Textarea::make('fragment')
                            ->label('Fragmento')
                            ->rows(4),

                        Forms\Components\Toggle::make('verified')
                            ->label('Verificado')
                            ->default(false),
                    ]),

                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2),
                    ]),
            ]);
    }
}
