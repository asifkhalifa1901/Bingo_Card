<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ $card->title }}</title>
        @php
            [$cellBg, $borderColor] = match ($card->category) {
                'Icebreaker' => ['#ecfeff', '#22c55e'],
                'Educational' => ['#e0f2fe', '#0ea5e9'],
                'Work' => ['#e0e7ff', '#6366f1'],
                'Party' => ['#fee2e2', '#f97316'],
                'Family' => ['#fdf2f8', '#ec4899'],
                'Fitness' => ['#dcfce7', '#22c55e'],
                'Event' => ['#fef3c7', '#f59e0b'],
                'Hobby' => ['#e0f2fe', '#3b82f6'],
                'Seasonal' => ['#fee2e2', '#ef4444'],
                default => ['#ffffff', '#d1d5db'],
            };
        @endphp
        <style>
            * {
                box-sizing: border-box;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            body {
                margin: 0;
                padding: 24px;
                font-size: 12px;
                color: #111827;
            }

            .title {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 4px;
                text-align: center;
            }

            .subtitle {
                font-size: 11px;
                color: #6b7280;
                text-align: center;
                margin-bottom: 16px;
            }

            .grid {
                display: table;
                width: 100%;
                border-collapse: collapse;
            }

            .row {
                display: table-row;
            }

            .cell {
                display: table-cell;
                border: 1px solid {{ $borderColor }};
                padding: 8px;
                text-align: center;
                vertical-align: middle;
                font-size: 11px;
                height: 48px;
                word-wrap: break-word;
                background-color: {{ $cellBg }};
            }

            .footer {
                margin-top: 16px;
                font-size: 9px;
                color: #9ca3af;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="title">{{ $card->title }}</div>
        <div class="subtitle">
            {{ $card->description ?? 'Custom bingo card generated with Bingo Card Creator AI.' }}
        </div>

        @php
            $cells = collect($card->cells)->sortBy(['row', 'col'])->groupBy('row');
        @endphp

        <div class="grid">
            @foreach ($cells as $row => $rowCells)
                <div class="row">
                    @foreach ($rowCells->sortBy('col') as $cell)
                        <div class="cell">
                            <div>{{ $cell['text'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="footer">
            Generated {{ now()->format('Y-m-d H:i') }}
        </div>
    </body>
</html>

