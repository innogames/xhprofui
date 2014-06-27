<?php
namespace Xhprof\GuiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Xhprof\GuiBundle\Entity\Profiling;

class ProfilingSaveHandler extends ContainerAware {

    /**
     * register the profiling save handler
     *
     * @return void
     */
    public function register()
    {
        xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
        register_shutdown_function(array($this, 'shutdownFunction'));
    }

    /**
     * @return void
     */
    public function shutdownFunction()
    {
        $xhprof_data = xhprof_disable();
        $this->save($xhprof_data);
    }

    /**
     * @param array $xhprof_data
     *
     * @return void
     */
    private function save(array $xhprof_data)
    {
        $main = $xhprof_data['main()'];

        $profiling = new Profiling();
        $profiling->setData($xhprof_data);
        $profiling->setCpu($main['pmu']);
        $profiling->setMemory($main['mu']);
        $profiling->setPeakMemory($main['cpu']);
        $profiling->setTimestamp(new \DateTime());
        $profiling->setWallTime($main['wt']);

        $em = $this->container->get('doctrine')->getEntityManager();

        $em->persist($profiling);
        $em->flush();

        //fclose($handle);
    }

} 