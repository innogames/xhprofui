<?php
namespace Xhprof\StoreBundle\Services;

class ProfilingSaveHandler {

    /**
     * @var ProfilingDataManager
     */
    private $data_manager;

    /**
     * @param ProfilingDataManager $data_manager
     */
    public function __construct(ProfilingDataManager $data_manager)
    {
        $this->data_manager = $data_manager;
    }

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
        $this->data_manager->save($xhprof_data);
    }


} 