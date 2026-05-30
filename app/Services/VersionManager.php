<?php

namespace App\Services;

use App\Models\ManuscriptVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VersionManager
{
    protected const IMG_PATTERN = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';
    protected const CHAPTER_PATTERNS = [
        '/<h1[^>]*>(.*?)<\/h1>/is',
        '/<h2[^>]*>(.*?)<\/h2>/is',
    ];

    public function processVersion(ManuscriptVersion $version): void
    {
        $content = $version->html_content;

        if (!$content) {
            return;
        }

        $version->file_hash = hash('sha256', $content);
        $version->word_count = $this->countWords($content);
        $version->image_count = $this->extractImages($content, $version);
        $version->chapter_count = $this->extractChapters($content);

        if ($version->parent_version_id) {
            $version->change_summary = $this->generateDiff($version);
        }

        $version->save();
    }

    protected function countWords(string $content): int
    {
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        return (int) str_word_count(trim($text));
    }

    protected function extractImages(string $content, ManuscriptVersion $version): int
    {
        preg_match_all(self::IMG_PATTERN, $content, $matches);
        $count = count($matches[1] ?? []);

        foreach ($matches[1] ?? [] as $imgPath) {
            $this->extractImageToTemp($imgPath, $version);
        }

        return $count;
    }

    protected function extractImageToTemp(string $imgPath, ManuscriptVersion $version): void
    {
        $tempDir = storage_path("app/temp/images/{$version->id}");
        
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        if (filter_var($imgPath, FILTER_VALIDATE_URL)) {
            try {
                $contents = file_get_contents($imgPath);
                if ($contents) {
                    $filename = basename(parse_url($imgPath, PHP_URL_PATH));
                    file_put_contents("{$tempDir}/{$filename}", $contents);
                }
            } catch (\Exception $e) {
                Log::warning("Could not download image: {$imgPath}");
            }
        } elseif (Storage::exists($imgPath)) {
            $contents = Storage::get($imgPath);
            $filename = basename($imgPath);
            file_put_contents("{$tempDir}/{$filename}", $contents);
        }
    }

    protected function extractChapters(string $content): int
    {
        $count = 0;
        foreach (self::CHAPTER_PATTERNS as $pattern) {
            preg_match_all($pattern, $content, $matches);
            $count += count($matches[0]);
        }
        return $count;
    }

    protected function generateDiff(ManuscriptVersion $version): string
    {
        $parent = $version->parentVersion;
        
        if (!$parent || !$parent->html_content) {
            return 'Initial version';
        }

        $changes = [];
        
        $oldWords = $this->countWords($parent->html_content);
        $newWords = $version->word_count;
        $changes[] = "Words: {$oldWords} → {$newWords} (" . ($newWords - $oldWords) . ")";

        $oldImages = $parent->image_count ?? 0;
        $newImages = $version->image_count ?? 0;
        $changes[] = "Images: {$oldImages} → {$newImages}";

        $oldChapters = $parent->chapter_count ?? 0;
        $newChapters = $version->chapter_count ?? 0;
        $changes[] = "Chapters: {$oldChapters} → {$newChapters}";

        return implode('; ', $changes);
    }
}