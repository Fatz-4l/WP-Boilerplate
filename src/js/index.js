console.log(`Vanilla Wordpress Theme by DNA © ${new Date().getFullYear()}`);

//Import CSS
import "../css/tailwind.css";
import "../css/index.css";

//Import Base Components
import MenuDesktop from "./base/menu-desktop.js";
import MenuMobile from "./base/menu-mobile.js";
import LazyScriptLoader from "./base/lazy-script-loader.js";
import LazyImageLoader from "./base/lazy-img-loader.js";
import BreakPoints from "./base/break-points.js";
import BaseTemplate from "./base-template.js";

document.addEventListener("DOMContentLoaded", () => {
  // Initialize Crucial Components
  new MenuDesktop();
  new MenuMobile();
  new LazyImageLoader();
  new BreakPoints();

  // Lazy Load Components
  const scriptLoader = new LazyScriptLoader();

  // Register Components
  scriptLoader.registerModule(".home-hero-section", BaseTemplate);

  // Observe Sections
  scriptLoader.observe();
});

console.log("Theme Initialized");
