<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class ManuscriptVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'work_language_id',
        'parent_version_id',
        'edition_id',
        'version_number',
        'name',
        'status',
        'html_content',
        'file_path',
        'file_hash',
        'word_count',
        'chapter_count',
        'image_count',
        'change_summary',
        'is_candidate',
        'is_final',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'is_candidate' => 'boolean',
        'is_final' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'timestamp',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($version) {
            $version->calculateCounts();
        });

        static::updating(function ($version) {
            if ($version->isDirty('html_content')) {
                $version->calculateCounts();
            }
        });
    }

    protected function calculateCounts(): void
    {
        if ($this->html_content) {
            $this->word_count = $this->countWords($this->html_content);
            $this->chapter_count = $this->countChapters($this->html_content);
            $this->image_count = $this->countImages($this->html_content);
            $this->file_hash = Hash::make($this->html_content);
        }
    }

    protected function countWords(string $content): int
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', '', $text);

        return (int) str_word_count(trim($text));
    }

    protected function countChapters(string $content): int
    {
        preg_match_all('/<h[1-6][^>]*>.*?<\/h[1-6]>/is', $content, $matches);

        return count($matches[0]);
    }

    protected function countImages(string $content): int
    {
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);

        return count($matches[1]);
    }

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    }

    public function workLanguage(): BelongsTo
    {
        return $this->belongsTo(WorkLanguage::class);
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parentVersion(): BelongsTo
    {
        return $this->belongsTo(ManuscriptVersion::class, 'parent_version_id');
    }

    public function childVersions(): HasMany
    {
        return $this->hasMany(ManuscriptVersion::class, 'parent_version_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function createChildVersion(array $data = []): ManuscriptVersion
    {
        return static::create(array_merge([
            'work_id' => $this->work_id,
            'work_language_id' => $this->work_language_id,
            'parent_version_id' => $this->id,
            'version_number' => $this->nextVersionNumber(),
            'status' => 'draft',
            'is_candidate' => false,
            'is_final' => false,
            'is_published' => false,
        ], $data));
    }

    public function nextVersionNumber(): string
    {
        $latest = static::where('work_id', $this->work_id)
            ->where('work_language_id', $this->work_language_id)
            ->orderByDesc('id')
            ->first();

        return $latest ? (string) (((int) $latest->version_number) + 1) : '1';
    }

    public function markAsFinal(): bool
    {
        return $this->update([
            'is_final' => true,
            'is_candidate' => false,
            'status' => 'final',
        ]);
    }

    public function markAsPublished(): bool
    {
        return $this->update([
            'is_published' => true,
            'is_final' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
