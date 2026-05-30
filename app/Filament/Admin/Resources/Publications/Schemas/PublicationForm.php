<?php

namespace App\Filament\Admin\Resources\Publications\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class PublicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Información Básica')
                    ->schema([
                        Forms\Components\Select::make('work_id')
                            ->relationship('work', 'title_public')
                            ->label('Obra')
                            ->required(),

                        Forms\Components\Select::make('work_language_id')
                            ->relationship('workLanguage', 'language_code')
                            ->label('Idioma')
                            ->required(),

                        Forms\Components\Select::make('manuscript_version_id')
                            ->relationship('manuscriptVersion', 'version_number')
                            ->label('Versión de Manuscrito')
                            ->nullable(),

                        Forms\Components\Select::make('platform_id')
                            ->relationship('platform', 'name')
                            ->label('Plataforma')
                            ->required(),

                        Forms\Components\Select::make('marketplace_id')
                            ->relationship('marketplace', 'name')
                            ->label('Marketplace')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Formato y Precio')
                    ->schema([
                        Forms\Components\Select::make('format')
                            ->label('Formato')
                            ->options([
                                'paperback' => 'Paperback',
                                'hardcover' => 'Hardcover',
                                'kindle' => 'Kindle',
                                'audiobook' => 'Audiobook',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->step('0.01')
                            ->nullable(),

                        Forms\Components\TextInput::make('currency')
                            ->label('Moneda')
                            ->maxLength(3)
                            ->default('USD'),

                        Forms\Components\TextInput::make('external_identifier')
                            ->label('Identificador Externo')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('isbn')
                            ->label('ISBN')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('asin')
                            ->label('ASIN')
                            ->maxLength(20),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Publicación')
                    ->schema([
                        Forms\Components\TextInput::make('public_url')
                            ->label('URL Pública')
                            ->url()
                            ->maxLength(500),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft' => 'Borrador',
                                'processing' => 'Procesando',
                                'published' => 'Publicado',
                                'error' => 'Error',
                            ])
                            ->default('draft'),

                        Forms\Components\DatePicker::make('published_at')
                            ->label('Fecha de Publicación'),

                        Forms\Components\Textarea::make('territories')
                            ->label('Territorios')
                            ->rows(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3),
                    ]),
            ]);
    }
}
