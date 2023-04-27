# bloom
Bloom filter and Cuckoo filter based on php language

## redis bloom 模块
```
1.需要开启redis的module功能，参考链接：https://www.cnblogs.com/emunshe/p/13690115.html
2.如果make失败，也可以用项目中的redisbloom.so文件
3.开启redis的模块并重启。redis的配置文件中加上这句代码：loadmodule /你的路径/redisbloom.so
4.目前版本集成redis的bloom模块布隆和布谷鸟所有的方法。
```

## 思路
```
1.对于过滤器不支持的方法，返回false,具体支持的方法请见各个实例类的method
2.对于不同过滤器命令不一样的问题，需要实现抽象方法获取区别参数和命令
3.部分相同命令方法通过type区分不同实例，并获取对应命令
4.bucket默认按照不同过滤器区分，也可自定义
```

## 使用说明
#### 单例布隆
```
$bloomFactory = BloomSingle::createBloom($redisConfig);
$bloomFactory->add($address);
```
#### 多例布隆
```
$bloomMultiple = BloomFactory::getInstance($config, BloomEnums::BLOOM_FILTER);
$bloomMultiple->add($address);
```

#### 单例布谷鸟
```
$cuckooFactory = BloomSingle::createCuckoo($redisConfig);
$cuckooFactory->add($address);
```
#### 多例布谷鸟
```
$cuckooMultiple = BloomFactory::getInstance($config);
$cuckooMultiple->add($address);
```