<?php

namespace Moyu\Bloom\Service\BloomFilter;

use Moyu\Bloom\Enum\BloomEnums;
use Moyu\Bloom\Service\BloomAbstract;
use Moyu\Bloom\Service\RedisClient\RedisSingle;
use Predis\Client;

/**
 * 布隆过滤器
 * Class BloomFilter
 * @package Service\BloomFilter
 */
class BloomFilter extends BloomAbstract
{
    /**
     * @var string
     */
    protected string $type = BloomEnums::BLOOM_FILTER;

    /**
     * BF支持的方法
     * @var array
     */
    protected array $method = [
        BloomEnums::COMMAND_ADD,
        BloomEnums::COMMAND_CARD,
        BloomEnums::COMMAND_RESERVE,
        BloomEnums::COMMAND_MADD,
        BloomEnums::COMMAND_INSERT,
        BloomEnums::COMMAND_EXISTS,
        BloomEnums::COMMAND_MEXISTS,
        BloomEnums::COMMAND_INFO,
        BloomEnums::COMMAND_LOADCHUNK,
        BloomEnums::COMMAND_SCANDUMP,
    ];

    /**
     * CuckooFilter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->redis = RedisSingle::getInstance($config);
    }

    /**
     * @param array $item
     * @param string $bucket
     * @return mixed
     * @throws \Exception
     */
    public function mAdd(array $item, string $bucket = ''): mixed
    {
        $params = [$this->getCommand(BloomEnums::COMMAND_MADD), $this->getBucket($bucket)];
        return $this->redis->executeRaw(array_merge($params, $item));
    }

    /**
     * @param $item
     * @param string $bucket
     * @return array
     * @throws \Exception
     */
    protected function getCountArgs($item, string $bucket = ''): array
    {
        return [$this->getCommand(BloomEnums::COMMAND_CARD), $this->getBucket($bucket), $item];
    }

    /**
     * @param string $bucket
     * @param array $items
     * @param int $capacity
     * @param int $noCreate
     * @param float $rate
     * @return array|mixed
     * @throws \Exception
     */
    protected function getInsertArgs(string $bucket, array $items, $capacity = 0, $noCreate = 0, $rate = 0.01)
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_INSERT),
            $this->getBucket($bucket),
        ];
        if ($capacity) {
            $params = array_merge($params, [BloomEnums::CAPACITY_KEY, $capacity]);
        }
        if ($rate) {
            $params = array_merge($params, [BloomEnums::ERROR_KEY, $rate]);
        }
        $params = array_merge($params, $this->getExpansion($opts['expansion'] ?? 0));//扩大指数，默认时2^1

        if (!empty($opts['non_scaling'])) {
            $params = array_merge($params, [BloomEnums::NON_SCALING_KEY]);
        }
        $items = array_merge([BloomEnums::INSERT_KEY], $items);

        return array_merge($params, $items);
    }

    /**
     * @param int $capacity
     * @param string $bucket
     * @param array $opts
     * @param float $rate
     * @return array
     * @throws \Exception
     */
    protected function getReserveArgs(int $capacity, string $bucket, array $opts = array(), float $rate = 0.01): array
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_RESERVE),
            $this->getBucket($bucket),
            $rate,
            $capacity
        ];
        if (!empty($opts['non_scaling'])) {
            $params = array_merge($params, [BloomEnums::NON_SCALING_KEY]);
        }
        $params = array_merge($params, $this->getExpansion($opts['expansion'] ?? 0));//扩大指数，默认时2^1
        return $params;
    }
}
