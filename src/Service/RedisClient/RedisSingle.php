<?php

namespace Moyu\Bloom\Service\RedisClient;

use Predis\Client;

/**
 * Class RedisSingle
 * @package Moyu\Bloom\Service\RedisClient
 */
class RedisSingle
{
    /**
     * 单例redis
     * @var Client
     */
    private static $redis;

    /**
     * RedisSingle constructor.
     */
    private function __construct()
    {
        // TODO: Implement __construct() method.
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * 单例redis
     * @param array $config
     * @return Client
     */
    public static function getInstance(array $config): Client
    {
        if (self::$redis instanceof Client) {
            return self::$redis;
        }
        self::$redis = new Client($config);
        return self::$redis;
    }
}
