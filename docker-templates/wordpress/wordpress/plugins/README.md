# WordPress Plugins Directory

Place your custom WordPress plugins here. They will be mounted into the container.

## Installing Plugins

1. **From WordPress Admin Panel:**
   - Go to Plugins → Add New
   - Search and install plugins directly

2. **Manual Installation:**
   - Download plugin ZIP file
   - Extract to this directory
   - Activate from WordPress Admin

3. **Using WP-CLI:**

```bash
docker exec -it wordpress_cli wp plugin install plugin-name --activate
```

## Recommended Plugins for Production

- **Security:** Wordfence, Sucuri
- **Caching:** W3 Total Cache, WP Super Cache
- **SEO:** Yoast SEO, Rank Math
- **Backup:** UpdraftPlus, BackWPup

- **Security:** Wordfence, Sucuri
- **Caching:** W3 Total Cache, WP Super Cache
- **SEO:** Yoast SEO, Rank Math
- **Backup:** UpdraftPlus, BackWPup
