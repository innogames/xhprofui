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
        return $this->render('XhprofGuiBundle:Profiling:index.html.twig', array('profiling' => $profiling));
    }
}
