<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/8/15
 * Time: 10:15 PM
 */
require_once 'db_credentials.php';

$mem = new Memcached();
$mem->addServer(HOST, MEMCACHED_PORT);

function getPageFromCache($query)
{
    global $mem;
    $querykey = "KEY" . md5($query);
    return $mem->get($querykey);
}

function putPageToCache($query, $set)
{
    global $mem;
    $ok = $mem->set("KEY" . md5($query), $set);
    $result = $mem->getResultCode();
    $msg = $mem->getResultMessage();
    // echo $ok." ".$result." ".$msg;
}

function clearCache()
{
    global $mem;
    $mem->flush();
}
