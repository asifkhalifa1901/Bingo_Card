<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AiBingoGenerator
{
    public function generate(string $prompt, int $gridSize = 5, ?string $topic = null): array
    {
        $apiKey = Config::get('services.openai.api_key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (! $apiKey) {
            return $this->fallback($prompt, $gridSize, $topic);
        }

        $systemMessage = 'You generate structured bingo card data as pure JSON (no markdown). '
            .'Respond with an object: { "title": string, "description": string, "grid_size": number, "cells": [ { "row": number, "col": number, "text": string, "image_prompt": string|null } ] }. '
            ."grid_size must be {$gridSize}. Create fun, concise cell text for a bingo game based on the topic and description. "
            .'image_prompt should be a short description that could be used to generate an image for that cell.';

        $topicLine = $topic ? "Topic: {$topic}\n" : '';
        $userMessage = "Create a {$gridSize}x{$gridSize} bingo card.\n{$topicLine}Description: {$prompt}";

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
            ]);

        if (! $response->successful()) {
            return $this->fallback($prompt, $gridSize, $topic);
        }

        $content = Arr::get($response->json(), 'choices.0.message.content', '');
        $decoded = json_decode($content, true);

        if (! is_array($decoded) || empty($decoded['cells']) || empty($decoded['title'])) {
            return $this->fallback($prompt, $gridSize, $topic);
        }

        return [
            'title' => $decoded['title'],
            'description' => $decoded['description'] ?? ($topic ? "{$topic} - {$prompt}" : $prompt),
            'grid_size' => $gridSize,
            'cells' => $this->normaliseCells($decoded['cells'], $gridSize),
        ];
    }

    protected function normaliseCells(array $cells, int $gridSize): array
    {
        $normalised = [];

        foreach ($cells as $cell) {
            $row = (int) ($cell['row'] ?? 0);
            $col = (int) ($cell['col'] ?? 0);

            if ($row < 0 || $row >= $gridSize || $col < 0 || $col >= $gridSize) {
                continue;
            }

            $normalised[] = [
                'row' => $row,
                'col' => $col,
                'text' => (string) ($cell['text'] ?? ''),
                'image_prompt' => $cell['image_prompt'] ?? null,
            ];
        }

        // If API didn't provide a full grid, fill sequentially.
        if (count($normalised) < $gridSize * $gridSize) {
            $needed = $gridSize * $gridSize - count($normalised);
            $index = 1;

            for ($i = 0; $i < $needed; $i++) {
                $row = intdiv(count($normalised), $gridSize);
                $col = count($normalised) % $gridSize;

                $normalised[] = [
                    'row' => $row,
                    'col' => $col,
                    'text' => "Item {$index}",
                    'image_prompt' => null,
                ];

                $index++;
            }
        }

        return $normalised;
    }

    protected function fallback(string $prompt, int $gridSize, ?string $topic = null): array
    {
        $cells = [];
        $index = 1;
        $baseLabel = $topic ?: 'Item';

        for ($row = 0; $row < $gridSize; $row++) {
            for ($col = 0; $col < $gridSize; $col++) {
                $cells[] = [
                    'row' => $row,
                    'col' => $col,
                    'text' => "{$baseLabel} {$index}",
                    'image_prompt' => null,
                ];

                $index++;
            }
        }

        return [
            'title' => $topic ? "{$topic} Bingo" : 'AI Bingo Card',
            'description' => $topic ? "{$topic} - {$prompt}" : $prompt,
            'grid_size' => $gridSize,
            'cells' => $cells,
        ];
    }
}

