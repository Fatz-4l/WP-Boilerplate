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
      { rootMargin: "350px 0px" }
    );

    document
      .querySelectorAll("img[loading='lazy']")
      .forEach((img) => this.observer.observe(img));
  }

  loadImage(img) {
    const dataSrcset = img.dataset.srcset;
    const dataSrc = img.dataset.src;
    if (!dataSrc && !dataSrcset) return;

    if (dataSrcset) {
      img.srcset = dataSrcset;
      delete img.dataset.srcset;
    }

    if (dataSrc) {
      img.src = dataSrc;
      delete img.dataset.src;
    } else if (dataSrcset) {
      const firstCandidate = dataSrcset.split(",")[0].split(" ")[0];
      img.src = firstCandidate;
    }

    img.addEventListener("load", () => {
      requestAnimationFrame(() => {
        img.classList.add("loaded");
      });
    });
  }
}
