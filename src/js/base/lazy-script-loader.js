export default class LazyScriptLoader {
  constructor() {
    console.log("LazyScriptLoader init");
    // Observer for lazy loaded scripts
    this.observer = new IntersectionObserver(
      (entries) =>
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.initializeScript(entry.target);
            this.observer.unobserve(entry.target);
          }
        }),
      { rootMargin: "300px", threshold: 0 }
    );

    // Map of section classes to their corresponding modules
    this.moduleMap = new Map();
  }

  registerModule(sectionClass, moduleClass) {
    this.moduleMap.set(sectionClass, moduleClass);
  }

  observe() {
    this.moduleMap.forEach((moduleClass, sectionClass) => {
      const sections = document.querySelectorAll(sectionClass);
      sections.forEach((section) => {
        this.observer.observe(section);
      });
    });
  }

  initializeScript(section) {
    for (const [sectionClass, ModuleClass] of this.moduleMap) {
      if (section.matches(sectionClass)) {
        new ModuleClass(section);
        break;
      }
    }
  }
}
