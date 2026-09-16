<?php
namespace ElegenceIO\Core\Psr;
use ElegenceIO\Console\Requests\ServerRequests;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

final class Requests
{
    private static ?ServerRequestInterface $psr = null;
    public static function capture():ServerRequestInterface
    {
        if(is_null(self::$psr)){
         return ServerRequestFactory::fromGlobals();
        }
        return self::$psr;
    }

    public static function captureArgs():array
    {
        return $_SERVER["argv"];
    }

    



}