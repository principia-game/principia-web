# principia-web
Open source reimplementation of Principia's community site.

## Setting up
1. Get nginx with PHP and MariaDB up and running.
1. Import the database dump in the `sql` folder.
1. Copy the `conf/config.sample.php` file to `conf/config.php` and fill in your database credentials.
1. Run `composer update` with Composer to download dependencies.
1. Compile the SCSS stylesheets `assets/scss/` into `assets/css/` (see `tools/compile-scss-{dartsass,sassc}`).
1. Make the `levels/` and `templates/cache/` directories writeable by PHP.

Right now, a default nginx site configuration is not available although this may change in the future if there is demand for it.

### Connecting with the game
Compile Principia to point at your instance of principia-web.

### Optional: memcached
principia-web makes use of memcached to off-load the primary database. To enable it please set up (a) memcached server(s), install the `php-memcached` extension and set the server details in `$memcachedServers`, one array item for each server.

### Optional: Featured levels list
The Principia client has a selection of featured levels on the main menu. By default principia-web has placeholder data, but you can edit this by cloning [Featured List Creator](https://github.com/principia-preservation-project/featured-list-creator), and moving the `main.py` source file into `featured/`. Edit the `featured/data/data.json` to replace the placeholders with real levels.

**The images have to be in JPEG format and of the dimensions 240x135!** Run `featured/main.py` to update and replace the pre-generated featured levels file.

### Optional: Discord webhooks
Principia-web has an option to trigger a Discord webhook during various events (e.g. level upload, forum post...). To enable, put the webhook URL in the respective variable in the principia-web config file. **You will also need the curl PHP extension to be enabled and working!**

## Unit tests
To run unit tests, you need to [download PHPUnit](https://phpunit.de/getting-started/phpunit-10.html). Then run

```bash
./phpunit --bootstrap lib/common.php --testdox tests
```

## License
Principia-web is licensed under the AGPLv3 license. This means you need to provide the source code of any forks, even if the fork is being run on a remote server. For more information, see the [LICENSE](https://github.com/principia-preservation-project/principia-web/blob/master/LICENSE) file, or [this page on choosealicense.com](https://choosealicense.com/licenses/agpl-3.0/).
