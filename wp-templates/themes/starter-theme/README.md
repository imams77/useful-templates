# Starter Theme

A minimal WordPress starter theme with [Vite](https://vitejs.dev/), [Tailwind CSS v4](https://tailwindcss.com/), and SCSS support.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Node.js 18+
- npm 9+

## Folder Structure

```
starter-theme/
├── assets/
│   └── src/
│       ├── css/              # Tailwind CSS entry point
│       │   └── app.css
│       ├── scss/             # Custom SCSS stylesheets
│       │   ├── main.css
│       │   └── main.scss
│       ├── js/               # Source scripts
│       │   ├── main.js       # Entry point (styles + plugins + custom code)
│       │   └── plugins.js    # Third-party plugin boilerplate
│       ├── fonts/            # Custom web fonts
│       └── images/           # Source images
├── inc/                      # PHP includes
│   ├── enqueue.php           # Script & style enqueuing (Vite integration)
│   ├── theme-setup.php       # Theme supports, menus, image sizes, widgets
│   ├── custom-post-types.php # CPT & taxonomy registration
│   └── helpers.php           # Vite helpers & utility functions
├── template-parts/           # Reusable template partials
│   ├── header/
│   ├── footer/
│   │   └── footer-widgets.php
│   └── content/
│       ├── content-archive.php
│       ├── content-none.php
│       ├── content-page.php
│       └── content-single.php
├── templates/                # Custom page templates (auto-detected by WP)
│   └── anaya-page.php        # Example custom page template
├── style.css                 # Theme metadata (required by WP)
├── functions.php             # Theme bootstrap
├── index.php                 # Main fallback template
├── header.php                # Site header with wp_head()
├── footer.php                # Site footer with wp_footer()
├── sidebar.php
├── page.php
├── single.php
├── archive.php
├── search.php
├── 404.php
├── comments.php
├── package.json
├── vite.config.js
└── .gitignore
```

## Getting Started

1. Clone or copy this theme into `wp-content/themes/`.

2. Install dependencies:

   ```sh
   npm install
   ```

3. Start the dev server (with hot-reload):

   ```sh
   npm run dev
   ```

4. Build for production:

   ```sh
   npm run build
   ```

5. Activate the theme in **Appearance → Themes** in wp-admin.

## Asset Pipeline

All assets are imported through a single entry point — `assets/src/js/main.js`:

```js
import '../css/app.css';    // Tailwind CSS
import '../scss/main.scss'; // Custom SCSS
```

### Tailwind CSS v4

Tailwind is loaded via the `@tailwindcss/vite` plugin — no `tailwind.config.js` or `postcss.config.js` needed.

- **`assets/src/css/app.css`** — The Tailwind entry point. Contains `@import "tailwindcss"`, an `@source` directive to scan all PHP files for class usage, and an optional `@theme {}` block for customising design tokens.
- Use Tailwind utility classes directly in your PHP templates — including any file inside `templates/`.
- Tailwind v4 automatically picks up classes from all `.php` files in the theme root thanks to the `@source` directive:

  ```css
  @import "tailwindcss";
  @source "../../**/*.php";
  ```

### SCSS

- **`assets/src/scss/main.scss`** — Custom component/layout styles written in SCSS.
- SCSS is kept separate from Tailwind to avoid conflicts between Sass's `@import`/`@use` and Tailwind's CSS-native directives.

### Vite

- During **development**, `npm run dev` starts a Vite dev server at `http://localhost:5173` with HMR (hot module replacement) for instant style/script updates.
- During **production**, `npm run build` compiles assets into `assets/dist/` with hashed filenames. The `inc/enqueue.php` file reads the Vite `manifest.json` to enqueue the correct files.

#### Vite Dev Server Detection

The enqueue system auto-detects whether the Vite dev server is running. You can also force the mode via `wp-config.php`:

```php
// Force dev mode (useful behind Docker/proxies):
define('STARTER_THEME_VITE_DEV', true);

// Change the dev server URL:
define('STARTER_THEME_VITE_DEV_SERVER_URL', 'http://localhost:5173');
```

## Adding Custom Page Templates

Any `.php` file placed inside the `templates/` folder with a `Template Name` header comment is automatically detected by WordPress and available as a page template in the editor.

### How to create a new custom page template

1. Create a new file in `templates/`, for example `templates/my-custom-page.php`.
2. Add the required WordPress template header at the top:

   ```php
   <?php
   /**
    * Template Name: My Custom Page
    * Description: A brief description of what this template does.
    *
    * @package Starter_Theme
    */

   get_header(); ?>

   <main id="main-content" class="min-h-screen">
       <!-- Use Tailwind classes freely here -->
       <section class="container mx-auto px-4 py-16">
           <h1 class="text-4xl font-bold mb-6"><?php the_title(); ?></h1>

           <?php while ( have_posts() ) : the_post(); ?>
               <div class="prose max-w-none">
                   <?php the_content(); ?>
               </div>
           <?php endwhile; ?>
       </section>
   </main>

   <?php get_footer(); ?>
   ```

3. In the WordPress admin, edit a page and select your template from the **Template** dropdown in the Page Attributes panel.

**Tailwind classes work automatically** in any file inside `templates/` — no extra configuration needed. The `@source "../../**/*.php"` directive in `app.css` ensures Tailwind scans all PHP files across the entire theme directory.

An example template (`templates/anaya-page.php`) is included as a reference.

## Adding JavaScript Plugins

The theme includes a structured boilerplate for adding third-party JS plugins. Everything is managed in `assets/src/js/main.js`.

### Quick Start: Adding a Plugin

1. **Install the package:**

   ```sh
   npm install swiper
   ```

2. **Import it** in `main.js` (uncomment or add at the top):

   ```js
   import Swiper from 'swiper';
   import { Navigation, Pagination, Autoplay } from 'swiper/modules';
   import 'swiper/css';
   ```

3. **Create an init function:**

   ```js
   function initSwiper() {
     new Swiper('.swiper', {
       modules: [Navigation, Pagination, Autoplay],
       loop: true,
       autoplay: { delay: 5000 },
       pagination: { el: '.swiper-pagination', clickable: true },
       navigation: {
         nextEl: '.swiper-button-next',
         prevEl: '.swiper-button-prev',
       },
     });
   }
   ```

4. **Register it** in the `plugins` array:

   ```js
   const plugins = [
     { name: 'MobileMenu',  enabled: true, init: initMobileMenu },
     { name: 'SmoothScroll', enabled: true, init: initSmoothScroll },
     { name: 'Swiper',       enabled: true, init: initSwiper },  // ← add here
   ];
   ```

The bootstrap system runs each enabled plugin on `DOMContentLoaded` with error isolation — if one plugin fails, the others still load.

### Supported Plugin Examples

The boilerplate includes commented-out examples for these popular plugins:

| Plugin | Install Command | Purpose |
|--------|----------------|---------|
| jQuery | `npm install jquery` | DOM manipulation |
| Swiper | `npm install swiper` | Touch sliders/carousels |
| GSAP | `npm install gsap` | Animations & ScrollTrigger |
| AOS | `npm install aos` | Animate on scroll |
| Fancybox | `npm install @fancyapps/ui` | Lightbox/modals |

### Alternative: Separate Plugin File

For larger projects you may prefer to keep plugin init code in `assets/src/js/plugins.js` and import it into `main.js`:

```js
import { initPlugins } from './plugins.js';
```

The `plugins.js` file follows the same registry pattern and is already included in the theme.

## Scripts

| Command           | Description                              |
| ----------------- | ---------------------------------------- |
| `npm run dev`     | Start Vite dev server with HMR           |
| `npm run build`   | Build optimized assets for production    |
| `npm run preview` | Preview the production build locally     |

## License

GPL-2.0-or-later