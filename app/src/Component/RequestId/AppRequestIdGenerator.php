<?php


namespace App\Component\RequestId;

use Chrisguitarguy\RequestId\RequestIdGenerator;

class AppRequestIdGenerator implements RequestIdGenerator
{
    /**
     * Create a new request ID.
     */
    public function generate(): string
    {
        return implode('.', [
            self::date(),
            self::time(),
            self::uniq(),
        ]);
    }

    private static function uniq(): string
    {
        $rand = rand(0, 10 * (10 ** 6));
        return substr($rand, 0, 6);
    }

    private static function date(): string
    {
        return date('ymd');
    }

    private static function time(): string
    {
        return date('His');
    }
}
