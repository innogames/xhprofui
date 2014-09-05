<?php
namespace Xhprof\GuiBundle\Services;

use Doctrine\ORM\EntityManager;
use Xhprof\GuiBundle\Entity\Profiling;

class ProfilingSaveHandler
{

    /**
     * @var EntityManager
     */
    private $entity_manager;

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
     * sets the doctrine service
     *
     * @param EntityManager $entity_manager
     */
    public function setDoctrineEntityManager($entity_manager)
    {
        $this->entity_manager = $entity_manager;
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
        var_dump($xhprof_data);
        $main = $xhprof_data['main()'];

        $profiling = new Profiling();
        $profiling->setData($xhprof_data);
        $profiling->setCpu($main['cpu']);
        $profiling->setMemory($main['mu']);
        $profiling->setPeakMemory($main['pmu']);
        $profiling->setTimestamp(new \DateTime());
        $profiling->setWallTime($main['wt']);
        $profiling->setRequestUri($_SERVER['REQUEST_URI']);
        $profiling->setRequestMethod($_SERVER['REQUEST_METHOD']);
        $profiling->setGetParams($_GET);
        $profiling->setPostParams($_POST);
        $profiling->setCookiesParams($_COOKIE);
        $profiling->setServerName($_SERVER['SERVER_NAME']);

        $this->entity_manager->persist($profiling);
        $this->entity_manager->flush();
    }
}
