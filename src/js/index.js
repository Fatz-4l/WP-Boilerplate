console.log(`Vanilla Wordpress Theme by DNA © ${new Date().getFullYear()}`);

//Import CSS
import "../css/tailwind.css";
import "../css/index.css";

//Import JS
import BaseTemplate from "./base-template.js";
import MobileToggle from "./mobile-toggle.js";
import LazyImageLoader from "./lazy-img-loader.js";
import LazyScriptLoader from "./lazy-script-loader.js";

document.addEventListener("DOMContentLoaded", () => {
  // Initialize crucial components immediately
  new MobileToggle();
  new LazyImageLoader();

  // Initialize lazy loading for other components
  const scriptLoader = new LazyScriptLoader();

  // Register modules with their corresponding section classes
  scriptLoader.registerModule(".home-hero-section", BaseTemplate);

  // Start observing sections
  scriptLoader.observe();
});

// Your JavaScript code here
console.log("Theme initialized");
