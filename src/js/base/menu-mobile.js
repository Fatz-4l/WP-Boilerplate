import ScrollLock from './utils/scroll-lock.js';

export default class MenuMobile {
  constructor() {
    this.button = document.getElementById('mobile-menu-button');
    this.menu = document.getElementById('mobile-menu');
    this.header = document.querySelector('header');
    this.main = document.querySelector('main');
    this.scrollLock = new ScrollLock(this.menu);

    this.init();
  }

  init() {
    this.button.addEventListener('click', () => this.toggleMenu());
    this.initSubMenus();
    this.setDynamicHeight();
    this.bindResizeEvent();
  }

  toggleMenu() {
    const isOpening = this.menu.classList.contains('menu-closed');
    this.menu.classList.toggle('menu-open', isOpening);
    this.menu.classList.toggle('menu-closed', !isOpening);
    this.button.classList.toggle('burger-open', isOpening);
    this.header.classList.toggle('mobile-menu-open', isOpening);
    this.main.classList.toggle('mobile-menu-open', isOpening);
    document.body.classList.toggle('mobile-menu-open', isOpening);

    if (isOpening) {
      this.scrollLock.lock();
      this.closeAllSubMenus();
      this.setDynamicHeight();
    } else {
      this.scrollLock.unlock();
    }
  }

  initSubMenus() {
    const toggles = this.menu.querySelectorAll('.sub-menu-toggle');

    toggles.forEach(toggle => {
      toggle.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        this.toggleSubMenu(toggle);
      });
    });
  }

  toggleSubMenu(toggle) {
    const subMenu = toggle.closest('li').querySelector('.sub-menu');
    if (!subMenu) return;

    const isOpening = subMenu.classList.contains('sub-menu-closed') ||
                     !subMenu.classList.contains('sub-menu-open');

    subMenu.classList.toggle('sub-menu-open', isOpening);
    subMenu.classList.toggle('sub-menu-closed', !isOpening);
    toggle.classList.toggle('toggle-open', isOpening);

    if (isOpening) {
      setTimeout(() => this.setDynamicHeight(), 100);
    } else {
      this.closeNestedMenus(subMenu);
    }
  }

  closeNestedMenus(subMenu) {
    subMenu.querySelectorAll('.sub-menu').forEach(nested => {
      nested.classList.remove('sub-menu-open');
      nested.classList.add('sub-menu-closed');
    });
    subMenu.querySelectorAll('.sub-menu-toggle').forEach(toggle => {
      toggle.classList.remove('toggle-open');
    });
  }

  closeAllSubMenus() {
    this.menu.querySelectorAll('.sub-menu').forEach(subMenu => {
      subMenu.classList.remove('sub-menu-open');
      subMenu.classList.add('sub-menu-closed');
    });
    this.menu.querySelectorAll('.sub-menu-toggle').forEach(toggle => {
      toggle.classList.remove('toggle-open');
    });
  }

  setDynamicHeight() {
    const viewportHeight = window.visualViewport?.height || window.innerHeight;
    const availableHeight = viewportHeight - this.header.getBoundingClientRect().height;
    this.menu.style.maxHeight = `${Math.max(200, availableHeight)}px`;
  }

  bindResizeEvent() {
    const handler = () => this.setDynamicHeight();
    ['resize', 'orientationchange'].forEach(event => window.addEventListener(event, handler));
    window.visualViewport?.addEventListener('resize', handler);
  }
}
