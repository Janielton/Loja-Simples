<?php

namespace Controllers;

class Constants
{
    const path = '{path}';

    static function getPath()
    {
        return self::path;
    }
    static function getPathDB()
    {
        return self::path . "\data";
    }

    static function getPathCSS()
    {
        return self::path . "\css";
    }

    static function getPathJS()
    {
        return self::path . "\js";
    }

    static function getSite()
    {
        return "{site}";
    }
}
