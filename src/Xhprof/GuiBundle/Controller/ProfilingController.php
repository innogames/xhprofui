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
        $data = null;
        if ($profiling) {
            $data = $profiling->getData();
            uasort($data, function($a, $b) {
                if ($a['wt'] == $b['wt']) {
                    return 0;
                }
                return ($a['wt'] < $b['wt']) ? 1 : -1;
            });
        }
        return $this->render('XhprofGuiBundle:Profiling:index.html.twig', array('profiling' => $profiling, 'data' => $data));
    }

    public function testAction() {
        return $this->render('XhprofGuiBundle:Profiling:test.html.twig', array());
    }
}
