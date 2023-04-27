<?php

namespace Example;

use Enum\BloomEnums;
use Service\BloomFactory;
use Service\BloomFilter\BloomFilter;
use Service\BloomSingle;
use Service\CuckooFilter\CuckooFilter;
use Service\RedisClient\RedisDefault;

class Demo
{
    /**
     * @param $address
     * @param array|null $config
     * @throws \Exception
     */
    public function run($address, array $config = null)
    {
        $config = $config ? $config : RedisDefault::getRedisDefaultConfig();
        /**
         * 布隆创建1:Multiple cases
         * @var $bloom BloomFilter
         */
        $bloomMultiple = BloomFactory::getInstance($config, BloomEnums::BLOOM_FILTER);
        $bloomMultiple->add($address);
        var_dump($bloomMultiple->exist($address));//assert true
        /**
         * 布隆创建2:单例工厂
         * @var $bloom BloomFilter
         */
        $bloomFactory = BloomSingle::createBloom($config);
        $bloomFactory->add($address);
        var_dump($bloomFactory->exist($address));//assert true
        /**
         * 布谷鸟创建1:Multiple cases
         * @var $bloom CuckooFilter
         */
        $cuckoo = BloomFactory::getInstance($config);
        $cuckoo->add($address);
        var_dump($cuckoo->exist($address));//assert true
        /**
         * 布谷鸟创建2:单例工厂
         * @var $bloom CuckooFilter
         */
        $cuckooFactory = BloomSingle::createCuckoo($config);
        $cuckooFactory->add($address);
        var_dump($cuckooFactory->exist($address));//assert true
    }

}
