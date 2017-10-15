<?php

$mem = new Memcached();
$mem->addServer("127.0.0.1", 11211);

$mem->flush() or die('Fail clear mcached');
echo('Success clear all memcached');
?>