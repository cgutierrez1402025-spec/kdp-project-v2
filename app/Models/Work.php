<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Series;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'series_id',
        'series_number',
        'title_internal',
        'title_public',
        'subtitle',
        'author_name',
        'pen_name',
        'genre',
        'subgenre',
        'work_type',
        'original_language',
        'status',
        'target_audience',
        'age_recommendation',
        'description_internal',
        'description_marketing',
        'start_date',
        'planned_publish_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'planned_publish_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }
}
