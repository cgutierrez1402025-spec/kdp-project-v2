<?php

namespace App\Filament\Admin\Resources\Publications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PublicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryQueryUsing(fn ($query) => $query->with(['work', 'platform', 'marketplace', 'workLanguage', 'manuscriptVersion']))
            ->columns([
                TextColumn::make('work.title_public')
                    ->label('Obra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('format')
                    ->label('Formato')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'paperback' => 'primary',
                        'hardcover' => 'success',
                        'kindle' => 'warning',
                        'audiobook' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('platform.name')
                    ->label('Plataforma')
                    ->searchable(),

                TextColumn::make('marketplace.name')
                    ->label('Marketplace')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'draft' => 'gray',
                        'processing' => 'warning',
                        'published' => 'success',
                        'error' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('format')
                    ->label('Formato')
                    ->options([
                        'paperback' => 'Paperback',
                        'hardcover' => 'Hardcover',
                        'kindle' => 'Kindle',
                        'audiobook' => 'Audiobook',
                    ]),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'processing' => 'Procesando',
                        'published' => 'Publicado',
                        'error' => 'Error',
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
