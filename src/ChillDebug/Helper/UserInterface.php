<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 22:04
 */

namespace ChillDebug\Helper;


class UserInterface
{
    public  static function isCLI()
    {
        return (php_sapi_name() === 'cli');
    }
}