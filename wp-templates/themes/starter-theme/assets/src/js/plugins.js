/**
 * Plugins Registry
 *
 * A simple boilerplate for registering and initializing third-party plugins.
 * Import your plugin libraries here and export initialization functions.
 *
 * Usage:
 *   1. Install a package:  npm install swiper jquery
 *   2. Import it below.
 *   3. Create an init function.
 *   4. Register it in the `plugins` array at the bottom.
 *
 * Each plugin object should have:
 *   - name  (string)  : A human-readable name for logging/debugging.
 *   - init  (function): The function that initializes the plugin. Can be async.
 */

// =============================================================================
// 1. PLUGIN IMPORTS
//    Uncomment or add your plugin imports here.
// =============================================================================

// import $ from 'jquery';
// import Swiper from 'swiper';
// import 'swiper/css';
// import { Navigation, Pagination, Autoplay } from 'swiper/modules';
// import { gsap } from 'gsap';
// import { ScrollTrigger } from 'gsap/ScrollTrigger';

// =============================================================================
// 2. PLUGIN INITIALIZERS
//    Create a function for each plugin so they stay isolated and manageable.
// =============================================================================

/**
 * Example: jQuery setup
 * Uncomment after running `npm install jquery`
 */
// function initJquery() {
//   window.$ = window.jQuery = $;
//
//   $(document).ready(function () {
//     console.log('[Plugin] jQuery is ready.');
//
//     // Your jQuery code here...
//   });
// }

/**
 * Example: Swiper slider
 * Uncomment after running `npm install swiper`
 */
// function initSwiper() {
//   const swiperEl = document.querySelector('.swiper');
//   if (!swiperEl) return;
//
//   const swiper = new Swiper('.swiper', {
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
//   console.log('[Plugin] Swiper initialized.');
//   return swiper;
// }

/**
 * Example: GSAP + ScrollTrigger
 * Uncomment after running `npm install gsap`
 */
// function initGsap() {
//   gsap.registerPlugin(ScrollTrigger);
//
//   gsap.from('.animate-on-scroll', {
//     scrollTrigger: {
//       trigger: '.animate-on-scroll',
//       start: 'top 80%',
//     },
//     opacity: 0,
//     y: 40,
//     duration: 0.8,
//     stagger: 0.15,
//   });
//
//   console.log('[Plugin] GSAP initialized.');
// }

// =============================================================================
// 3. PLUGINS REGISTRY
//    Add each plugin here. They will be initialized in order.
// =============================================================================

const plugins = [
  // { name: 'jQuery',         init: initJquery },
  // { name: 'Swiper',         init: initSwiper },
  // { name: 'GSAP',           init: initGsap },
];

// =============================================================================
// 4. BOOTSTRAP — No need to edit below this line.
// =============================================================================

/**
 * Initialize all registered plugins.
 * Called from main.js on DOMContentLoaded.
 */
export async function initPlugins() {
  for (const plugin of plugins) {
    try {
      await plugin.init();
      console.log(`[Plugins] ✔ ${plugin.name}`);
    } catch (error) {
      console.error(`[Plugins] ✘ ${plugin.name} failed to initialize:`, error);
    }
  }
}

export default plugins;