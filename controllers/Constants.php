<?php

namespace Controllers;

use stdClass;

class Constants
{
    //Painel
    const path = "C:\Laragon\www\xloja\\";
    static function getPath()
    {
        return self::path;
    }
    static function getPathDB()
    {
        return self::path . "data";
    }
    static function getPathCache()
    {
        return self::path . "data/cache";
    }
    static function getPathCSS()
    {
        return self::path . "css";
    }
    static function getPathJS()
    {
        return self::path . "js";
    }
    static function getPathAssets()
    {
        return self::path . "assets";
    }
    static function getSite()
    {
        return "https://xloja.appmania.com";
    }
    static function getAcesso()
    {
        $obj = new stdClass();
        $obj->usuario = "user@gmail.com";
        $obj->senha = "12345";
        return $obj;
    }
}
