import Alpine from 'alpinejs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Beschikbaar voor de pagina-specifieke animatiescripts in de views
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

window.Alpine = Alpine;

Alpine.store('cart', {
    count: 0,
    bumped: false,
    add() {
        this.count++;
        this.bumped = true;
        setTimeout(() => (this.bumped = false), 400);
    },
});

Alpine.start();
