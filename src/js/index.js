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
  // Initialize Crucial Components
  new MobileToggle();
  new LazyImageLoader();
  new BreakPoints();
  new LazyScriptLoader();

  // Initialize Lazy Loading Components
  const scriptLoader = new LazyScriptLoader();

  // Register Components
  scriptLoader.registerModule(".home-hero-section", BaseTemplate);

  // Observe Sections
  scriptLoader.observe();
});


console.log("Theme Initialized");
