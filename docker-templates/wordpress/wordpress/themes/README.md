# WordPress Themes Directory

Place your custom WordPress themes here. They will be mounted into the container.

## Installing Themes

1. **From WordPress Admin Panel:**
   - Go to Appearance → Themes → Add New
   - Search and install themes directly

2. **Manual Installation:**
   - Download theme ZIP file
   - Extract to this directory
   - Activate from WordPress Admin

3. **Using WP-CLI:**

```bash
docker exec -it wordpress_cli wp theme install theme-name --activate
```

## Default Themes

WordPress comes with default themes (twentytwentythree, etc.) which are stored in the main WordPress volume.


WordPress comes with default themes (twentytwentythree, etc.) which are stored in the main WordPress volume.
