<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'work_language_id',
        'manuscript_version_id',
        'platform_id',
        'marketplace_id',
        'format',
        'external_identifier',
        'public_url',
        'status',
        'price',
        'currency',
        'territories',
        'isbn',
        'asin',
        'published_at',
        'notes',
    ];

    protected $casts = [
        'published_at' => 'timestamp',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function workLanguage(): BelongsTo
    {
        return $this->belongsTo(WorkLanguage::class);
    }

    public function manuscriptVersion(): BelongsTo
    {
        return $this->belongsTo(ManuscriptVersion::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }
}