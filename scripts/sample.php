<?php

define('XHPROF_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . '../scripts/config.yml');
include '../scripts/xhprof_handler.phar';

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

// start profiling
//xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

// run program
foo();

// stop profiler
//$xhprof_data = xhprof_disable();

var_dump('foo!');

exit();

echo "<pre>";

// display raw xhprof data for the profiler run
print_r($xhprof_data);


$XHPROF_ROOT = realpath(dirname(__FILE__) .'/../vendor/facebook/xhprof');
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

echo "---------------\n".
    "Assuming you have set up the http based UI for \n".
    "XHProf at some address, you can view run at \n".
    "http://xhprof.localhost/index.php?run=$run_id&source=xhprof_foo\n".
    "---------------\n";

echo "</pre>";