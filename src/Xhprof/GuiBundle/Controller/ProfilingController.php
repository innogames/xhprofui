<?php

namespace Xhprof\GuiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilingController extends Controller
{
    public function indexAction($id)
    {
        return $this->render('XhprofGuiBundle:Profiling:index.html.twig', array('id' => $id));
    }
}
