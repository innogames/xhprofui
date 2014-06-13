<?php

namespace Xhprof\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XhprofStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
