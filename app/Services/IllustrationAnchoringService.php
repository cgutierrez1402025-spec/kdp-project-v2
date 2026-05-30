<?php

namespace App\Services;

use App\Models\IllustrationAnchor;
use App\Models\ManuscriptVersion;
use App\Models\Illustration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class IllustrationAnchoringService
{
    public function applyAnchor(IllustrationAnchor $anchor): ManuscriptVersion|false
    {
        return DB::transaction(function () use ($anchor) {
            $manuscript = $anchor->manuscriptVersion;
            $illustration = $anchor->illustration;

            if (!$manuscript || !$illustration) {
                return false;
            }

            $html = $manuscript->html_content ?? '';

            $insertionPoint = $this->findInsertionPoint($html, $anchor);

            if ($insertionPoint === false) {
                Log::warning("Could not find insertion point for anchor {$anchor->id}");
                return false;
            }

            $imageTag = $this->buildImageTag($illustration, $anchor);
            $newHtml = $this->insertAtPosition($html, $imageTag, $insertionPoint, $anchor);

            $newVersion = ManuscriptVersion::create([
                'work_id' => $manuscript->work_id,
                'work_language_id' => $manuscript->work_language_id,
                'parent_version_id' => $manuscript->id,
                'edition_id' => $manuscript->edition_id,
                'version_number' => $this->nextVersionNumber($manuscript),
                'name' => 'Anchored: ' . $illustration->title,
                'status' => 'draft',
                'html_content' => $newHtml,
                'is_candidate' => false,
                'is_final' => false,
                'is_published' => false,
                'created_by' => $manuscript->created_by,
            ]);

            $anchor->status = 'applied';
            $anchor->save();

            app(VersionManager::class)->processVersion($newVersion);

            return $newVersion;
        });
    }

    protected function findInsertionPoint(string $html, IllustrationAnchor $anchor): int|false
    {
        if ($anchor->search_text) {
            $pos = stripos($html, $anchor->search_text);
            return $pos !== false ? $pos + strlen($anchor->search_text) : false;
        }

        if ($anchor->css_selector) {
            preg_match('/<' . preg_quote($anchor->css_selector, '/') . '[^>]*>/i', $html, $matches, PREG_OFFSET_CAPTURE);
            return $matches ? ($matches[0][1] + strlen($matches[0][0])) : false;
        }

        if ($anchor->html_marker) {
            $pos = stripos($html, $anchor->html_marker);
            return $pos !== false ? $pos + strlen($anchor->html_marker) : false;
        }

        return false;
    }

    protected function buildImageTag(Illustration $illustration, IllustrationAnchor $anchor): string
    {
        $alt = $illustration->alt_text ?? $illustration->title;
        $url = $illustration->file_optimized ?? $illustration->file_original;
        
        return sprintf(
            '<img src="%s" alt="%s" class="anchor-%d" />',
            $url,
            $alt,
            $anchor->id
        );
    }

    protected function insertAtPosition(string $html, string $tag, int $position, IllustrationAnchor $anchor): string
    {
        $before = substr($html, 0, $position);
        $after = substr($html, $position);
        
        $insertion = $anchor->position_type 
            ? ($anchor->position_type === 'before' ? $tag . $before . $after : $before . $after . $tag)
            : $before . $tag . $after;

        return $insertion;
    }

    protected function nextVersionNumber(ManuscriptVersion $manuscript): string
    {
        $latest = ManuscriptVersion::where('work_id', $manuscript->work_id)
            ->where('work_language_id', $manuscript->work_language_id)
            ->orderByDesc('id')
            ->first();

        return $latest ? (string) (((int) $latest->version_number) + 1) : '1';
    }
}