<?php

namespace Moyu\Bloom\Service;

interface BloomInterface
{
    /**
     * 向过滤器中添加一个元素
     * @param $item
     * @param string $bucket
     * @return bool
     */
    public function add($item, string $bucket = ''): bool;

    /**
     * 向过滤器中添加多个元素
     * @param array $item
     * @param string $bucket
     * @return mixed
     */
    public function mAdd(array $item, string $bucket = '');

    /**
     * 删除过滤器中的元素
     * @param $item
     * @param string $bucket
     * @return bool
     */
    public function del($item, string $bucket = ''): bool;

    /**
     * 判断过滤器中是否存在一个元素
     * @param $item
     * @param string $bucket
     * @return bool
     */
    public function exist($item, string $bucket = ''): bool;

    /**
     * 判断过滤器中是否存在多个元素
     * @param array $item
     * @param string $bucket
     * @return mixed
     */
    public function mExists(array $item, string $bucket = '');

    /**
     * 向过滤器中添加一个元素，只有当元素不存在时才会添加成功
     * @param $item
     * @param string $bucket
     * @return bool
     */
    public function addNx($item, string $bucket = ''): bool;

    /**
     * 计算过滤器中对应元素的个数
     * @param $item
     * @param string $bucket
     * @return int
     */
    public function count($item, string $bucket = ''): int;

    /**
     * 查看过滤器的基础信息
     * @param string $bucket
     */
    public function info(string $bucket = '');

    /**
     * 修改过滤器的属性
     * @param int $capacity
     * @param string $bucket
     * @param array $opts
     * @param float $rate
     * @return mixed
     */
    public function reserve(int $capacity, string $bucket, array $opts = array(), float $rate = 0.01): mixed;

    /**
     * 新建过滤器
     * @param int $capacity
     * @param string $bucket
     * @param array $items
     * @param int $noCreate
     * @return mixed
     */
    public function insert(string $bucket, array $items, int $capacity = 0, int $noCreate = 0, float $rate = 0.01);

    /**
     * 新建过滤器，是否存在
     * @param int $capacity
     * @param string $bucket
     * @param array $items
     * @param int $noCreate
     * @return mixed
     */
    public function insertNx(string $bucket, array $items, int $capacity = 0, int $noCreate = 0): mixed;

    /**
     * 从AOF中加载数据
     * @param string $bucket
     * @param int $iterator
     * @return mixed
     */
    public function loadChunk(string $bucket, int $iterator): mixed;

    /**
     * 向AOF中持久化数据
     * @param string $bucket
     * @param int $iterator
     * @return mixed
     */
    public function scanDump(string $bucket, int $iterator): mixed;
}