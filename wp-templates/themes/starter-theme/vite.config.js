import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';

/**
 * Custom plugin: reload the browser when any PHP file in the theme changes.
 *
 * Vite's HMR only covers files in its module graph (JS/CSS). Since PHP files
 * are served by WordPress (not Vite), we watch them separately and trigger a
 * full page reload so you get a live-reload experience while editing templates.
 */
function phpReload() {
  return {
    name: 'php-reload',
    configureServer(server) {
      const themeRoot = path.resolve(__dirname);

      server.watcher.add(path.join(themeRoot, '**/*.php'));

      server.watcher.on('change', (filePath) => {
        if (filePath.endsWith('.php')) {
          server.ws.send({ type: 'full-reload', path: '*' });
        }
      });
    },
  };
}

export default defineConfig({
  root: 'assets/src',

  plugins: [
    tailwindcss(),
    phpReload(),
  ],

  build: {
    outDir: path.resolve(__dirname, 'assets/dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'assets/src/js/main.js'),
      },
    },
  },

  server: {
    origin: 'http://localhost:5173',
    port: 5173,
    strictPort: true,
    cors: true,
  },
});