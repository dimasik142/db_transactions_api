<?php
/**
 * User: dimasik142
 * User: ivanov.dmytro.ua@gmail.com
 * Date: 15.02.2018
 * Time: 13:03
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include('api/DB/Sql.php');
include('api/DB/Transaction/Transaction.php');

$transaction = new \Sql\Transaction\Transaction();

$transaction->startTransaction(3);
$transaction->runQuery("INSERT INTO `OrderTab` VALUES ('A','222')");
$transaction->runQuery("INSERT INTO `OrderTab` VALUES ('B','333')");
$transaction->runQuery("INSERT INTO `User` VALUES ('22')");
$transaction->runQuery("INSERT INTO `User` VALUES ('23')");
$transaction->runQuery("INSERT INTO `User` VALUES ('34')");
$transaction->finishTransaction();
