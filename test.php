<?php

$path = '/usr/bin/test';

while ($path !== '') {
    echo $path . "\n";
    $path = substr($path, 0, strrpos($path, '/'));
}

