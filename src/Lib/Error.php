<?php

namespace Stubborn\Lib;

class StubbornException  extends \RuntimeException
{

}

class Error
{
    public static function get(string $message)
    {
        return (new StubbornException($message));
    }

    public static function throw(string $message)
    {
        throw self::get($message);
    }

    public static function report(string $message, array $data = [])
    {
        echo "Error: $message\n" . json_encode($data) . PHP_EOL;
        exit(1);
    }
}
