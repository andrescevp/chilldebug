<?php
/**
 * Created by PhpStorm.
 * User: andres
 * Date: 26/05/17
 * Time: 21:18
 */

namespace ChillDebug\Handler;


use ChillDebug\Dto\RequestDto;
use ChillDebug\Helper\UserInterface;

class RequestHandler
{
    public static $requests = [];

    public static function buildRequest($time = null)
    {
        if (!$time) {
            $time = time();
        }

        $request = (new RequestDto())
            ->setFiles($_FILES)
            ->setServer($_SERVER)
            ->setGet((array) $_GET)
            ->setPost($_POST)
            ->setTime($time)
            ->setRequest($_REQUEST)
            ->setCookies($_COOKIE);

        if (!UserInterface::isCLI()) {
            $request->setSession(isset($_SESSION) ? $_SESSION : []);
        }

        $requestRaw = [
            'time' => $request->getTime(),
            'server' => $request->getServer(),
            'request' => $request->getRequest(),
            'get' => $request->getGet(),
            'post' => $request->getPost(),
            'files' => $request->getFiles(),
            'cookies' => $request->getCookies(),
            'session' => $request->getSession(),
        ];

        self::$requests[md5(serialize($request))] = $requestRaw;

        return $request;
    }

    public static function getRequest($key)
    {
        return self::$requests[$key];
    }
}