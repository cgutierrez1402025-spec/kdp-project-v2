<?php

namespace App\Filament\Resources;

use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Obras';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica')
                    ->schema([
                        Forms\Components\TextInput::make('title_internal')
                            ->label('Título Interno')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('title_public')
                            ->label('Título Público')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subtitle')
                            ->label('Subtítulo')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('author_name')
                            ->label('Nombre del Autor')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pen_name')
                            ->label('Seudónimo')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Detalles de la Obra')
                    ->schema([
                        Forms\Components\Select::make('genre')
                            ->label('Género')
                            ->options([
                                'ficcion' => 'Ficción',
                                'novela' => 'Novela',
                                'poesia' => 'Poesía',
                                'cuento' => 'Cuento',
                                'drama' => 'Drama',
                                'infantil' => 'Infantil',
                                'juvenil' => 'Juvenil',
                                'romance' => 'Romance',
                                'ciencia_ficcion' => 'Ciencia Ficción',
                                'fantasia' => 'Fantasía',
                                'misterio' => 'Misterio',
                                'thriller' => 'Thriller',
                                'otra' => 'Otra',
                            ]),

                        Forms\Components\Select::make('work_type')
                            ->label('Tipo de Obra')
                            ->options([
                                'original' => 'Original',
                                'reescritura' => 'Reescritura',
                                'adaptacion' => 'Adaptación',
                                'traduccion' => 'Traducción',
                            ]),

                        Forms\Components\Select::make('original_language')
                            ->label('Idioma Original')
                            ->options([
                                'es' => 'Español',
                                'en' => 'Inglés',
                                'fr' => 'Francés',
                                'de' => 'Alemán',
                                'it' => 'Italiano',
                                'pt' => 'Portugués',
                                'ru' => 'Ruso',
                                'ja' => 'Japonés',
                                'zh' => 'Chino',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'idea' => 'Idea',
                                'redaccion' => 'Redacción',
                                'revision' => 'Revisión',
                                'preparacion' => 'Preparación',
                                'publicada' => 'Publicada',
                            ])
                            ->default('idea')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Descripción y Notas')
                    ->schema([
                        Forms\Components\Textarea::make('description_marketing')
                            ->label('Descripción de Marketing')
                            ->rows(4),

                        Forms\Components\Textarea::make('description_internal')
                            ->label('Descripción Interna')
                            ->rows(4),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Fechas y Público')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Fecha de Inicio'),

                        Forms\Components\DatePicker::make('planned_publish_date')
                            ->label('Fecha Prevista de Publicación'),

                        Forms\Components\TextInput::make('target_audience')
                            ->label('Público Objetivo'),

                        Forms\Components\TextInput::make('age_recommendation')
                            ->label('Recomendación de Edad'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_public')
                    ->label('Título Público')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author_name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('genre')
                    ->label('Género')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'idea' => 'gray',
                        'redaccion' => 'warning',
                        'revision' => 'info',
                        'preparacion' => 'primary',
                        'publicada' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('original_language')
                    ->label('Idioma')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'idea' => 'Idea',
                        'redaccion' => 'Redacción',
                        'revision' => 'Revisión',
                        'preparacion' => 'Preparación',
                        'publicada' => 'Publicada',
                    ]),

                Tables\Filters\SelectFilter::make('genre')
                    ->label('Género')
                    ->options([
                        'ficcion' => 'Ficción',
                        'novela' => 'Novela',
                        'poesia' => 'Poesía',
                        'cuento' => 'Cuento',
                        'infantil' => 'Infantil',
                        'juvenil' => 'Juvenil',
                        'romance' => 'Romance',
                        'ciencia_ficcion' => 'Ciencia Ficción',
                        'fantasia' => 'Fantasía',
                    ]),

                Tables\Filters\SelectFilter::make('original_language')
                    ->label('Idioma Original')
                    ->options([
                        'es' => 'Español',
                        'en' => 'Inglés',
                        'fr' => 'Francés',
                        'de' => 'Alemán',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers aquí
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\WorkResource\Pages\ListWorks::class,
            'create' => \App\Filament\Resources\WorkResource\Pages\CreateWork::class,
            'edit' => \App\Filament\Resources\WorkResource\Pages\EditWork::class,
        ];
    }
}
