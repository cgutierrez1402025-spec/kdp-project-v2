<?php

namespace App\Filament\Admin\Resources\BookPromotions\Pages;

use App\Filament\Admin\Resources\BookPromotions\BookPromotionResource;
use Filament\Resources\Pages\EditRecord;

class EditBookPromotion extends EditRecord
{
    protected static string $resource = BookPromotionResource::class;
}
