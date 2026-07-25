import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Beschikbaar voor de pagina-specifieke animatiescripts in de views
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

window.Alpine = Alpine;

const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/*
 * Feedback bij het winkelwagen-icoon: het tasje wiebelt even en er
 * floept een "+1"-chip omhoog, zodat je ziet dat er iets bij komt.
 */
function cartPing(qty = 1) {
    const btn = document.getElementById('cartButton');
    if (!btn || reduce || !window.gsap) return;

    const icon = btn.querySelector('i');
    gsap.fromTo(icon, { rotate: 0 }, { keyframes: { rotate: [0, -14, 10, -6, 0] }, duration: 0.5, ease: 'power1.inOut' });

    const plus = document.createElement('span');
    plus.textContent = `+${qty}`;
    plus.className = 'cart-plus';
    btn.appendChild(plus);
    gsap.fromTo(plus,
        { y: 6, opacity: 0, scale: 0.5 },
        { y: -20, opacity: 1, scale: 1, duration: 0.4, ease: 'back.out(2.5)', onComplete() {
            gsap.to(plus, { y: -32, opacity: 0, duration: 0.35, delay: 0.3, ease: 'power1.in', onComplete: () => plus.remove() });
        } });
}

Alpine.store('cart', {
    items: [],
    open: false,
    bumped: false,

    init() {
        try {
            this.items = JSON.parse(localStorage.getItem('dns-cart') || '[]');
        } catch {
            this.items = [];
        }
        Alpine.effect(() => localStorage.setItem('dns-cart', JSON.stringify(this.items)));
    },

    get count() {
        return this.items.reduce((n, i) => n + i.qty, 0);
    },
    get total() {
        return this.items.reduce((s, i) => s + i.price * i.qty, 0);
    },

    add(item, qty = 1) {
        const existing = this.items.find((i) => i.id === item.id);
        if (existing) existing.qty += qty;
        else this.items.push({ ...item, qty });

        cartPing(qty);
        this.bumped = true;
        setTimeout(() => (this.bumped = false), 500);
    },
    inc(id) {
        const item = this.items.find((i) => i.id === id);
        if (item) item.qty++;
    },
    dec(id) {
        const item = this.items.find((i) => i.id === id);
        if (item && item.qty > 1) item.qty--;
    },
    remove(id) {
        this.items = this.items.filter((i) => i.id !== id);
    },
    clear() {
        this.items = [];
    },
    format(value) {
        return value.toLocaleString('nl-NL', { style: 'currency', currency: 'EUR' });
    },
});

Alpine.start();

/*
 * Onload-animatie: elementen met .load-reveal faden bij het laden van de
 * pagina gestaggerd omhoog, in documentvolgorde (zoals de hero op home).
 * clearProps ruimt de inline stijlen na afloop op zodat hovers en
 * sticky-posities weer normaal werken.
 */
if (!reduce) {
    const loadEls = gsap.utils.toArray('.load-reveal');
    if (loadEls.length) {
        gsap.from(loadEls, {
            y: 26,
            opacity: 0,
            duration: 0.7,
            ease: 'power3.out',
            stagger: 0.08,
            delay: 0.05,
            // Alleen de geanimeerde props opruimen - 'all' zou ook eigen
            // inline stijlen wissen (zoals de achtergrond-gradient van
            // het productbeeld op de detailpagina).
            clearProps: 'opacity,transform,translate,rotate,scale',
        });
    }
}

/*
 * Gedeelde animaties voor alle pagina's: scroll-reveals (.reveal) en
 * tellers ([data-count]). Na afloop worden inline stijlen opgeruimd
 * zodat hover-effecten (translate) blijven werken.
 */
if (!reduce) {
    gsap.utils.toArray('.reveal').forEach((el) => {
        gsap.to(el, {
            opacity: 1, y: 0, duration: 0.8, ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 86%' },
            onComplete() { el.classList.remove('reveal'); gsap.set(el, { clearProps: 'opacity,transform,translate,rotate,scale' }); },
        });
    });

    document.querySelectorAll('[data-count]').forEach((el) => {
        const end = +el.dataset.count;
        gsap.fromTo(el, { innerText: 0 }, {
            innerText: end, duration: 1.8, delay: 1, snap: { innerText: 1 }, ease: 'power2.out',
            onUpdate() { el.innerText = Math.round(el.innerText).toLocaleString('nl-NL') + '+'; },
        });
    });
} else {
    document.querySelectorAll('.reveal').forEach((el) => el.classList.remove('reveal'));
    document.querySelectorAll('[data-count]').forEach((el) => { el.textContent = (+el.dataset.count).toLocaleString('nl-NL') + '+'; });
}
