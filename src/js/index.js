console.log(`Vanilla Wordpress Theme by DNA © ${new Date().getFullYear()}`);

//Import CSS
import "../css/tailwind.css";
import "../css/index.css";

//Import Base Components
import LazyScriptLoader from "./base/lazy-script-loader.js";
import LazyImageLoader from "./base/lazy-img-loader.js";
import BreakPoints from "./base/break-points.js";
import BaseTemplate from "./base-template.js";

//Import Components
import MobileToggle from "./mobile-toggle.js";

document.addEventListener("DOMContentLoaded", () => {
  // Initialize crucial components immediately
  new MobileToggle();
  new LazyImageLoader();
  new BreakPoints();
  new LazyScriptLoader();

  // Initialize lazy loading for other components
  const scriptLoader = new LazyScriptLoader();

  // Register modules with their corresponding section classes
  scriptLoader.registerModule(".home-hero-section", BaseTemplate);

  // Start observing sections
  scriptLoader.observe();
});

// Your JavaScript code here
console.log("Theme initialized");
