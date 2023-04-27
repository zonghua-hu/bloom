<?php

namespace Service\RedisClient;

use Enum\RedisEnums;

class RedisDefault
{
    /**
     * @return array
     */
    public static function getRedisDefaultConfig(): array
    {
        return [
            RedisEnums::REDIS_HOST     => 'redis',
            RedisEnums::REDIS_PORT     => 6379,
            RedisEnums::REDIS_TIMEOUT  => 0,
            RedisEnums::REDIS_DATABASE => 0,
            RedisEnums::REDIS_AUTH     => null,
        ];
    }
}
