<?php
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
}

function clearCache()
{
    global $mem;
    $mem->flush();
}
