<?php
namespace Xhprof\StoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Xhprof\StoreBundle\Entity\Profiling;

class ProfilingSaveHandler extends ContainerAware {

    /**
     * register the profiling save handler
     *
     * @return void
     */
    public function register()
    {
        xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        //register_shutdown_function(array($this, 'shutdownFunction'));
    }

    /**
     * @return void
     */
    public function shutdownFunction() {
        $xhprof_data = xhprof_disable();
        $this->save($xhprof_data);
    }

    /**
     * @param array $xhprof_data
     *
     * @return void
     */
    public function save(array $xhprof_data) {
        $main = $xhprof_data['main()'];

        $handle = fopen('php://memory', 'w+');
        $gzdata = gzcompress(json_encode($xhprof_data));
        fputs($handle, $gzdata);
        rewind($handle);

        $profiling = new Profiling();
        $profiling->setData($handle);
        $profiling->setCpu($main['pmu']);
        $profiling->setMemory($main['mu']);
        $profiling->setPeakMemory($main['cpu']);
        $profiling->setTimestamp(new \DateTime());
        $profiling->setWallTime($main['wt']);

        $em = $this->container->get('doctrine')->getEntityManager();

        $em->persist($profiling);
        $em->flush();

        fclose($handle);
    }


} 