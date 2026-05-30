<?php

namespace App\Filament\Admin\Resources\ManuscriptVersions\Pages;

use App\Filament\Admin\Resources\ManuscriptVersions\ManuscriptVersionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewManuscriptVersion extends ViewRecord
{
    protected static string $resource = ManuscriptVersionResource::class;

    public function form(Schema $schema): Schema
    {
        return ManuscriptVersionForm::configure($schema);
    }
}
