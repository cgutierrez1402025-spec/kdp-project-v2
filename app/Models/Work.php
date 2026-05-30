<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'title',
        'slug',
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
        'description',
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

    public function languages(): HasMany
    {
        return $this->hasMany(WorkLanguage::class);
    }

    public function editions(): HasMany
    {
        return $this->hasMany(Edition::class);
    }

    public function manuscriptVersions(): HasMany
    {
        return $this->hasMany(ManuscriptVersion::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function illustrations(): HasMany
    {
        return $this->hasMany(Illustration::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(Source::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function royaltyEntries(): HasMany
    {
        return $this->hasMany(RoyaltyEntry::class, 'work_id');
    }

    public function bookEvents(): HasMany
    {
        return $this->hasMany(BookEvent::class, 'work_id');
    }

    public function physicalPrintRuns(): HasMany
    {
        return $this->hasMany(PhysicalPrintRun::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function bookDeliveries(): HasMany
    {
        return $this->hasMany(BookDelivery::class);
    }

    public function awardSubmissions(): HasMany
    {
        return $this->hasMany(AwardSubmission::class);
    }

    public function promotionalAssets(): HasMany
    {
        return $this->hasMany(PromotionalAsset::class);
    }

    public function aPlusProjects(): HasMany
    {
        return $this->hasMany(AplusProject::class);
    }
}
