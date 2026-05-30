<?php

namespace App\Filament\Admin\Resources\ManuscriptVersions\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class ManuscriptVersionForm
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

                        Forms\Components\Select::make('parent_version_id')
                            ->relationship('parentVersion', 'version_number')
                            ->label('Versión Padre')
                            ->nullable(),

                        Forms\Components\Select::make('edition_id')
                            ->relationship('edition', 'edition_number')
                            ->label('Edición')
                            ->nullable(),

                        Forms\Components\TextInput::make('version_number')
                            ->label('Número de Versión')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft' => 'Borrador',
                                'review' => 'Revisión',
                                'final' => 'Final',
                                'published' => 'Publicado',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contenido')
                    ->schema([
                        Forms\Components\RichEditor::make('html_content')
                            ->label('Contenido HTML')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('file_path')
                            ->label('Archivo')
                            ->maxLength(512),
                    ]),

                Forms\Components\Section::make('Opciones')
                    ->schema([
                        Forms\Components\Toggle::make('is_candidate')
                            ->label('Candidata'),

                        Forms\Components\Toggle::make('is_final')
                            ->label('Final'),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Publicada'),

                        Forms\Components\Textarea::make('change_summary')
                            ->label('Resumen de Cambios')
                            ->rows(2),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Estadísticas')
                    ->schema([
                        Forms\Components\TextInput::make('word_count')
                            ->label('Palabras')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('chapter_count')
                            ->label('Capítulos')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('image_count')
                            ->label('Imágenes')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(3),
            ]);
    }
}
