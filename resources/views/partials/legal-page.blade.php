{{--
    Gedeelde opbouw voor juridische pagina's (privacybeleid, voorwaarden).
    Verwacht:
        $pageName      - naam voor breadcrumb ("Privacybeleid")
        $titlePre      - deel van de titel vóór het accent ("Privacy")
        $titleAccent   - accentdeel van de titel ("beleid")
        $intro         - array met introparagrafen (mag leeg)
        $articles      - [['title' => ..., 'blocks' => [string|array, ...]], ...]
                         string = paragraaf, array = opsomming
        $metaLine      - optionele regel onderaan (bijv. ingangsdatum)
--}}
<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[1100px]">

        {{-- Breadcrumb --}}
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">{{ $pageName }}</span>
        </nav>

        {{-- Paginakop --}}
        <div class="load-reveal mb-12">
            <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">{{ $titlePre }}<em class="text-primary italic">{{ $titleAccent }}</em></h1>
            @foreach ($intro as $paragraph)
                <p class="mt-4 max-w-[68ch] leading-[1.8] font-light text-dark-soft">{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="grid items-start gap-10 lg:grid-cols-[280px_1fr]">

            {{-- Inhoudsopgave --}}
            <aside class="load-reveal sticky top-24 hidden max-h-[calc(100vh-8rem)] overflow-y-auto rounded-card border border-primary/15 bg-offwhite p-5 lg:block">
                <h2 class="mb-3 text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Inhoud</h2>
                <ul class="flex flex-col gap-0.5">
                    @foreach ($articles as $i => $article)
                        <li><a href="#artikel-{{ $i + 1 }}" class="block rounded-lg px-3 py-1.5 text-[.82rem] leading-snug transition-colors hover:bg-cream-deep hover:text-primary-deep">{{ $article['title'] }}</a></li>
                    @endforeach
                </ul>
            </aside>

            {{-- Artikelen --}}
            <div class="load-reveal flex flex-col gap-10">
                @foreach ($articles as $i => $article)
                    <article id="artikel-{{ $i + 1 }}" class="scroll-mt-28">
                        <h2 class="mb-3.5 font-serif text-[1.35rem] leading-[1.3] font-medium">{{ $article['title'] }}</h2>
                        <div class="flex flex-col gap-3.5">
                            @foreach ($article['blocks'] as $block)
                                @if (is_array($block))
                                    <ul class="flex flex-col gap-2.5">
                                        @foreach ($block as $item)
                                            <li class="flex gap-3 leading-[1.75] font-light text-dark-soft">
                                                <i class="fa-light fa-angle-right mt-2 shrink-0 text-[.7rem] text-primary"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="leading-[1.8] font-light text-dark-soft">{{ $block }}</p>
                                @endif
                            @endforeach
                        </div>
                    </article>
                @endforeach

                @if (!empty($metaLine))
                    <p class="border-t border-primary/15 pt-6 text-[.85rem] font-light text-dark-soft italic">{{ $metaLine }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
