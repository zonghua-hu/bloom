<?php

namespace Moyu\Bloom\example;

use Moyu\Bloom\Enum\BloomEnums;
use Moyu\Bloom\Service\BloomFactory;
use Moyu\Bloom\Service\BloomFilter\BloomFilter;
use Moyu\Bloom\Service\BloomSingle;
use Moyu\Bloom\Service\CuckooFilter\CuckooFilter;
use Moyu\Bloom\Service\RedisClient\RedisDefault;

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
