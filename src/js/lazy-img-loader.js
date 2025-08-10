export default class LazyImageLoader {
  constructor() {
    this.observer = new IntersectionObserver(
      (entries) =>
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.loadImage(entry.target);
            this.observer.unobserve(entry.target);
          }
        }),
      { rootMargin: "100px" }
    );

    document
      .querySelectorAll("img[loading='lazy']")
      .forEach((img) => this.observer.observe(img));
  }

  loadImage(img) {
    const dataSrc = img.dataset.src;
    if (!dataSrc) return;

    img.srcset = dataSrc;
    img.src = dataSrc.split(",")[0].split(" ")[0];
    delete img.dataset.src;

    img.addEventListener("load", () => {
      requestAnimationFrame(() => {
        img.classList.add("loaded");
      });
    });
  }
}
