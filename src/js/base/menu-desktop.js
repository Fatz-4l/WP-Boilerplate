export default class MenuDesktop {
  constructor() {
    if (window.innerWidth < 768) return;
    this.menu = document.getElementById('desktop-menu');
    this.parents = this.menu.querySelectorAll('.menu-item-parent');
    this.triggers = this.menu.querySelectorAll('.dropdown-trigger');
    this.subParents = this.menu.querySelectorAll('.menu-item-sub-parent');
    this.subTriggers = this.menu.querySelectorAll('.dropdown-trigger-sub');
    this.timeouts = { main: null, sub: null };
    this.init();
  }

  init() {
    this.setupDropdowns();
  }

  setupDropdowns() {
    this.triggers?.forEach(trigger => {
      const parent = trigger.closest('.menu-item-parent');
      this.addEvents(trigger, parent, 'main');
    });


    this.subTriggers?.forEach(trigger => {
      const parent = trigger.closest('.menu-item-sub-parent');
      this.addEvents(trigger, parent, 'sub');
    });
  }

  addEvents(trigger, parent, type) {
    trigger.addEventListener('click', e => {
      e.preventDefault();
      this.toggleDropdown(parent, type);
    });
    parent.addEventListener('mouseenter', () => this.openDropdown(parent, type));
    parent.addEventListener('mouseleave', () => this.closeDropdown(parent, type));
  }

  toggleDropdown(item, type) {
    const isOpen = item.classList.contains('dropdown-active');
    this.closeAllDropdowns();
    if (!isOpen) item.classList.add('dropdown-active');
  }

  openDropdown(item, type) {
    if (this.timeouts[type]) {
      clearTimeout(this.timeouts[type]);
      this.timeouts[type] = null;
    }

    const elements = type === 'sub' ? this.subParents : this.parents;
    elements?.forEach(el => {
      if (el !== item) el.classList.remove('dropdown-active');
    });

    item.classList.add('dropdown-active');
  }

  closeDropdown(item, type) {
    this.timeouts[type] = setTimeout(() => {
      item.classList.remove('dropdown-active');
      if (type === 'main') {
        item.querySelectorAll('.menu-item-sub-parent').forEach(sub => {
          sub.classList.remove('dropdown-active');
        });
      }
    }, 300);
  }

  closeAllDropdowns() {
    this.parents?.forEach(item => item.classList.remove('dropdown-active'));
    this.subParents?.forEach(item => item.classList.remove('dropdown-active'));
  }


}
