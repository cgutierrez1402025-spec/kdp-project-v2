<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KdpSelectPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'start_date',
        'end_date',
        'auto_renewal',
        'free_promo_days_allowed',
        'free_promo_days_used',
        'free_promo_days_remaining',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_renewal' => 'boolean',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function bookPromotions(): HasMany
    {
        return $this->hasMany(BookPromotion::class);
    }
}
