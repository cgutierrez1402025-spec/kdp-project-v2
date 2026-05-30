<?php

namespace App\Filament\Admin\Resources\Marketplaces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MarketplacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryQueryUsing(fn ($query) => $query->with('platform'))
            ->columns([
                TextColumn::make('platform.name')
                    ->label('Plataforma')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currency')
                    ->label('Moneda')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->label('Plataforma')
                    ->relationship('platform', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
