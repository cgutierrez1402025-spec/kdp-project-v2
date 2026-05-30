<?php

namespace App\Filament\Admin\Resources\ManuscriptVersions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $title = 'Capítulos';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('chapter_order')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),

                TextColumn::make('level')
                    ->label('Nivel'),

                TextColumn::make('word_count')
                    ->label('Palabras')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteAction::make()->bulk(),
            ]);
    }
}
