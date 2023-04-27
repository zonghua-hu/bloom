<?php

namespace Service\CuckooFilter;

use Enum\BloomEnums;
use Predis\Client;
use Service\BloomAbstract;

/**
 * 布谷鸟过滤器
 * Class CuckooFilter
 * @package Service\CuckooFilter
 */
class CuckooFilter extends BloomAbstract
{
    /**
     * @var string
     */
    protected string $type = BloomEnums::CUCKOO_FILTER;

    /**
     * CF支持的方法
     * @var array
     */
    protected array $method = [
        BloomEnums::COMMAND_ADD,
        BloomEnums::COMMAND_ADDNX,
        BloomEnums::COMMAND_DEL,
        BloomEnums::COMMAND_EXISTS,
        BloomEnums::COMMAND_COUNT,
        BloomEnums::COMMAND_INFO,
        BloomEnums::COMMAND_RESERVE,
        BloomEnums::COMMAND_INSERT,
        BloomEnums::COMMAND_INSERTNX,
        BloomEnums::COMMAND_LOADCHUNK,
        BloomEnums::COMMAND_SCANDUMP,
        BloomEnums::COMMAND_MEXISTS,
    ];

    /**
     * CuckooFilter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->redis = new Client($config);
    }

    /**
     * @param $item
     * @param string $bucket
     * @return bool
     * @throws \Exception
     */
    public function del($item, string $bucket = ''): bool
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_DEL),
            $this->getBucket($bucket),
            $item
        ];
        return $this->redis->executeRaw($params);
    }

    /**
     * @param $item
     * @param string $bucket
     * @return bool
     * @throws \Exception
     */
    public function addNx($item, string $bucket = ''): bool
    {
        $params = [$this->getCommand(BloomEnums::COMMAND_ADDNX), $this->getBucket($bucket), $item];
        return $this->redis->executeRaw($params);
    }

    /**
     * @param string $bucket
     * @param array $items
     * @param int $capacity
     * @param int $noCreate
     * @return mixed
     * @throws \Exception
     */
    public function insertNx(string $bucket, array $items, int $capacity = 0, int $noCreate = 0): mixed
    {
        $params = $this->getInsertNxArgs($bucket, $items, $capacity, $noCreate);
        return $this->redis->executeRaw($params);
    }

    /**
     * @param $item
     * @param string $bucket
     * @return array
     * @throws \Exception
     */
    protected function getCountArgs($item, string $bucket = ''): array
    {
        return [$this->getCommand(BloomEnums::COMMAND_COUNT), $this->getBucket($bucket), $item];
    }

    /**
     * @param int $capacity
     * @param string $bucket
     * @param array $opts
     * @param float $rate
     * @return array|mixed
     * @throws \Exception
     */
    protected function getReserveArgs(int $capacity, string $bucket, array $opts = [], float $rate = 0.01)
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_RESERVE),
            $this->getBucket($bucket),
            $capacity
        ];
        $params = array_merge($params, $this->getBucketSize($opts['bucket_size'] ?? 0));//大小
        $params = array_merge($params, $this->getMaxIteration($opts['max_iteration'] ?? 0));//迭代次数
        $params = array_merge($params, $this->getExpansion($opts['expansion'] ?? 0));//扩大指数，默认时2^1
        return $params;
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
        return $this->format($bucket, $items, $capacity, $noCreate, BloomEnums::COMMAND_INSERT);
    }

    /**
     * @param string $bucket
     * @param array $items
     * @param int $capacity
     * @param int $noCreate
     * @return mixed
     * @throws \Exception
     */
    private function getInsertNxArgs(string $bucket, array $items, int $capacity = 0, int $noCreate = 0): mixed
    {
        return $this->format($bucket, $items, $capacity, $noCreate, BloomEnums::COMMAND_INSERTNX);
    }

    /**
     * @param string $bucket
     * @param array $items
     * @param int $capacity
     * @param int $noCreate
     * @param string $command
     * @return array
     * @throws \Exception
     */
    private function format(string $bucket, array $items, int $capacity, int $noCreate, string $command)
    {
        $params = [$this->getCommand($command), $this->getBucket($bucket)];
        if ($capacity) {
            $params = array_merge($params, [BloomEnums::CAPACITY_KEY, $capacity]);
        }
        if ($noCreate) {
            $params = array_merge($params, [BloomEnums::NOCREATE]);
        }
        $items = array_merge([BloomEnums::INSERT_KEY], $items);

        return array_merge($params, $items);
    }
}
