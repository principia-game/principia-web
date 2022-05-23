# principia-web
Open source reimplementation of Principia's community site.

## Setting up
1. Get nginx with PHP and MariaDB up and running.
1. Import the database dump in the `sql` folder.
1. Copy the `conf/config.sample.php` file to `conf/config.php` and fill in your database credentials.
1. Run `composer update` with Composer to download dependencies.
1. Compile the SCSS stylesheet `assets/css/style.scss` into `assets/css/style.css`.
1. Make the `levels/` and `templates/cache/` directories writeable by PHP.

Right now, a default nginx site configuration is not available although this may change in the future if there is demand for it.

### Connecting with the game
#### Windows (and Linux)
Connecting to your own site with the Windows version is easy using Artemking's cURL mod. Simply edit the `rcurl-config.json` file to redirect to your own site. It should look something like this:

```json
{
	"http://principiagame.com": "http://principia-web.uwu",
	"http://wiki.principiagame.com/wiki/": "https://principia-preservation-project.github.io/Wiki-archive/"
}
```

Where `http://principia-web.uwu` is your own site. **HTTPS does <u>not</u> work with the game!** Due to this, even if `$https` is set to `true`, principia-web will not redirect internal pages to HTTPS.

#### Android
You're mainly on your own. Decompile the game using [Apktool](https://ibotpeaches.github.io/Apktool/) and change all occurences in the Smali code of `http://principiagame.com` to your own site.

You will also need to edit the native libraries to replace any occurences. Doing it manually is a pain, so for the Android community site mod, [a script was written to automatically do it](https://gist.github.com/rollerozxa/dc882179249520bade66a0f5bfddb99e). It might need some edits to suit your needs though.

### Optional: memcached
principia-web makes use of memcached to off-load the primary database. To enable it please set up (a) memcached server(s), install the `php-memcached` extension and set the server details in `$memcachedServers`, one array item for each server.

### Optional: Featured levels list
The Principia client has a selection of featured levels on the main menu. By default principia-web has placeholder data, but you can edit this by cloning [Featured List Creator](https://github.com/principia-preservation-project/featured-list-creator), and moving the `main.py` source file into `featured/`. Edit the `featured/data/data.json` to replace the placeholders with real levels.

**The images have to be in JPEG format and of the dimensions 240x135!** Run `featured/main.py` to update and replace the pre-generated featured levels file.

### Optional: Discord webhook on level upload
Principia-web has an option to trigger a Discord webhook on upload. To enable, put the webhook URL in `$webhook` in the principia-web config file. **You will also need the curl PHP extension to be enabled and working!**

## License
Principia-web is licensed under the AGPLv3 license. This means you need to provide the source code of any forks, even if the fork is being run on a remote server. For more information, see the [LICENSE](https://github.com/principia-preservation-project/principia-web/blob/master/LICENSE) file, or [this page on choosealicense.com](https://choosealicense.com/licenses/agpl-3.0/).
