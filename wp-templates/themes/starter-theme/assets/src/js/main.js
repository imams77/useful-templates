/**
 * Main JavaScript Entry Point
 *
 * This file is the single entry point for all front-end assets.
 * Vite processes this file and all its imports.
 */

// ─── Styles ──────────────────────────────────────────────────────────
import '../css/app.css';    // Tailwind CSS
import '../scss/main.scss'; // Custom SCSS

// ─── Plugin Imports ──────────────────────────────────────────────────
// Uncomment and install the plugins you need.
// After installing (e.g., `npm install swiper`), uncomment the import lines below.
//
// --- jQuery ---
// import $ from 'jquery';
// window.jQuery = window.$ = $;
//
// --- Swiper ---
// import Swiper from 'swiper';
// import { Navigation, Pagination, Autoplay } from 'swiper/modules';
// import 'swiper/css';
// import 'swiper/css/navigation';
// import 'swiper/css/pagination';
//
// --- GSAP ---
// import { gsap } from 'gsap';
// import { ScrollTrigger } from 'gsap/ScrollTrigger';
// gsap.registerPlugin(ScrollTrigger);
//
// --- AOS (Animate On Scroll) ---
// import AOS from 'aos';
// import 'aos/dist/aos.css';
//
// --- Fancybox (Lightbox) ---
// import { Fancybox } from '@fancyapps/ui';
// import '@fancyapps/ui/dist/fancybox/fancybox.css';

// ─── Plugin Initializers ─────────────────────────────────────────────
// Each function initializes a specific plugin.
// Add your configuration here and register it in the `plugins` array below.

/**
 * Example: Initialize Swiper sliders.
 * Uncomment after installing: npm install swiper
 */
// function initSwiper() {
//   const heroSlider = new Swiper('.swiper-hero', {
//     modules: [Navigation, Pagination, Autoplay],
//     loop: true,
//     autoplay: {
//       delay: 5000,
//       disableOnInteraction: false,
//     },
//     pagination: {
//       el: '.swiper-pagination',
//       clickable: true,
//     },
//     navigation: {
//       nextEl: '.swiper-button-next',
//       prevEl: '.swiper-button-prev',
//     },
//   });
//
//   return heroSlider;
// }

/**
 * Example: Initialize AOS (Animate On Scroll).
 * Uncomment after installing: npm install aos
 */
// function initAOS() {
//   AOS.init({
//     duration: 800,
//     easing: 'ease-in-out',
//     once: true,
//     offset: 100,
//   });
// }

/**
 * Example: Initialize Fancybox lightbox.
 * Uncomment after installing: npm install @fancyapps/ui
 */
// function initFancybox() {
//   Fancybox.bind('[data-fancybox]', {
//     animated: true,
//     showClass: 'fancybox-fadeIn',
//     hideClass: 'fancybox-fadeOut',
//   });
// }

// ─── Custom Modules ──────────────────────────────────────────────────
// Add your own JS modules below.

/**
 * Mobile menu toggle.
 */
function initMobileMenu() {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');

  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    const isOpen = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
  });
}

/**
 * Smooth scroll for anchor links.
 */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (e) => {
      const targetId = anchor.getAttribute('href');
      if (!targetId || targetId === '#') return;

      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

// ─── Plugin Registry ─────────────────────────────────────────────────
// Register all initializer functions here.
// Each entry has a `name` (for debugging) and an `init` function.
// Set `enabled` to false to temporarily disable a plugin without removing code.

const plugins = [
  { name: 'MobileMenu',   enabled: true,  init: initMobileMenu },
  { name: 'SmoothScroll',  enabled: true,  init: initSmoothScroll },
  // { name: 'Swiper',      enabled: true,  init: initSwiper },
  // { name: 'AOS',         enabled: true,  init: initAOS },
  // { name: 'Fancybox',    enabled: true,  init: initFancybox },
];

// ─── Bootstrap ───────────────────────────────────────────────────────

function boot() {
  plugins.forEach(({ name, enabled, init }) => {
    if (!enabled) return;

    try {
      init();
    } catch (err) {
      console.error(`[starter-theme] Failed to initialize "${name}":`, err);
    }
  });
}

// Run when DOM is ready.
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}