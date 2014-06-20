<?php

namespace Xhprof\GuiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilingController extends Controller
{
    public function indexAction($id)
    {
        $data_manager = $this->get('xhprof.profiling.data.manager');
        $profiling = $data_manager->loadById($id);
        var_dump($profiling);
        return $this->render('XhprofGuiBundle:Profiling:index.html.twig', array('id' => $id));
    }
}
