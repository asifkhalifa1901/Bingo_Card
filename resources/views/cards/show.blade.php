<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Preview Bingo Card - {{ $card->title }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">
        <header class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur">
            <div class="mx-auto max-w-4xl px-4 py-4 flex items-center justify-between gap-4">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#F53003]/10 dark:bg-[#FF4433]/20 flex items-center justify-center border border-[#F53003]/30 dark:border-[#FF4433]/40">
                        <span class="text-xs font-semibold text-[#F53003] dark:text-[#FF4433]">AI</span>
                    </div>
                    <span class="font-semibold text-sm">Bingo Card Creator</span>
                </a>
                <div class="flex items-center gap-2 text-xs sm:text-sm">
                    <a href="{{ route('cards.edit', $card) }}" class="px-3 py-1.5 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615]">
                        Edit card
                    </a>
                    <a href="{{ route('cards.download', $card) }}" class="px-3 py-1.5 rounded-sm border border-black bg-[#1b1b18] text-white hover:bg-black">
                        Download PDF
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="mx-auto max-w-4xl px-4 py-8">
                @php
                    $themeClass = match ($card->category) {
                        'Icebreaker' => 'card-theme-icebreaker',
                        'Educational' => 'card-theme-educational',
                        'Work' => 'card-theme-work',
                        'Party' => 'card-theme-party',
                        'Family' => 'card-theme-family',
                        'Fitness' => 'card-theme-fitness',
                        'Event' => 'card-theme-event',
                        'Hobby' => 'card-theme-hobby',
                        'Seasonal' => 'card-theme-seasonal',
                        default => 'card-theme-default',
                    };
                @endphp
                <div class="bg-white dark:bg-[#161615] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] p-6 shadow-[0px_0px_1px_rgba(0,0,0,0.03),0px_4px_12px_rgba(0,0,0,0.06)]">
                    <div class="flex items-center justify-between mb-4 gap-3">
                        <div>
                            <h1 class="text-2xl font-semibold mb-1">{{ $card->title }}</h1>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                {{ $card->description ?? 'Preview your bingo card before downloading or printing.' }}
                            </p>
                        </div>
                                <div class="text-xs text-[#706f6c] dark:text-[#A1A09A] text-right">
                            <p>{{ $gridSize }} x {{ $gridSize }}</p>
                            <p class="capitalize">{{ $card->source }} card</p>
                        </div>
                    </div>

                    <div class="mt-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg overflow-hidden card-theme {{ $themeClass }}">
                        <div class="grid" style="grid-template-columns: repeat({{ $gridSize }}, minmax(0, 1fr));">
                            @foreach ($cells as $cell)
                                <div class="flex flex-col items-center justify-center text-center px-2 py-3 border border-[#f3f3f0] dark:border-[#3E3E3A] bingo-grid-cell">
                                    <span class="text-xs sm:text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                        {{ $cell['text'] ?? '' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="mt-4 text-[11px] text-[#706f6c] dark:text-[#A1A09A]">
                        Tip: Use your browser&rsquo;s print dialog for quick printing, or download as PDF for sharing or professional printing.
                    </p>
                </div>
            </section>
        </main>
    </body>
</html>

