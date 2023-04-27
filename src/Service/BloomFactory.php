<?php

namespace Service;

use Enum\BloomEnums;
use Service\BloomFilter\BloomFilter;
use Service\CuckooFilter\CuckooFilter;

/**
 * 过滤器工厂
 * Class BloomFactory
 * @package Service
 */
class BloomFactory
{
    /**
     * 对象集合
     * @var array
     */
    protected static array $objects = [];

    /**
     * @var string[]
     */
    protected static array $map = [
        BloomEnums::BLOOM_FILTER  => BloomFilter::class,
        BloomEnums::CUCKOO_FILTER => CuckooFilter::class,
    ];

    /**
     * BloomFactory constructor.
     */
    private function __construct()
    {
        // TODO: Implement __construct() method.
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @param array $config
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance(array $config, $type = BloomEnums::CUCKOO_FILTER): mixed
    {
        if (!isset(self::$map[$type])) {
            throw new \Exception('invalid type');
        }
        if (!isset(self::$objects[$type])) {
            $className = self::$map[$type];
            self::$objects[$type] = new $className($config);
        }
        return self::$objects[$type];
    }
}
