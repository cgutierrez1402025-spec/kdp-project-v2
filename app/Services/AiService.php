<?php

namespace App\Services;

use App\Models\AiTool;
use App\Models\Prompt;
use App\Models\Work;

class AiService
{
    protected string $apiKey;

    protected string $defaultModel = 'gpt-4';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateContent(string $prompt, string $model = 'gpt-4'): array
    {
        return [
            'success' => true,
            'result' => 'Generated content placeholder',
            'error' => null,
        ];
    }

    public function suggestTags(string $workTitle, string $description): array
    {
        $prompt = "Suggest 5-10 relevant tags for a book with title '{$workTitle}' and description: {$description}. Return only comma-separated tags.";

        return $this->generateContent($prompt, 'gpt-3.5-turbo');
    }

    public function improveDescription(string $originalText): array
    {
        $prompt = "Improve this book description to make it more engaging and marketable. Keep it concise and compelling:\n\n{$originalText}";

        return $this->generateContent($prompt);
    }

    public function translateText(string $text, string $targetLanguage): array
    {
        $prompt = "Translate the following text to {$targetLanguage}:\n\n{$text}";

        return $this->generateContent($prompt, 'gpt-3.5-turbo');
    }

    public function savePromptExecution(
        Work $work,
        string $promptText,
        string $result,
        string $purpose,
        ?int $aiToolId = null,
        string $model = 'gpt-4',
        int $rating = 5
    ): Prompt {
        return Prompt::create([
            'work_id' => $work->id,
            'ai_tool_id' => $aiToolId,
            'title' => $this->getPurposeTitle($purpose),
            'prompt_text' => $promptText,
            'purpose' => $purpose,
            'result_text' => $result,
            'rating' => $rating,
            'reused' => false,
            'generated_final_content' => true,
        ]);
    }

    protected function getPurposeTitle(string $purpose): string
    {
        return match ($purpose) {
            'tags' => 'Tag Suggestions',
            'improve_description' => 'Description Improvement',
            'translate' => 'Translation',
            default => 'AI Generation',
        };
    }
}