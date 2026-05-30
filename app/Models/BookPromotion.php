<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'marketplace_id',
        'kdp_select_period_id',
        'promotion_type',
        'promotion_name',
        'start_date',
        'end_date',
        'normal_price',
        'promotional_price',
        'status',
        'objective',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }

    public function kdpSelectPeriod(): BelongsTo
    {
        return $this->belongsTo(KdpSelectPeriod::class);
    }
}
