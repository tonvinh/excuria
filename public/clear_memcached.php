<?php

$mem = new Memcached();
$mem->addServer("memcache.excuri.com", 11211);

$mem->flush() or die('Fail clear mcached');
echo('Success clear all memcached');
?>