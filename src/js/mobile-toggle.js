export default class MobileToggle {
    constructor() {
        this.$button = document.querySelector('#mobile-menu-button');
        this.$menu = document.querySelector('#menu');
        this.$burgerLines = this.$button?.querySelectorAll('span');
        this.$body = document.body;
        
        if (!this.$button || !this.$menu || !this.$burgerLines) return;
        
        // Add initial transition classes
        this.$menu.classList.add('transition-transform', 'duration-300', 'ease-in-out');
        
        this.init();
    }

    init() {
        this.$button.addEventListener('click', () => this.toggleMenu());
    }

    toggleMenu() {
        // Toggle menu visibility using transform
        this.$menu.classList.toggle('translate-x-0');
        this.$menu.classList.toggle('-translate-x-full');
        
        // Toggle body scroll
        this.$body.style.overflow = this.$menu.classList.contains('translate-x-0') ? 'hidden' : '';
        
        // Toggle burger animation
        this.$burgerLines[0].classList.toggle('rotate-45');
        this.$burgerLines[0].classList.toggle('translate-y-2');
        this.$burgerLines[1].classList.toggle('opacity-0');
        this.$burgerLines[2].classList.toggle('-rotate-45');
        this.$burgerLines[2].classList.toggle('-translate-y-2');
    }
}


