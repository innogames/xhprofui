<?php

namespace Xhprof\StoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfilingControllerTest extends WebTestCase
{
    public function testStore()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profilings/store');
    }
}
