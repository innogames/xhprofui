<?php

namespace Xhprof\GuiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilingController extends Controller
{
    public function indexAction($id)
    {
        $doctrine = $this->get('doctrine');
        $profiling = $doctrine->getRepository('XhprofGuiBundle:Profiling')
            ->find($id);
        var_dump($profiling);
        return $this->render('XhprofGuiBundle:Profiling:index.html.twig', array('id' => $id));
    }
}
