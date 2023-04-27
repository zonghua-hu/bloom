<?php

namespace Moyu\Bloom\Service;

use Moyu\Bloom\Service\BloomFilter\BloomFilter;
use Moyu\Bloom\Service\CuckooFilter\CuckooFilter;

class BloomSingle
{
    /**
     * @var CuckooFilter
     */
    private static $cuckoo;

    /**
     * @var BloomFilter
     */
    private static $Bloom;

    private function __construct()
    {
        // TODO: Implement __construct() method.
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 获取布谷鸟单例
     * @param array $config
     * @return CuckooFilter
     * @throws \Exception
     */
    public static function createCuckoo(array $config): CuckooFilter
    {
        if (empty($config)) {
            throw new \Exception("The redis configuration cannot be empty");
        }
        if (!self::$cuckoo instanceof CuckooFilter) {
            self::$cuckoo = new CuckooFilter($config);
        }
        return self::$cuckoo;
    }

    /**
     * 获取布隆单例
     * @param array $config
     * @return BloomFilter
     * @throws \Exception
     */
    public static function createBloom(array $config): BloomFilter
    {
        if (empty($config)) {
            throw new \Exception("The redis configuration cannot be empty");
        }
        if (!self::$Bloom instanceof BloomFilter) {
            self::$Bloom = new BloomFilter($config);
        }
        return self::$Bloom;
    }
}