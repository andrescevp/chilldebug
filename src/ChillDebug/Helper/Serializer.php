<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 22:01
 */

namespace ChillDebug\Helper;


class Serializer
{
    public static function castToArray($object)
    {
        return (array) $object;
    }

    public static function castToJson($object)
    {
        return json_encode((array) $object, JSON_PRETTY_PRINT);
    }

}