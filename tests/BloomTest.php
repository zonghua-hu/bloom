<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Service\BloomAbstract;
use Service\BloomFactory;
use Service\RedisClient\RedisDefault;

class BloomTest extends TestCase
{
    /**
     * @var BloomAbstract $object
     */
    protected $object;

    protected function setUp(): void
    {
        parent::setUp();
        $this->object = BloomFactory::getInstance(RedisDefault::getRedisDefaultConfig());
    }

    public function testAdd()
    {
        $this->assertEquals(true, $this->object->add("aaa"));
    }

    public function testExist()
    {
        $this->assertEquals(true, $this->object->exist("aaa"));
    }

    public function testDel()
    {
        $this->assertEquals(true, $this->object->del("aaa"));
    }
}
