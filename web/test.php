<pre><?php

use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';
$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$kernel->boot();
$myservice = $kernel->getContainer()->get('xhprof.profiling.save.handler');
$myservice->register();

echo "test";

//$xhprof_data = xhprof_disable();
//var_dump($xhprof_data);
//$myservice->save($xhprof_data);

$doctrine = $kernel->getContainer()->get('doctrine');
/** @var Xhprof\StoreBundle\Entity\Profiling $profiling */
$profiling = $doctrine->getRepository('XhprofStoreBundle:Profiling')
    ->find(10);

$resource = $profiling->getData();
var_dump(json_decode(gzuncompress(stream_get_contents($resource)), true));