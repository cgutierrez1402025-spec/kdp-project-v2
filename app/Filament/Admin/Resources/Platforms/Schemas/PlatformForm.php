<?php

namespace App\Filament\Admin\Resources\Platforms\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class PlatformForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Información de Plataforma')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),
                    ]),
            ]);
    }
}
