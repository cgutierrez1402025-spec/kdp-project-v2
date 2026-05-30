<?php

namespace App\Filament\Admin\Resources\SourceUsages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SourceUsagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryQueryUsing(fn ($query) => $query->with(['source', 'work', 'manuscriptVersion', 'chapter']))
            ->columns([
                TextColumn::make('source.title')
                    ->label('Fuente')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('work.title_public')
                    ->label('Obra')
                    ->searchable(),

                TextColumn::make('manuscriptVersion.version_number')
                    ->label('Versión')
                    ->searchable(),

                TextColumn::make('chapter.title')
                    ->label('Capítulo')
                    ->searchable(),

                TextColumn::make('usage_type')
                    ->label('Tipo')
                    ->searchable(),

                ToggleColumn::make('verified')
                    ->label('Verificado'),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
