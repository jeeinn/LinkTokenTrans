# LinkTokenTrans
链克交易查询

A LinkToken Transaction SDK base on OneThingCloud Inc.

## Method

**getBalance**

获取账户当前链克数
```php
$balance = $trans->getBalance();
print_r($balance);
```
**getTransCount**

获取帐户交易的次数
```php
$count = $trans->getTransCount();
print_r($count);
```
**getRecords**

getRecords(int $page=1, int $pageCount=20)

获取交易记录

```php
use LinkTokenTrans\Trans;

$address = '0x405d8ef6f679718374ce83c05ed373d7140ac3c8';
$trans = new Trans($address);
$records = $trans->getRecords();
print_r($records);
```
## TODO
1. 读取钱包文件，设置密码后发起一笔交易
2. 如果官方API有变，尽可能更新
3. Others...

