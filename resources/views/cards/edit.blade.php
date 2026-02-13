<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Bingo Card - {{ $card->title }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col">
        <header class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur">
            <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between gap-4">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#F53003]/10 dark:bg-[#FF4433]/20 flex items-center justify-center border border-[#F53003]/30 dark:border-[#FF4433]/40">
                        <span class="text-xs font-semibold text-[#F53003] dark:text-[#FF4433]">AI</span>
                    </div>
                    <span class="font-semibold text-sm">Bingo Card Creator</span>
                </a>
                <div class="flex items-center gap-2 text-xs sm:text-sm">
                    <a href="{{ route('cards.show', $card) }}" class="px-3 py-1.5 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615]">
                        Preview
                    </a>
                    <a href="{{ route('cards.download', $card) }}" class="px-3 py-1.5 rounded-sm border border-black bg-[#1b1b18] text-white hover:bg-black">
                        Download PDF
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="mx-auto max-w-6xl px-4 py-6 lg:py-10">
                <div class="grid grid-cols-1 lg:grid-cols-[2fr,1.4fr] gap-8 items-start">
                    <div>
                        <h1 class="text-2xl font-semibold mb-1">Edit bingo card</h1>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4">
                            Update the title, text, and optional image prompts for each square. Changes are saved to MySQL.
                        </p>

                        @if (session('status'))
                            <div class="mb-4 rounded-md border border-[#22c55e]/40 bg-[#ecfdf3] text-[#166534] text-xs px-3 py-2">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-md border border-[#F53003]/30 bg-[#fff2f2] text-[#F53003] text-xs px-3 py-2">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('cards.update', $card) }}">
                            @csrf
                            @method('PUT')

                            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                        Title
                                    </label>
                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ old('title', $card->title) }}"
                                        class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-sm text-[#111827] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#F53003] dark:focus:ring-[#FF4433]"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                        Grid size
                                    </label>
                                    <select
                                        name="grid_size"
                                        class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-2 py-2 text-sm text-[#111827] dark:text-[#EDEDEC]"
                                        onchange="window.location='{{ route('cards.edit', $card) }}?grid_size='+this.value"
                                    >
                                        @foreach ([3,4,5] as $size)
                                            <option value="{{ $size }}" @selected($size == (old('grid_size', $gridSize ?? $card->grid_size)))>{{ $size }} x {{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                    Description (optional)
                                </label>
                                <textarea
                                    name="description"
                                    rows="2"
                                    class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-sm text-[#111827] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#F53003] dark:focus:ring-[#FF4433]"
                                >{{ old('description', $card->description) }}</textarea>
                            </div>

                            @php
                                $gridSize = old('grid_size', $gridSize ?? $card->grid_size);
                                $baseCells = $cells ?? $card->cells;
                                $cells = collect(old('cells', $baseCells))->sortBy(['row', 'col'])->values();
                            @endphp

                            <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-3 sm:p-4 mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                            Squares
                                        </p>
                                        <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A]">
                                            Click any cell to edit its text or image prompt.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid gap-2" style="grid-template-columns: repeat({{ $gridSize }}, minmax(0, 1fr));">
                                    @foreach ($cells as $index => $cell)
                                        <div class="rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] p-2 flex flex-col gap-1 bingo-grid-cell">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[10px] text-[#706f6c] dark:text-[#A1A09A]">
                                                    Row {{ $cell['row'] + 1 }}, Col {{ $cell['col'] + 1 }}
                                                </span>
                                            </div>
                                            <input
                                                type="hidden"
                                                name="cells[{{ $index }}][row]"
                                                value="{{ $cell['row'] }}"
                                            />
                                            <input
                                                type="hidden"
                                                name="cells[{{ $index }}][col]"
                                                value="{{ $cell['col'] }}"
                                            />
                                            <input
                                                type="text"
                                                name="cells[{{ $index }}][text]"
                                                value="{{ $cell['text'] ?? '' }}"
                                                placeholder="Cell text"
                                                class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-2 py-1 text-[11px] text-[#111827] dark:text-[#EDEDEC] focus:outline-none focus:ring-1 focus:ring-[#F53003] dark:focus:ring-[#FF4433] mb-1"
                                                data-cell-text-index="{{ $index }}"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-sm px-4 py-2 bg-[#1b1b18] text-white text-sm leading-normal border border-black hover:bg-black btn-primary-glow"
                                >
                                    Save changes
                                </button>
                                <a
                                    href="{{ route('cards.show', [$card, 'grid_size' => $gridSize]) }}"
                                    class="text-xs text-[#706f6c] dark:text-[#A1A09A] underline underline-offset-4"
                                >
                                    View read-only preview
                                </a>
                            </div>
                        </form>
                    </div>

                    <aside class="space-y-4">
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
                        <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/90 dark:bg-[#161615]/90 p-4">
                            <h2 class="text-sm font-medium mb-2">Live preview</h2>
                            <div class="card-theme {{ $themeClass }} p-3">
                                <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1">
                                    {{ $card->title }}
                                </p>
                                <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                    {{ $card->description ?? 'Customizable bingo card preview.' }}
                                </p>
                                <div class="grid gap-[2px]" style="grid-template-columns: repeat({{ $gridSize }}, minmax(0, 1fr));">
                                    @foreach ($cells as $index => $cell)
                                        <div
                                            class="flex flex-col items-center justify-center text-center px-1 py-1.5 border border-[#f3f3f0] dark:border-[#3E3E3A] text-[9px] bingo-grid-cell"
                                            data-preview-cell-index="{{ $index }}"
                                        >
                                            <span data-preview-text>{{ $cell['text'] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-4 text-xs text-[#706f6c] dark:text-[#A1A09A] space-y-2">
                            <h2 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Tips</h2>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Keep cell text short so it fits nicely on printed cards.</li>
                                <li>Use simple phrases or numbers in each square.</li>
                                <li>Save, then download as PDF when you are ready to print or share.</li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </section>
        </main>
    </body>
</html>

