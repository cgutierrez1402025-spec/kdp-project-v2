<?php

namespace App\Filament\Admin\Resources\Works\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryQueryUsing(fn ($query) => $query->with(['series', 'user', 'publications']))
            ->columns([
                TextColumn::make('title_public')
                    ->label('Título Público')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('author_name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('series.title')
                    ->label('Serie')
                    ->sortable(),

                TextColumn::make('series_number')
                    ->label('Número en Serie')
                    ->sortable(),

                TextColumn::make('genre')
                    ->label('Género')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'idea' => 'gray',
                        'redaccion' => 'warning',
                        'revision' => 'info',
                        'preparacion' => 'primary',
                        'publicada' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('original_language')
                    ->label('Idioma')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'idea' => 'Idea',
                        'redaccion' => 'Redacción',
                        'revision' => 'Revisión',
                        'preparacion' => 'Preparación',
                        'publicada' => 'Publicada',
                    ]),

                SelectFilter::make('genre')
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
                        'misterio' => 'Misterio',
                        'thriller' => 'Thriller',
                        'otra' => 'Otra',
                    ]),

                SelectFilter::make('original_language')
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
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
