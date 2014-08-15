<?php

namespace Xhprof\GuiBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Xhprof\GuiBundle\Tests\MyWebTestCase;

class ProfilingControllerTest extends MyWebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profilings');

        $this->assertTrue($crawler->filter('html:contains("Profilings list")')->count() > 0);
    }
}
