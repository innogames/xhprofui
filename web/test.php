<?php

use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';
$kernel = new AppKernel('dev', true);
$kernel->boot();
$myservice = $kernel->getContainer()->get('xhprof.profiling.save.handler');
$myservice->test();