<?php

namespace App\Http\Controllers;

use App\Models\BingoCard;
use App\Services\AiBingoGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BingoCardController extends Controller
{
    public function templates()
    {
        $templates = BingoCard::query()
            ->where('is_template', true)
            ->orderBy('title')
            ->paginate(12);

        return view('cards.templates', compact('templates'));
    }

    public function show(Request $request, BingoCard $card)
    {
        $gridSize = (int) $request->query('grid_size', $card->grid_size);
        $gridSize = max(3, min(5, $gridSize));

        $existingCells = $card->cells ?? [];
        $normalisedCells = $this->normaliseCellsWithSequence($existingCells, $gridSize, $card->title);

        return view('cards.show', [
            'card' => $card,
            'gridSize' => $gridSize,
            'cells' => $normalisedCells,
        ]);
    }

    public function edit(Request $request, BingoCard $card)
    {
        $gridSize = (int) $request->query('grid_size', $card->grid_size);
        $gridSize = max(3, min(5, $gridSize));

        $existingCells = $card->cells ?? [];
        $normalisedCells = $this->normaliseCellsWithSequence($existingCells, $gridSize, $card->title);

        return view('cards.edit', [
            'card' => $card,
            'gridSize' => $gridSize,
            'cells' => $normalisedCells,
        ]);
    }

    public function update(Request $request, BingoCard $card)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'grid_size' => ['required', 'integer', 'min:3', 'max:5'],
            'cells' => ['required', 'array'],
            'cells.*.row' => ['required', 'integer'],
            'cells.*.col' => ['required', 'integer'],
            'cells.*.text' => ['nullable', 'string', 'max:255'],
        ]);

        // Normalise cells to match the selected grid size (e.g. 4x4 = 16 cells)
        $gridSize = (int) $data['grid_size'];
        $flatCells = array_values($data['cells']);
        $normalisedCells = $this->normaliseCellsWithSequence($flatCells, $gridSize, $data['title'] ?? $card->title);

        $card->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'grid_size' => $gridSize,
            'cells' => $normalisedCells,
            'source' => $card->source === 'template' ? 'template' : 'manual',
        ]);

        return redirect()
            ->route('cards.show', $card)
            ->with('status', 'Bingo card updated.');
    }

    public function generate(Request $request, AiBingoGenerator $generator)
    {
        $validated = $request->validate([
            'prompt' => ['nullable', 'string', 'max:1000', 'required_without:template_id'],
            'topic' => ['nullable', 'string', 'max:255'],
            'grid_size' => ['nullable', 'integer', 'min:3', 'max:5'],
            'template_id' => ['nullable', 'integer', 'exists:bingo_cards,id', 'required_without:prompt'],
        ]);

        $gridSize = $validated['grid_size'] ?? 5;
        $topic = $validated['topic'] ?? null;

        if (! empty($validated['template_id'])) {
            $template = BingoCard::query()
                ->where('is_template', true)
                ->findOrFail($validated['template_id']);

            $card = $template->replicate([
                'uuid',
                'is_template',
                'created_at',
                'updated_at',
            ]);
            $card->is_template = false;
            $card->source = 'template';
        } else {
            $generated = $generator->generate($validated['prompt'], $gridSize, $topic);

            $card = new BingoCard([
                'title' => $generated['title'],
                'description' => $generated['description'],
                'grid_size' => $generated['grid_size'],
                'cells' => $generated['cells'],
                'is_template' => false,
                'source' => 'ai',
            ]);
        }

        DB::transaction(function () use (&$card) {
            $card->save();
        });

        return redirect()->route('cards.edit', $card);
    }

    public function download(BingoCard $card)
    {
        $pdf = Pdf::loadView('cards.pdf', ['card' => $card]);

        $filename = str()->slug($card->title ?: 'bingo-card').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Normalise cells to match a grid size and auto-fill missing cells with sequential "Item N" labels.
     */
    private function normaliseCellsWithSequence(array $cells, int $gridSize, ?string $baseLabel = null): array
    {
        $baseLabel = $baseLabel !== null && $baseLabel !== '' ? $baseLabel : 'Item';
        $maxCells = $gridSize * $gridSize;

        // Special case: numeric range like "1-55 number card" or "1-50 numbers" -> fill with shuffled numbers
        // We only care that the title STARTS with "start-end", any words after are ignored.
        if (preg_match('/^\s*(\d+)\s*-\s*(\d+)/i', $baseLabel, $rangeMatches)) {
            $start = (int) $rangeMatches[1];
            $end = (int) $rangeMatches[2];
            if ($start > $end) {
                [$start, $end] = [$end, $start];
            }

            $pool = range($start, $end);
            if (count($pool) === 0) {
                $pool = range(1, $maxCells);
            }

            $numbers = [];
            while (count($numbers) < $maxCells) {
                $shuffled = $pool;
                shuffle($shuffled);
                foreach ($shuffled as $n) {
                    $numbers[] = (string) $n;
                    if (count($numbers) >= $maxCells) {
                        break 2;
                    }
                }
            }

            $normalisedCells = [];
            foreach (range(0, $maxCells - 1) as $index) {
                $row = intdiv($index, $gridSize);
                $col = $index % $gridSize;

                $normalisedCells[] = [
                    'row' => $row,
                    'col' => $col,
                    'text' => $numbers[$index],
                    'image_prompt' => null,
                ];
            }

            return $normalisedCells;
        }

        // Default behaviour: continue sequence based on existing text
        $flatCells = array_values($cells);

        // Trim to max cells for this grid
        $flatCells = array_slice($flatCells, 0, $maxCells);

        // Determine the last used item number (best-effort)
        $lastNumber = 0;
        foreach ($flatCells as $cell) {
            $text = $cell['text'] ?? '';
            if ($text === '') {
                continue;
            }

            if (preg_match('/(\d+)\s*$/', $text, $matches)) {
                $num = (int) $matches[1];
                if ($num > $lastNumber) {
                    $lastNumber = $num;
                }
            } else {
                $lastNumber++;
            }
        }

        // Pad with numbered items until grid is full
        while (count($flatCells) < $maxCells) {
            $lastNumber++;
            $flatCells[] = [
                'text' => $baseLabel . ' ' . $lastNumber,
                'image_prompt' => null,
            ];
        }

        // Rebuild with proper row/col
        $normalisedCells = [];
        foreach (range(0, $maxCells - 1) as $index) {
            $row = intdiv($index, $gridSize);
            $col = $index % $gridSize;
            $cell = $flatCells[$index] ?? [];

            $normalisedCells[] = [
                'row' => $row,
                'col' => $col,
                'text' => $cell['text'] ?? '',
                'image_prompt' => $cell['image_prompt'] ?? null,
            ];
        }

        return $normalisedCells;
    }
}

