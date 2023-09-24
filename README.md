# principia-web
This is the source code that powers the new Principia community site, including the forum and wiki.

## Setting up
There's a lot of steps to setting up your own instance of principia-web, but here's the basic gist for seting up a basic development instance:

1. Get nginx with PHP and MariaDB up and running.
1. Import the database dump in the `sql` folder.
1. Copy the `conf/config.sample.php` file to `conf/config.php` and fill in your database credentials.
1. Run `composer update` with Composer to download dependencies.
1. Compile the SCSS stylesheets.

### nginx configuration example
This is an example of how to configure nginx to work with the principia-web router:

```nginx
location / {
	try_files /static$uri /router.php?$args;
}
location /router.php {
	# pass on to PHP-FPM
}
```

## Unit tests
To run unit tests, you need to [download PHPUnit](https://phpunit.de/getting-started/phpunit-10.html). Then run

```bash
./phpunit --bootstrap lib/common.php --testdox tests
```

## License
Principia-web is licensed under the AGPLv3 license. This means you need to provide the source code of any forks, even if the fork is being run on a remote server. For more information, see the [LICENSE](https://github.com/principia-game/principia-web/blob/master/LICENSE) file, or [this page on choosealicense.com](https://choosealicense.com/licenses/agpl-3.0/).
