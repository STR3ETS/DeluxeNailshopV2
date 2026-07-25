{{--
    Cookie-consentbar. Keuze wordt 13 maanden bewaard in localStorage
    (conform het privacybeleid, artikel 11). Functionele cookies staan
    altijd aan; analytisch en marketing zijn instelbaar. Andere pagina's
    kunnen de voorkeuren heropenen met:
        window.dispatchEvent(new CustomEvent('open-cookie-settings'))
    Koppel later de echte scripts (GA/Facebook/Adwords) aan de opgeslagen
    voorkeuren in localStorage('dns-cookie-consent').
--}}
<div x-data="{
        show: false,
        settings: false,
        prefs: { analytisch: false, marketing: false },
        init() {
            try {
                const saved = JSON.parse(localStorage.getItem('dns-cookie-consent') || 'null');
                const maxAge = 13 * 30 * 864e5;
                if (saved && Date.now() - saved.ts < maxAge) {
                    this.prefs = { analytisch: !!saved.analytisch, marketing: !!saved.marketing };
                    return;
                }
            } catch {}
            setTimeout(() => this.show = true, 900);
        },
        save() {
            localStorage.setItem('dns-cookie-consent', JSON.stringify({ ...this.prefs, ts: Date.now() }));
            this.show = false;
            // Pas terugwisselen naar de startweergave als de uitfade (300ms) klaar is
            setTimeout(() => this.settings = false, 400);
        },
        acceptAll() { this.prefs = { analytisch: true, marketing: true }; this.save(); },
        necessaryOnly() { this.prefs = { analytisch: false, marketing: false }; this.save(); },
    }"
    @open-cookie-settings.window="show = true; settings = true">

    <div x-cloak x-show="show"
         x-transition:enter="transition duration-500 ease-out" x-transition:enter-start="translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition duration-300 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-6 opacity-0"
         class="fixed right-4 bottom-4 left-4 z-[90] rounded-card border border-primary/15 bg-offwhite p-6 shadow-card sm:left-auto sm:right-6 sm:bottom-6 sm:w-[420px]"
         role="dialog" aria-label="Cookievoorkeuren">

        <div class="flex items-start gap-3.5">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light fa-cookie-bite text-[1.1rem]"></i></span>
            <div>
                <h2 class="font-serif text-[1.15rem] font-medium">Wij gebruiken <em class="text-primary italic">cookies</em></h2>
                <p class="mt-1.5 text-[.85rem] leading-[1.65] font-light text-dark-soft">Zo werkt de shop goed en maken we hem steeds een beetje beter. Functionele cookies staan altijd aan - jij bepaalt de rest. <a href="{{ url('/cookies') }}" class="font-medium text-primary-deep transition-colors hover:text-primary">Lees ons cookiebeleid</a></p>
            </div>
        </div>

        {{-- Voorkeuren --}}
        <div x-cloak x-show="settings" x-transition.opacity.duration.200ms class="mt-5 flex flex-col gap-3 border-t border-primary/15 pt-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[.88rem] font-medium">Functioneel</p>
                    <p class="text-[.76rem] font-light text-dark-soft">Sessie, inloggen en winkelwagen</p>
                </div>
                <span class="text-[.72rem] font-semibold tracking-[.1em] text-primary-deep uppercase">Altijd aan</span>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[.88rem] font-medium">Analytisch</p>
                    <p class="text-[.76rem] font-light text-dark-soft">Anoniem inzicht in het gebruik van de site</p>
                </div>
                <button type="button" role="switch" :aria-checked="prefs.analytisch" @click="prefs.analytisch = !prefs.analytisch"
                        class="relative h-6 w-11 shrink-0 rounded-full transition-colors duration-300" :class="prefs.analytisch ? 'bg-primary' : 'bg-cream-deep'">
                    <span class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-300" :class="prefs.analytisch && 'translate-x-5'"></span>
                </button>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[.88rem] font-medium">Marketing</p>
                    <p class="text-[.76rem] font-light text-dark-soft">Relevante advertenties via Facebook &amp; Google</p>
                </div>
                <button type="button" role="switch" :aria-checked="prefs.marketing" @click="prefs.marketing = !prefs.marketing"
                        class="relative h-6 w-11 shrink-0 rounded-full transition-colors duration-300" :class="prefs.marketing ? 'bg-primary' : 'bg-cream-deep'">
                    <span class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-300" :class="prefs.marketing && 'translate-x-5'"></span>
                </button>
            </div>
        </div>

        {{-- Acties --}}
        <div class="mt-5 flex flex-col gap-2.5">
            <div x-show="!settings" class="flex flex-col gap-2.5">
                <button type="button" @click="acceptAll()" class="w-full rounded-full bg-primary px-6 py-3 text-[.88rem] font-semibold text-white transition-colors hover:bg-primary-deep">Alles accepteren</button>
                <div class="flex gap-2.5">
                    <button type="button" @click="necessaryOnly()" class="flex-1 rounded-full border border-dark/20 px-5 py-2.5 text-[.85rem] font-semibold transition-colors hover:border-dark">Alleen noodzakelijk</button>
                    <button type="button" @click="settings = true" class="flex-1 rounded-full px-5 py-2.5 text-[.85rem] font-semibold text-primary-deep transition-colors hover:bg-cream-deep">Voorkeuren</button>
                </div>
            </div>
            <button x-cloak x-show="settings" type="button" @click="save()" class="w-full rounded-full bg-primary px-6 py-3 text-[.88rem] font-semibold text-white transition-colors hover:bg-primary-deep">Voorkeuren opslaan</button>
        </div>
    </div>
</div>
