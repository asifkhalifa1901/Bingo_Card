<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bingo Templates - Bingo Card Creator AI</title>
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
                <a
                    href="{{ route('landing') }}"
                    class="inline-flex items-center gap-1.5 rounded-sm px-4 py-1.5 bg-[#1b1b18] text-white text-xs sm:text-sm leading-normal border border-black hover:bg-black btn-primary-glow"
                >
                    New AI card
                </a>
            </div>
        </header>

        <main class="flex-1">
            <section class="mx-auto max-w-6xl px-4 py-8">
                <div class="flex items-center justify-between mb-6 gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold mb-1">Predesigned bingo templates</h1>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Pick a ready‑made layout with a mini preview, then jump straight into editing.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-[11px] px-2.5 py-1 rounded-full border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>{{ $templates->total() }} templates</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($templates as $template)
                        @php
                            $categoryClass = match ($template->category) {
                                'Icebreaker' => 'template-card-icebreaker',
                                'Educational' => 'template-card-educational',
                                'Work' => 'template-card-work',
                                'Party' => 'template-card-party',
                                'Family' => 'template-card-family',
                                'Fitness' => 'template-card-fitness',
                                'Event' => 'template-card-event',
                                'Hobby' => 'template-card-hobby',
                                'Seasonal' => 'template-card-seasonal',
                                default => '',
                            };
                        @endphp
                        <form method="POST" action="{{ route('cards.generate') }}" class="group">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <button
                                type="submit"
                                class="template-card w-full text-left rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] bg-gradient-to-b from-white to-[#f9fafb] dark:from-[#161615] dark:to-[#050509] p-4 hover:shadow-[0_18px_40px_rgba(15,23,42,0.22)] hover:-translate-y-0.5 transition-all flex flex-col gap-3 h-full relative {{ $categoryClass }}"
                            >
                                <div
                                    class="relative rounded-xl overflow-hidden mb-1"
                                    style="padding: 2px; background: radial-gradient(circle at top left, #f97316, #ec4899 35%, #4f46e5 70%);"
                                >
                                    <div
                                        class="relative rounded-[10px] bg-[#FDFDFC] dark:bg-[#050509] text-[9px] text-[#4b5563] dark:text-[#A1A09A] grid grid-cols-5 gap-[2px] overflow-hidden"
                                    >
                                        @foreach (collect($template->cells)->take(15) as $cell)
                                            <div class="flex items-center justify-center text-center px-1 py-1.5 bg-white/90 dark:bg-[#111111] border border-[#e5e7eb] dark:border-[#27272a] truncate bingo-grid-cell">
                                                {{ $cell['text'] ?? '' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-[#111827] dark:text-[#F9FAFB]">
                                        {{ $template->title }}
                                    </p>
                                    @if ($template->category)
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] bg-amber-100 text-amber-800 dark:bg-amber-400/10 dark:text-amber-200">
                                            {{ $template->category }}
                                        </span>
                                    @endif
                                    @if ($template->description)
                                        <p class="text-xs text-[#6b7280] dark:text-[#A1A09A] line-clamp-2">
                                            {{ $template->description }}
                                        </p>
                                    @endif
                                </div>
                                <span class="mt-auto inline-flex items-center justify-center rounded-full px-3 py-1.5 text-xs border border-[#e5e7eb] dark:border-[#3E3E3A] text-[#111827] dark:text-[#F9FAFB] bg-white dark:bg-[#111111] group-hover:border-[#111827] dark:group-hover:border-amber-300">
                                    Use this template
                                </span>
                            </button>
                        </form>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $templates->links() }}
                </div>
            </section>
        </main>
    </body>
</html>

