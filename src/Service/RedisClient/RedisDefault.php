<?php

namespace Moyu\Bloom\Service\RedisClient;

use Moyu\Bloom\Enum\RedisEnums;

class RedisDefault
{
    /**
     * @return array
     */
    public static function getRedisDefaultConfig(): array
    {
        return [
            RedisEnums::REDIS_HOST     => '127.0.0.1',
            RedisEnums::REDIS_PORT     => 6379,
            RedisEnums::REDIS_TIMEOUT  => 0,
            RedisEnums::REDIS_DATABASE => 0,
            RedisEnums::REDIS_AUTH     => null,
        ];
    }
}
