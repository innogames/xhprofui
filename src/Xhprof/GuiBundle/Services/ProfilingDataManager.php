<?php
namespace Xhprof\GuiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Xhprof\GuiBundle\Entity\Profiling;

class ProfilingDataManager extends ContainerAware {

    /**
     * @param array $xhprof_data
     *
     * @return void
     */
    public function save(array $xhprof_data)
    {
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

    public function loadById($id)
    {
        $doctrine = $this->container->get('doctrine');
        /** @var Xhprof\GuiBundle\Entity\Profiling $profiling */
        return $doctrine->getRepository('XhprofGuiBundle:Profiling')
            ->find($id);
    }
}