<?php

namespace App\Filament\Admin\Resources\ManuscriptVersions\Tables;

use App\Filament\Admin\Resources\ManuscriptVersions\ManuscriptVersionResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ManuscriptVersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryQueryUsing(fn ($query) => $query->with(['work', 'workLanguage', 'edition', 'creator']))
            ->columns([
                TextColumn::make('work.title_public')
                    ->label('Obra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version_number')
                    ->label('Versión')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('workLanguage.language_code')
                    ->label('Idioma'),

                TextColumn::make('parent_version_number')
                    ->label('Padre')
                    ->formatStateUsing(fn ($record) => $record->parentVersion->version_number ?? '-')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'draft' => 'gray',
                        'review' => 'warning',
                        'final' => 'primary',
                        'published' => 'success',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_candidate')
                    ->label('Candidata'),

                ToggleColumn::make('is_final')
                    ->label('Final'),

                ToggleColumn::make('is_published')
                    ->label('Publicada'),

                TextColumn::make('word_count')
                    ->label('Palabras')
                    ->numeric(),

                TextColumn::make('chapter_count')
                    ->label('Capítulos')
                    ->numeric(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'review' => 'Revisión',
                        'final' => 'Final',
                        'published' => 'Publicado',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('createVersion')
                    ->label('Crear Nueva Versión')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record) {
                        $newVersion = $record->createChildVersion([
                            'html_content' => $record->html_content,
                            'notes' => 'Clonado de versión '.$record->version_number,
                        ]);

                        return redirect()->to(ManuscriptVersionResource::getUrl('edit', ['record' => $newVersion]));
                    })
                    ->visible(fn ($record) => ! $record->is_published),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
