# principia-web
The next generation of the Principia community website.

## Setting up and installing
1. Get Apache with PHP and MariaDB/MySQL up and running.
1. Import the database dump from `sql`.
1. Copy the `conf/config.sample.php` file to `conf/config.php` and fill in your database credentials.
1. Run `composer install` with Composer to download dependencies.
1. Compile the SCSS files in `assets/css` to CSS.
1. Make the `levels/` and `templates/cache/` directories writeable by Apache.

The config file contains many options. You might want to look through them.

### Optional: Featured levels list
The Principia client has a selection of featured levels on the main menu. By default principia-web has placeholder data, but you can edit this by cloning [Featured List Creator](https://github.com/principia-preservation-project/featured-list-creator), and moving the `main.py` source file into `featured/`. Edit the `featured/data/data.json` to replace the placeholders with real levels. **The images have to be in JPEG format and of the dimensions 240x135!** Run `featured/main.py` to update and replace the pre-generated featured levels file.

### Optional: Discord webhook on level upload
Principia-web has an option to trigger a Discord webhook on upload. To enable, put the webhook URL in `$webhook` in the principia-web config file. **You will also need the curl PHP extension to be enabled and working!**