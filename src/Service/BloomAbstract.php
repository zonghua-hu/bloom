<?php

namespace Moyu\Bloom\Service;

use Moyu\Bloom\Enum\BloomEnums;
use Predis\Client;

/**
 * Class BloomAbstract
 * @package Service
 */
abstract class BloomAbstract implements BloomInterface
{
    /**
     * @var Client $redis
     */
    protected $redis;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $bloomKey = '::filter::';

    /**
     * @var array|array[]
     */
    protected array $method;

    public function reserve(int $capacity, string $bucket, array $opts = array(), float $rate = 0.01): mixed
    {
        return $this->redis->executeRaw($this->getReserveArgs($capacity, $bucket, $opts, $rate));
    }

    public function insert(string $bucket, array $items, int $capacity = 0, int $noCreate = 0, float $rate = 0.01)
    {
        $params = $this->getInsertArgs($bucket, $items, $capacity, $noCreate, $rate);
        return $this->redis->executeRaw($params);
    }

    public function add($item, string $bucket = ''): bool
    {
        $params = [$this->getCommand(BloomEnums::COMMAND_ADD), $this->getBucket($bucket), $item];
        return $this->redis->executeRaw($params);
    }

    public function exist($item, string $bucket = ''): bool
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_EXISTS),
            $this->getBucket($bucket),
            $item
        ];
        return $this->redis->executeRaw($params);
    }

    public function mExists(array $item, string $bucket = '')
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_MEXISTS),
            $this->getBucket($bucket)
        ];
        return $this->redis->executeRaw(array_merge($params, $item));
    }

    public function info(string $bucket = ''): mixed
    {
        $params = [
            $this->getCommand(BloomEnums::COMMAND_INFO),
            $this->getBucket($bucket),
        ];
        return $this->redis->executeRaw($params);
    }

    public function scanDump(string $bucket, int $iterator): mixed
    {
        if (empty($bucket)) {
            throw new \Exception("bucket is not exist~");
        }
        $params = [
            $this->getCommand(BloomEnums::COMMAND_SCANDUMP),
            $this->getBucket($bucket),
            $iterator
        ];
        return $this->redis->executeRaw($params);
    }

    public function loadChunk(string $bucket, int $iterator): mixed
    {
        if (empty($bucket)) {
            throw new \Exception("bucket is not exist~");
        }
        $scan = $this->scanDump($bucket, $iterator);
        if (empty($scan)) {
            return false;
        }
        $params = [$this->getCommand(BloomEnums::COMMAND_LOADCHUNK), $this->getBucket($bucket), $scan[0], $scan[1]];
        return $this->redis->executeRaw($params);
    }

    public function count($item, string $bucket = ''): int
    {
        return $this->redis->executeRaw($this->getCountArgs($item, $bucket));
    }

    public function insertNx(string $bucket, array $items, int $capacity = 0, int $noCreate = 0): mixed
    {
        return false;
    }

    public function del($item, string $bucket = ''): bool
    {
        return false;
    }

    public function addNx($item, string $bucket = ''): bool
    {
        return false;
    }

    public function mAdd(array $item, string $bucket = ''): mixed
    {
        return false;
    }

    /**
     * @param $item
     * @param string $bucket
     * @return array
     */
    abstract protected function getCountArgs($item, string $bucket = ''): array;

    /**
     * @param int $capacity
     * @param string $bucket
     * @param array $opts
     * @param float $rate
     * @return mixed
     */
    abstract protected function getReserveArgs(int $capacity, string $bucket, array $opts = [], float $rate = 0.01);

    /**
     * @param string $bucket
     * @param array $items
     * @param int $capacity
     * @param int $noCreate
     * @param float $rate
     * @return mixed
     */
    abstract protected function getInsertArgs(string $bucket, array $items, $capacity = 0, $noCreate = 0, $rate = 0.01);

    /**
     * @param string $bucket
     * @return string
     */
    final protected function getBucket(string $bucket = ''): string
    {
        return $bucket ? $bucket : $this->type . $this->bloomKey;
    }

    /**
     * @param string $command
     * @return string
     * @throws \Exception
     */
    final protected function getCommand(string $command): string
    {
        if (!in_array($command, $this->method) || !$command) {
            throw new \Exception("method not exist~");
        }
        return $this->type . '.' . $command;
    }

    /**
     * @param int $bucketSize
     * @return array
     */
    final protected function getBucketSize(int $bucketSize): array
    {
        return $bucketSize ? [BloomEnums::BUCKET_KEY, $bucketSize] : [BloomEnums::BUCKET_KEY, BloomEnums::BUCKET_VALUE];
    }

    /**
     * @param int $maxNumber
     * @return array
     */
    final protected function getMaxIteration(int $maxNumber): array
    {
        return $maxNumber ? [BloomEnums::MAX_KEY, $maxNumber] : [BloomEnums::MAX_KEY, BloomEnums::MAX_VALUE];
    }

    /**
     * @param int $exp
     * @return array
     */
    final protected function getExpansion(int $exp): array
    {
        return $exp ? [BloomEnums::EXPANSION_KEY, $exp] : [BloomEnums::EXPANSION_KEY, BloomEnums::EXPANSION_VALUE];
    }

    public function __destruct()
    {
        $this->redis->disconnect();
    }
}
