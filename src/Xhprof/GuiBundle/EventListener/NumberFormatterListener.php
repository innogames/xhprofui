<?php

namespace Xhprof\GuiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig_Environment;

/**
 * Initializes the number formatting based on the current locale.
 */
class NumberFormatterListener implements EventSubscriberInterface
{

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $locale = $request->getLocale();

        // @todo set the number format depending on the locale
        //$this->twig->getExtension('core')->setNumberFormat(3, '.', ',');
    }

    /**
     * @param Twig_Environment $twig
     */
    public function setTwig(Twig_Environment $twig) {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController'
        );
    }
}
