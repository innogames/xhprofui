<?php

namespace Xhprof\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProfilingController extends Controller
{
    public function storeAction()
    {
        return $this->render('XhprofStoreBundle:Profiling:store.html.twig');
    }
}
