export default class BreakPoints {
  constructor() {
    this.viewportWidth = 0;
    this.breakpointBadge = null;
    this.init();
  }

  init() {
    this.createBreakpointBadge();
    this.updateViewportWidth();
    this.bindEvents();
  }

  createBreakpointBadge() {
    // Create the breakpoint badge element
    this.breakpointBadge = document.createElement('div');
    this.breakpointBadge.id = 'breakpoint-badge';
    this.breakpointBadge.className = 'text-lg fixed bottom-4 right-10 bg-black text-white text-xs py-1 px-4 rounded-full z-[9999999] uppercase font-mono flex items-center';

    // Create the breakpoint text container
    const breakpointText = document.createElement('div');
    breakpointText.innerHTML = `
      <span class="sm:hidden">xs</span>
      <span class="hidden sm:inline md:hidden">sm</span>
      <span class="hidden md:inline lg:hidden">md</span>
      <span class="hidden lg:inline xl:hidden">lg</span>
      <span class="hidden xl:inline 2xl:hidden">xl</span>
      <span class="hidden 2xl:inline 3xl:hidden">2xl</span>
      <span class="hidden 3xl:inline">3xl</span>
    `;

    // Create the pixel width display
    const pixelDisplay = document.createElement('span');
    pixelDisplay.className = 'ml-2 pl-2 border-l border-white/30';
    pixelDisplay.id = 'breakpoint-pixel';

    // Append elements
    this.breakpointBadge.appendChild(breakpointText);
    this.breakpointBadge.appendChild(pixelDisplay);

    // Add to DOM
    document.body.appendChild(this.breakpointBadge);
  }

  updateViewportWidth() {
    this.viewportWidth = window.innerWidth;
    this.updatePixelDisplay();
  }

  updatePixelDisplay() {
    const pixelDisplay = document.getElementById('breakpoint-pixel');
    if (pixelDisplay) {
      pixelDisplay.textContent = `${this.viewportWidth}px`;
    }
  }

  bindEvents() {
    // Update on resize
    window.addEventListener('resize', () => {
      this.updateViewportWidth();
    });
  }

  destroy() {
    // Remove event listeners and DOM element
    window.removeEventListener('resize', this.updateViewportWidth);
    if (this.breakpointBadge && this.breakpointBadge.parentNode) {
      this.breakpointBadge.parentNode.removeChild(this.breakpointBadge);
    }
  }
}
