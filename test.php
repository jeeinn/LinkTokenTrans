<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 22:27
 */

require_once __DIR__ . '/vendor/autoload.php';

use LinkTokenTrans\Trans;

$address = '0x405d8ef6f679718374ce83c05ed373d7140ac3c8';
$addressOther = '0xc27ca753caf195d07d18d87cbca92a4e04853333';
$trans = new Trans($address);

$records = $trans->getRecords(1, 2);
//$records = $trans->getRecords();
print_r($records);

$balance = $trans->getBalance();
print_r($balance);

$count = $trans->getTransCount();
print_r($count);