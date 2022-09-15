<?php

namespace App\Extensions;

class AssetsHelper
{
    /**
     * File name where source map is defined
     */
    const SOURCE_MAP = 'assets-source-map.json';

    /**
     * @var array|null
     */
    public static $sourceMap;

    /**
     * @param string $map Bundle name defined in self::SOURCE_MAP
     */
    public static function getJSBundle(string $map)
    {
        return self::getBundle($map, 'js');
    }

    /**
     * @param string $map Bundle name defined in self::SOURCE_MAP
     */
    public static function getCSSBundle(string $map)
    {
        self::getBundle($map, 'css');
    }

    /**
     * @param string $type js or css
     */
    protected static function getBundle(string $map, string $type)
    {
        $sourceMap = self::getSourceMap();
        $buildPath = $sourceMap['buildPath'];
        $map = str_replace('.', '/', $map);
        $bundlePrefix = basename($map);

        if (!app()->environment('dev')) {
            $path = strstr($map, $bundlePrefix, true)."{$type}/";
            $files = glob(public_path($buildPath.$path).$bundlePrefix.'-*');
            if (!count($files)) {
                throw new \Exception("Asset bundle [ $bundlePrefix ] not found");
            }
            foreach ($files as $file) {
                if ($type == 'js') {
                    echo \HTML::script($buildPath.$path.basename($file));
                } else {
                    echo \HTML::style($buildPath.$path.basename($file));
                }
            }
        } else {
            $files = $sourceMap[$type][$bundlePrefix];
            foreach ($files as $file) {
                if ($type == 'js') {
                    echo \HTML::script($file);
                } else {
                    echo \HTML::style($file);
                }

            }
        }
    }

    /**
     * @return array
     */
    protected static function getSourceMap()
    {
        if (self::$sourceMap === null) {
            self::$sourceMap = json_decode(file_get_contents(base_path(self::SOURCE_MAP)), true);
        }

        return self::$sourceMap;
    }
};