<?php
/*
 * This file is a part of Mibew GeoIp Plugin.
 *
 * Copyright 2014 Dmitriy Simushev <simushevds@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @file The main file of Mibew:GeoIp plugin.
 */

namespace Mibew\Mibew\Plugin\GeoIp;

use GeoIp2\Database\Reader as GeoIpReader;

/**
 * This plugin provides an API for GeoIP information.
 */
class Plugin extends \Mibew\Plugin\AbstractPlugin implements \Mibew\Plugin\PluginInterface
{
    /**
     * Class construct.
     *
     * @param array $config Configurations array.
     */
    public function __construct($config)
    {
        $config += array(
            'database_path' => $this->getFilesPath() . '/db/GeoIP.mmdb',
        );

        parent::__construct($config);

        // Use autoloader for Composer's packages that shipped with the plugin
        require(__DIR__ . '/vendor/autoload.php');
    }

    /**
     * The plugin does not need extra initialization thus it is always ready to
     * work.
     *
     * @return boolean
     */
    public function initialized()
    {
        return true;
    }

    /**
     * Retrieves Geo Info by IP.
     *
     * @param string $ip IP address
     * @param string $locale Locale name which should be used for country and
     *   city names. If this argument is ommited default names in english will
     *   be used.
     * @return array Associative array of Geo information. It has the following
     *   keys:
     *    - "country_name": Name of the country the IP is situated in.
     *    - "country_code": ISO 3166-1 Alpha-2 code the IP is situated in.
     *    - "city": Name of the city the IP is situated in.
     *    - "latitude": Latitude of the place the IP is situated in.
     *    - "longitude": Longitude of the place the IP is situated in.
     */
    public function getGeoInfo($ip, $locale = false)
    {
        try {
            $reader = new GeoIpReader($this->config['database_path']);
            $record = $reader->city($ip);
        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            return array(
                'country_name' => 'Unknown',
                'country_code' => false,
                'city' => 'Unknown',
                'latitude' => 0,
                'longitude' => 0,
            );
        }

        if ($locale) {
            // Make sure the locale in "xx-XX" format.
            $locale = $this->formatLocale($locale);
        }

        // Try to use localized names for country and city. If there are no such
        // names use english ones.
        $country_name = $locale && isset($record->country->names[$locale])
            ? $record->country->names[$locale]
            : $record->country->name;
        $city = $locale && isset($record->city->names[$locale])
            ? $record->city->names[$locale]
            : $record->city->name;

        return array(
            'country_name' => $country_name ?: 'Unknown',
            'country_code' => $record->country->isoCode ?: false,
            'city' => $city ?: 'Unknown',
            'latitude' => $record->location->latitude ?: 0,
            'longitude' => $record->location->longitude ?: 0,
        );
    }

    /**
     * This method is intentionally empty but should exist to let the plugin
     * implements {@link \Mibew\Plugin\PluginInterface}.
     */
    public function run()
    {
    }

    /**
     * Returns pluing's version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return '1.0.2';
    }

    /**
     * Returns plugin's dependencies.
     *
     * @return type
     */
    public static function getDependencies()
    {
        return array();
    }

    /**
     * Returns plugin's system requirements
     *
     * @return type
     */
    public static function getSystemRequirements()
    {
        if (extension_loaded('gmp')) {
            return array('ext-gmp' => '*');
        }
        else {
            return array('ext-bcmath' => '*');
        }
    }

    /**
     * Format locale name using "xx-XX" format.
     *
     * @param string $locale Original locale name in format it is used in Mibew.
     * @return string|false Formatted locale name or boolean false if the
     *   original locale name is not correct.
     */
    protected function formatLocale($locale)
    {
        // Make sure the original locale name is correct
        if (!locale_pattern_check($locale)) {
            return false;
        }

        $parts = explode('-', $locale);

        if (count($parts) == 1) {
            // The locale is just in "xx" format. There is no need to modify it.
            return $parts[0];
        }

        // Make sure the locale in "xx-XX" format.
        return $parts[0] . '-' . strtoupper($parts[1]);
    }
}
