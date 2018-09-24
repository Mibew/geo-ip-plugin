# Mibew Geo IP plugin

Provides Geo IP information for other plugins.

*Requires either [GMP (GNU Multiple Precision)](http://php.net/manual/en/book.gmp.php) or [BC Math (BCMath Arbitrary Precision Mathematics)](http://php.net/manual/en/book.bc.php) PHP extension.*

## Installation

1. Get the archive with the plugin sources. You can download it from the [official site](https://mibew.org/plugins#mibew-geoip) or build the plugin from sources.

2. Untar/unzip the plugin's archive.

3. Put files of the plugins to the `<Mibew root>/plugins`  folder.

4. Obtain a copy of MaxMind's GeoIP2 City datatbase. You can use a free one from here http://dev.maxmind.com/geoip/geoip2/geolite2/.

5. Rename the database file to `GeoIP.mmdb` and put it into `<Mibew root>/plugins/Mibew/Mibew/Plugin/GeoIp/db` directory.

6. (optional) Add plugins configs to "plugins" structure in "`<Mibew root>`/configs/config.yml". If the "plugins" stucture looks like `plugins: []` it will become:
    ```yaml
    plugins:
        "Mibew:GeoIp": # Plugin's configurations are described below
            database_path: "/usr/local/share/GeoIP/GeoIP2-City.mmdb"
    ```

7. Navigate to "`<Mibew Base URL>`/operator/plugin" page and enable the plugin.


## Plugin's configurations

The plugin can be configured with values in "`<Mibew root>`/configs/config.yml" file.

### config.database_path

Type: `String`

Default: `<Plugin dir>/db/GeoIP.mmdb`

Specify location of the GeoIP database. This value is optional and can be skipped.


## Build from sources

There are several actions one should do before use the latest version of the plugin from the repository:

1. Obtain a copy of the repository using `git clone`, download button, or another way.
2. Install [node.js](http://nodejs.org/) and [npm](https://www.npmjs.org/).
3. Install [Gulp](http://gulpjs.com/).
4. Install npm dependencies using `npm install`.
5. Run Gulp to build the sources using `gulp default`.

Finally `.tar.gz` and `.zip` archives of the ready-to-use Plugin will be available in `release` directory.


## License

[Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0.html)
