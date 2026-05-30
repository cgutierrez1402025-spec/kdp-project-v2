<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenAI\Client;
use Anthropic\Client as AnthropicClient;

class AiTool extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'provider',
        'tool_type',
        'model',
        'url',
        'strengths',
        'weaknesses',
        'quality_score',
        'notes',
    ];

    protected $casts = [
        'quality_score' => 'integer',
    ];

    const PROVIDERS = [
        'openai' => 'OpenAI',
        'anthropic' => 'Anthropic',
        'google' => 'Google',
        'cohere' => 'Cohere',
    ];

    public function prompts(): HasMany
    {
        return $this->hasMany(Prompt::class);
    }

    public function getApiClient()
    {
        return match($this->provider) {
            'openai' => app(Client::class),
            'anthropic' => app(AnthropicClient::class),
            default => null,
        };
    }
}
