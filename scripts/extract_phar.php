<?php

$phar = new Phar('xhprof_handler.phar');
$phar->extractTo(__DIR__ . DIRECTORY_SEPARATOR . 'phar');
