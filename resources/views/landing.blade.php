<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bingo Card Creator AI</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#050816] text-slate-100 min-h-screen flex flex-col">
        <header class="sticky top-0 z-30 border-b border-white/10 bg-[#050816]/90 backdrop-blur">
            <div class="mx-auto max-w-6xl px-4 lg:px-6 py-3 flex items-center justify-between gap-3">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#F97316] to-[#F43F5E] flex items-center justify-center shadow-[0_0_0_1px_rgba(255,255,255,0.06)]">
                        <span class="text-[11px] font-semibold tracking-wide text-white">AI</span>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-semibold text-sm text-white">Bingo Card Creator</span>
                        <span class="text-[11px] text-slate-400">Instant AI-powered bingo cards</span>
                    </div>
                </a>

                <button
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-white/10 text-[11px] text-slate-200 bg-white/5 hover:bg-white/10 lg:hidden"
                    type="button"
                    data-nav-toggle
                >
                    <span>Menu</span>
                    <span class="i-ph-list text-xs"></span>
                </button>

                <nav
                    class="hidden lg:flex items-center gap-4 text-[13px] text-slate-300"
                    data-nav-menu
                    data-open="false"
                >
                    <a href="{{ route('cards.templates') }}" class="hover:text-white transition-colors">Templates</a>
                    <form method="POST" action="{{ route('cards.generate') }}">
                        @csrf
                        <input type="hidden" name="prompt" value="Create a 5x5 classic bingo card for a fun get‑together with friends.">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 bg-white text-slate-900 text-[13px] font-medium shadow-sm hover:bg-slate-100"
                        >
                            Create a card
                        </button>
                    </form>
                </nav>
            </div>

            <nav
                class="mx-auto max-w-6xl px-4 lg:px-6 pb-3 lg:hidden hidden"
                data-nav-menu
                data-open="false"
            >
                <div class="rounded-2xl border border-white/10 bg-slate-900/80 px-4 py-3 flex flex-col gap-2 text-[13px] text-slate-200">
                    <a href="{{ route('cards.templates') }}" class="py-1 hover:text-white">Templates</a>
                    <form method="POST" action="{{ route('cards.generate') }}" class="pt-1 mt-1 border-t border-white/5">
                        @csrf
                        <input type="hidden" name="prompt" value="Create a 5x5 classic bingo card for a fun get‑together with friends.">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-full px-4 py-1.5 bg-white text-slate-900 text-[13px] font-medium shadow-sm hover:bg-slate-100"
                        >
                            Create a card
                        </button>
                    </form>
                </div>
            </nav>
        </header>

        <main class="flex-1">
            <section class="mx-auto max-w-6xl px-4 lg:px-6 py-10 lg:py-16 flex flex-col lg:flex-row gap-10 lg:gap-14">
                <div class="flex-1 max-w-xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-slate-900/80 px-3 py-1 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] uppercase tracking-wide text-slate-200/80">
                            AI bingo card generator
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight mb-4 text-white">
                        Create beautiful bingo cards
                        <br class="hidden sm:block" />
                        <span class="bg-gradient-to-r from-amber-300 via-orange-400 to-rose-400 bg-clip-text text-transparent">
                            from a single prompt.
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300/90 mb-6 max-w-lg">
                        Tell the AI what you&rsquo;re planning &mdash; a classroom lesson, party, icebreaker, or team event.
                        We instantly build an editable bingo card with text and optional image prompts in every square.
                    </p>

                    @if ($errors->any())
                        <div class="mb-4 rounded-md border border-[#F53003]/30 bg-[#fff2f2] text-[#F53003] text-xs px-3 py-2">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('cards.generate') }}"
                        class="rounded-lg border border-[#e3e3e0] bg-white shadow-[0px_0px_1px_rgba(0,0,0,0.03),0px_1px_2px_rgba(0,0,0,0.06)] p-4 sm:p-5 mb-4 text-[#1b1b18]"
                    >
                        @csrf
                        <label for="topic" class="block text-xs font-medium text-[#4b5563] mb-1.5">
                            Topic for your bingo card
                        </label>
                        <input
                            id="topic"
                            type="text"
                            name="topic"
                            value="{{ old('topic') }}"
                            class="w-full rounded-xl border border-[#e5e7eb] bg-[#FDFDFC] text-[#111827] placeholder:text-[#9ca3af] px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/70 mb-3"
                            placeholder="e.g. Remote work, Classroom rewards, Birthday party"
                        />

                        <label for="prompt" class="block text-xs font-medium text-[#4b5563] mb-1.5">
                            Enter your description for bingo card
                        </label>
                        <textarea
                            id="prompt"
                            name="prompt"
                            rows="3"
                            required
                            class="w-full rounded-xl border border-[#e5e7eb] bg-[#FDFDFC] text-[#111827] placeholder:text-[#9ca3af] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400/70 mb-3"
                            placeholder="Describe any extra rules, tone, or details you want for this bingo card."
                        >{{ old('prompt') }}</textarea>

                        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                            <div class="flex items-center gap-2">
                                <label for="grid_size" class="text-xs text-[#4b5563]">Grid</label>
                                <select
                                    id="grid_size"
                                    name="grid_size"
                                    class="rounded-lg border border-[#e5e7eb] bg-[#FDFDFC] text-[#111827] px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-orange-400/60"
                                >
                                    @foreach ([3,4,5] as $size)
                                        <option value="{{ $size }}" @selected(old('grid_size', 5) == $size)>{{ $size }} x {{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-1.5 rounded-full px-5 py-2 bg-gradient-to-r from-orange-400 to-rose-500 text-white text-xs sm:text-sm font-medium shadow-sm hover:from-orange-300 hover:to-rose-400 btn-primary-glow"
                            >
                                <span>Generate with AI</span>
                            </button>
                        </div>

                        <p class="mt-2 text-[11px] text-slate-400">
                            You can edit every square, tweak the text, and save or download your finished card.
                        </p>
                    </form>
                </div>

                <div class="flex-1">
                    <div class="relative rounded-3xl bg-white border border-[#e5e7eb] p-4 sm:p-6 overflow-hidden shadow-[0_18px_40px_rgba(15,23,42,0.22)]">
                        <div class="relative flex flex-col gap-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] uppercase tracking-wide text-[#f97316] mb-1">
                                        Live preview
                                    </p>
                                    <p class="text-sm font-medium text-[#111827]">
                                        AI bingo card preview
                                    </p>
                                </div>
                            </div>

                            <div class="mt-2 rounded-2xl bg-[#f9fafb] border border-[#e5e7eb] p-3 shadow-[0_10px_30px_rgba(15,23,42,0.15)]">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-xs font-medium text-[#111827]">Remote work bingo</p>
                                    <span class="text-[10px] text-[#6b7280]">5 x 5</span>
                                </div>
                                <div class="grid grid-cols-5 gap-[2px] border border-[#e5e7eb] rounded-lg overflow-hidden text-[9px] sm:text-[10px]">
                                    @php
                                        $sampleCells = [
                                            'Dog barks on call', 'Muted while talking', 'Wi-Fi glitch',
                                            'Someone says &ldquo;you&apos;re on mute&rdquo;', 'Virtual background fail',
                                            'Coffee refill', 'Slack notification', 'Keyboard clacking',
                                            'Screen share issues', 'Late joiner',
                                            'Overlapping meetings', 'Kids in background', 'Frozen face',
                                            'Echo on call', 'New tab overload',
                                            'Mic not working', 'Camera off', 'Quick recap',
                                            'Time zone mix-up', 'Window light glare',
                                            'Standing desk', 'Headphones on', 'Emoji reaction',
                                            'Chat explodes', 'End meeting for all',
                                        ];
                                    @endphp
                                    @foreach ($sampleCells as $cell)
                                        <div class="flex items-center justify-center text-center px-1 py-1.5 bg-white text-[#111827] border border-[#e5e7eb] bingo-grid-cell">
                                            {{ $cell }}
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-[10px] text-[#6b7280]">
                                    Your real cards will be fully editable with text in each square.
                                </p>
                            </div>

                            @if ($templates->count())
                                <div class="mt-2" id="how-it-works">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-white">
                                            Quick-start templates
                                        </p>
                                        <a
                                            href="{{ route('cards.templates') }}"
                                            class="text-[11px] text-amber-200 underline underline-offset-4"
                                        >
                                            Browse all
                                        </a>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        @foreach ($templates as $template)
                                            <form method="POST" action="{{ route('cards.generate') }}" class="group">
                                                @csrf
                                                <input type="hidden" name="template_id" value="{{ $template->id }}">
                                                <button
                                                    type="submit"
                                                    class="w-full text-left rounded-xl border border-white/5 bg-slate-900/80 px-3 py-2 hover:border-amber-300/60 transition-colors"
                                                >
                                                    <p class="text-[11px] font-medium text-slate-100 truncate">
                                                        {{ $template->title }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 truncate">
                                                        {{ $template->description }}
                                                    </p>
                                                    @if ($template->category)
                                                        <span class="inline-flex mt-1 text-[10px] rounded-full px-2 py-0.5 bg-amber-400/10 text-amber-200">
                                                            {{ $template->category }}
                                                        </span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-white/10 bg-slate-950/90">
            <div class="mx-auto max-w-6xl px-4 lg:px-6 py-4 text-[11px] text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} Bingo Card Creator AI. All rights reserved.</p>
                <p>Built with Laravel, Tailwind, and AI-generated bingo cards.</p>
            </div>
        </footer>
    </body>
</html>

