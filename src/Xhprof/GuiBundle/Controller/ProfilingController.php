<?php

namespace Xhprof\GuiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xhprof\GuiBundle\Entity\ProfilingRepository;

class ProfilingController extends Controller
{
    /**
     * Show a single profiling in detail
     *
     * @param integer $id the profiling id
     *
     * @throws \InvalidArgumentException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Invalid id given, numeric value expected!');
        }
        $doctrine = $this->get('doctrine');
        $profiling = $doctrine->getRepository('XhprofGuiBundle:Profiling')
            ->find($id);
        $data = null;
        if ($profiling) {
            $data = $profiling->getData();
            // sort by walltime, temporary solution
            uasort(
                $data,
                function ($a, $b) {
                    if ($a['wt'] == $b['wt']) {
                        return 0;
                    }

                    return ($a['wt'] < $b['wt']) ? 1 : -1;
                }
            );
        }

        return $this->render(
            'XhprofGuiBundle:Profiling:index.html.twig',
            array('profiling' => $profiling, 'data' => $data)
        );
    }

    public function listAction()
    {
        $doctrine = $this->get('doctrine');
        /** @var ProfilingRepository $repository */
        $repository = $doctrine->getRepository('XhprofGuiBundle:Profiling');
        $profilings = $repository->findAll();
        return $this->render('XhprofGuiBundle:Profiling:list.html.twig', array('profilings' => $profilings));
    }

    public function testAction()
    {
        return $this->render('XhprofGuiBundle:Profiling:test.html.twig', array());
    }
}
