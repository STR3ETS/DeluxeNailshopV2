@php
    // Footer: elke kolom bevat één of meer linkgroepen (titel + links)
    $footerColumns = [
        [
            ['title' => 'Shoppen', 'links' => collect(config('shop.categories'))->map(fn ($c) => ['label' => $c['name'], 'url' => url('/producten').'?categorie='.$c['slug']])->all()],
        ],
        [
            ['title' => 'Merken',   'links' => collect(config('shop.brands'))->map(fn ($merk) => ['label' => $merk, 'url' => url('/producten')])->push(['label' => 'Alle merken', 'url' => url('/producten')])->all()],
            ['title' => 'Over ons', 'links' => [['label' => 'Ons verhaal', 'url' => '#'], ['label' => 'Reviews', 'url' => '#'], ['label' => 'Blog', 'url' => '#']]],
        ],
        [
            ['title' => 'Klantenservice', 'links' => [['label' => 'Contact', 'url' => '#'], ['label' => 'Verzenden & retour', 'url' => '#'], ['label' => 'Veelgestelde vragen', 'url' => url('/faq')], ['label' => 'Betaalmethoden', 'url' => '#'], ['label' => 'Cadeaubonnen', 'url' => '#']]],
        ],
    ];

    $footerContact = [
        ['icon' => 'fa-light fa-envelope',   'label' => 'E-mail',     'value' => 'info@deluxenailshop.nl'],
        ['icon' => 'fa-brands fa-whatsapp',  'label' => 'WhatsApp',   'value' => '+31 6 12 34 56 78'],
        ['icon' => 'fa-light fa-clock',      'label' => 'Bereikbaar', 'value' => 'ma t/m vr · 09:00 - 17:00'],
    ];

    $socials = [
        ['icon' => 'fa-instagram',   'label' => 'Instagram'],
        ['icon' => 'fa-tiktok',      'label' => 'TikTok'],
        ['icon' => 'fa-facebook-f',  'label' => 'Facebook'],
        ['icon' => 'fa-pinterest-p', 'label' => 'Pinterest'],
    ];

    // Betaalmethoden: FA-brand-icoon waar beschikbaar, anders een tekstbadge
    $payments = [
        ['type' => 'icon', 'icon' => 'fa-ideal',        'label' => 'iDEAL'],
        ['type' => 'icon', 'icon' => 'fa-paypal',       'label' => 'PayPal'],
        ['type' => 'icon', 'icon' => 'fa-cc-mastercard','label' => 'Mastercard'],
        ['type' => 'icon', 'icon' => 'fa-cc-visa',      'label' => 'Visa'],
        ['type' => 'icon', 'icon' => 'fa-cc-apple-pay', 'label' => 'Apple Pay'],
    ];

    $legalLinks = [
        ['label' => 'Privacybeleid',        'url' => url('/privacybeleid')],
        ['label' => 'Algemene voorwaarden', 'url' => url('/algemene-voorwaarden')],
        ['label' => 'Cookies',              'url' => url('/cookies')],
    ];

    $footerText = 'Dé webshop voor professionele nagelproducten. Voor nagelstylistes én iedereen die thuis salonresultaat wil.';
    $domain = 'deluxenailshop.nl';
@endphp

{{-- Footer --}}
<footer class="mt-[5.5rem] bg-dark px-6 pt-16 pb-8 text-cream">
    <div class="mx-auto grid max-w-[1240px] grid-cols-1 gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr_1.35fr]">

        {{-- Merk, reviews & socials --}}
        <div>
            <a href="{{ url('/') }}" class="mb-5 inline-block">
                <img src="{{ asset('logo/deluxenailshop_transp_goud_v1.png') }}" alt="{{ config('app.name') }}" class="h-14 w-auto">
            </a>
            <p class="max-w-[32ch] text-[.88rem] leading-[1.7] font-light opacity-75">{{ $footerText }}</p>
            <div class="mt-5 flex items-center gap-[3px] text-[.7rem] text-gold">
                @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                <span class="ml-2 text-[.8rem] font-medium text-cream/80">4,9/5 - 2.400+ reviews</span>
            </div>
            <div class="mt-6 flex gap-2.5">
                @foreach ($socials as $social)
                    <a href="#" aria-label="{{ $social['label'] }}" class="grid h-10 w-10 place-items-center rounded-full border border-cream/20 transition-colors hover:border-gold hover:bg-gold hover:text-dark">
                        <i class="fa-brands {{ $social['icon'] }} text-[1rem]"></i>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Linkkolommen --}}
        @foreach ($footerColumns as $column)
            <div class="flex flex-col gap-10">
                @foreach ($column as $group)
                    <div>
                        <h4 class="mb-4 text-[.78rem] tracking-[.18em] text-gold uppercase">{{ $group['title'] }}</h4>
                        <ul class="flex flex-col gap-2.5">
                            @foreach ($group['links'] as $link)
                                <li><a href="{{ $link['url'] }}" class="text-[.9rem] font-light opacity-85 transition-colors hover:text-gold hover:opacity-100">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- Hulp nodig? --}}
        <div class="self-start rounded-card border border-cream/10 bg-white/5 p-6 sm:col-span-2 lg:col-span-1">
            <h4 class="mb-2 font-serif text-[1.35rem] font-medium">Hulp <em class="text-gold italic">nodig</em>?</h4>
            <p class="mb-5 text-[.85rem] leading-[1.6] font-light opacity-75">Ons team van nagelstylistes denkt graag met je mee.</p>
            <ul class="flex flex-col gap-3.5">
                @foreach ($footerContact as $contact)
                    <li class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gold/15 text-gold"><i class="{{ $contact['icon'] }} text-[.9rem]"></i></span>
                        <span class="flex flex-col">
                            <small class="text-[.68rem] tracking-[.12em] uppercase opacity-60">{{ $contact['label'] }}</small>
                            <span class="text-[.88rem] font-medium">{{ $contact['value'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Onderbalk --}}
    <div class="mx-auto mt-14 flex max-w-[1240px] flex-wrap items-center justify-between gap-x-8 gap-y-4 border-t border-cream/10 pt-6">
        <span class="text-[.78rem] opacity-60">© {{ date('Y') }} {{ $domain }} · Gemaakt door <a href="https://halfmanmedia.nl" target="_blank" rel="noopener" class="font-medium transition-colors hover:text-gold">HalfmanMedia</a></span>
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
            @foreach ($legalLinks as $link)
                <a href="{{ $link['url'] }}" class="text-[.78rem] opacity-60 transition-all hover:text-gold hover:opacity-100">{{ $link['label'] }}</a>
            @endforeach
        </div>
        <div class="flex items-center gap-3">
            @foreach ($payments as $payment)
                @if ($payment['type'] === 'icon')
                    <i class="fa-brands {{ $payment['icon'] }} text-[1.45rem] opacity-70" title="{{ $payment['label'] }}" aria-label="{{ $payment['label'] }}"></i>
                @else
                    <span class="rounded-[6px] border border-cream/25 px-1.5 py-0.5 text-[.62rem] font-semibold tracking-[.08em] uppercase opacity-70" title="{{ $payment['label'] }}">{{ $payment['label'] }}</span>
                @endif
            @endforeach
        </div>
    </div>
</footer>
