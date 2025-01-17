console.log(`Vanilla Wordpress Theme by DNA © ${new Date().getFullYear()}`);

//Import CSS
import "../css/tailwind.css";

//Import JS
import "./base-template.js";
import MobileToggle from "./mobile-toggle.js";

// Initialize Scripts
document.addEventListener('DOMContentLoaded', () => {
    new MobileToggle();
});


