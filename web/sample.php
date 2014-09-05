<?php

define('XHPROF_CONFIG', __DIR__ . '/../scripts/config.yml');
include __DIR__ . '/../scripts/xhprof_handler.phar';

function bar($x) {
    if ($x > 0) {
        bar($x - 1);
    }
}

function foo() {
    for ($idx = 0; $idx < 5; $idx++) {
        bar($idx);
        $x = strlen("abc");
    }
}

// run program
foo();
