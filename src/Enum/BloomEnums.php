<?php

namespace Enum;

class BloomEnums
{
    //布隆过滤器
    public const BLOOM_FILTER = 'BF';
    //布谷鸟过滤器
    public const CUCKOO_FILTER = 'CF';
    //bucketSize key
    public const BUCKET_KEY = 'BUCKETSIZE';
    //bucketSize value
    public const BUCKET_VALUE = 2;
    //MAX ITERATIONS key
    public const MAX_KEY = 'MAXITERATIONS';
    //MAX ITERATIONS value
    public const MAX_VALUE = 20;
    //EXPANSION key
    public const EXPANSION_KEY = 'EXPANSION';
    //EXPANSION value
    public const EXPANSION_VALUE = 1;
    //insert key
    public const INSERT_KEY = "ITEMS";
    //no create
    public const NOCREATE = "NOCREATE";
    //CAPACITY key
    public const CAPACITY_KEY = "CAPACITY";
    //NON_SCALING-布隆达到容量时返回报错
    public const NON_SCALING_KEY = "NONSCALING";
    //ERROR key
    public const ERROR_KEY = "ERROR";
    //method command
    public const COMMAND_ADD       = 'ADD';      //添加元素
    public const COMMAND_CARD      = 'CARD';     //元素计数
    public const COMMAND_RESERVE   = 'RESERVE';  //修改目标过滤器的属性
    public const COMMAND_MADD      = 'MADD';     //批量添加
    public const COMMAND_INSERT    = 'INSERT';   //新建过滤器添加元素
    public const COMMAND_EXISTS    = 'EXISTS';   //元素是否存在
    public const COMMAND_MEXISTS   = 'MEXISTS';  //批量判断是否存在
    public const COMMAND_INFO      = 'INFO';     //获取过滤器信息
    public const COMMAND_LOADCHUNK = 'LOADCHUNK';//从AOF中加载数据
    public const COMMAND_SCANDUMP  = 'SCANDUMP'; //向AOF中持久化数据
    public const COMMAND_ADDNX     = 'ADDNX';    //不存在时才会添加成功
    public const COMMAND_INSERTNX  = 'INSERTNX'; //布谷鸟过滤器不存在则创建
    public const COMMAND_DEL       = 'DEL';      //删除一个元素，删除的是元素的指纹
    public const COMMAND_COUNT     = 'COUNT';    //布谷鸟过滤器中对应元素的个数
}
