/**
 * Scroll Lock Utility
 *
 * Usage:
 *
 * new ScrollLock()           // Lock all scrolling
 * new ScrollLock(element)    // Allow element to scroll
 *
 * .lock()    // Enable lock
 * .unlock()  // Disable lock
 *
 * Markup Requirements:
 * - Scrollable elements need: overflow-y-auto
 * - Optional: overscroll-contain (prevents scroll chaining)
 */

export default class ScrollLock {
  constructor(allowElement = null) {
    this.locked = false;
    this.scrollY = 0;
    this.allowElement = allowElement;
  }

  lock() {
    if (this.locked) return;

    this.scrollY = window.scrollY;
    document.body.style.cssText = `
      overflow: hidden;
      position: fixed;
      top: -${this.scrollY}px;
      width: 100%;
    `;

    const preventScroll = (e) => {
      if (this.allowElement?.contains(e.target)) return;
      e.preventDefault();
    };

    this.preventScroll = preventScroll;
    this.locked = true;
  }

  unlock() {
    if (!this.locked) return;

    document.body.style.cssText = '';
    window.scrollTo(0, this.scrollY);
    this.locked = false;
  }
}
